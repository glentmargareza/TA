<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();
$q = 'Villa Padi Cangkringan';
$url = 'https://www.google.com/maps/search/' . urlencode($q);

$res = $client->get($url, [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept-Language' => 'id-ID,id;q=0.9',
    ],
    'timeout' => 8,
]);

$body = (string) $res->getBody();
$pos = strpos($body, 'Villa Padi Cangkringan');
if ($pos !== false) {
    echo "Found at offset $pos:\n";
    echo substr($body, max(0, $pos - 200), 1000) . "\n";
}

