<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\PickupZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $selectedSlug = $request->query('package', 'short-trip');
        $packages = Package::where('is_active', true)->get();
        $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();
        
        $slots = ScheduleSlot::where('is_active', true)->get();
        $pickupZones = PickupZone::all();
        $jeeps = \App\Models\Jeep::all();

        return view('booking.create', compact('packages', 'selectedPackage', 'slots', 'pickupZones', 'jeeps'));
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

        $pickupFee = 0;
        if ($validated['arrival_type'] === 'pickup' && !empty($validated['pickup_zone_id'])) {
            $zone = PickupZone::find($validated['pickup_zone_id']);
            if ($zone) {
                $pickupFee = (int) $zone->additional_fee;
            }
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
            'pickup_address' => !empty($validated['notes']) 
                ? trim(($validated['pickup_address'] ?? '') . ' [Catatan: ' . $validated['notes'] . ']')
                : ($validated['pickup_address'] ?? null),
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
