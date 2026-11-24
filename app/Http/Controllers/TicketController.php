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
            $cleanCode = trim($code);
            $booking = Booking::with(['package', 'slot'])
                ->where('qr_token', $cleanCode)
                ->orWhere('booking_code', strtoupper($cleanCode))
                ->orWhere('customer_phone', $cleanCode)
                ->first();
        }

        return view('ticket.check', compact('booking', 'hasSearched', 'code'));
    }
}
