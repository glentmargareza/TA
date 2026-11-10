<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();

$queries = [
    'Villa Padi',
    'Villa Padi Cangkringan',
    'Villa Padi Cangkringan Yogyakarta',
    'The Cangkringan',
    'Sambi Resort',
    'Griya Persada',
    'HeHa Forest',
    'Ledok Sambi',
];

foreach ($queries as $q) {
    $url = 'https://photon.komoot.io/api/?q=' . urlencode($q) . '&limit=3&lat=-7.6&lon=110.43';
    try {
        $res = $client->get($url, [
            'headers' => ['User-Agent' => 'JeepWukirsari/1.0'],
            'timeout' => 5,
        ]);
        $data = json_decode($res->getBody(), true);
        echo "=== Query: $q ===\n";
        foreach ($data['features'] ?? [] as $f) {
            $p = $f['properties'];
            $c = $f['geometry']['coordinates'];
            echo "  * " . ($p['name'] ?? '-') . " | " . ($p['street'] ?? '') . ", " . ($p['city'] ?? ($p['county'] ?? '')) . " (" . $c[1] . ", " . $c[0] . ")\n";
        }
        if (empty($data['features'])) echo "  (NO RESULTS)\n";
    } catch (\Throwable $e) {
        echo "Error for '$q': " . $e->getMessage() . "\n";
    }
}

