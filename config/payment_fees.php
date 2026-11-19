<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Biaya Admin Gateway Pembayaran (Payment Fee Configuration)
    |--------------------------------------------------------------------------
    |
    | Nilai di bawah ini adalah tarif referensi sementara untuk simulasi & integrasi,
    | bukan tarif final merchant, dan dapat disesuaikan dengan skema fee resmi akun Midtrans.
    |
    | Struktur konfigurasi per metode pembayaran:
    | - 'type'        : 'flat' (nominal tetap dalam Rupiah) atau 'percent' (persentase)
    | - 'rate'        : nilai Rupiah (jika flat) atau persen (jika percent)
    | - 'tax_percent' : persentase pajak terpisah (misal PPN 11%), default 0 jika belum ada
    | - 'name'        : label nama biaya untuk tampilan antarmuka dan bukti transaksi
    |
    */

    'fees' => [
        'bri_va' => [
            'type'        => 'flat',
            'rate'        => (float) env('FEE_BRI_VA_RATE', 4000), // Referensi sementara: Rp 4.000 flat
            'tax_percent' => (float) env('FEE_BRI_VA_TAX_PERCENT', 0),
            'name'        => 'Biaya Layanan BRI Virtual Account',
        ],

        'dana' => [
            'type'        => 'percent',
            'rate'        => (float) env('FEE_DANA_RATE', 1.5), // Referensi sementara: 1.5%
            'tax_percent' => (float) env('FEE_DANA_TAX_PERCENT', 0),
            'name'        => 'Biaya Layanan DANA (1,5%)',
        ],

        'cash' => [
            'type'        => 'flat',
            'rate'        => 0,
            'tax_percent' => 0,
            'name'        => 'Bayar Tunai di Loket (Bebas Biaya)',
        ],

        'cash_basecamp' => [
            'type'        => 'flat',
            'rate'        => 0,
            'tax_percent' => 0,
            'name'        => 'Pelunasan Tunai di Basecamp (Bebas Biaya)',
        ],
    ],
];

