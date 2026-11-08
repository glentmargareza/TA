<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$queries = ['Villa Padi', 'Villa Padi Cangkringan', 'Villa Padi Cangkringan Yogyakarta'];

foreach ($queries as $q) {
    echo "=== Query: $q ===\n";
    $req = \Illuminate\Http\Request::create('/api/places-autocomplete?q=' . urlencode($q), 'GET');
    $res = $app->handle($req);
    echo "Status: " . $res->getStatusCode() . "\n";
    echo "Body: " . $res->getContent() . "\n\n";
}

