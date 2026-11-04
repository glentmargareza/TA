<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // ====== PENGATURAN PENJEMPUTAN ======
    // Titik lokasi basecamp presisi: Bedoyo, Wukirsari, Cangkringan, Sleman, DIY
    private const DEFAULT_LAT  = -7.6437244;
    private const DEFAULT_LNG  = 110.4338597;
    private const FREE_KM      = 3;        // Jarak jemput gratis (km)
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

    public function create(Request $request)
    {
        $selectedSlug = $request->query('package', 'short-trip');
        $packages = Package::where('is_active', true)->get();
        $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();

        $slots = ScheduleSlot::where('is_active', true)->get();

        $bookingData = [
            'basecamp'    => ['lat' => $this->baseLat(), 'lng' => $this->baseLng()],
            'freeKm'      => self::FREE_KM,
            'feePerJeep'  => self::FEE_PER_JEEP,
            'gmapsKey'    => $this->browserKey(),
            'distanceUrl' => route('booking.distance'),
            'resolveUrl' => route('booking.resolve'),
            'selectedId'  => $selectedPackage?->id,
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
            $status = 'FULL_PAID';
        } else {
            $payableAmount = round($totalAmount * 0.30);
            $remainingAmount = $totalAmount - $payableAmount;
            $status = 'DP_PAID';
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
            'booking_status' => $status,
            'snap_token' => $snapToken,
        ]);

        return redirect()->route('booking.success', $booking->booking_code);
    }

    public function success(string $code)
    {
        $booking = Booking::with(['package', 'slot', 'pickupZone'])->where('booking_code', $code)->firstOrFail();
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
            $todo[] = 'GOOGLE_MAPS_BROWSER_KEY di .env masih kosong: pencarian nama hotel & peta Google belum aktif.';
        }
        if (!env('GOOGLE_MAPS_SERVER_KEY') && !env('GOOGLE_MAPS_BROWSER_KEY')) {
            $todo[] = 'GOOGLE_MAPS_SERVER_KEY di .env masih kosong: jarak belum memakai Google Routes.';
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

    public function resolveLink(Request $r)
    {
        $url  = (string) $r->query('url');
        $host = parse_url($url, PHP_URL_HOST);
        if (!in_array($host, ['maps.app.goo.gl', 'goo.gl'], true)) {
            return response()->json(['ok' => false], 422);   // cegah SSRF
        }
        $res = Http::withOptions(['allow_redirects' => false])->timeout(8)->get($url);
        $loc = $res->header('Location');
        return response()->json(['ok' => (bool) $loc, 'url' => $loc]);
    }
}