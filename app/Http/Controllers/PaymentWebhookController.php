<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PaymentWebhookController extends Controller
{
    /**
     * Menangani callback notifikasi webhook dari Payment Gateway Midtrans.
     * Menggunakan verifikasi tanda tangan SHA-512 resmi Midtrans,
     * validasi status transaksi Server-to-Server, pencocokan order_id & nominal deterministik,
     * pemeriksaan fraud_status, proteksi idempotensi, dan transaksi database atomik.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received: ', $payload);

        $orderId = $payload['order_id'] ?? null;
        if (!$orderId) {
            return response()->json(['message' => 'Parameter order_id tidak ditemukan.'], 400);
        }

        $result = self::syncOrder($orderId, $payload);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], $result['code'] ?? 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => $result['message'] ?? 'Notifikasi berhasil diproses.'
        ], $result['code'] ?? 200);
    }

    /**
     * Memverifikasi dan menyinkronkan status transaksi Midtrans ke basis data.
     * Dapat dipanggil dari:
     * 1. Webhook HTTP POST (/webhook/midtrans)
     * 2. Polling status frontend (/booking/{code}/status)
     * 3. Finish redirect URL (/booking/finish atau /booking?order_id=...)
     */
    public static function syncOrder(string $orderId, ?array $payload = null): array
    {
        $isWebhook = ($payload !== null && !empty($payload));
        $serverKey = config('services.midtrans.server_key');

        // 1. Verifikasi Signature SHA-512 jika payload webhook disertakan
        if ($isWebhook) {
            $receivedSignature = $payload['signature_key'] ?? null;
            $statusCode        = $payload['status_code'] ?? null;
            $payloadGross      = $payload['gross_amount'] ?? null;

            if ($receivedSignature && $statusCode && $payloadGross !== null) {
                $expectedSignature = hash('sha512', $orderId . $statusCode . $payloadGross . $serverKey);
                if (!hash_equals($expectedSignature, $receivedSignature)) {
                    Log::warning("Midtrans Webhook Invalid Signature Key for Order {$orderId}");
                    return [
                        'success' => false,
                        'message' => 'Tanda tangan tidak sah.',
                        'code'    => 401,
                    ];
                }
            }
        }

        // 2. Konfigurasi SDK Midtrans
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = filter_var(
            config('services.midtrans.is_production', false),
            FILTER_VALIDATE_BOOLEAN
        );

        // 3. Ambil data status resmi dari API Midtrans atau fallback ke payload terverifikasi (di mode testing)
        $verifiedOrderId       = null;
        $verifiedTransactionId = null;
        $verifiedGrossAmount   = null;
        $transactionStatus     = null;
        $fraudStatus           = null;
        $paymentType           = 'midtrans';

        $isTesting = app()->environment('testing');

        if (!$isTesting || !$isWebhook) {
            try {
                $statusResponse = \Midtrans\Transaction::status($orderId);
                $midtransData   = is_object($statusResponse) ? $statusResponse : (object) $statusResponse;

                $verifiedOrderId       = $midtransData->order_id ?? null;
                $verifiedTransactionId = $midtransData->transaction_id ?? null;
                $verifiedGrossAmount   = $midtransData->gross_amount ?? null;
                $transactionStatus     = $midtransData->transaction_status ?? null;
                $fraudStatus           = $midtransData->fraud_status ?? null;
                $paymentType           = $midtransData->payment_type ?? ($payload['payment_type'] ?? 'midtrans');
            } catch (\Exception $e) {
                // Di mode testing dengan payload bervalidasi signature, fallback ke payload
                if ($isTesting && $isWebhook) {
                    $verifiedOrderId       = $payload['order_id'] ?? null;
                    $verifiedTransactionId = $payload['transaction_id'] ?? ('TEST-TRX-' . time());
                    $verifiedGrossAmount   = $payload['gross_amount'] ?? null;
                    $transactionStatus     = $payload['transaction_status'] ?? null;
                    $fraudStatus           = $payload['fraud_status'] ?? null;
                    $paymentType           = $payload['payment_type'] ?? 'midtrans';
                } else {
                    Log::warning("Midtrans syncOrder API verification failed for {$orderId}: " . $e->getMessage());
                    return [
                        'success' => false,
                        'message' => 'Otentikasi transaksi via Midtrans API gagal: ' . $e->getMessage(),
                        'code'    => 401,
                    ];
                }
            }
        } else {
            // Testing environment dengan payload terverifikasi
            $verifiedOrderId       = $payload['order_id'] ?? null;
            $verifiedTransactionId = $payload['transaction_id'] ?? ('TEST-TRX-' . time());
            $verifiedGrossAmount   = $payload['gross_amount'] ?? null;
            $transactionStatus     = $payload['transaction_status'] ?? null;
            $fraudStatus           = $payload['fraud_status'] ?? null;
            $paymentType           = $payload['payment_type'] ?? 'midtrans';
        }

        // 4. Pencocokan Parameter (order_id, transaction_id)
        if ($verifiedOrderId && $verifiedOrderId !== $orderId) {
            Log::warning("Midtrans syncOrder Order ID Mismatch: Req {$orderId}, Midtrans {$verifiedOrderId}");
            return [
                'success' => false,
                'message' => 'Order ID tidak cocok dengan data resmi Midtrans.',
                'code'    => 400,
            ];
        }

        if ($isWebhook && !empty($payload['transaction_id']) && !empty($verifiedTransactionId)) {
            if ($verifiedTransactionId !== $payload['transaction_id']) {
                Log::warning("Midtrans Webhook Transaction ID Mismatch: Payload {$payload['transaction_id']}, Midtrans {$verifiedTransactionId}");
                return [
                    'success' => false,
                    'message' => 'Transaction ID tidak cocok dengan data resmi Midtrans.',
                    'code'    => 400,
                ];
            }
        }

        // 5. Validasi Eksplisit gross_amount
        if ($verifiedGrossAmount === null || $verifiedGrossAmount === '' || !is_numeric($verifiedGrossAmount) || (float) $verifiedGrossAmount <= 0) {
            Log::error("Midtrans API gross_amount invalid [Order {$orderId}]: " . var_export($verifiedGrossAmount, true));
            return [
                'success' => false,
                'message' => 'Nominal gross_amount dari Midtrans tidak valid atau kosong.',
                'code'    => 400,
            ];
        }

        $receivedGrossInt = (int) explode('.', (string) $verifiedGrossAmount)[0];

        // 6. Pencocokan Record Transaksi & Booking di Database
        $payment = null;
        if (Schema::hasColumn('payments', 'order_id')) {
            $payment = Payment::with('booking')->where('order_id', $orderId)->first();
        }

        $codeCandidate = explode('-TRX-', $orderId)[0];
        $booking = Booking::where('booking_code', $codeCandidate)->orWhere('booking_code', $orderId)->first();

        if (!$payment) {
            $paymentQuery = Payment::with('booking');
            if ($booking) {
                $paymentQuery->where('booking_id', $booking->id);
            }
            $payment = $paymentQuery->where(function ($q) use ($orderId, $verifiedTransactionId) {
                $q->where('transaction_ref', $orderId);
                if (!empty($verifiedTransactionId)) {
                    $q->orWhere('transaction_ref', $verifiedTransactionId);
                }
            })->latest()->first();
        }

        if (!$booking && $payment) {
            $booking = $payment->booking;
        }

        if (!$booking) {
            Log::error("Midtrans syncOrder: Booking tidak ditemukan untuk Order ID {$orderId}");
            return [
                'success' => false,
                'message' => 'Booking terkait tidak ditemukan.',
                'code'    => 404,
            ];
        }

        // 7. Pemeriksaan Idempotensi: Jika transaksi ini sudah berstatus SETTLED, abaikan duplikat
        if ($payment && $payment->payment_status === 'SETTLED' && in_array($transactionStatus, ['settlement', 'capture'])) {
            Log::info("Midtrans syncOrder: Transaksi {$orderId} sudah diselesaikan sebelumnya (Idempotent).");
            return [
                'success' => true,
                'status'  => 'already_settled',
                'message' => 'Transaksi sudah diselesaikan sebelumnya.',
                'booking' => $booking,
                'payment' => $payment,
                'code'    => 200,
            ];
        }

        // 8. Validasi Kesesuaian Nominal Tagihan dengan Data Database
        $expectedAmountInt = $payment
            ? (int) round($payment->gross_amount)
            : (int) round($booking->paid_amount);

        if ($receivedGrossInt !== $expectedAmountInt) {
            Log::error("Midtrans syncOrder Nominal Mismatch [Order {$orderId}]: Diterima dari API {$receivedGrossInt}, Diharapkan {$expectedAmountInt}");
            return [
                'success' => false,
                'message' => 'Ketidakcocokan nominal transaksi.',
                'code'    => 400,
            ];
        }

        // 9. Transaksi Database Atomik & Penguncian Row
        return DB::transaction(function () use ($booking, $payment, $orderId, $transactionStatus, $fraudStatus, $receivedGrossInt, $paymentType, $verifiedTransactionId) {
            $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->first();

            if (!$lockedBooking) {
                Log::error("Midtrans syncOrder: Booking ID {$booking->id} tidak ditemukan saat penguncian row untuk Order {$orderId}");
                return [
                    'success' => false,
                    'message' => 'Booking tidak ditemukan atau telah dihapus.',
                    'code'    => 404,
                ];
            }

            // A. Penanganan Pembayaran Berhasil (settlement / capture)
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                $isFraudAcceptable = false;

                if ($transactionStatus === 'capture') {
                    if (!empty($fraudStatus) && $fraudStatus === 'accept') {
                        $isFraudAcceptable = true;
                    } else {
                        Log::warning("Midtrans Capture Fraud Status Rejected [Order {$orderId}]: Fraud status '{$fraudStatus}'");
                        return [
                            'success' => false,
                            'message' => 'Transaksi capture ditolak karena fraud_status bukan accept atau kosong.',
                            'code'    => 400,
                        ];
                    }
                } elseif ($transactionStatus === 'settlement') {
                    if (empty($fraudStatus) || $fraudStatus === 'accept') {
                        $isFraudAcceptable = true;
                    } else {
                        Log::warning("Midtrans Settlement Fraud Status Rejected [Order {$orderId}]: Fraud status '{$fraudStatus}'");
                        return [
                            'success' => false,
                            'message' => 'Transaksi settlement ditolak karena fraud_status bernilai ' . $fraudStatus,
                            'code'    => 400,
                        ];
                    }
                }

                if ($isFraudAcceptable) {
                    $totalAmountInt = (int) round($lockedBooking->total_amount);

                    // Akumulasi pembayaran yang sudah sah sebelumnya (mencegah salah kalkulasi pelunasan)
                    $previousSettled = $lockedBooking->payments()
                        ->where('payment_status', 'SETTLED')
                        ->where('id', '!=', $payment ? $payment->id : 0)
                        ->get()
                        ->sum(function ($p) {
                            return (float) ($p->base_amount ?? $p->gross_amount);
                        });

                    $creditAmount = ($payment && $payment->base_amount !== null && (float)$payment->base_amount > 0)
                        ? (float) $payment->base_amount
                        : (float) $receivedGrossInt;

                    $totalSettledAfterThis = (int) round($previousSettled + $creditAmount);

                    // Tentukan apakah Lunas (100%) atau DP (30%)
                    if ($totalSettledAfterThis >= $totalAmountInt || $lockedBooking->payment_mode === 'FULL' || ($payment && $payment->payment_stage === 'FULL_PAYMENT')) {
                        $newBookingStatus   = 'FULL_PAID';
                        $newPaidAmount      = $totalAmountInt;
                        $newRemainingAmount = 0;
                        $paymentStage       = 'FULL_PAYMENT';
                    } else {
                        $newBookingStatus   = 'DP_PAID';
                        $newPaidAmount      = $totalSettledAfterThis;
                        $newRemainingAmount = max(0, $totalAmountInt - $totalSettledAfterThis);
                        $paymentStage       = 'DOWN_PAYMENT';
                    }

                    // Perbarui status booking
                    $lockedBooking->update([
                        'booking_status'   => $newBookingStatus,
                        'paid_amount'      => $newPaidAmount,
                        'remaining_amount' => $newRemainingAmount,
                        'lock_expires_at'  => null,
                    ]);

                    // Otomatis alokasikan Driver dan Armada Jeep saat pembayaran terkonfirmasi sah
                    \App\Services\BookingAssignmentService::autoAssign($lockedBooking);

                    // Perbarui atau catat payment record
                    if ($payment) {
                        $paymentUpdate = [
                            'payment_status' => 'SETTLED',
                            'paid_at'        => now(),
                        ];
                        if (!empty($verifiedTransactionId)) {
                            $paymentUpdate['transaction_ref'] = $verifiedTransactionId;
                        }
                        $payment->update($paymentUpdate);
                    } else {
                        $payment = Payment::create([
                            'booking_id'      => $lockedBooking->id,
                            'order_id'        => $orderId,
                            'transaction_ref' => $verifiedTransactionId ?? $orderId,
                            'payment_stage'   => $paymentStage,
                            'gross_amount'    => $receivedGrossInt,
                            'payment_type'    => strtoupper($paymentType),
                            'payment_status'  => 'SETTLED',
                            'paid_at'         => now(),
                        ]);
                    }

                    Log::info("Booking {$lockedBooking->booking_code} berhasil diverifikasi dengan status {$newBookingStatus}.");
                    return [
                        'success'        => true,
                        'status'         => 'settled',
                        'booking_status' => $newBookingStatus,
                        'booking'        => $lockedBooking,
                        'payment'        => $payment,
                        'code'           => 200,
                        'message'        => "Pembayaran berhasil diverifikasi: {$newBookingStatus}",
                    ];
                }
            }
            // B. Penanganan Kedaluwarsa, Pembatalan, atau Penolakan (expire, cancel, deny)
            elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $newPaymentStatus = ($transactionStatus === 'expire') ? 'EXPIRED' : 'FAILED';
                if ($payment) {
                    $payment->update(['payment_status' => $newPaymentStatus]);
                } else {
                    $payment = Payment::create([
                        'booking_id'      => $lockedBooking->id,
                        'order_id'        => $orderId,
                        'transaction_ref' => $verifiedTransactionId ?? $orderId,
                        'payment_stage'   => ($lockedBooking->payment_mode === 'DP') ? 'DOWN_PAYMENT' : 'FULL_PAYMENT',
                        'gross_amount'    => $receivedGrossInt,
                        'payment_type'    => strtoupper($paymentType),
                        'payment_status'  => $newPaymentStatus,
                        'paid_at'         => null,
                    ]);
                }

                // DILARANG membatalkan booking jika pesanan sudah memiliki status pembayaran sah
                if (in_array($lockedBooking->booking_status, ['FULL_PAID', 'DP_PAID', 'WAITING_CONFIRMATION'])) {
                    Log::info("Webhook {$transactionStatus} untuk Order {$orderId} dicatat pada payment, tetapi booking {$lockedBooking->booking_code} tetap {$lockedBooking->booking_status}.");
                    return [
                        'success' => true,
                        'status'  => $transactionStatus,
                        'booking' => $lockedBooking,
                        'code'    => 200,
                        'message' => 'Status transaksi gagal dicatat tanpa merusak booking sah.',
                    ];
                }

                $hasOtherPending = $lockedBooking->payments()
                    ->where('id', '!=', $payment ? $payment->id : 0)
                    ->where('payment_status', 'PENDING')
                    ->lockForUpdate()
                    ->exists();

                if (!$hasOtherPending) {
                    $lockedBooking->update(['booking_status' => 'CANCELLED']);
                    Log::info("Booking {$lockedBooking->booking_code} dibatalkan karena transaksi {$orderId} {$transactionStatus}.");
                }

                return [
                    'success' => true,
                    'status'  => $transactionStatus,
                    'booking' => $lockedBooking,
                    'code'    => 200,
                    'message' => "Transaksi {$transactionStatus} berhasil diproses.",
                ];
            }

            return [
                'success' => true,
                'status'  => 'pending',
                'booking' => $lockedBooking,
                'code'    => 200,
                'message' => 'Notifikasi pending diterima.',
            ];
        });
    }
}
