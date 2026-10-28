<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Jeep;
use App\Models\Driver;
use App\Models\BookingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])->sum('paid_amount');
        $readyJeepsCount = Jeep::where('status', 'READY')->count();
        $totalJeepsCount = Jeep::count();
        $pendingDpCount = Booking::where('booking_status', 'DP_PAID')->count();
        $pendingDpAmount = Booking::where('booking_status', 'DP_PAID')->sum('remaining_amount');

        $bookings = Booking::with(['package', 'slot', 'assignments.jeep', 'assignments.driver'])
            ->orderBy('id', 'desc')
            ->get();

        $jeeps = Jeep::all();
        $drivers = Driver::where('is_active', true)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'readyJeepsCount',
            'totalJeepsCount',
            'pendingDpCount',
            'pendingDpAmount',
            'bookings',
            'jeeps',
            'drivers'
        ));
    }

    public function settleDp($id)
    {
        return DB::transaction(function () use ($id) {
            $booking = Booking::where('id', $id)->lockForUpdate()->firstOrFail();

            // Guard: Hanya booking DP_PAID yang memiliki sisa tagihan valid yang boleh dilunasi
            if ($booking->booking_status !== 'DP_PAID' || (float) $booking->remaining_amount <= 0) {
                return redirect()->back()->with('error', "Pesanan {$booking->booking_code} tidak memiliki sisa tagihan untuk dilunasi.");
            }

            // Tangkap nilai sisa tagihan sebelum di-nol-kan
            $settlementAmount = (int) round($booking->remaining_amount);

            // Catat transaksi pelunasan tunai di kasir basecamp
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_ref' => 'LOKET-' . $booking->booking_code . '-' . strtoupper(Str::random(6)),
                'payment_stage' => 'SETTLEMENT',
                'payment_type' => 'CASH_BASECAMP',
                'gross_amount' => $settlementAmount,
                'payment_status' => 'SETTLED',
                'paid_at' => now(),
            ]);

            // Perbarui status booking menjadi lunas
            $booking->update([
                'booking_status' => 'FULL_PAID',
                'paid_amount' => $booking->total_amount,
                'remaining_amount' => 0.00,
            ]);

            return redirect()->back()->with('success', "Pelunasan tunai sebesar Rp " . number_format($settlementAmount, 0, ',', '.') . " untuk booking {$booking->booking_code} berhasil dicatat!");
        });
    }

    public function assignDriver(Request $request, $id)
    {
        $validated = $request->validate([
            'jeep_id' => 'required|exists:jeeps,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        BookingAssignment::updateOrCreate(
            ['booking_id' => $id],
            [
                'jeep_id' => $validated['jeep_id'],
                'driver_id' => $validated['driver_id'],
                'assigned_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Armada dan driver berhasil ditugaskan!');
    }
}
