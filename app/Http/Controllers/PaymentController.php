<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\MidtransCoreApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Memulai transaksi pembayaran Core API ke Midtrans Sandbox
     */
    public function charge(Request $request, string $code, MidtransCoreApiService $paymentService): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:bca_va,bni_va,bri_va,mandiri_echannel,qris,gopay,shopeepay',
            'custom_amount' => 'nullable|integer|min:1',
        ]);

        $booking = Booking::where('booking_code', $code)->firstOrFail();

        // 1. Guard: Booking sudah berstatus terbayar atau sedang diverifikasi manual
        if (in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            return response()->json([
                'status' => 'already_paid',
                'message' => 'Pesanan ini sudah selesai dibayar.',
                'redirect_url' => route('booking.success', $booking->booking_code),
            ], 200);
        }

        if ($booking->booking_status === 'WAITING_CONFIRMATION') {
            return response()->json([
                'status' => 'waiting_confirmation',
                'message' => 'Pesanan ini memiliki bukti transfer manual yang sedang dalam antrean verifikasi admin.',
            ], 400);
        }

        // 2. Proteksi Concurrency & Double Request (Atomic Lock)
        $lock = Cache::lock("charge_lock_{$booking->id}", 10);

        if (!$lock->get()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi sedang diproses. Mohon tunggu beberapa detik sebelum mencoba kembali.'
            ], 429);
        }

        try {
            $paymentData = $paymentService->charge(
                $booking,
                $validated['payment_method'],
                isset($validated['custom_amount']) ? (int) $validated['custom_amount'] : null
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi pembayaran berhasil diinisialisasi.',
                'data' => $paymentData,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error("PaymentController Charge Exception [Booking {$code}]: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        } finally {
            $lock->release();
        }
    }

    /**
     * Endpoint polling status pembayaran terkini
     */
    public function status(string $code): JsonResponse
    {
        $booking = Booking::with('payments')->where('booking_code', $code)->firstOrFail();

        // Auto-expire jika booking PENDING telah melewati batas waktu lock
        if ($booking->booking_status === 'PENDING' && $booking->lock_expires_at && $booking->lock_expires_at->isPast()) {
            $booking->update(['booking_status' => 'CANCELLED']);
            $booking->payments()->where('payment_status', 'PENDING')->update(['payment_status' => 'EXPIRED']);
        }

        $latestPayment = $booking->payments()->latest()->first();

        return response()->json([
            'status' => 'success',
            'booking_code' => $booking->booking_code,
            'booking_status' => $booking->booking_status,
            'payment_mode' => $booking->payment_mode,
            'total_amount' => (int) round($booking->total_amount),
            'paid_amount' => (int) round($booking->paid_amount),
            'remaining_amount' => (int) round($booking->remaining_amount),
            'is_paid' => in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID']),
            'is_waiting_confirmation' => $booking->booking_status === 'WAITING_CONFIRMATION',
            'is_cancelled' => in_array($booking->booking_status, ['CANCELLED']),
            'redirect_url' => in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID']) 
                ? route('booking.success', $booking->booking_code) 
                : null,
            'latest_payment' => $latestPayment ? [
                'order_id' => $latestPayment->order_id ?? $latestPayment->transaction_ref,
                'payment_status' => $latestPayment->payment_status,
                'payment_stage' => $latestPayment->payment_stage,
                'gross_amount' => (int) round($latestPayment->gross_amount),
                'payment_type' => $latestPayment->payment_type,
                'paid_at' => $latestPayment->paid_at ? $latestPayment->paid_at->toDateTimeString() : null,
            ] : null,
        ]);
    }

    /**
     * Mengambil detail transaksi pembayaran pending yang sedang aktif
     */
    public function activePayment(string $code, MidtransCoreApiService $paymentService): JsonResponse
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();

        $activePayment = Payment::where('booking_id', $booking->id)
            ->where('payment_status', 'PENDING')
            ->latest()
            ->first();

        if (!$activePayment) {
            return response()->json([
                'status' => 'none',
                'data' => null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $paymentService->formatPaymentResponse($activePayment),
        ]);
    }
}
