<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new \GuzzleHttp\Client();

$q = 'Villa Padi Cangkringan Yogyakarta';
$url = 'https://www.google.com/maps/search/' . urlencode($q);

try {
    $res = $client->get($url, [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        ],
        'allow_redirects' => true,
        'timeout' => 8,
    ]);
    
    $body = (string) $res->getBody();
    echo "Status: " . $res->getStatusCode() . "\n";
    echo "Body length: " . strlen($body) . "\n";
    
    // Check if coordinates exist in body
    // !3d-7.xxx!4d110.xxx
    if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $body, $m)) {
        echo "Found coordinates via !3d!4d: " . $m[1] . ", " . $m[2] . "\n";
    }
    // @-7.xxx,110.xxx
    if (preg_match('/@(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $body, $m)) {
        echo "Found coordinates via @: " . $m[1] . ", " . $m[2] . "\n";
    }
    // Check title or og:title
    if (preg_match('/<meta content="([^"]+)" property="og:title"/', $body, $m)) {
        echo "og:title: " . html_entity_decode($m[1]) . "\n";
    }
    if (preg_match('/<title>([^<]+)<\/title>/', $body, $m)) {
        echo "title: " . html_entity_decode($m[1]) . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

