<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();

echo "=== 1. Test Nominatim with various queries ===\n";
$queries = [
    'Villa Padi Cangkringan',
    'Villa Padi Sleman',
    'Villa Padi Yogyakarta',
    'Villa Padi',
];

foreach ($queries as $q) {
    try {
        $res = $client->get('https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q' => $q,
                'format' => 'jsonv2',
                'countrycodes' => 'id',
                'limit' => 5,
                'addressdetails' => 1,
            ],
            'headers' => [
                'User-Agent' => 'JeepWukirsariTest/1.0 (info@jeepwukirsari.id)',
            ],
            'timeout' => 5,
        ]);
        $data = json_decode($res->getBody(), true);
        echo "Nominatim query '$q': found " . count($data) . " items\n";
        foreach ($data as $d) {
            echo "  - " . ($d['display_name'] ?? '') . " (" . $d['lat'] . ", " . $d['lon'] . ")\n";
        }
    } catch (\Throwable $e) {
        echo "Nominatim error: " . $e->getMessage() . "\n";
    }
}

