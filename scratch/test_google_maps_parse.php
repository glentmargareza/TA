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

// Look for coordinates pattern in the entire page
if (preg_match_all('/\[null,null,(-7\.\d+),(110\.\d+)\]/', $body, $matches)) {
    echo "Found [null,null,lat,lng]:\n";
    print_r(array_unique($matches[0]));
}

if (preg_match_all('/\[(-7\.\d+),(110\.\d+)\]/', $body, $matches)) {
    echo "Found [lat,lng]:\n";
    print_r(array_unique($matches[0]));
}

// Look for "Villa Padi" in the body
if (preg_match_all('/"([^"]*Villa Padi[^"]*)"/i', $body, $matches)) {
    echo "Found strings with Villa Padi:\n";
    print_r(array_unique($matches[1]));
}

