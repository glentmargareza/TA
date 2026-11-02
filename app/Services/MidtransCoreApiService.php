<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class MidtransCoreApiService
{
    public function __construct()
    {
        $this->configureMidtrans();
    }

    /**
     * Konfigurasi Midtrans SDK menggunakan Server Key dan Client Key dari konfigurasi
     */
    protected function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$clientKey = config('services.midtrans.client_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    /**
     * Membuat transaksi charge melalui Midtrans Core API
     */
    public function charge(Booking $booking, string $paymentMethod, ?int $customAmount = null): array
    {
        // 1. Guard: Booking harus berstatus PENDING
        if ($booking->booking_status !== 'PENDING') {
            throw ValidationException::withMessages([
                'booking' => 'Reservasi ini sudah terbayar atau tidak dalam status menunggu pembayaran.'
            ]);
        }

        // 2. Kalkulasi Nominal Integer Deterministik
        $totalAmountInt = (int) round($booking->total_amount);
        $minDpInt = (int) intdiv(($totalAmountInt * 30) + 99, 100); // 30% ceiling

        if ($booking->payment_mode === 'FULL') {
            $chargeAmount = $totalAmountInt;
            $paymentStage = 'FULL_PAYMENT';
        } else {
            // Skema DP
            if ($customAmount !== null) {
                if ($customAmount < $minDpInt) {
                    throw ValidationException::withMessages([
                        'amount' => 'Nominal pembayaran DP minimal 30% sebesar Rp ' . number_format($minDpInt, 0, ',', '.')
                    ]);
                }
                if ($customAmount > $totalAmountInt) {
                    throw ValidationException::withMessages([
                        'amount' => 'Nominal pembayaran tidak boleh melebihi total tagihan sebesar Rp ' . number_format($totalAmountInt, 0, ',', '.')
                    ]);
                }
                if ($customAmount === $totalAmountInt) {
                    $chargeAmount = $totalAmountInt;
                    $paymentStage = 'FULL_PAYMENT';
                } else {
                    $chargeAmount = $customAmount;
                    $paymentStage = 'DOWN_PAYMENT';
                }
            } else {
                $defaultPaid = (int) round($booking->paid_amount);
                $chargeAmount = max($defaultPaid, $minDpInt);
                $paymentStage = ($chargeAmount >= $totalAmountInt) ? 'FULL_PAYMENT' : 'DOWN_PAYMENT';
            }
        }

        // 3. Pencegahan Transaksi Ganda: Periksa Transaksi Pending yang Masih Aktif
        $existingPayment = $this->getActivePendingPayment($booking->id, $paymentMethod);
        if ($existingPayment) {
            return $this->formatPaymentResponse($existingPayment);
        }

        // 4. Generate order_id Unik dengan Entropi Tinggi
        $orderId = $booking->booking_code . '-TRX-' . strtoupper(Str::random(6));

        // 5. Susun Payload Sesuai Metode Pembayaran Resmi Midtrans
        $payload = $this->buildChargePayload($booking, $orderId, $chargeAmount, $paymentMethod, $paymentStage);

        // 6. Eksekusi Request Charge ke Midtrans Sandbox API
        try {
            $response = \Midtrans\CoreApi::charge($payload);
        } catch (\Exception $e) {
            Log::error("Midtrans Core API Charge Error [{$orderId}]: " . $e->getMessage());
            throw new \RuntimeException('Gagal menghubungi gateway pembayaran Midtrans: ' . $e->getMessage(), $e->getCode(), $e);
        }

        // 7. Ekstraksi dan Normalisasi Data Respons
        $normalizedData = $this->extractNormalizedResponse($response, $orderId, $paymentMethod, $chargeAmount);

        // 8. Simpan Transaksi ke Tabel payments (Defensif Kompatibel)
        $payment = $this->persistPaymentRecord($booking, $normalizedData, $paymentStage);

        return $normalizedData;
    }

    /**
     * Membangun payload charge terstruktur sesuai dokumentasi spesifik Midtrans
     */
    protected function buildChargePayload(Booking $booking, string $orderId, int $chargeAmount, string $paymentMethod, string $paymentStage): array
    {
        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $chargeAmount,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'phone' => $booking->customer_phone,
                'email' => $booking->customer_email ?? 'wisatawan@jeepwukirsari.id',
            ],
            'item_details' => [
                [
                    'id' => (string) $booking->package_id,
                    'price' => $chargeAmount,
                    'quantity' => 1,
                    'name' => ($paymentStage === 'DOWN_PAYMENT' ? 'DP (30%) ' : 'Lunas ') . substr($booking->package->name, 0, 35),
                ]
            ],
            'custom_expiry' => [
                'order_time' => now()->format('Y-m-d H:i:s O'),
                'expiry_duration' => 30,
                'unit' => 'minute',
            ],
        ];

        switch ($paymentMethod) {
            case 'bca_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bca'];
                break;

            case 'bni_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bni'];
                break;

            case 'bri_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bri'];
                break;

            case 'mandiri_echannel':
                $payload['payment_type'] = 'echannel';
                $payload['echannel'] = [
                    'bill_info1' => 'Tagihan Jeep:',
                    'bill_info2' => 'Wukirsari Tour'
                ];
                break;

            case 'qris':
                $payload['payment_type'] = 'qris';
                $payload['qris'] = ['acquirer' => 'gopay'];
                break;

            case 'gopay':
                $payload['payment_type'] = 'gopay';
                $payload['gopay'] = [
                    'enable_callback' => true,
                    'callback_url' => route('booking.payment', $booking->booking_code)
                ];
                break;

            case 'shopeepay':
                $payload['payment_type'] = 'shopeepay';
                $payload['shopeepay'] = [
                    'callback_url' => route('booking.payment', $booking->booking_code)
                ];
                break;

            default:
                throw ValidationException::withMessages([
                    'payment_method' => 'Metode pembayaran tidak didukung.'
                ]);
        }

        return $payload;
    }

    /**
     * Mengekstraksi dan menormalisasi respons mentah Midtrans Core API
     */
    protected function extractNormalizedResponse($response, string $orderId, string $paymentMethod, int $chargeAmount): array
    {
        $responseObj = is_object($response) ? $response : (object) $response;
        
        $vaNumber = null;
        if (!empty($responseObj->va_numbers) && is_array($responseObj->va_numbers)) {
            $vaNumber = $responseObj->va_numbers[0]->va_number ?? null;
        } elseif (!empty($responseObj->permata_va_number)) {
            $vaNumber = $responseObj->permata_va_number;
        }

        $billerCode = $responseObj->biller_code ?? null;
        $billKey = $responseObj->bill_key ?? null;

        $qrUrl = null;
        $deeplinkUrl = null;
        if (!empty($responseObj->actions) && is_array($responseObj->actions)) {
            foreach ($responseObj->actions as $action) {
                $actionObj = (object) $action;
                if (($actionObj->name ?? '') === 'generate-qr-code') {
                    $qrUrl = $actionObj->url ?? null;
                }
                if (($actionObj->name ?? '') === 'deeplink-redirect') {
                    $deeplinkUrl = $actionObj->url ?? null;
                }
            }
        }

        $qrString = $responseObj->qr_string ?? null;

        $expiresAt = null;
        if (!empty($responseObj->expiry_time)) {
            try {
                $expiresAt = Carbon::parse($responseObj->expiry_time);
            } catch (\Exception $e) {
                $expiresAt = now()->addMinutes(30);
            }
        } else {
            $expiresAt = now()->addMinutes(30);
        }

        return [
            'order_id' => $orderId,
            'transaction_id' => $responseObj->transaction_id ?? null,
            'payment_method' => $paymentMethod,
            'gross_amount' => $chargeAmount,
            'va_number' => $vaNumber,
            'biller_code' => $billerCode,
            'bill_key' => $billKey,
            'qr_url' => $qrUrl,
            'qr_string' => $qrString,
            'deeplink_url' => $deeplinkUrl,
            'expires_at' => $expiresAt->toDateTimeString(),
            'transaction_status' => $responseObj->transaction_status ?? 'pending',
            'raw_response' => (array) $responseObj,
        ];
    }

    /**
     * Menyimpan transaksi ke tabel payments dengan penanganan kompatibilitas skema
     */
    protected function persistPaymentRecord(Booking $booking, array $normalizedData, string $paymentStage): Payment
    {
        $hasOrderIdCol = Schema::hasColumn('payments', 'order_id');

        if ($hasOrderIdCol) {
            return Payment::create([
                'booking_id' => $booking->id,
                'order_id' => $normalizedData['order_id'],
                'transaction_ref' => $normalizedData['transaction_id'] ?? $normalizedData['order_id'],
                'payment_stage' => $paymentStage,
                'payment_type' => $normalizedData['payment_method'],
                'payment_method' => $normalizedData['payment_method'],
                'va_number' => $normalizedData['va_number'],
                'biller_code' => $normalizedData['biller_code'],
                'bill_key' => $normalizedData['bill_key'],
                'qr_url' => $normalizedData['qr_url'],
                'qr_string' => $normalizedData['qr_string'],
                'deeplink_url' => $normalizedData['deeplink_url'],
                'gross_amount' => $normalizedData['gross_amount'],
                'payment_status' => 'PENDING',
                'expires_at' => $normalizedData['expires_at'],
                'payload_response' => $normalizedData['raw_response'],
                'paid_at' => null,
            ]);
        } else {
            // Skema sebelum migrasi dijalankan: simpan ke kolom yang ada & simpan cache
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'transaction_ref' => $normalizedData['order_id'],
                'payment_stage' => $paymentStage,
                'payment_type' => $normalizedData['payment_method'],
                'gross_amount' => $normalizedData['gross_amount'],
                'payment_status' => 'PENDING',
                'paid_at' => null,
            ]);

            Cache::put("payment_payload_{$normalizedData['order_id']}", $normalizedData, 1800);
            return $payment;
        }
    }

    /**
     * Memeriksa apakah ada transaksi pending aktif untuk booking dan metode ini
     */
    protected function getActivePendingPayment(int $bookingId, string $paymentMethod): ?Payment
    {
        $hasOrderIdCol = Schema::hasColumn('payments', 'order_id');

        if ($hasOrderIdCol) {
            return Payment::where('booking_id', $bookingId)
                ->where('payment_status', 'PENDING')
                ->where('payment_method', $paymentMethod)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->latest()
                ->first();
        } else {
            return Payment::where('booking_id', $bookingId)
                ->where('payment_status', 'PENDING')
                ->where('payment_type', $paymentMethod)
                ->latest()
                ->first();
        }
    }

    /**
     * Menyeragamkan format output dari Payment model menjadi array standar
     */
    public function formatPaymentResponse(Payment $payment): array
    {
        $hasOrderIdCol = Schema::hasColumn('payments', 'order_id');

        if ($hasOrderIdCol && !empty($payment->order_id)) {
            return [
                'order_id' => $payment->order_id,
                'transaction_id' => $payment->transaction_ref,
                'payment_method' => $payment->payment_method ?? $payment->payment_type,
                'gross_amount' => (int) round($payment->gross_amount),
                'va_number' => $payment->va_number,
                'biller_code' => $payment->biller_code,
                'bill_key' => $payment->bill_key,
                'qr_url' => $payment->qr_url,
                'qr_string' => $payment->qr_string,
                'deeplink_url' => $payment->deeplink_url,
                'expires_at' => $payment->expires_at ? $payment->expires_at->toDateTimeString() : null,
                'transaction_status' => 'pending',
                'raw_response' => $payment->payload_response ?? [],
            ];
        }

        // Ambil dari cache jika belum ada kolom order_id di database
        $cached = Cache::get("payment_payload_{$payment->transaction_ref}");
        if ($cached) {
            return $cached;
        }

        // Fallback andal jika cache kedaluwarsa: panggil status Midtrans langsung via API
        if (!empty($payment->transaction_ref)) {
            try {
                $statusResp = \Midtrans\Transaction::status($payment->transaction_ref);
                if ($statusResp) {
                    $extracted = $this->extractNormalizedResponse(
                        $statusResp, 
                        $payment->transaction_ref, 
                        $payment->payment_type ?? 'bank_transfer', 
                        (int) round($payment->gross_amount)
                    );
                    Cache::put("payment_payload_{$payment->transaction_ref}", $extracted, 1800);
                    return $extracted;
                }
            } catch (\Exception $e) {
                Log::warning("Inquiry status Midtrans fallback dilewati: " . $e->getMessage());
            }
        }

        return [
            'order_id' => $payment->transaction_ref,
            'transaction_id' => $payment->transaction_ref,
            'payment_method' => $payment->payment_type,
            'gross_amount' => (int) round($payment->gross_amount),
            'va_number' => null,
            'biller_code' => null,
            'bill_key' => null,
            'qr_url' => null,
            'qr_string' => null,
            'deeplink_url' => null,
            'expires_at' => null,
            'transaction_status' => 'pending',
            'raw_response' => [],
        ];
    }
}
