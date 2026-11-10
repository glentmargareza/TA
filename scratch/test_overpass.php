<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();

// Query Overpass for tourism=hotel,guest_house,motel,hostel,chalet,apartment,resort in bbox around Merapi/Sleman/Yogya
$bbox = "-7.85,110.30,-7.50,110.55"; // Area Sleman, Cangkringan, Pakem, Kaliurang, Kota Yogyakarta
$overpassQuery = <<<QL
[out:json][timeout:15];
(
  node["tourism"~"hotel|guest_house|motel|hostel|chalet|apartment|resort|camp_site"]($bbox);
  way["tourism"~"hotel|guest_house|motel|hostel|chalet|apartment|resort|camp_site"]($bbox);
);
out center tags;
QL;

try {
    $res = $client->post('https://overpass-api.de/api/interpreter', [
        'form_params' => ['data' => $overpassQuery],
        'timeout' => 15,
        'headers' => [
            'User-Agent' => 'JeepWukirsari/1.0',
        ],
    ]);
    $data = json_decode($res->getBody(), true);
    $elements = $data['elements'] ?? [];
    echo "Overpass found " . count($elements) . " tourism accommodations in Sleman/Yogya/Merapi area!\n";

    // Check if Villa Padi is in here
    foreach ($elements as $el) {
        $name = $el['tags']['name'] ?? '';
        if (stripos($name, 'Padi') !== false || stripos($name, 'Cangkringan') !== false) {
            echo "Found matching: $name\n";
        }
    }
} catch (\Throwable $e) {
    echo "Overpass error: " . $e->getMessage() . "\n";
}

