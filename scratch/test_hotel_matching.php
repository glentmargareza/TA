<?php

require __DIR__ . '/../vendor/autoload.php';

$hotels = [
    [
        'nama' => 'Villa Padi Cangkringan',
        'alias' => ['Villa Padi', 'Villa Padi Jogja', 'Villa Padi Yogyakarta', 'Padi Villa'],
        'alamat' => 'Jl. Raya Merapi Golf, Pusmalang, Wukirsari, Cangkringan, Sleman, Yogyakarta',
        'lat' => -7.6369528,
        'lng' => 110.4431872,
    ],
    [
        'nama' => 'Villa Padi Pakem',
        'alias' => ['Villa Padi Pakem Jogja'],
        'alamat' => 'Jl. Pakem - Turi Km 0.5, Pakembinangun, Pakem, Sleman',
        'lat' => -7.6663245,
        'lng' => 110.4158931,
    ],
    [
        'nama' => 'The Cangkringan Villas & Spa',
        'alias' => ['The Cangkringan', 'Cangkringan Villas'],
        'alamat' => 'Jl. Raya Merapi Golf, Umbulharjo, Cangkringan, Sleman',
        'lat' => -7.6266357,
        'lng' => 110.4443825,
    ],
    [
        'nama' => 'Griya Persada Convention Hotel & Resort Kaliurang',
        'alias' => ['Griya Persada', 'Griya Persada Kaliurang'],
        'alamat' => 'Jl. Boyong No. 99, Kaliurang Barat, Hargobinangun, Pakem, Sleman',
        'lat' => -7.5996024,
        'lng' => 110.4243681,
    ],
    [
        'nama' => 'Warung Kopi Klotok Pakem',
        'alias' => ['Kopi Klotok', 'Kopi Klotok Kaliurang'],
        'alamat' => 'Jl. Kaliurang Km 16, Area Sawah, Pakembinangun, Pakem, Sleman',
        'lat' => -7.6688190,
        'lng' => 110.4196120,
    ]
];

$normalize = function (string $str): string {
    $str = mb_strtolower($str);
    $str = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $str);
    $str = preg_replace('/\b(yogyakarta|jogja|yogya|diy|d\.i\.y|sleman|cangkringan|pakem|indonesia)\b/u', ' ', $str);
    return trim(preg_replace('/\s+/', ' ', $str));
};

function searchTest(string $query, array $hotels, $normalize) {
    $cleanQuery = $normalize($query);
    if (empty($cleanQuery)) {
        $cleanQuery = trim(preg_replace('/\s+/', ' ', preg_replace('/[^\p{L}\p{N}\s]/u', ' ', mb_strtolower($query))));
    }
    $queryWords = array_values(array_filter(explode(' ', $cleanQuery), fn($w) => mb_strlen($w) >= 2));

    $matched = [];
    foreach ($hotels as $item) {
        $name = $item['nama'];
        $aliases = $item['alias'] ?? [];
        $candidates = array_merge([$name], $aliases);
        $score = 0;

        foreach ($candidates as $cand) {
            $cleanCand = $normalize($cand);
            if (empty($cleanCand)) continue;

            if ($cleanCand === $cleanQuery) {
                $score = max($score, 100);
                break;
            }
            if (str_contains($cleanCand, $cleanQuery) || str_contains($cleanQuery, $cleanCand)) {
                $score = max($score, 90);
                continue;
            }

            $candWords = array_values(array_filter(explode(' ', $cleanCand), fn($w) => mb_strlen($w) >= 2));
            $allWordsFound = true;
            foreach ($queryWords as $qw) {
                $found = false;
                foreach ($candWords as $cw) {
                    if (str_contains($cw, $qw) || str_contains($qw, $cw)) {
                        $found = true;
                        break;
                    }
                    if (max(mb_strlen($qw), mb_strlen($cw)) >= 4 && levenshtein($qw, $cw) <= 1) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $allWordsFound = false;
                    break;
                }
            }
            if ($allWordsFound && !empty($queryWords)) {
                $score = max($score, 80);
            }
        }

        if ($score > 0) {
            $matched[] = ['score' => $score, 'name' => $name, 'address' => $item['alamat']];
        }
    }

    usort($matched, fn($a, $b) => $b['score'] <=> $a['score']);
    return $matched;
}

$testQueries = [
    'Villa Padi Cangkringan Yogyakarta',
    'villa padi',
    'kopi klotok',
    'griya persada kaliurang',
    'the cangkringan',
    'cangkringan',
];

foreach ($testQueries as $q) {
    echo "=== Query: $q ===\n";
    $results = searchTest($q, $hotels, $normalize);
    foreach ($results as $r) {
        echo "  - [score {$r['score']}] {$r['name']} ({$r['address']})\n";
    }
}

