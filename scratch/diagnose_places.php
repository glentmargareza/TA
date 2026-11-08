<?php

require 'C:/xampp/htdocs/TA/vendor/autoload.php';
$app = require_once 'C:/xampp/htdocs/TA/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== 1. CHECKING ENVIRONMENT VARIABLES ===\n";
$envFile = base_path('.env');
$envContent = file_exists($envFile) ? file_get_contents($envFile) : '';

$hasKeyInEnv = preg_match('/^GOOGLE_MAPS_API_KEY=(.*)$/m', $envContent, $mKey);
$rawKeyInEnv = $hasKeyInEnv ? trim($mKey[1], " \t\n\r\0\x0B\"'") : null;

$envServerKey = preg_match('/^GOOGLE_MAPS_SERVER_KEY=(.*)$/m', $envContent, $mServer);
$rawServerKey = $envServerKey ? trim($mServer[1], " \t\n\r\0\x0B\"'") : null;

$envBrowserKey = preg_match('/^GOOGLE_MAPS_BROWSER_KEY=(.*)$/m', $envContent, $mBrowser);
$rawBrowserKey = $envBrowserKey ? trim($mBrowser[1], " \t\n\r\0\x0B\"'") : null;

echo "GOOGLE_MAPS_API_KEY in .env: " . ($rawKeyInEnv ? ("YES (Length: " . strlen($rawKeyInEnv) . ", prefix: " . substr($rawKeyInEnv, 0, 6) . "...)") : "EMPTY / NOT SET") . "\n";
echo "GOOGLE_MAPS_SERVER_KEY in .env: " . ($rawServerKey ? ("YES (Length: " . strlen($rawServerKey) . ")") : "EMPTY / NOT SET") . "\n";
echo "GOOGLE_MAPS_BROWSER_KEY in .env: " . ($rawBrowserKey ? ("YES (Length: " . strlen($rawBrowserKey) . ")") : "EMPTY / NOT SET") . "\n";

$effectiveKey = config('services.google.maps_api_key') 
    ?: (env('GOOGLE_MAPS_API_KEY') 
    ?: ($rawKeyInEnv ?: ($rawServerKey ?: $rawBrowserKey)));

echo "Effective Key in Laravel config/env: " . ($effectiveKey ? ("YES (Length: " . strlen($effectiveKey) . ")") : "EMPTY / NULL") . "\n\n";

echo "=== 2. DIRECT TEST TO GOOGLE PLACES API (NEW) ===\n";
if ($effectiveKey) {
    // 2a. Test places:searchText
    echo "Testing POST https://places.googleapis.com/v1/places:searchText\n";
    $payloadText = [
        'textQuery'    => 'Villa Padi Cangkringan',
        'languageCode' => 'id',
        'regionCode'   => 'ID',
        'locationBias' => [
            'circle' => [
                'center' => [
                    'latitude'  => -7.6,
                    'longitude' => 110.43,
                ],
                'radius' => 30000.0,
            ],
        ],
    ];

    $resText = \Illuminate\Support\Facades\Http::withHeaders([
        'X-Goog-Api-Key'   => $effectiveKey,
        'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location',
        'Content-Type'     => 'application/json',
    ])->post('https://places.googleapis.com/v1/places:searchText', $payloadText);

    echo "HTTP Status: " . $resText->status() . "\n";
    echo "Response Body:\n" . $resText->body() . "\n\n";

    // 2b. Test places:autocomplete
    echo "Testing POST https://places.googleapis.com/v1/places:autocomplete\n";
    $payloadAuto = [
        'input'        => 'Villa Padi Cangkringan',
        'languageCode' => 'id',
        'regionCode'   => 'ID',
        'locationBias' => [
            'circle' => [
                'center' => [
                    'latitude'  => -7.6,
                    'longitude' => 110.43,
                ],
                'radius' => 30000.0,
            ],
        ],
    ];

    $resAuto = \Illuminate\Support\Facades\Http::withHeaders([
        'X-Goog-Api-Key' => $effectiveKey,
        'Content-Type'   => 'application/json',
    ])->post('https://places.googleapis.com/v1/places:autocomplete', $payloadAuto);

    echo "HTTP Status: " . $resAuto->status() . "\n";
    echo "Response Body:\n" . $resAuto->body() . "\n\n";
} else {
    echo "Skipping direct Google test because key is EMPTY / NOT SET in .env\n\n";
}

echo "=== 3. CALLING LOCAL CONTROLLER ENDPOINT ===\n";
$req = \Illuminate\Http\Request::create('/api/places-autocomplete?q=Villa+Padi+Cangkringan', 'GET');
$res = $app->handle($req);
echo "Local Endpoint Status: " . $res->getStatusCode() . "\n";
echo "Local Endpoint Response Body:\n" . $res->getContent() . "\n\n";

