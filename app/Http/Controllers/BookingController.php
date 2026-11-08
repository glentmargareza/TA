<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
     * Pencarian manual nama penginapan / hotel / alamat penjemputan.
     * Menggunakan Google Places API (New) Text Search (places:searchText).
     * Tidak fallback ke Nominatim secara diam-diam.
     */
    public function placesSearch(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json(['ok' => true, 'results' => [], 'source' => 'google']);
        }

        $key = $this->apiKey();
        if (empty($key)) {
            Log::error('Google Places Text Search error: GOOGLE_MAPS_API_KEY belum diisi di file .env server.');
            return response()->json([
                'ok'         => false,
                'error'      => 'Kunci Google Maps belum diatur di server (GOOGLE_MAPS_API_KEY belum diisi).',
                'error_type' => 'MISSING_KEY',
                'results'    => [],
                'source'     => 'google',
            ]);
        }

        $cacheKey = 'places_textsearch_' . md5(mb_strtolower($query));
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $performSearch = function (string $text) use ($key) {
            $body = [
                'textQuery'    => $text,
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

            return Http::withHeaders([
                'X-Goog-Api-Key'   => $key,
                'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
                'Content-Type'     => 'application/json',
            ])->timeout(6)->post('https://places.googleapis.com/v1/places:searchText', $body);
        };

        try {
            $res = $performSearch($query);

            if (!$res->ok()) {
                $status = $res->status();
                $body = $res->body();
                $json = $res->json();
                $errMsg = $json['error']['message'] ?? ('HTTP Error ' . $status);
                $errStatus = $json['error']['status'] ?? 'ERROR';

                Log::error("Google Places Text Search error [{$status} - {$errStatus}]: {$body}");

                return response()->json([
                    'ok'         => false,
                    'error'      => "Google Places error ({$errStatus}): {$errMsg}",
                    'error_type' => $errStatus,
                    'results'    => [],
                    'source'     => 'google',
                ]);
            }

            $places = $res->json('places') ?? [];

            // Jika Text Search awal kosong, coba ulang tanpa kata wilayah (Yogyakarta, Sleman, Cangkringan, dsb.)
            if (empty($places)) {
                $cleaned = trim(preg_replace('/\b(yogyakarta|jogja|yogya|diy|d\.i\.y|sleman|cangkringan)\b/i', '', $query));
                $cleaned = trim(preg_replace('/[,\-\s]+/', ' ', $cleaned));

                if (mb_strlen($cleaned) >= 3 && mb_strtolower($cleaned) !== mb_strtolower($query)) {
                    $resRetry = $performSearch($cleaned);
                    if ($resRetry->ok()) {
                        $places = $resRetry->json('places') ?? [];
                    }
                }
            }

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

                if (count($results) >= 5) {
                    break;
                }
            }

            $data = [
                'ok'      => true,
                'results' => $results,
                'source'  => 'google',
            ];

            Cache::put($cacheKey, $data, 60);

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('Google Places Text Search exception: ' . $e->getMessage());

            return response()->json([
                'ok'         => false,
                'error'      => 'Gagal menghubungi Google Places: ' . $e->getMessage(),
                'error_type' => 'EXCEPTION',
                'results'    => [],
                'source'     => 'google',
            ]);
        }
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

        if (!$lat || !$lng || $lat < -11.5 || $lat > 6.5 || $lng < 94 || $lng > 141.5) {
            return response()->json(['ok' => false, 'name' => null, 'address' => null, 'places' => []], 422);
        }

        $cacheKey = 'geo_rev_v3_' . round($lat, 4) . '_' . round($lng, 4);

        if ($cached = Cache::get($cacheKey)) {
            return response()->json($cached);
        }

        $places = $this->nearbyPlaces($lat, $lng);

        if (!empty($places)) {
            $data = [
                'ok'      => true,
                'name'    => $places[0]['name'],
                'address' => $places[0]['address'],
                'places'  => $places,
                'source'  => 'google',
            ];
        } else {
            $rev = $this->lookupReverseName($lat, $lng);
            $data = [
                'ok'          => !empty($rev['name']) || !empty($rev['address']),
                'name'        => $rev['name'] ?? null,
                'address'     => $rev['address'] ?? null,
                'places'      => [],
                'source'      => $rev['source'] ?? 'none',
                'attribution' => '© OpenStreetMap contributors',
            ];
        }

        if ($data['ok']) {
            Cache::put($cacheKey, $data, now()->addDay());
        }

        return response()->json($data);
    }

    /**
     * Helper: cari tempat terdekat (hotel dulu) via Google Places Nearby Search (New)
     */
    private function nearbyPlaces(float $lat, float $lng): array
    {
        $key = $this->apiKey();
        if (!$key) return [];

        $search = function (array $extra, float $radius) use ($key, $lat, $lng) {
            try {
                $res = Http::timeout(6)->withHeaders([
                    'X-Goog-Api-Key'   => $key,
                    'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
                ])->post('https://places.googleapis.com/v1/places:searchNearby', array_merge([
                    'maxResultCount'      => 5,
                    'rankPreference'      => 'DISTANCE',
                    'languageCode'        => 'id',
                    'locationRestriction' => ['circle' => [
                        'center' => ['latitude' => $lat, 'longitude' => $lng],
                        'radius' => $radius,
                    ]],
                ], $extra));

                if ($res->ok()) {
                    return $res->json('places') ?? [];
                }
                Log::info('Google Nearby Search error: ' . $res->status() . ' ' . $res->body());
            } catch (\Throwable $e) {
                Log::info('Google Nearby Search exception: ' . $e->getMessage());
            }
            return [];
        };

        // Bertahap: penginapan 100 m -> tempat apa pun 100 m -> penginapan 300 m
        $found = $search(['includedTypes' => ['lodging']], 100.0);
        if (empty($found)) $found = $search(['includedTypes' => ['lodging']], 300.0);

        $places = [];
        foreach ($found as $p) {
            $name = $p['displayName']['text'] ?? null;
            $plat = $p['location']['latitude'] ?? null;
            $plng = $p['location']['longitude'] ?? null;
            if (!$name || $plat === null || $plng === null) continue;

            $places[] = [
                'name'    => $name,
                'address' => $p['formattedAddress'] ?? '',
                'lat'     => (float) $plat,
                'lng'     => (float) $plng,
            ];
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