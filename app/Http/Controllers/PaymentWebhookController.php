<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PaymentWebhookController extends Controller
{
    /**
     * Menangani callback notifikasi webhook dari Payment Gateway Midtrans.
     * Menggunakan verifikasi tanda tangan SHA-512, pencocokan order_id akurat,
     * validasi nominal integer, idempotensi, dan transaksi atomik.
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

        // 2. Pencocokan Record Transaksi & Booking
        $payment = null;
        if (Schema::hasColumn('payments', 'order_id')) {
            $payment = Payment::with('booking')->where('order_id', $orderId)->first();
        }

        // Ekstrak kandidat kode booking dari order_id (format: WKR-XXXXXX atau WKR-XXXXXX-TRX-...)
        $codeCandidate = explode('-TRX-', $orderId)[0];
        $booking = Booking::where('booking_code', $codeCandidate)->orWhere('booking_code', $orderId)->first();

        if (!$payment) {
            $paymentQuery = Payment::with('booking');
            if ($booking) {
                $paymentQuery->where('booking_id', $booking->id);
            }
            $payment = $paymentQuery->where(function ($q) use ($orderId, $payload) {
                $q->where('transaction_ref', $orderId);
                if (!empty($payload['transaction_id'])) {
                    $q->orWhere('transaction_ref', $payload['transaction_id']);
                }
            })->latest()->first();
        }

        if (!$booking && $payment) {
            $booking = $payment->booking;
        }

        if (!$booking) {
            Log::error("Midtrans Webhook: Booking tidak ditemukan untuk Order ID {$orderId}");
            return response()->json(['message' => 'Booking terkait tidak ditemukan.'], 404);
        }

        // 3. Pemeriksaan Idempotensi: Jika transaksi ini sudah berstatus SETTLED, abaikan duplikat
        if ($payment && $payment->payment_status === 'SETTLED' && in_array($transactionStatus, ['settlement', 'capture'])) {
            Log::info("Midtrans Webhook: Transaksi {$orderId} sudah diselesaikan sebelumnya (Idempotent).");
            return response()->json(['status' => 'success', 'message' => 'Transaksi sudah diselesaikan sebelumnya.'], 200);
        }

        // 4. Validasi Nominal Deterministik (Integer Rupiah Murni)
        $receivedGrossInt = (int) explode('.', (string) $grossAmount)[0];
        $expectedAmountInt = $payment 
            ? (int) round($payment->gross_amount) 
            : (int) round($booking->paid_amount);

        if ($receivedGrossInt !== $expectedAmountInt) {
            Log::error("Midtrans Webhook Nominal Mismatch [Order {$orderId}]: Diterima {$receivedGrossInt}, Diharapkan {$expectedAmountInt}");
            return response()->json(['message' => 'Ketidakcocokan nominal transaksi.'], 400);
        }

        // 5. Transaksi Database Atomik & Penguncian Row
        return DB::transaction(function () use ($booking, $payment, $orderId, $transactionStatus, $fraudStatus, $receivedGrossInt, $paymentType, $payload) {
            // Kunci baris booking
            $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->first();

            // A. Penanganan Pembayaran Berhasil (settlement / capture)
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept' || empty($fraudStatus)) {
                    $totalAmountInt = (int) round($lockedBooking->total_amount);

                    // Tentukan apakah Lunas (100%) atau DP (30%)
                    if (($payment && $payment->payment_stage === 'FULL_PAYMENT') || $lockedBooking->payment_mode === 'FULL' || $receivedGrossInt >= $totalAmountInt) {
                        $newBookingStatus = 'FULL_PAID';
                        $newPaidAmount = $totalAmountInt;
                        $newRemainingAmount = 0;
                        $paymentStage = 'FULL_PAYMENT';
                    } else {
                        $newBookingStatus = 'DP_PAID';
                        $newPaidAmount = $receivedGrossInt;
                        $newRemainingAmount = max(0, $totalAmountInt - $receivedGrossInt);
                        $paymentStage = 'DOWN_PAYMENT';
                    }

                    // Perbarui status booking (menggunakan kolom booking_status)
                    $lockedBooking->update([
                        'booking_status' => $newBookingStatus,
                        'paid_amount' => $newPaidAmount,
                        'remaining_amount' => $newRemainingAmount,
                    ]);

                    // Perbarui atau catat payment record
                    if ($payment) {
                        $paymentUpdate = [
                            'payment_status' => 'SETTLED',
                            'paid_at' => now(),
                        ];
                        if (!empty($payload['transaction_id'])) {
                            $paymentUpdate['transaction_ref'] = $payload['transaction_id'];
                        }
                        $payment->update($paymentUpdate);
                    } else {
                        Payment::create([
                            'booking_id' => $lockedBooking->id,
                            'transaction_ref' => $payload['transaction_id'] ?? $orderId,
                            'payment_stage' => $paymentStage,
                            'gross_amount' => $receivedGrossInt,
                            'payment_type' => strtoupper($paymentType),
                            'payment_status' => 'SETTLED',
                            'paid_at' => now(),
                        ]);
                    }

                    Log::info("Booking {$lockedBooking->booking_code} berhasil diverifikasi dengan status {$newBookingStatus}.");
                    return response()->json(['status' => 'success', 'message' => "Pembayaran berhasil diverifikasi: {$newBookingStatus}"]);
                }
            } 
            // B. Penanganan Kedaluwarsa, Pembatalan, atau Penolakan (expire, cancel, deny)
            elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Perbarui status payment record terkait
                $newPaymentStatus = ($transactionStatus === 'expire') ? 'EXPIRED' : 'FAILED';
                if ($payment) {
                    $payment->update(['payment_status' => $newPaymentStatus]);
                } else {
                    Payment::create([
                        'booking_id' => $lockedBooking->id,
                        'transaction_ref' => $payload['transaction_id'] ?? $orderId,
                        'payment_stage' => ($lockedBooking->payment_mode === 'DP') ? 'DOWN_PAYMENT' : 'FULL_PAYMENT',
                        'gross_amount' => $receivedGrossInt,
                        'payment_type' => strtoupper($paymentType),
                        'payment_status' => $newPaymentStatus,
                        'paid_at' => null,
                    ]);
                }

                // DILARANG membatalkan booking jika pesanan sudah memiliki status pembayaran sah (FULL_PAID atau DP_PAID)!
                if (in_array($lockedBooking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
                    Log::info("Webhook {$transactionStatus} untuk Order {$orderId} dicatat pada payment, tetapi booking {$lockedBooking->booking_code} tetap {$lockedBooking->booking_status}.");
                    return response()->json(['status' => 'success', 'message' => 'Status transaksi gagal dicatat tanpa merusak booking sah.']);
                }

                // Periksa apakah masih ada transaksi PENDING lain yang belum kedaluwarsa
                $hasOtherPending = $lockedBooking->payments()
                    ->where('id', '!=', $payment ? $payment->id : 0)
                    ->where('payment_status', 'PENDING')
                    ->exists();

                if (!$hasOtherPending) {
                    $lockedBooking->update(['booking_status' => 'CANCELLED']);
                    Log::info("Booking {$lockedBooking->booking_code} dibatalkan karena transaksi {$orderId} {$transactionStatus}.");
                }

                return response()->json(['status' => 'success', 'message' => "Transaksi {$transactionStatus} berhasil diproses."]);
            }

            return response()->json(['status' => 'success', 'message' => 'Notifikasi pending diterima.']);
        });
    }
}
