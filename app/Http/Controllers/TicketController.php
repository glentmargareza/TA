<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Halaman pencarian tiket mandiri (login customer — /cek-tiket)
     */
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

    /**
     * Halaman publik verifikasi tiket via QR Code — /tiket/{code}
     * Dapat diakses siapapun (customer scan QR sendiri), TANPA butuh login.
     * Hanya menampilkan status booking; tidak mencatat pembayaran apapun.
     * Admin yang perlu memproses pelunasan menggunakan /admin/scan/{code}.
     */
    public function show(string $code)
    {
        $code = strtoupper(trim($code));
        $booking = Booking::with(['package', 'slot'])->where('booking_code', $code)->first();

        // Jika user sudah login sebagai admin, langsung redirect ke halaman admin scan
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.scan', $code);
        }

        return view('ticket.show', compact('booking', 'code'));
    }
}
