<?php

namespace App\Services;

class PaymentFeeService
{
    /**
     * Menghitung rincian biaya admin berdasarkan metode pembayaran dan nominal yang dibayarkan saat itu.
     * Berlaku deterministik untuk skema DP maupun Pelunasan 100%.
     *
     * @param string $method Metode pembayaran (bri_va, dana, cash, dll.)
     * @param int|float $baseAmount Nominal dasar booking yang dibayarkan pada transaksi ini
     * @return array
     */
    public static function calculate(string $method, float|int $baseAmount): array
    {
        $baseAmount = max(0, (float) $baseAmount);
        $methodKey = strtolower(trim($method));

        // Normalisasi alias metode jika diperlukan
        if (in_array($methodKey, ['bri', 'briva'])) {
            $methodKey = 'bri_va';
        }

        $allFees = config('payment_fees.fees', []);
        $config = $allFees[$methodKey] ?? [
            'type'        => 'flat',
            'rate'        => 0,
            'tax_percent' => 0,
            'name'        => 'Biaya Layanan',
        ];

        $feeType    = $config['type'] ?? 'flat';
        $rate       = (float) ($config['rate'] ?? 0);
        $taxPercent = (float) ($config['tax_percent'] ?? 0);

        if ($feeType === 'percent') {
            $rawFee = ($baseAmount * $rate) / 100.0;
        } else {
            $rawFee = $rate;
        }

        $taxAmount = 0.0;
        if ($taxPercent > 0) {
            $taxAmount = ($rawFee * $taxPercent) / 100.0;
        }

        $adminFee    = (int) round($rawFee + $taxAmount);
        $totalAmount = (int) round($baseAmount + $adminFee);

        return [
            'method'       => $methodKey,
            'method_name'  => $config['name'] ?? 'Biaya Layanan Admin',
            'base_amount'  => (int) round($baseAmount),
            'fee_type'     => $feeType,
            'fee_rate'     => $rate,
            'tax_percent'  => $taxPercent,
            'tax_amount'   => (int) round($taxAmount),
            'admin_fee'    => $adminFee,
            'total_amount' => $totalAmount,
            'is_free'      => ($adminFee === 0),
        ];
    }

    /**
     * Mengambil daftar tarif untuk diinjeksikan secara aman ke antarmuka pengguna (frontend)
     */
    public static function getFrontendRates(): array
    {
        $configs = config('payment_fees.fees', []);
        $rates = [];
        foreach ($configs as $key => $conf) {
            $rates[$key] = [
                'type'        => $conf['type'] ?? 'flat',
                'rate'        => (float) ($conf['rate'] ?? 0),
                'tax_percent' => (float) ($conf['tax_percent'] ?? 0),
                'name'        => $conf['name'] ?? '',
            ];
        }
        return $rates;
    }
}

