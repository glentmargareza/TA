<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\PickupZone;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // ====== PENGATURAN PENJEMPUTAN ======
    // GANTI koordinat basecamp dengan titik yang benar (klik kanan di Google Maps > salin koordinat)
    private const BASECAMP_LAT = -7.6352;
    private const BASECAMP_LNG = 110.4366;
    private const FREE_KM      = 3;       // jarak jemput gratis (km)
    private const FEE_PER_JEEP = 50000;   // biaya tambahan per jeep jika jarak > FREE_KM

    public function create(Request $request)
    {
        $selectedSlug = $request->query('package', 'short-trip');
        $packages = Package::where('is_active', true)->get();
        $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();

        $slots = ScheduleSlot::where('is_active', true)->get();

        // Data untuk JavaScript di halaman (harga dan sesi diambil dari database)
        $bookingData = [
            'basecamp'   => ['lat' => self::BASECAMP_LAT, 'lng' => self::BASECAMP_LNG],
            'freeKm'     => self::FREE_KM,
            'feePerJeep' => self::FEE_PER_JEEP,
            'selectedId' => $selectedPackage?->id,
            'packages'   => $packages->map(fn ($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (int) $p->price_per_jeep,
                'dur'   => $p->duration ?? '',
            ])->values(),
            'slots'      => $slots->map(function ($s) {
                $time  = substr((string) $s->start_time, 0, 5);          // contoh: 08:00
                $label = $s->slot_label ?: ('Sesi ' . $s->id);
                return [
                    'id'    => $s->id,
                    'label' => $label . ' (' . $time . ' WIB)',          // contoh: Sesi 1 (08:00 WIB)
                    'time'  => $time,
                ];
            })->values(),
        ];

        return view('booking.create', compact('packages', 'selectedPackage', 'slots', 'bookingData'));
    }

    public function store(Request $request)
    {
        if (!$request->has('jeep_quantity') || empty($request->input('jeep_quantity'))) {
            $passengerCount = (int) $request->input('passenger_count', 4);
            $request->merge(['jeep_quantity' => max(1, (int) ceil($passengerCount / 4))]);
        }
        if (!$request->has('payment_mode') || empty($request->input('payment_mode'))) {
            $request->merge(['payment_mode' => 'FULL']);
        }

        $needsCoords = $request->input('arrival_type') === 'pickup' && !$request->boolean('pickup_manual');

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'slot_id' => 'required|exists:schedule_slots,id',
            'trip_date' => 'required|date|after_or_equal:today',
            'passenger_count' => 'required|integer|min:1',
            'child_count' => 'nullable|integer|min:0',
            'jeep_quantity' => 'nullable|integer|min:1',
            'arrival_type' => 'nullable|in:self_drive,pickup',
            'pickup_address' => 'required_if:arrival_type,pickup|nullable|string|max:500',
            'pickup_lat' => ['nullable', 'numeric', 'between:-11.5,6.5', Rule::requiredIf($needsCoords)],
            'pickup_lng' => ['nullable', 'numeric', 'between:94,141.5', Rule::requiredIf($needsCoords)],
            'pickup_manual' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
            'customer_notes' => 'nullable|string|max:500',
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
            'pickup_address.required_if' => 'Nama hotel / alamat penjemputan wajib diisi.',
            'pickup_lat.required' => 'Lokasi penjemputan belum ditentukan. Pilih hotel dari daftar atau bagikan lokasi.',
            'pickup_lng.required' => 'Lokasi penjemputan belum ditentukan. Pilih hotel dari daftar atau bagikan lokasi.',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        $pax = (int) $validated['passenger_count'];
        $childCount = (int) ($validated['child_count'] ?? 0);

        // Aturan kapasitas: maks. 4 dewasa per jeep; 5 orang boleh 1 jeep jika ada anak-anak.
        $minJeeps = ($pax === 5 && $childCount >= 1) ? 1 : (int) ceil($pax / 4);
        $jeepQuantity = max((int) ($validated['jeep_quantity'] ?? $minJeeps), $minJeeps);

        $packageSubtotal = $package->price_per_jeep * $jeepQuantity;

        // ====== BIAYA JEMPUT (dihitung ulang di server, bukan dari browser) ======
        // Gratis sampai FREE_KM; lebih dari itu FEE_PER_JEEP x jumlah jeep.
        $arrivalType = $validated['arrival_type'] ?? 'self_drive';
        $address     = $validated['pickup_address'] ?? null;
        $pickupFee   = 0.00;
        $pickupKm    = null;
        $pickupLat   = null;
        $pickupLng   = null;

        if ($arrivalType === 'pickup') {
            if ($request->boolean('pickup_manual')) {
                $address = trim(($address ?? '') . ' [Biaya jemput menunggu konfirmasi admin]');
            } else {
                $pickupLat = (float) $validated['pickup_lat'];
                $pickupLng = (float) $validated['pickup_lng'];
                $pickupKm  = $this->routeDistanceKm($pickupLat, $pickupLng);
                $pickupFee = $pickupKm > self::FREE_KM ? self::FEE_PER_JEEP * $jeepQuantity : 0.00;
            }
        } else {
            $address = null;
        }

        if (!empty($validated['notes'])) {
            $address = trim(($address ?? '') . ' [Catatan: ' . $validated['notes'] . ']');
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

        $bookingCode = 'WKR-' . strtoupper(Str::random(6));

        // Midtrans Snap Token
        $snapToken = null;
        try {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized', true);
            \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds', true);

            $params = [
                'transaction_details' => [
                    'order_id' => $bookingCode,
                    'gross_amount' => (int) $payableAmount,
                ],
                'customer_details' => [
                    'first_name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'email' => $validated['customer_email'] ?? 'wisatawan@jeepwukirsari.id',
                ],
                'item_details' => [
                    [
                        'id' => $package->id,
                        'price' => (int) $payableAmount,
                        'quantity' => 1,
                        'name' => ($validated['payment_mode'] === 'DP' ? 'DP (30%) ' : 'Lunas (100%) ') . substr($package->name, 0, 30),
                    ]
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::warning('Midtrans Snap Token skipped: ' . $e->getMessage());
        }

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? ($validated['notes'] ?? null),
            'package_id' => $package->id,
            'slot_id' => $validated['slot_id'],
            'trip_date' => $validated['trip_date'],
            'jeep_quantity' => $jeepQuantity,
            'passenger_count' => $validated['passenger_count'],
            'arrival_type' => $arrivalType,
            'pickup_zone_id' => null,
            'pickup_address' => $address,
            'pickup_lat' => $pickupLat,
            'pickup_lng' => $pickupLng,
            'pickup_distance_km' => $pickupKm,
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

        // 1. Notifikasi: Ada booking baru
        ActivityLog::logBookingNew($booking);

        // 2. Notifikasi: Cek apakah kapasitas jadwal sudah penuh
        $slot = ScheduleSlot::find($validated['slot_id']);
        $maxJeeps = $slot?->max_jeeps ?? 20;
        $totalBookedInSlot = Booking::where('trip_date', $validated['trip_date'])
            ->where('slot_id', $validated['slot_id'])
            ->whereIn('booking_status', ['FULL_PAID', 'DP_PAID', 'PENDING'])
            ->sum('jeep_quantity');

        if ($totalBookedInSlot >= $maxJeeps) {
            ActivityLog::logScheduleFull($slot, $validated['trip_date'], $maxJeeps);
        }

        if (in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            return redirect()->route('booking.success', $booking->booking_code);
        }

        return redirect()->route('booking.payment', $booking->booking_code);
    }

    public function payment(string $code)
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

    // ====== HITUNG JARAK RUTE JALAN DARI BASECAMP (OSRM, cadangan: garis lurus x 1.3) ======
    private function routeDistanceKm(float $lat, float $lng): float
    {
        try {
            $url = sprintf(
                'https://router.project-osrm.org/route/v1/driving/%F,%F;%F,%F',
                self::BASECAMP_LNG, self::BASECAMP_LAT, $lng, $lat
            );
            $res = Http::timeout(6)->get($url, ['overview' => 'false']);
            if ($res->ok() && $res->json('code') === 'Ok') {
                return round(((float) $res->json('routes.0.distance')) / 1000, 1);
            }
        } catch (\Throwable $e) {
            Log::warning('OSRM gagal, pakai jarak perkiraan: ' . $e->getMessage());
        }
        return round($this->haversineKm(self::BASECAMP_LAT, self::BASECAMP_LNG, $lat, $lng) * 1.3, 1);
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return 2 * $r * asin(sqrt($a));
    }
}