<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function check(Request $request)
    {
        $code = $request->query('code');
        $booking = null;
        $hasSearched = false;

        if ($code) {
            $hasSearched = true;
            $booking = Booking::with(['package', 'slot'])
                ->where('booking_code', strtoupper(trim($code)))
                ->orWhere('customer_phone', trim($code))
                ->first();
        }

        return view('ticket.check', compact('booking', 'hasSearched', 'code'));
    }
}
