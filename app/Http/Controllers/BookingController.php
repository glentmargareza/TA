<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // ====== PENGATURAN PENJEMPUTAN ======
    // Titik lokasi basecamp presisi: Bedoyo, Wukirsari, Cangkringan, Sleman, DIY
    private const DEFAULT_LAT  = -7.6437244;
    private const DEFAULT_LNG  = 110.4338597;
    private const FREE_KM      = 5;        // Jarak jemput gratis (km)
    private const FEE_PER_JEEP = 50000;    // Biaya tambahan per jeep jika jarak > FREE_KM

    private function baseLat(): float
    {
        return (float) (env('BASECAMP_LAT') ?: self::DEFAULT_LAT);
    }

    private function baseLng(): float
    {
        return (float) (env('BASECAMP_LNG') ?: self::DEFAULT_LNG);
    }

    private function browserKey(): ?string
    {
        return config('services.google.maps_browser_key') ?: (env('GOOGLE_MAPS_BROWSER_KEY') ?: null);
    }

    private function serverKey(): ?string
    {
        return config('services.google.maps_server_key')
            ?: (env('GOOGLE_MAPS_SERVER_KEY') ?: (env('GOOGLE_MAPS_BROWSER_KEY') ?: null));
    }

    private function apiKey(): ?string
    {
        return config('services.google.maps_api_key')
            ?: (env('GOOGLE_MAPS_API_KEY')
            ?: ($this->serverKey() ?: $this->browserKey()));
    }

    public function create(Request $request)
    {
        $selectedSlug = $request->query('package', 'short-trip');
        $packages = Package::where('is_active', true)->get();
        $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();

        $slots = ScheduleSlot::where('is_active', true)->get();

        $bookingData = [
            'basecamp'     => ['lat' => $this->baseLat(), 'lng' => $this->baseLng()],
            'freeKm'       => self::FREE_KM,
            'feePerJeep'   => self::FEE_PER_JEEP,
            'gmapsKey'     => $this->browserKey(),
            'hasGoogleKey' => !empty($this->apiKey()),
            'distanceUrl'  => route('booking.distance'),
            'resolveUrl'   => route('places.resolve_place'),
            'searchUrl'    => route('places.autocomplete'),
            'detailUrl'    => route('places.details'),
            'reverseUrl'   => route('booking.reverse'),
            'selectedId'   => $selectedPackage?->id,
            'packages'    => $packages->map(fn ($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (int) $p->price_per_jeep,
                'dur'   => $p->duration ?? '',
            ])->values(),
            'slots'       => $slots->map(function ($s) {
                $time  = substr((string) $s->start_time, 0, 5);
                $label = $s->slot_label ?: ('Sesi ' . $s->id);
                return [
                    'id'    => $s->id,
                    'label' => $label . ' (' . $time . ' WIB)',
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

        $minJeeps = ($pax === 5 && $childCount >= 1) ? 1 : (int) ceil($pax / 4);
        $jeepQuantity = max((int) ($validated['jeep_quantity'] ?? $minJeeps), $minJeeps);

        $packageSubtotal = $package->price_per_jeep * $jeepQuantity;

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
                $pickupKm  = $this->routeDistance($pickupLat, $pickupLng)['km'];
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
            $remainingAmount = 0.00;
        } else {
            $payableAmount = round($totalAmount * 0.30);
            $remainingAmount = $totalAmount - $payableAmount;
        }

        $bookingCode = 'WKR-' . strtoupper(Str::random(6));

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
            'snap_token' => $snapToken,
        ]);

        // Pencatatan otomatis kandidat hotel baru jika antar-jemput dan belum ada di hotels.json (> 100 m)
        if ($arrivalType === 'pickup' && $pickupLat !== null && $pickupLng !== null && !empty($validated['pickup_address'])) {
            try {
                $this->recordCandidateHotel($validated['pickup_address'], $pickupLat, $pickupLng);
            } catch (\Throwable $e) {
                Log::warning('Gagal mencatat kandidat hotel: ' . $e->getMessage());
            }
        }

        return redirect()->route('booking.payment', $booking->booking_code);
    }

    public function payment(string $code)
    {
        $booking = Booking::with(['package', 'slot', 'pickupZone', 'payments' => function ($q) {
                $q->orderBy('id', 'desc');
            }])
            ->where('booking_code', $code)
            ->firstOrFail();

        // Jika booking sudah selesai dibayar, langsung alihkan ke e-tiket
        if (in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            $hasSettled = $booking->payments->where('payment_status', 'SETTLED')->isNotEmpty();
            if ($hasSettled || $booking->payments->isEmpty()) {
                return redirect()->route('booking.success', $booking->booking_code);
            }
        }

        // Auto-expire jika booking PENDING telah melewati batas waktu lock (mencegah slot tertahan tanpa batas)
        if ($booking->booking_status === 'PENDING' && $booking->lock_expires_at && $booking->lock_expires_at->isPast()) {
            $booking->update([
                'booking_status' => 'CANCELLED',
            ]);
            $booking->payments()->where('payment_status', 'PENDING')->update([
                'payment_status' => 'EXPIRED',
            ]);

            return redirect()->route('booking.create')
                ->with('error', 'Batas waktu pembayaran untuk reservasi ' . $booking->booking_code . ' telah berakhir. Silakan buat reservasi baru.');
        }

        return view('booking.payment', compact('booking'));
    }

    /**
     * Konfirmasi pengiriman bukti transfer Bank BPD DIY (Transfer Manual)
     */
    public function confirmTransfer(Request $request, string $code)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:100',
            'sender_bank' => 'required|string|max:50',
            'payment_proof' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $booking = Booking::with('package')->where('booking_code', $code)->firstOrFail();

        // 1. Simpan berkas bukti transfer ke public/uploads/proofs
        $file = $request->file('payment_proof');
        $fileName = 'proof_' . $booking->booking_code . '_' . time() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('uploads/proofs');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $file->move($destinationPath, $fileName);
        $proofUrl = 'uploads/proofs/' . $fileName;

        // 2. Buat record transaksi pembayaran sah (SETTLED)
        $payableAmount = (float) $booking->paid_amount;
        $stage = $booking->payment_mode === 'DP' ? 'DOWN_PAYMENT' : 'FULL_PAYMENT';

        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => 'BPD-' . $booking->booking_code . '-' . strtoupper(Str::random(4)),
            'transaction_ref' => 'BPD-MANUAL-' . date('YmdHis'),
            'payment_stage' => $stage,
            'payment_type' => 'bank_transfer',
            'payment_method' => 'Bank BPD DIY (Transfer Manual)',
            'gross_amount' => $payableAmount,
            'payment_status' => 'SETTLED',
            'paid_at' => now(),
            'payload_response' => [
                'sender_name' => $validated['sender_name'],
                'sender_bank' => $validated['sender_bank'],
                'proof_url' => $proofUrl,
                'target_account' => '069211001060',
                'target_bank' => 'Bank BPD DIY',
                'target_name' => 'Ariyanto',
                'submitted_at' => now()->toDateTimeString(),
            ],
        ]);

        // 3. Perbarui status booking
        $booking->booking_status = $booking->payment_mode === 'DP' ? 'DP_PAID' : 'FULL_PAID';
        $booking->save();

        // 4. Catat notifikasi aktivitas admin
        ActivityLog::logPaymentSuccess(
            $booking,
            $payableAmount,
            "Transfer Manual Bank BPD DIY (Pengirim: {$validated['sender_name']} - {$validated['sender_bank']})"
        );

        return redirect()->route('booking.success', $booking->booking_code)
            ->with('success', 'Bukti transfer Bank BPD DIY berhasil dikirim! E-Tiket reservasi Anda telah aktif.');
    }

    /**
     * Konfirmasi opsi bayar tunai langsung di Loket Basecamp
     */
    public function confirmCash(Request $request, string $code)
    {
        $booking = Booking::with('package')->where('booking_code', $code)->firstOrFail();

        $payableAmount = (float) $booking->paid_amount;
        $stage = $booking->payment_mode === 'DP' ? 'DOWN_PAYMENT' : 'FULL_PAYMENT';

        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => 'CASH-' . $booking->booking_code . '-' . strtoupper(Str::random(4)),
            'transaction_ref' => 'LOKET-CASH-' . date('YmdHis'),
            'payment_stage' => $stage,
            'payment_type' => 'cash',
            'payment_method' => 'Bayar Tunai di Loket Kasir',
            'gross_amount' => $payableAmount,
            'payment_status' => 'SETTLED',
            'paid_at' => now(),
            'payload_response' => [
                'cashier_note' => 'Pembayaran tunai di loket basecamp paguyuban',
                'confirmed_at' => now()->toDateTimeString(),
            ],
        ]);

        $booking->booking_status = $booking->payment_mode === 'DP' ? 'DP_PAID' : 'FULL_PAID';
        $booking->save();

        ActivityLog::create([
            'user_id' => Auth::id() ?? $booking->user_id,
            'type' => 'BOOKING_CASH',
            'title' => 'Reservasi Bayar di Loket Kasir 💵',
            'message' => "Wisatawan {$booking->customer_name} memilih pembayaran tunai di loket basecamp untuk booking #{$booking->booking_code}.",
            'icon_type' => 'payment',
            'link' => route('admin.bookings', ['search' => $booking->booking_code]),
            'is_read' => false,
            'metadata' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->customer_name,
                'amount' => $payableAmount,
            ],
        ]);

        return redirect()->route('booking.success', $booking->booking_code)
            ->with('success', 'Pesanan berhasil dikonfirmasi untuk pembayaran tunai di loket kasir basecamp!');
    }

    public function success(string $code)
    {
        $booking = Booking::with(['package', 'slot', 'pickupZone', 'payments'])
            ->where('booking_code', $code)
            ->firstOrFail();

        // 1. Guard Status Booking: Hanya izinkan tiket diakses jika sudah lunas atau DP
        if (!in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            if ($booking->booking_status === 'WAITING_CONFIRMATION') {
                return redirect()->route('booking.payment', $booking->booking_code)
                    ->with('info', 'Pembayaran Anda sedang dalam antrean verifikasi Admin Loket. E-Tiket resmi akan segera terbit setelah disetujui.');
            }

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

        if ($booking->payments->isNotEmpty()) {
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
        }

        return view('booking.success', compact('booking'));
    }

    public function distance(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-11.5,6.5',
            'lng' => 'required|numeric|between:94,141.5',
        ]);

        $r = $this->routeDistance((float) $data['lat'], (float) $data['lng']);
        unset($r['error']);

        return response()->json($r);
    }

    public function check()
    {
        abort_unless(config('app.debug'), 404);

        $lat = $this->baseLat();
        $lng = $this->baseLng();
        $placeholder = abs($lat - self::DEFAULT_LAT) < 0.00001 && abs($lng - self::DEFAULT_LNG) < 0.00001;

        $test = $this->routeDistance(-7.7828, 110.3670);

        $todo = [];
        if (!$this->browserKey()) {
            $todo[] = 'GOOGLE_MAPS_BROWSER_KEY opsional/kosong: sistem otomatis memakai Leaflet + Photon & OpenStreetMap gratis.';
        }
        if (!env('GOOGLE_MAPS_SERVER_KEY') && !env('GOOGLE_MAPS_BROWSER_KEY')) {
            $todo[] = 'GOOGLE_MAPS_SERVER_KEY opsional/kosong: jarak dihitung via OSRM rute gratis / estimasi Haversine.';
        }
        if ($placeholder) {
            $todo[] = 'BASECAMP_LAT / BASECAMP_LNG di .env belum diisi: jarak dihitung dari titik CONTOH, bukan basecamp Anda.';
        }
        if (($test['source'] ?? '') !== 'google') {
            $todo[] = 'Jarak belum dari Google (sumber: ' . ($test['source'] ?? '?') . '). Pesan dari Google: ' . ($test['error'] ?? 'kunci server kosong');
        }
        if (!$todo) {
            $todo[] = 'Semua pengaturan sudah benar.';
        }

        return response()->json([
            'kunci_browser'           => $this->browserKey() ? 'TERISI' : 'KOSONG',
            'kunci_server'            => $this->serverKey() ? 'TERISI' : 'KOSONG',
            'basecamp'               => ['lat' => $lat, 'lng' => $lng],
            'basecamp_masih_contoh'  => $placeholder,
            'tes_jarak_ke_tugu_jogja' => $test,
            'yang_perlu_diperbaiki'  => $todo,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function routeDistance(float $lat, float $lng): array
    {
        $error = null;
        $key = $this->serverKey();

        if ($key) {
            try {
                $res = Http::timeout(8)->withHeaders([
                    'X-Goog-Api-Key'   => $key,
                    'X-Goog-FieldMask' => 'routes.distanceMeters',
                ])->post('https://routes.googleapis.com/directions/v2:computeRoutes', [
                    'origin'            => ['location' => ['latLng' => ['latitude' => $this->baseLat(), 'longitude' => $this->baseLng()]]],
                    'destination'       => ['location' => ['latLng' => ['latitude' => $lat, 'longitude' => $lng]]],
                    'travelMode'        => 'DRIVE',
                    'routingPreference' => 'TRAFFIC_UNAWARE',
                    'units'             => 'METRIC',
                ]);

                if ($res->ok() && $res->json('routes.0') !== null) {
                    $meters = (float) ($res->json('routes.0.distanceMeters') ?? 0);
                    return ['km' => round($meters / 1000, 1), 'approx' => false, 'source' => 'google'];
                }

                $error = $res->json('error.message') ?: ('HTTP ' . $res->status());
                Log::warning('Google Routes gagal: ' . $res->status() . ' ' . $res->body());
            } catch (\Throwable $e) {
                $error = $e->getMessage();
                Log::warning('Google Routes error: ' . $e->getMessage());
            }
        }

        try {
            $url = sprintf(
                'https://router.project-osrm.org/route/v1/driving/%F,%F;%F,%F',
                $this->baseLng(), $this->baseLat(), $lng, $lat
            );
            $res = Http::timeout(6)->get($url, ['overview' => 'false']);
            if ($res->ok() && $res->json('code') === 'Ok') {
                return ['km' => round(((float) $res->json('routes.0.distance')) / 1000, 1), 'approx' => false, 'source' => 'osrm', 'error' => $error];
            }
        } catch (\Throwable $e) {
            Log::warning('OSRM gagal: ' . $e->getMessage());
        }

        return [
            'km'     => round($this->haversineKm($this->baseLat(), $this->baseLng(), $lat, $lng) * 1.3, 1),
            'approx' => true,
            'source' => 'estimate',
            'error'  => $error,
        ];
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return 2 * $r * asin(sqrt($a));
    }

    /**
     * Resolve lokasi tempat / link Google Maps / koordinat
     * Menggunakan Google Places API (New) untuk mendapatkan nama penginapan dan alamat akurat,
     * dengan fallback cepat dan aman ke URL name & reverse geocoding OSM.
     */
    public function resolvePlace(Request $request)
    {
        $raw = trim((string) ($request->input('url') ?? ($request->input('input') ?? $request->query('url', ''))));
        if (empty($raw)) {
            return response()->json(['ok' => false, 'message' => 'Input link atau koordinat wajib diisi.'], 422);
        }

        $coords = null;
        $nameFromUrl = null;
        $finalUrl = $raw;

        // 1. Cek jika input adalah koordinat langsung: "lat, lng" atau "lat lng"
        if (preg_match('/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/', $raw, $m)) {
            $coords = ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        } else {
            // Input adalah link URL
            $url = $raw;
            if (!preg_match('#^https?://#i', $url)) {
                $url = 'https://' . $url;
            }

            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            $allowedHosts = [
                'maps.app.goo.gl',
                'goo.gl',
                'maps.google.com',
                'www.google.com',
                'google.com',
                'maps.google.co.id',
                'www.google.co.id',
                'google.co.id',
            ];

            $hostMatch = false;
            foreach ($allowedHosts as $allowed) {
                if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                    $hostMatch = true;
                    break;
                }
            }

            if (!$hostMatch) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Domain link tidak didukung. Gunakan link resmi Google Maps atau koordinat.',
                ], 422);
            }

            // a. Jika link pendek (maps.app.goo.gl / goo.gl), ikuti redirect di server sampai URL akhir
            $finalUrl = $url;
            $current = $url;
            $redirectCount = 0;

            while ($redirectCount < 5) {
                $redirectCount++;
                try {
                    $res = Http::withoutRedirecting()
                        ->timeout(6)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        ])
                        ->get($current);

                    if (in_array($res->status(), [301, 302, 303, 307, 308])) {
                        $loc = $res->header('Location');
                        if (!empty($loc)) {
                            if (str_starts_with($loc, '/')) {
                                $parsed = parse_url($current);
                                $loc = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? 'www.google.com') . $loc;
                            }
                            $finalUrl = $loc;
                            $current = $loc;
                            // Jika URL redirect sudah memuat nama tempat dan koordinat, langsung selesai
                            if (str_contains($loc, '/place/') && (str_contains($loc, '!3d') || str_contains($loc, '@'))) {
                                break;
                            }
                            continue;
                        }
                    }
                    break;
                } catch (\Throwable $e) {
                    Log::warning('Redirect follow exception: ' . $e->getMessage());
                    break;
                }
            }

            // b. Dari URL akhir ambil nama tempat dan koordinat
            $decoded = urldecode($finalUrl);

            // Ekstrak nama tempat: pola /maps/place/NAMA/@lat,lng atau /place/NAMA/ atau ?q=NAMA
            if (preg_match('#/maps/place/([^/@?]+)#i', $decoded, $m) || preg_match('#/place/([^/@?]+)#i', $decoded, $m)) {
                $cand = trim(str_replace('+', ' ', $m[1]));
                if (!preg_match('#^-?\d{1,2}\.\d+#', $cand)) {
                    $nameFromUrl = $cand;
                }
            } elseif (preg_match('#[?&]q=([^&@]+)#i', $decoded, $m)) {
                $cand = trim(str_replace('+', ' ', $m[1]));
                if (!preg_match('#^-?\d{1,2}\.\d+#', $cand)) {
                    $nameFromUrl = $cand;
                }
            }

            // Ekstrak koordinat (urutan: !3d..!4d.., lalu @lat,lng, lalu ?q=lat,lng)
            $coords = $this->extractCoordinatesFromText($finalUrl);
        }

        $apiKey = $this->apiKey();
        $placeFound = false;
        $name = null;
        $address = null;
        $lat = $coords['lat'] ?? null;
        $lng = $coords['lng'] ?? null;
        $source = 'none';

        // c. Jika ada nama dan key tersedia: panggil Places API (New) Text Search, dibias ke koordinat (radius 200 m)
        if ($apiKey && !empty($nameFromUrl)) {
            try {
                $payload = [
                    'textQuery'    => $nameFromUrl,
                    'languageCode' => 'id',
                ];
                if ($coords) {
                    $payload['locationBias'] = [
                        'circle' => [
                            'center' => [
                                'latitude'  => $coords['lat'],
                                'longitude' => $coords['lng'],
                            ],
                            'radius' => 200.0,
                        ],
                    ];
                }

                $resText = Http::timeout(5)->withHeaders([
                    'X-Goog-Api-Key'   => $apiKey,
                    'X-Goog-FieldMask' => 'places.displayName,places.formattedAddress,places.location',
                    'Content-Type'     => 'application/json',
                ])->post('https://places.googleapis.com/v1/places:searchText', $payload);

                if ($resText->ok()) {
                    $places = $resText->json('places') ?? [];
                    if (!empty($places)) {
                        $top = $places[0];
                        $name = $top['displayName']['text'] ?? $nameFromUrl;
                        $address = $top['formattedAddress'] ?? '';
                        if (isset($top['location']['latitude'], $top['location']['longitude'])) {
                            $lat = (float) $top['location']['latitude'];
                            $lng = (float) $top['location']['longitude'];
                        }
                        $source = 'google_places_text';
                        $placeFound = true;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Google Places Text Search error: ' . $e->getMessage());
            }
        }

        // d. Jika hanya koordinat (atau text search belum dapat): panggil Nearby Search, includedTypes lodging, radius 50 m
        if ($apiKey && !$placeFound && $coords) {
            try {
                $payload = [
                    'includedTypes'       => ['lodging'],
                    'locationRestriction' => [
                        'circle' => [
                            'center' => [
                                'latitude'  => $coords['lat'],
                                'longitude' => $coords['lng'],
                            ],
                            'radius' => 50.0,
                        ],
                    ],
                    'rankPreference'      => 'DISTANCE',
                    'languageCode'        => 'id',
                    'maxResultCount'      => 5,
                ];

                $resNear = Http::timeout(5)->withHeaders([
                    'X-Goog-Api-Key'   => $apiKey,
                    'X-Goog-FieldMask' => 'places.displayName,places.formattedAddress,places.location',
                    'Content-Type'     => 'application/json',
                ])->post('https://places.googleapis.com/v1/places:searchNearby', $payload);

                if ($resNear->ok()) {
                    $places = $resNear->json('places') ?? [];
                    if (!empty($places)) {
                        $top = $places[0];
                        $name = $top['displayName']['text'] ?? null;
                        $address = $top['formattedAddress'] ?? '';
                        if (isset($top['location']['latitude'], $top['location']['longitude'])) {
                            $lat = (float) $top['location']['latitude'];
                            $lng = (float) $top['location']['longitude'];
                        }
                        $source = 'google_places_nearby';
                        $placeFound = true;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Google Places Nearby Search error: ' . $e->getMessage());
            }
        }

        // f. Jika Places tidak menemukan apa pun: fallback ke nama dari URL, lalu reverse geocoding (Nominatim / Photon)
        if (!$placeFound) {
            if (!empty($nameFromUrl)) {
                $name = $nameFromUrl;
                $source = 'url_name';
            }

            if ($lat !== null && $lng !== null) {
                $rev = $this->lookupReverseName($lat, $lng);
                if (empty($address)) {
                    $address = $rev['address'] ?? '';
                }
                if (empty($name)) {
                    $name = !empty($rev['name']) ? $rev['name'] : (!empty($address) ? $address : ('Titik Koordinat (' . round($lat, 4) . ', ' . round($lng, 4) . ')'));
                    $source = 'osm_reverse';
                }
            }
        }

        if (empty($name)) {
            $name = 'Titik Penjemputan (' . round($lat, 4) . ', ' . round($lng, 4) . ')';
        }

        if ($lat === null || $lng === null) {
            return response()->json([
                'ok'      => false,
                'message' => 'Koordinat titik penjemputan tidak ditemukan dalam link tersebut.',
                'url'     => $finalUrl,
            ], 422);
        }

        return response()->json([
            'ok'      => true,
            'name'    => $name,
            'address' => $address,
            'lat'     => (float) $lat,
            'lng'     => (float) $lng,
            'source'  => $source,
            'url'     => $finalUrl,
        ]);
    }

    /**
     * Alias untuk kompatibilitas rute lama
     */
    public function resolveMapLink(Request $request)
    {
        return $this->resolvePlace($request);
    }

    /**
     * Alias route lama agar kompatibel
     */
    public function resolveLink(Request $r)
    {
        return $this->resolveMapLink($r);
    }

    /**
     * Pencarian nama penginapan / hotel / alamat penjemputan.
     * Urutan sumber:
     * 1. Google Places (hanya jika GOOGLE_MAPS_API_KEY terisi; jika kosong/gagal lewati secara diam-diam).
     * 2. File hotels.json lokal (pencocokan tidak peka huruf besar/kecil, abaikan kata wilayah, toleran typo).
     * 3. Photon Komoot API (https://photon.komoot.io/api/?q=...&limit=5&lat=-7.6&lon=110.43).
     * 4. Jika kosong: kembalikan hasil kosong tanpa error teknis.
     */
    public function placesSearch(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json(['ok' => true, 'results' => [], 'source' => 'none']);
        }

        $cacheKey = 'places_search_v4_' . md5(mb_strtolower($query));
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        // 1. Coba Google Places jika key tersedia di backend (lewati diam-diam jika kosong atau gagal)
        $key = $this->apiKey();
        if (!empty($key)) {
            try {
                $body = [
                    'textQuery'    => $query,
                    'languageCode' => 'id',
                    'regionCode'   => 'ID',
                    'locationBias' => [
                        'circle' => [
                            'center' => [
                                'latitude'  => -7.6000,
                                'longitude' => 110.4300,
                            ],
                            'radius' => 30000.0,
                        ],
                    ],
                ];

                $res = Http::withHeaders([
                    'X-Goog-Api-Key'   => $key,
                    'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
                    'Content-Type'     => 'application/json',
                ])->timeout(4)->post('https://places.googleapis.com/v1/places:searchText', $body);

                if ($res->ok()) {
                    $places = $res->json('places') ?? [];

                    // Retry tanpa kata wilayah jika kosong
                    if (empty($places)) {
                        $cleaned = trim(preg_replace('/\b(yogyakarta|jogja|yogya|diy|d\.i\.y|sleman|cangkringan)\b/i', '', $query));
                        $cleaned = trim(preg_replace('/[,\-\s]+/', ' ', $cleaned));
                        if (mb_strlen($cleaned) >= 3 && mb_strtolower($cleaned) !== mb_strtolower($query)) {
                            $body['textQuery'] = $cleaned;
                            $resRetry = Http::withHeaders([
                                'X-Goog-Api-Key'   => $key,
                                'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
                                'Content-Type'     => 'application/json',
                            ])->timeout(4)->post('https://places.googleapis.com/v1/places:searchText', $body);
                            if ($resRetry->ok()) {
                                $places = $resRetry->json('places') ?? [];
                            }
                        }
                    }

                    if (!empty($places)) {
                        $results = [];
                        foreach ($places as $p) {
                            $name = $p['displayName']['text'] ?? 'Lokasi';
                            $address = $p['formattedAddress'] ?? '';
                            $lat = isset($p['location']['latitude']) ? (float) $p['location']['latitude'] : null;
                            $lng = isset($p['location']['longitude']) ? (float) $p['location']['longitude'] : null;

                            $results[] = [
                                'id'         => $p['id'] ?? null,
                                'name'       => $name,
                                'address'    => $address,
                                'full_label' => trim($name . ($address ? ', ' . $address : '')),
                                'lat'        => $lat,
                                'lng'        => $lng,
                                'source'     => 'google',
                            ];
                            if (count($results) >= 5) break;
                        }

                        if (!empty($results)) {
                            $data = ['ok' => true, 'results' => $results, 'source' => 'google'];
                            Cache::put($cacheKey, $data, 60);
                            return response()->json($data);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Lewati Google secara diam-diam
                Log::info('Google Places skipped: ' . $e->getMessage());
            }
        }

        // 2a. Sumber pencarian utama tanpa key: File hotels.json lokal
        $localHotels = $this->searchHotelsJson($query);
        if (!empty($localHotels)) {
            $data = [
                'ok'      => true,
                'results' => array_slice($localHotels, 0, 5),
                'source'  => 'local_json',
            ];
            Cache::put($cacheKey, $data, 60);
            return response()->json($data);
        }

        // 2b. Sumber cadangan: Photon Komoot API
        try {
            $photonResults = $this->searchPhoton($query);
            if (!empty($photonResults)) {
                $data = [
                    'ok'      => true,
                    'results' => array_slice($photonResults, 0, 5),
                    'source'  => 'photon',
                ];
                Cache::put($cacheKey, $data, 60);
                return response()->json($data);
            }
        } catch (\Throwable $e) {
            Log::info('Photon search skipped: ' . $e->getMessage());
        }

        // 2c. Jika kosong: return data kosong tanpa error teknis
        $data = [
            'ok'      => true,
            'results' => [],
            'source'  => 'none',
        ];
        Cache::put($cacheKey, $data, 60);
        return response()->json($data);
    }

    /**
     * Cari hotel dari file hotels.json lokal.
     * Pencocokan tidak peka huruf besar/kecil, abaikan kata wilayah, toleran salah ketik ringan.
     */
    private function searchHotelsJson(string $query): array
    {
        $possiblePaths = [
            database_path('hotels.json'),
            database_path('data/hotels.json'),
            storage_path('app/hotels.json'),
            base_path('hotels.json'),
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            return [];
        }

        $json = @file_get_contents($filePath);
        $hotels = json_decode($json, true);
        if (!is_array($hotels)) {
            return [];
        }

        $normalize = function (string $str): string {
            $str = mb_strtolower($str);
            $str = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $str);
            $str = preg_replace('/\b(yogyakarta|jogja|yogya|diy|d\.i\.y|sleman|cangkringan|pakem|indonesia)\b/u', ' ', $str);
            return trim(preg_replace('/\s+/', ' ', $str));
        };

        $cleanQuery = $normalize($query);
        if (empty($cleanQuery)) {
            $cleanQuery = trim(preg_replace('/\s+/', ' ', preg_replace('/[^\p{L}\p{N}\s]/u', ' ', mb_strtolower($query))));
        }
        if (mb_strlen($cleanQuery) < 2) {
            return [];
        }

        $queryWords = array_values(array_filter(explode(' ', $cleanQuery), fn($w) => mb_strlen($w) >= 2));

        // Kata umum yang tidak dipakai untuk membedakan hotel
        $generic = ['hotel','villa','vila','resort','homestay','penginapan','guest','house',
                    'the','spa','and','resto','hostel','inn','lodge','cottage','kaliurang','merapi'];
        $queryDistinct = array_values(array_diff($queryWords, $generic)) ?: $queryWords;

        $matched = [];

        foreach ($hotels as $item) {
            $name = $item['nama'] ?? ($item['name'] ?? '');
            if (empty($name)) continue;

            $aliases = $item['alias'] ?? ($item['aliases'] ?? []);
            if (is_string($aliases)) {
                $aliases = array_map('trim', explode(',', $aliases));
            }
            if (!is_array($aliases)) {
                $aliases = [];
            }

            $address = $item['alamat'] ?? ($item['address'] ?? '');
            $lat = isset($item['lat']) ? (float) $item['lat'] : (isset($item['latitude']) ? (float) $item['latitude'] : null);
            $lng = isset($item['lng']) ? (float) $item['lng'] : (isset($item['longitude']) ? (float) $item['longitude'] : null);

            if ($lat === null || $lng === null) continue;

            $candidates = array_merge([$name], $aliases);
            $matchedThis = false;
            $score = 0;

            foreach ($candidates as $cand) {
                $cleanCand = $normalize($cand);
                if (empty($cleanCand)) continue;

                // exact match
                if ($cleanCand === $cleanQuery) {
                    $matchedThis = true;
                    $score = max($score, 100);
                    break;
                }

                $candWords    = array_values(array_filter(explode(' ', $cleanCand), fn($w) => mb_strlen($w) >= 2));
                $candDistinct = array_values(array_diff($candWords, $generic)) ?: $candWords;

                // semua kata pembeda dari query harus ada di nama/alias
                $all = !empty($queryDistinct);
                foreach ($queryDistinct as $qw) {
                    $found = false;
                    foreach ($candDistinct as $cw) {
                        if (str_contains($cw, $qw) || str_contains($qw, $cw)) { $found = true; break; }
                        $len = max(mb_strlen($qw), mb_strlen($cw));
                        if ($len >= 4 && levenshtein($qw, $cw) <= ($len >= 7 ? 2 : 1)) { $found = true; break; }
                    }
                    if (!$found) { $all = false; break; }
                }
                if ($all) { $matchedThis = true; $score = max($score, 85); }
            }

            if ($matchedThis) {
                $matched[] = [
                    'score' => $score,
                    'item'  => [
                        'id'         => null,
                        'name'       => $name,
                        'address'    => $address,
                        'full_label' => trim($name . ($address ? ', ' . $address : '')),
                        'lat'        => $lat,
                        'lng'        => $lng,
                        'source'     => 'local_json',
                    ],
                ];
            }
        }

        usort($matched, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_column($matched, 'item');
    }

    /**
     * Cari nama tempat via Photon Komoot API.
     * https://photon.komoot.io/api/?q={teks}&limit=5&lat=-7.6&lon=110.43
     */
    private function searchPhoton(string $query): array
    {
        $headers = [
            'User-Agent' => 'JeepWukirsari/1.0 (info@jeepwukirsari.id)',
            'Accept'     => 'application/json',
        ];

        $callPhoton = function (string $text) use ($headers) {
            return Http::withHeaders($headers)->timeout(4)->get('https://photon.komoot.io/api/', [
                'q'     => $text,
                'limit' => 5,
                'lat'   => -7.6,
                'lon'   => 110.43,
            ]);
        };

        $res = $callPhoton($query);
        $features = ($res->ok()) ? ($res->json('features') ?? []) : [];

        // Jika kosong, coba tanpa kata wilayah
        if (empty($features)) {
            $cleaned = trim(preg_replace('/\b(yogyakarta|jogja|yogya|diy|d\.i\.y|sleman|cangkringan)\b/i', '', $query));
            $cleaned = trim(preg_replace('/[,\-\s]+/', ' ', $cleaned));
            if (mb_strlen($cleaned) >= 3 && mb_strtolower($cleaned) !== mb_strtolower($query)) {
                $resClean = $callPhoton($cleaned);
                if ($resClean->ok()) {
                    $features = $resClean->json('features') ?? [];
                }
            }
        }

        $results = [];
        foreach ($features as $f) {
            $props  = $f['properties'] ?? [];
            $coords = $f['geometry']['coordinates'] ?? null;
            if (!$coords || count($coords) < 2) continue;

            $lng = (float) $coords[0];
            $lat = (float) $coords[1];

            $name = $props['name'] ?? ($props['street'] ?? 'Lokasi');
            $parts = array_filter([
                $props['street'] ?? null,
                $props['district'] ?? null,
                $props['city'] ?? ($props['county'] ?? null),
                $props['state'] ?? null,
            ]);
            $address = implode(', ', $parts);
            if (empty($address) && !empty($props['country'])) {
                $address = $props['country'];
            }

            $results[] = [
                'id'         => null,
                'name'       => $name,
                'address'    => $address,
                'full_label' => trim($name . ($address ? ', ' . $address : '')),
                'lat'        => $lat,
                'lng'        => $lng,
                'source'     => 'photon',
            ];

            if (count($results) >= 5) break;
        }

        return $results;
    }

    /**
     * Pencatatan otomatis hotel baru:
     * Jika nama hotel + koordinat belum ada di hotels.json (jarak > 100 m dari semua hotel terdaftar),
     * simpan sebagai kandidat baru ke database/hotel_kandidat.json.
     */
    private function recordCandidateHotel(string $name, float $lat, float $lng): void
    {
        $name = trim($name);
        if (empty($name) || !$lat || !$lng) {
            return;
        }

        $hotelsFile = database_path('hotels.json');
        $hotels = file_exists($hotelsFile) ? (json_decode(file_get_contents($hotelsFile), true) ?: []) : [];

        // 1. Cek apakah titik koordinat sudah terdaftar di hotels.json (jarak <= 100 meter atau nama identik)
        foreach ($hotels as $h) {
            $hLat = isset($h['lat']) ? (float) $h['lat'] : null;
            $hLng = isset($h['lng']) ? (float) $h['lng'] : null;
            if ($hLat !== null && $hLng !== null) {
                $distKm = $this->haversineKm($lat, $lng, $hLat, $hLng);
                if ($distKm <= 0.100) { // <= 100 meter
                    return; // Sudah terdaftar di hotels.json
                }
            }

            // Cek nama sama persis (case-insensitive)
            if (mb_strtolower(trim($h['nama'] ?? '')) === mb_strtolower($name)) {
                return;
            }
        }

        // 2. Cek apakah sudah pernah masuk ke database/hotel_kandidat.json
        $candFile = database_path('hotel_kandidat.json');
        $candidates = file_exists($candFile) ? (json_decode(file_get_contents($candFile), true) ?: []) : [];

        $foundIndex = null;
        foreach ($candidates as $idx => $c) {
            $cLat = isset($c['lat']) ? (float) $c['lat'] : null;
            $cLng = isset($c['lng']) ? (float) $c['lng'] : null;
            if ($cLat !== null && $cLng !== null) {
                $distKm = $this->haversineKm($lat, $lng, $cLat, $cLng);
                if ($distKm <= 0.100) {
                    $foundIndex = $idx;
                    break;
                }
            }
            if (mb_strtolower(trim($c['name'] ?? '')) === mb_strtolower($name)) {
                $foundIndex = $idx;
                break;
            }
        }

        if ($foundIndex !== null) {
            // Kandidat sudah ada, naikkan jumlah kali dipakai
            $candidates[$foundIndex]['jumlah_pakai'] = ($candidates[$foundIndex]['jumlah_pakai'] ?? 1) + 1;
            $candidates[$foundIndex]['updated_at'] = now()->toDateTimeString();
            if (mb_strlen($name) > mb_strlen($candidates[$foundIndex]['name'] ?? '')) {
                $candidates[$foundIndex]['name'] = $name;
            }
        } else {
            // Tambah kandidat baru
            $candidates[] = [
                'id'           => 'cand_' . substr(md5($name . '_' . $lat . '_' . $lng . '_' . microtime()), 0, 10),
                'name'         => $name,
                'lat'          => round($lat, 7),
                'lng'          => round($lng, 7),
                'jumlah_pakai' => 1,
                'created_at'   => now()->toDateTimeString(),
                'updated_at'   => now()->toDateTimeString(),
            ];
        }

        @file_put_contents($candFile, json_encode($candidates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Alias route lama searchLocation
     */
    public function searchLocation(Request $request)
    {
        return $this->placesSearch($request);
    }

    /**
     * A.1 Place Details untuk mengambil lat/lng dari saran Google Places
     * Header X-Goog-FieldMask: id,displayName,formattedAddress,location
     */
    public function placesDetail(Request $request)
    {
        $placeId = trim((string) $request->query('id', ''));
        $sessionToken = trim((string) $request->query('session_token', ''));

        if (empty($placeId)) {
            return response()->json(['ok' => false, 'message' => 'ID tempat wajib diisi'], 422);
        }

        $key = $this->apiKey();
        if (!$key) {
            return response()->json(['ok' => false, 'message' => 'Layanan Google Places belum aktif'], 503);
        }

        $cleanId = str_replace('places/', '', $placeId);
        $cacheKey = 'places_detail_' . md5($cleanId);

        $data = Cache::remember($cacheKey, 3600, function () use ($cleanId, $sessionToken, $key) {
            try {
                $url = "https://places.googleapis.com/v1/places/{$cleanId}";
                $params = [];
                if (!empty($sessionToken)) {
                    $params['sessionToken'] = $sessionToken;
                }

                $res = Http::withHeaders([
                    'X-Goog-Api-Key'   => $key,
                    'X-Goog-FieldMask' => 'id,displayName,formattedAddress,location',
                ])->timeout(5)->get($url, $params);

                if ($res->ok()) {
                    $json = $res->json();
                    $lat = $json['location']['latitude'] ?? null;
                    $lng = $json['location']['longitude'] ?? null;
                    $name = $json['displayName']['text'] ?? null;
                    $address = $json['formattedAddress'] ?? '';

                    if ($lat !== null && $lng !== null) {
                        return [
                            'ok'      => true,
                            'lat'     => (float) $lat,
                            'lng'     => (float) $lng,
                            'name'    => $name,
                            'address' => $address,
                            'source'  => 'google',
                        ];
                    }
                } else {
                    Log::info('Google Place Details error: ' . $res->status() . ' ' . $res->body());
                }
            } catch (\Throwable $e) {
                Log::warning('Google Place Details exception: ' . $e->getMessage());
            }

            return ['ok' => false, 'message' => 'Detail lokasi tidak ditemukan'];
        });

        return response()->json($data);
    }

    /**
 * Reverse geocoding (lat, lng -> nama hotel / alamat)
 * Prioritas: Google Places Nearby (hotel terdekat) -> Photon/Nominatim
 */
    /**
     * Reverse geocoding (lat, lng -> nama hotel / alamat)
     * Prioritas: Google Places Nearby (hotel terdekat) -> Photon/Nominatim
     */
    public function reverseLocation(Request $request)
    {
        $lat = (float) $request->query('lat');
        $lng = (float) $request->query('lng');
        $acc = (float) $request->query('accuracy', 0);

        if (!$lat || !$lng || $lat < -11.5 || $lat > 6.5 || $lng < 94 || $lng > 141.5) {
            return response()->json(['ok' => false, 'name' => null, 'address' => null, 'places' => []], 422);
        }

        $cacheKey = 'geo_rev_v4_' . round($lat, 4) . '_' . round($lng, 4) . '_' . (int) min($acc, 300);
        if ($cached = Cache::get($cacheKey)) {
            return response()->json($cached);
        }

        // radius mengikuti akurasi GPS (min 50 m, max 300 m)
        $radius = min(max($acc, 50), 300);
        $places = $this->nearbyPlaces($lat, $lng, $radius);

        foreach ($places as &$p) {
            $p['distance_m'] = (int) round($this->haversineKm($lat, $lng, $p['lat'], $p['lng']) * 1000);
        }
        unset($p);
        usort($places, fn ($a, $b) => $a['distance_m'] <=> $b['distance_m']);

        $best = $places[0] ?? null;
        // hanya auto-pilih jika GPS cukup akurat DAN hotel sangat dekat
        $confident = $best && $acc > 0 && $acc <= 50 && $best['distance_m'] <= 40;

        $rev = $this->lookupReverseName($lat, $lng);

        $data = [
            'ok'            => true,
            'lat'           => $lat,          // koordinat ASLI user, jangan ditimpa
            'lng'           => $lng,
            'accuracy'      => $acc,
            'confident'     => $confident,
            'name'          => $confident ? $best['name'] : null,
            'address'       => $confident ? $best['address'] : ($rev['address'] ?? null),
            'places'        => array_slice($places, 0, 5), // saran untuk dipilih user
            'source'        => $confident ? 'google' : ($rev['source'] ?? 'none'),
            'attribution'   => '© OpenStreetMap contributors',
        ];

        Cache::put($cacheKey, $data, now()->addHours(6));
        return response()->json($data);
    }

    /**
     * Helper: cari tempat terdekat (hotel dulu) via Google Places Nearby Search (New)
     */
    private function nearbyPlaces(float $lat, float $lng, float $radius = 100.0): array
    {
        $key = $this->apiKey();
        if (!$key) return [];

        try {
            $res = Http::timeout(6)->withHeaders([
                'X-Goog-Api-Key'   => $key,
                'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
            ])->post('https://places.googleapis.com/v1/places:searchNearby', [
                'includedTypes'       => ['lodging'],
                'maxResultCount'      => 10,
                'rankPreference'      => 'DISTANCE',
                'languageCode'        => 'id',
                'locationRestriction' => ['circle' => [
                    'center' => ['latitude' => $lat, 'longitude' => $lng],
                    'radius' => $radius,
                ]],
            ]);
            $found = $res->ok() ? ($res->json('places') ?? []) : [];
        } catch (\Throwable $e) {
            Log::info('Nearby error: ' . $e->getMessage());
            $found = [];
        }

        $places = [];
        foreach ($found as $p) {
            $name = $p['displayName']['text'] ?? null;
            $plat = $p['location']['latitude'] ?? null;
            $plng = $p['location']['longitude'] ?? null;
            if (!$name || $plat === null || $plng === null) continue;
            $places[] = ['name' => $name, 'address' => $p['formattedAddress'] ?? '', 'lat' => (float) $plat, 'lng' => (float) $plng];
        }
        return $places;
    }
    /**
     * Helper: Ekstrak nama tempat dari URL Google Maps (e.g. /place/Mas+Dedi+Servis+HP)
     */
    private function extractPlaceNameFromUrl(string $url): ?string
    {
        $decoded = urldecode($url);
        if (preg_match('#/place/([^/@?]+)#', $decoded, $m)) {
            $name = trim(str_replace('+', ' ', $m[1]));
            if (!empty($name) && !preg_match('#^-?\d+\.\d+#', $name)) {
                return $name;
            }
        }
        if (preg_match('#[?&]q=([^&@]+)#', $decoded, $m)) {
            $name = trim(str_replace('+', ' ', $m[1]));
            if (!empty($name) && !preg_match('#^-?\d+\.\d+#', $name)) {
                return $name;
            }
        }
        return null;
    }

    /**
     * Helper: Ekstrak koordinat numerik dari teks URL
     */
    private function extractCoordinatesFromText(string $text): ?array
    {
        $decoded = urldecode($text);

        // 1. !3dLAT!4dLNG
        if (preg_match('/!3d(-?\d{1,2}\.\d+)!4d(-?\d{2,3}\.\d+)/', $decoded, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        }

        // 2. @LAT,LNG
        if (preg_match('/@(-?\d{1,2}\.\d+),\s*(-?\d{2,3}\.\d+)/', $decoded, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        }

        // 3. ?q=LAT,LNG atau query=LAT,LNG atau ll=LAT,LNG
        if (preg_match('/[?&](?:q|query|ll|destination)=(-?\d{1,2}\.\d+)[,\s+]+(-?\d{2,3}\.\d+)/', $decoded, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        }

        // 4. Standalone coords
        if (preg_match('/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/', trim($decoded), $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2]];
        }

        return null;
    }

    /**
     * Helper: Reverse geocode nama tempat
     */
    private function lookupReverseName(float $lat, float $lng): array
    {
        $cacheKey = 'rev_lookup_' . round($lat, 4) . '_' . round($lng, 4);
        return Cache::remember($cacheKey, now()->addHour(), function () use ($lat, $lng) {
            $lodging = ['hotel', 'guest_house', 'hostel', 'motel', 'apartment', 'chalet', 'resort', 'villa', 'homestay'];
            $headers = ['User-Agent' => 'JeepWukirsari/1.0 (info@jeepwukirsari.id)', 'Accept' => 'application/json'];

            // 1. Photon Reverse
            try {
                $res = Http::withHeaders($headers)->timeout(3)->get('https://photon.komoot.io/reverse', ['lat' => $lat, 'lon' => $lng]);
                if ($res->ok() && !empty($res->json('features'))) {
                    $props = $res->json('features.0.properties') ?? [];
                    $isLodging = ($props['osm_key'] ?? '') === 'tourism' && in_array($props['osm_value'] ?? '', $lodging, true);
                    $area = implode(', ', array_filter([
                        $props['street'] ?? null,
                        $props['district'] ?? null,
                        $props['city'] ?? ($props['county'] ?? null),
                    ]));

                    if ($isLodging && !empty($props['name'])) {
                        return ['name' => $props['name'], 'address' => trim($props['name'] . ($area ? ', ' . $area : '')), 'source' => 'photon'];
                    }
                    if ($area !== '') {
                        return ['name' => $props['name'] ?? null, 'address' => $area, 'source' => 'photon'];
                    }
                }
            } catch (\Throwable $e) {}

            // 2. Fallback Nominatim Reverse
            try {
                $res = Http::withHeaders($headers)->timeout(4)->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $lat, 'lon' => $lng, 'format' => 'jsonv2', 'addressdetails' => 1,
                ]);
                if ($res->ok()) {
                    $d = $res->json();
                    $addr = $d['address'] ?? [];
                    $isLodging = ($d['category'] ?? '') === 'tourism' && in_array($d['type'] ?? '', $lodging, true);
                    $area = implode(', ', array_filter([
                        $addr['road'] ?? null,
                        $addr['suburb'] ?? ($addr['village'] ?? null),
                        $addr['county'] ?? ($addr['city'] ?? null),
                    ]));

                    if ($isLodging && !empty($d['name'])) {
                        return ['name' => $d['name'], 'address' => trim($d['name'] . ($area ? ', ' . $area : '')), 'source' => 'nominatim'];
                    }
                    if ($area !== '') {
                        return ['name' => $d['name'] ?? null, 'address' => $area, 'source' => 'nominatim'];
                    }
                }
            } catch (\Throwable $e) {}

            return ['name' => null, 'address' => null, 'source' => 'none'];
        });
    }

    public function nearbyLodging(Request $request)
{
    $lat = (float) $request->query('lat');
    $lng = (float) $request->query('lng');
    if (!$lat || !$lng) return response()->json(['places' => []]);

    $cacheKey = 'nearby_lodging_osm_' . round($lat, 2) . '_' . round($lng, 2);
    if ($cached = Cache::get($cacheKey)) {
        return response()->json(['places' => $cached]);
    }

    $places = [];
    try {
        $q = '[out:json][timeout:10];nwr(around:3000,' . $lat . ',' . $lng . ')'
           . '[tourism~"hotel|guest_house|hostel|motel|apartment|chalet"][name];out center 40;';
        $res = Http::timeout(12)->asForm()->post('https://overpass-api.de/api/interpreter', ['data' => $q]);

        foreach (($res->json('elements') ?? []) as $e) {
            $plat = $e['lat'] ?? ($e['center']['lat'] ?? null);
            $plng = $e['lon'] ?? ($e['center']['lon'] ?? null);
            $name = $e['tags']['name'] ?? null;
            if (!$name || $plat === null || $plng === null) continue;
            $places[] = ['name' => $name, 'address' => '', 'lat' => (float) $plat, 'lng' => (float) $plng];
        }
    } catch (\Throwable $e) {
        Log::info('Overpass lodging error: ' . $e->getMessage());
    }

    if ($places) Cache::put($cacheKey, $places, now()->addHours(12));

    return response()->json(['places' => $places]);
}
    
}