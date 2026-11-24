<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login tunggal (Universal untuk Wisatawan & Admin)
     */
    public function showLogin()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->filled('remember');

        // Ambil pending booking dari session sebelum session diregenerasi
        $pendingBookingCode = $request->session()->get('pending_booking_code');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', "Selamat datang di Panel Admin Loket, {$user->name}!");
            }

            // Klaim booking guest otomatis dari session jika ada
            $this->claimGuestBookingFromSession($user, $pendingBookingCode);

            return redirect()->intended(route('home'))
                ->with('success', "Halo {$user->name}, berhasil masuk ke akun Anda!");
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan formulir pendaftaran akun wisatawan baru
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun wisatawan
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Simpan kode booking guest dari session sebelum registrasi
        $pendingBookingCode = $request->session()->get('pending_booking_code');

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'role'     => 'user',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Klaim booking guest otomatis dari session jika ada
        $this->claimGuestBookingFromSession($user, $pendingBookingCode);

        return redirect()->route('home')->with('success', "Akun Anda berhasil didaftarkan! Selamat datang, {$user->name}.");
    }

    /**
     * Halaman profil akun
     */
    public function profile()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Siapkan varian format nomor telepon untuk pencarian yang toleran terhadap format (+62 / 0 / 62)
        $phoneVariants = [];
        if (!empty($user->phone)) {
            $rawPhone = trim($user->phone);
            $cleanDigits = preg_replace('/[^0-9]/', '', $rawPhone);
            $phoneVariants[] = $rawPhone;
            if ($cleanDigits) {
                $phoneVariants[] = $cleanDigits;
                if (str_starts_with($cleanDigits, '0')) {
                    $phoneVariants[] = '62' . substr($cleanDigits, 1);
                    $phoneVariants[] = '+62' . substr($cleanDigits, 1);
                } elseif (str_starts_with($cleanDigits, '62')) {
                    $phoneVariants[] = '0' . substr($cleanDigits, 2);
                    $phoneVariants[] = '+' . $cleanDigits;
                }
            }
        }

        // Ambil riwayat pemesanan yang cocok dengan email atau varian nomor hp user
        $myBookings = Booking::with(['package', 'slot'])
            ->where(function ($q) use ($user, $phoneVariants) {
                $q->where('customer_email', $user->email);
                if (!empty($phoneVariants)) {
                    $q->orWhereIn('customer_phone', array_unique($phoneVariants));
                }
            })
            ->orderBy('id', 'desc')
            ->get();

        $myReviews = \App\Models\Review::with('package')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('auth.profile', compact('user', 'myBookings', 'myReviews'));
    }

    /**
     * Update profil akun
     */
    public function updateProfile(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone'    => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Klaim booking guest secara manual dari halaman profil.
     *
     * Validasi keamanan:
     * - Booking harus ada dan kode valid.
     * - customer_email atau customer_phone harus cocok dengan akun yang login.
     * - Mencegah klaim oleh akun yang tidak berhak.
     * - Idempoten: booking yang sudah terhubung tidak diproses ulang.
     */
    public function claimBooking(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'booking_code' => 'required|string|max:30',
        ]);

        $code = strtoupper(trim($request->input('booking_code')));

        $booking = Booking::where('booking_code', $code)->first();

        if (!$booking) {
            return redirect()->route('profile')
                ->with('error', "Kode booking '{$code}' tidak ditemukan. Periksa kembali kode booking Anda.");
        }

        $emailMatch = !empty($booking->customer_email) && strtolower(trim($booking->customer_email)) === strtolower(trim($user->email));
        $phoneMatch = $this->phonesMatch($booking->customer_phone, $user->phone);

        // Jika booking sudah terhubung ke email akun ini
        if ($emailMatch) {
            return redirect()->route('profile')
                ->with('info', "Booking {$code} sudah terdaftar di riwayat pesanan Anda.");
        }

        // Cegah klaim booking jika sudah milik akun lain yang terdaftar
        if (!empty($booking->customer_email) && User::where('email', $booking->customer_email)->where('id', '!=', $user->id)->exists()) {
            Log::warning('Booking claim ditolak: booking sudah milik akun lain', [
                'user_id'      => $user->id,
                'booking_code' => $code,
            ]);
            return redirect()->route('profile')
                ->with('error', "Booking {$code} tidak dapat diklaim karena sudah terdaftar atas nama akun lain.");
        }

        // Keamanan: Validasi bahwa pemesan memang pemilik data (harus cocok nomor HP atau email)
        if (!$phoneMatch) {
            Log::warning('Booking claim ditolak: data kontak tidak cocok', [
                'user_id'       => $user->id,
                'booking_code'  => $code,
                'booking_phone' => $booking->customer_phone,
                'user_phone'    => $user->phone,
            ]);

            return redirect()->route('profile')
                ->with('error', "Booking {$code} tidak dapat diklaim. Nomor HP atau email pada pesanan tidak cocok dengan akun Anda.");
        }

        // Hubungkan booking ke user
        $booking->customer_email = $user->email;
        $booking->save();

        return redirect()->route('profile')
            ->with('success', "Booking {$code} berhasil dihubungkan ke akun Anda!");
    }

    /**
     * Klaim booking guest otomatis dari session setelah login/register.
     */
    private function claimGuestBookingFromSession(User $user, ?string $bookingCode = null): void
    {
        $code = $bookingCode ?: session('pending_booking_code');

        if (!$code) {
            return;
        }

        try {
            $booking = Booking::where('booking_code', $code)->first();

            if (!$booking) {
                session()->forget('pending_booking_code');
                return;
            }

            // Jika email booking sudah cocok, cukup bersihkan session
            if ($booking->customer_email && strtolower(trim($booking->customer_email)) === strtolower(trim($user->email))) {
                session()->forget('pending_booking_code');
                return;
            }

            // Validasi kepemilikan:
            // Klaim aman jika:
            // 1. Nomor HP booking cocok dengan nomor HP user, ATAU
            // 2. Email booking kosong (guest murni tanpa email) dan email belum terdaftar di akun lain
            $phoneMatch = $this->phonesMatch($booking->customer_phone, $user->phone);
            $emailBelongsToOther = !empty($booking->customer_email) && User::where('email', $booking->customer_email)->where('id', '!=', $user->id)->exists();

            if (($phoneMatch || empty($booking->customer_email)) && !$emailBelongsToOther) {
                $booking->customer_email = $user->email;
                if (empty($booking->customer_phone) && !empty($user->phone)) {
                    $booking->customer_phone = $user->phone;
                }
                $booking->save();

                Log::info('Booking guest diklaim otomatis setelah login/register', [
                    'user_id'      => $user->id,
                    'booking_code' => $code,
                ]);
            }

            session()->forget('pending_booking_code');
        } catch (\Throwable $e) {
            Log::error('Gagal klaim booking guest dari session: ' . $e->getMessage(), [
                'user_id'      => $user->id,
                'booking_code' => $code,
            ]);
        }
    }

    /**
     * Helper untuk membandingkan format nomor telepon Indonesia secara fleksibel (+62, 0, 62)
     */
    private function phonesMatch(?string $phone1, ?string $phone2): bool
    {
        if (empty($phone1) || empty($phone2)) {
            return false;
        }

        $clean1 = preg_replace('/[^0-9]/', '', $phone1);
        $clean2 = preg_replace('/[^0-9]/', '', $phone2);

        if (empty($clean1) || empty($clean2)) {
            return false;
        }

        // Normalisasi format awal 08... -> 628...
        if (str_starts_with($clean1, '0')) {
            $clean1 = '62' . substr($clean1, 1);
        }
        if (str_starts_with($clean2, '0')) {
            $clean2 = '62' . substr($clean2, 1);
        }

        return $clean1 === $clean2;
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar dari akun.');
    }
}
