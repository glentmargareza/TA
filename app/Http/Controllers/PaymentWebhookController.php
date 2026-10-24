<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Menangani callback notifikasi webhook dari Payment Gateway Midtrans.
     * Menggunakan verifikasi tanda tangan SHA-512 dan transaksi database atomik.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received: ', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $receivedSignature = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'midtrans';

        if (!$orderId || !$statusCode || !$grossAmount || !$receivedSignature) {
            return response()->json(['message' => 'Parameter webhook tidak lengkap.'], 400);
        }

        $serverKey = config('services.midtrans.server_key');

        // 1. Verifikasi Hash Signature SHA-512
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($calculatedSignature !== $receivedSignature) {
            Log::warning("Midtrans Webhook Signature Mismatch: Order ID {$orderId}");
            return response()->json(['message' => 'Tanda tangan otentikasi tidak valid (Unauthorized).'], 401);
        }

        // 2. Transaksi Database Atomik & Pembaruan Status
        DB::transaction(function () use ($orderId, $transactionStatus, $fraudStatus, $grossAmount, $paymentType, $payload) {
            $booking = Booking::where('booking_code', $orderId)->lockForUpdate()->first();

            if (!$booking) {
                Log::error("Booking dengan kode {$orderId} tidak ditemukan pada basis data.");
                return;
            }

            $numericGross = (float) $grossAmount;

            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept' || empty($fraudStatus)) {
                    $newStatus = ($booking->payment_mode === 'DP') ? 'DP_PAID' : 'FULL_PAID';
                    
                    $booking->update([
                        'status' => $newStatus,
                        'paid_amount' => $numericGross,
                        'remaining_amount' => max(0, $booking->total_amount - $numericGross),
                    ]);

                    Payment::create([
                        'booking_id' => $booking->id,
                        'transaction_ref' => $payload['transaction_id'] ?? $orderId,
                        'payment_stage' => ($booking->payment_mode === 'DP') ? 'DOWN_PAYMENT' : 'FULL_PAYMENT',
                        'gross_amount' => $numericGross,
                        'payment_type' => strtoupper($paymentType),
                        'payment_status' => 'SETTLED',
                        'paid_at' => now(),
                    ]);

                    Log::info("Booking {$orderId} berhasil diverifikasi status: {$newStatus}");
                }
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $booking->update(['status' => 'CANCELLED']);

                Payment::create([
                    'booking_id' => $booking->id,
                    'transaction_ref' => $payload['transaction_id'] ?? $orderId,
                    'payment_stage' => ($booking->payment_mode === 'DP') ? 'DOWN_PAYMENT' : 'FULL_PAYMENT',
                    'gross_amount' => $numericGross,
                    'payment_type' => strtoupper($paymentType),
                    'payment_status' => ($transactionStatus === 'expire') ? 'EXPIRED' : 'FAILED',
                    'paid_at' => null,
                ]);

                Log::info("Booking {$orderId} dibatalkan atau kedaluwarsa.");
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Notifikasi pembayaran berhasil diproses.']);
    }
}
