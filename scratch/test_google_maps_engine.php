<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();

function queryGoogleMapsNoKey(string $q, \GuzzleHttp\Client $client): array
{
    $url = 'https://www.google.com/maps/search/' . urlencode($q);
    try {
        $res = $client->get($url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            ],
            'timeout' => 5,
        ]);
        $body = (string) $res->getBody();

        // 1. Check staticmap center: staticmap?center=(-?\d+\.\d+)%2C(1\d+\.\d+)
        $lat = null;
        $lng = null;
        if (preg_match('/staticmap\?center=(-?\d+\.\d+)%2C(1\d+\.\d+)/', $body, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
        }

        // 2. Check coordinates in [null,null,lat,lng] or !3dlat!4dlng
        if (!$lat && preg_match('/!3d(-?\d+\.\d+)!4d(1\d+\.\d+)/', $body, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
        }

        if (!$lat && preg_match('/@(-?\d+\.\d+),\s*(1\d+\.\d+)/', $body, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
        }

        // 3. Extract place name
        $name = null;
        if (preg_match('/meta content="([^"]+)" property="og:title"/', $body, $m)) {
            $title = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
            if ($title && $title !== 'Google Maps' && !str_contains($title, 'temukan bisnis')) {
                // Sering formatnya: "Nama Tempat · Alamat"
                $parts = explode(' · ', $title);
                $name = trim($parts[0]);
            }
        }

        // If og:title was just "Google Maps", check <title>Nama - Google Maps</title>
        if (!$name && preg_match('/<title>([^<]+)<\/title>/', $body, $m)) {
            $t = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
            $t = str_replace([' - Google Maps', ' · Google Maps', ' - Google Peta'], '', $t);
            $t = trim($t);
            if ($t && $t !== 'Google Maps') {
                $name = $t;
            }
        }

        // Check if there are place card titles in json
        if (!$name && preg_match('/\["([^"]+)",null,null,null,null,null,20/', $body, $m)) {
            $name = $m[1];
        }

        return [
            'name' => $name ?: $q,
            'lat'  => $lat,
            'lng'  => $lng,
        ];
    } catch (\Throwable $e) {
        return ['error' => $e->getMessage()];
    }
}

$testQueries = [
    'Villa Padi Cangkringan',
    'Villa Padi Yogyakarta',
    'Hotel Tentrem Yogyakarta',
    'Kopi Klotok Kaliurang',
    'Griya Persada Kaliurang',
    'The Cangkringan Villas & Spa',
];

foreach ($testQueries as $q) {
    echo "=== Query: $q ===\n";
    $res = queryGoogleMapsNoKey($q, $client);
    print_r($res);
}

