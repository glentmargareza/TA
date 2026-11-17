<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();

$queries = ['Jalan Kaliurang', 'Jl Palagan', 'Pakem Sleman', 'Wukirsari Cangkringan'];

foreach ($queries as $q) {
    try {
        $res = $client->get('https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q' => $q,
                'format' => 'jsonv2',
                'countrycodes' => 'id',
                'limit' => 3,
                'addressdetails' => 1,
                'viewbox' => '110.15,-8.05,110.60,-7.50',
                'bounded' => 0,
            ],
            'headers' => ['User-Agent' => 'JeepWukirsari/1.0'],
            'timeout' => 5,
        ]);
        $data = json_decode($res->getBody(), true);
        echo "=== Nominatim for '$q' ===\n";
        foreach ($data as $d) {
            echo "  * " . ($d['display_name'] ?? '') . " (" . $d['lat'] . ", " . $d['lon'] . ")\n";
        }
    } catch (\Throwable $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

