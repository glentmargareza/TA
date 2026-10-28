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
        $selectedSlug = $request->query('package', 'adventure');
        $packages = Package::where('is_active', true)->get();
        $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();
        
        $slots = ScheduleSlot::where('is_active', true)->get();
        $pickupZones = PickupZone::all();

        return view('booking.create', compact('packages', 'selectedPackage', 'slots', 'pickupZones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'slot_id' => 'required|exists:schedule_slots,id',
            'trip_date' => 'required|date|after_or_equal:today',
            'passenger_count' => 'required|integer|min:1',
            'jeep_quantity' => 'required|integer|min:1',
            'arrival_type' => 'required|in:self_drive,pickup',
            'pickup_zone_id' => 'nullable|exists:pickup_zones,id',
            'pickup_address' => 'nullable|string|max:500',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:100',
            'payment_mode' => 'required|in:FULL,DP',
        ]);

        $package = Package::findOrFail($validated['package_id']);
        
        // Aturan Keselamatan Lereng Merapi: 1 Jeep maksimal 4 orang
        $minJeeps = (int) ceil($validated['passenger_count'] / 4);
        $jeepQuantity = max((int) $validated['jeep_quantity'], $minJeeps);

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
            'pickup_address' => $validated['pickup_address'] ?? null,
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

    public function success($code)
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
