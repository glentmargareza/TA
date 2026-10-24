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
        $jeepQuantity = max($validated['jeep_quantity'], $minJeeps);

        // Kalkulasi Biaya
        $packageSubtotal = $package->price_per_jeep * $jeepQuantity;
        
        $pickupFee = 0.00;
        if ($validated['arrival_type'] === 'pickup' && !empty($validated['pickup_zone_id'])) {
            $zone = PickupZone::find($validated['pickup_zone_id']);
            if ($zone) {
                $pickupFee = $zone->additional_fee;
            }
        }

        $totalAmount = $packageSubtotal + $pickupFee;
        
        if ($validated['payment_mode'] === 'FULL') {
            $payableAmount = $totalAmount;
            $remainingAmount = 0.00;
            $status = 'FULL_PAID';
        } else {
            $payableAmount = round($totalAmount * 0.30); // DP 30%
            $remainingAmount = $totalAmount - $payableAmount;
            $status = 'DP_PAID';
        }

        // Generate Booking Code Unik
        $bookingCode = 'WKR-' . strtoupper(Str::random(6));

        // Generate Midtrans Snap Token
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
            'booking_status' => $status,
            'snap_token' => $snapToken,
        ]);

        return redirect()->route('booking.success', $booking->booking_code);
    }

    public function success($code)
    {
        $booking = Booking::with(['package', 'slot', 'pickupZone'])->where('booking_code', $code)->firstOrFail();
        return view('booking.success', compact('booking'));
    }
}
