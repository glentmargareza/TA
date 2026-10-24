<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jeep;
use App\Models\Driver;
use App\Models\BookingAssignment;
use Illuminate\Http\Request;

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
        $booking = Booking::findOrFail($id);
        $booking->update([
            'booking_status' => 'FULL_PAID',
            'paid_amount' => $booking->total_amount,
            'remaining_amount' => 0.00,
        ]);

        return redirect()->back()->with('success', "Pelunasan untuk booking {$booking->booking_code} berhasil diterima!");
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
