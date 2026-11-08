<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GOOGLE_MAPS_API_KEY') ?: (config('services.google.maps_api_key') ?: '');

echo "=== GOOGLE PLACES API TEST ===\n";
echo "Configured Key: " . ($key ? ("SET (len " . strlen($key) . ")") : "EMPTY / NULL") . "\n";

$res = \Illuminate\Support\Facades\Http::withHeaders([
    'X-Goog-Api-Key'   => $key,
    'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
    'Content-Type'     => 'application/json',
])->post('https://places.googleapis.com/v1/places:searchText', [
    'textQuery'    => 'Villa Padi Cangkringan',
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
]);

echo "HTTP Status from Google: " . $res->status() . "\n";
echo "Raw Response Body:\n" . $res->body() . "\n";

