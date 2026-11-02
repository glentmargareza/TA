<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\PickupZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // ===== Pengaturan penjemputan otomatis (berdasarkan jarak) =====
    public const PICKUP_FREE_KM      = 3;      // jarak jemput gratis (km)
    public const PICKUP_EXTRA_PER_KM = 5000;   // biaya per km di atas batas gratis (Rp)
    public const BASECAMP_LAT        = -7.6352; // GANTI: koordinat basecamp asli
    public const BASECAMP_LNG        = 110.4366; // GANTI: koordinat basecamp asli

    public function create(Request $request)
    {
        $selectedSlug = $request->query('package', 'short-trip');
        $packages = Package::where('is_active', true)->get();
        $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();

        $slots = ScheduleSlot::where('is_active', true)->get();
        $pickupZones = PickupZone::all();
        $jeeps = \App\Models\Jeep::all();

        // Zona 1 = datang sendiri ke basecamp. Zona pertama dengan id > 1 dipakai untuk shuttle.
        $zonesSorted = $pickupZones->sortBy('id')->values();
        $baseZone    = $zonesSorted->first();
        $shuttleZone = $zonesSorted->first(fn ($z) => $z->id > 1) ?? $baseZone;

        return view('booking.create', compact(
            'packages', 'selectedPackage', 'slots', 'pickupZones', 'jeeps', 'baseZone', 'shuttleZone'
        ));
    }

    public function store(Request $request)
    {
        // Auto fallback jika beberapa field dikirim melalui antarmuka booking modern
        if (!$request->has('jeep_quantity') || empty($request->input('jeep_quantity'))) {
            $passengerCount = (int) $request->input('passenger_count', 4);
            $request->merge(['jeep_quantity' => max(1, (int) ceil($passengerCount / 4))]);
        }
        if (!$request->has('arrival_type') || empty($request->input('arrival_type'))) {
            $zoneId = $request->input('pickup_zone_id');
            $request->merge(['arrival_type' => (!empty($zoneId) && $zoneId > 1) ? 'pickup' : 'self_drive']);
        }
        if (!$request->has('payment_mode') || empty($request->input('payment_mode'))) {
            $request->merge(['payment_mode' => 'FULL']);
        }

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'slot_id' => 'required|exists:schedule_slots,id',
            'trip_date' => 'required|date|after_or_equal:today',
            'passenger_count' => 'required|integer|min:1',
            'child_count' => 'nullable|integer|min:0',
            'jeep_quantity' => 'nullable|integer|min:1',
            'arrival_type' => 'nullable|in:self_drive,pickup',
            'pickup_zone_id' => 'nullable|exists:pickup_zones,id',
            'pickup_address' => 'nullable|string|max:500',
            'pickup_distance_km' => 'nullable|numeric|min:0|max:200',
            'pickup_lat' => 'nullable|numeric|between:-90,90',
            'pickup_lng' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string|max:500',
            'vehicle_type' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'companion_count' => 'nullable|integer|min:0',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
            'payment_mode' => 'required|in:FULL,DP',
        ], [
            'customer_name.required' => 'Nama lengkap pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'trip_date.required' => 'Tanggal wisata wajib dipilih.',
            'trip_date.after_or_equal' => 'Tanggal wisata tidak boleh di masa lalu.',
            'slot_id.required' => 'Jadwal waktu keberangkatan wajib dipilih.',
            'package_id.required' => 'Paket wisata wajib dipilih.',
            'passenger_count.required' => 'Jumlah orang wajib diisi.',
            'passenger_count.min' => 'Jumlah peserta minimal 1 orang.',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        $pax = (int) $validated['passenger_count'];
        $childCount = (int) ($validated['child_count'] ?? 0);

        // Aturan Kapasitas Lereng Merapi:
        // - Normal maksimal 4 orang per Jeep.
        // - 5 orang dengan minimal 1 anak-anak boleh dalam 1 Jeep.
        // - 5 orang dewasa tanpa anak membutuhkan minimal 2 Jeep.
        if ($pax === 5 && $childCount >= 1) {
            $minJeeps = 1;
        } else {
            $minJeeps = (int) ceil($pax / 4);
        }

        $jeepQuantity = max((int) ($validated['jeep_quantity'] ?? $minJeeps), $minJeeps);

        // Kalkulasi Biaya secara Presisi (Integer Rupiah Murni)
        $packagePrice = (int) $package->price_per_jeep;
        $packageSubtotal = $packagePrice * $jeepQuantity;

        // Biaya penjemputan otomatis berdasarkan jarak rute dari basecamp.
        // Gratis sampai PICKUP_FREE_KM, selebihnya dikenakan per km (dibulatkan ke atas).
        // Dihitung ulang di server agar tidak bisa dimanipulasi dari browser.
        $pickupFee = 0;
        $pickupInfo = '';
        if ($validated['arrival_type'] === 'pickup') {
            if (!isset($validated['pickup_lat'], $validated['pickup_lng'])) {
                return back()->withInput()->withErrors([
                    'pickup_address' => 'Titik lokasi penjemputan wajib ditentukan (bagikan lokasi atau tempel link Google Maps).',
                ]);
            }

            $lat = (float) $validated['pickup_lat'];
            $lng = (float) $validated['pickup_lng'];
            $clientKm = isset($validated['pickup_distance_km']) ? (float) $validated['pickup_distance_km'] : null;
            $km = $this->resolvePickupDistanceKm($lat, $lng, $clientKm);

            $pickupFee = (int) (ceil(max(0.0, $km - self::PICKUP_FREE_KM)) * self::PICKUP_EXTRA_PER_KM);
            $pickupInfo = sprintf(
                '[Jarak jemput: %s km | https://maps.google.com/?q=%s,%s]',
                number_format($km, 1, '.', ''),
                $lat,
                $lng
            );

            // Simpan relasi zona agar halaman pembayaran/tiket tetap berfungsi.
            if (empty($validated['pickup_zone_id']) || (int) $validated['pickup_zone_id'] <= 1) {
                $validated['pickup_zone_id'] = PickupZone::where('id', '>', 1)->orderBy('id')->value('id')
                    ?? $validated['pickup_zone_id'] ?? null;
            }
        }

        $pickupAddress = !empty($validated['notes'])
            ? trim(($validated['pickup_address'] ?? '') . ' [Catatan: ' . $validated['notes'] . ']')
            : ($validated['pickup_address'] ?? null);

        if ($pickupInfo !== '') {
            $pickupAddress = trim(Str::limit((string) $pickupAddress, 330, '') . ' ' . $pickupInfo);
        }

        $totalAmount = $packageSubtotal + $pickupFee;

        if ($validated['payment_mode'] === 'FULL') {
            $payableAmount = $totalAmount;
            $remainingAmount = 0;
        } else {
            // DP minimal 30% dari total tagihan (pembulatan ke atas tanpa float)
            $payableAmount = (int) intdiv(($totalAmount * 30) + 99, 100);
            $remainingAmount = $totalAmount - $payableAmount;
        }

        // Generate Booking Code Unik
        $bookingCode = 'WKR-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'package_id' => $package->id,
            'slot_id' => $validated['slot_id'],
            'trip_date' => $validated['trip_date'],
            'jeep_quantity' => $jeepQuantity,
            'passenger_count' => $validated['passenger_count'],
            'arrival_type' => $validated['arrival_type'],
            'pickup_zone_id' => $validated['pickup_zone_id'] ?? null,
            'pickup_address' => $pickupAddress,
            'package_subtotal' => $packageSubtotal,
            'pickup_fee' => $pickupFee,
            'total_amount' => $totalAmount,
            'paid_amount' => $payableAmount,
            'remaining_amount' => $remainingAmount,
            'payment_mode' => $validated['payment_mode'],
            'booking_status' => 'PENDING',
            'lock_expires_at' => now()->addMinutes(30),
            'snap_token' => null,
        ]);

        return redirect()->route('booking.payment', $booking->booking_code);
    }

    /**
     * Jarak rute basecamp -> titik jemput (km, 1 desimal).
     * Memakai OSRM; jika gagal dijangkau, cadangan: jarak garis lurus x 1,3.
     * Nilai dari browser hanya dipakai jika selisihnya <= 1 km dari hitungan server.
     */
    private function resolvePickupDistanceKm(float $lat, float $lng, ?float $clientKm): float
    {
        $serverKm = null;

        try {
            $url = sprintf(
                'https://router.project-osrm.org/route/v1/driving/%F,%F;%F,%F',
                self::BASECAMP_LNG, self::BASECAMP_LAT, $lng, $lat
            );
            $res = Http::timeout(5)->get($url, ['overview' => 'false']);
            if ($res->ok() && $res->json('code') === 'Ok') {
                $serverKm = ((float) $res->json('routes.0.distance')) / 1000;
            }
        } catch (\Throwable $e) {
            Log::warning('OSRM tidak dapat dijangkau: ' . $e->getMessage());
        }

        if ($serverKm === null) {
            $serverKm = $this->haversineKm(self::BASECAMP_LAT, self::BASECAMP_LNG, $lat, $lng) * 1.3;
        }

        $serverKm = round($serverKm, 1);

        if ($clientKm !== null && abs($clientKm - $serverKm) <= 1.0) {
            return round($clientKm, 1);
        }

        return $serverKm;
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * $r * asin(sqrt($a));
    }

    public function payment($code)
    {
        $booking = Booking::with(['package', 'slot', 'pickupZone', 'payments'])
            ->where('booking_code', $code)
            ->firstOrFail();

        // Jika booking sudah selesai dibayar, langsung alihkan ke e-tiket
        if (in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            return redirect()->route('booking.success', $booking->booking_code);
        }

        return view('booking.payment', compact('booking'));
    }

    public function success(string $code)
    {
        $booking = Booking::with(['package', 'slot', 'pickupZone', 'payments'])
            ->where('booking_code', $code)
            ->firstOrFail();

        // 1. Guard Status Booking: Hanya izinkan tiket diakses jika sudah lunas atau DP
        if (!in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            if ($booking->booking_status === 'PENDING') {
                return redirect()->route('booking.payment', $booking->booking_code)
                    ->with('warning', 'Selesaikan pembayaran Anda untuk menerbitkan E-Tiket resmi.');
            }

            return redirect()->route('booking.create')
                ->with('error', 'Pesanan ini tidak valid atau telah dibatalkan.');
        }

        // 2. Guard Finansial: Verifikasi Silang Akumulasi Pembayaran Sah (SETTLED) di Basis Data
        $totalAmountInt = (int) round($booking->total_amount);
        $paidAmountInt  = (int) round($booking->paid_amount);
        $remainingInt   = (int) round($booking->remaining_amount);

        $settledPayments = $booking->payments->where('payment_status', 'SETTLED');
        $accumulatedSettledInt = (int) round($settledPayments->sum('gross_amount'));

        if ($booking->booking_status === 'FULL_PAID') {
            // Skenario Lunas (Lunas Online ATAU DP Online + Pelunasan Kasir Basecamp)
            if ($settledPayments->isEmpty() || $remainingInt !== 0 || $accumulatedSettledInt !== $totalAmountInt) {
                Log::warning("Inkonsistensi finansial pada tiket FULL_PAID: {$booking->booking_code}. Akumulasi: {$accumulatedSettledInt}, Total: {$totalAmountInt}");
                return redirect()->route('booking.payment', $booking->booking_code)
                    ->with('error', 'Pembayaran sah belum terverifikasi secara lengkap oleh sistem.');
            }
        } elseif ($booking->booking_status === 'DP_PAID') {
            // Skenario DP (Uang Muka Sah Online)
            if ($settledPayments->isEmpty() || $remainingInt <= 0 || $accumulatedSettledInt !== $paidAmountInt) {
                Log::warning("Inkonsistensi finansial pada tiket DP_PAID: {$booking->booking_code}. Akumulasi: {$accumulatedSettledInt}, Paid: {$paidAmountInt}");
                return redirect()->route('booking.payment', $booking->booking_code)
                    ->with('error', 'Pembayaran uang muka sah belum terverifikasi oleh sistem.');
            }
        }

        return view('booking.success', compact('booking'));
    }
}