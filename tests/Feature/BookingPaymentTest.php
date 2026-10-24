<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\Booking;

class BookingPaymentTest extends TestCase
{
    /**
     * Memverifikasi halaman publik dapat dimuat tanpa error (HTTP 200).
     */
    public function test_public_pages_load_successfully(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);

        $responseBooking = $this->get('/booking');
        $responseBooking->assertStatus(200);

        $responseCekTiket = $this->get('/cek-tiket');
        $responseCekTiket->assertStatus(200);

        $responseAdmin = $this->get('/admin');
        $responseAdmin->assertStatus(200);
    }

    /**
     * Memverifikasi pembuatan reservasi dengan validasi kapasitas keamanan (maks 4 orang per jeep).
     */
    public function test_booking_creation_and_auto_jeep_capacity(): void
    {
        $package = Package::first();
        $slot = ScheduleSlot::first();

        $postData = [
            'package_id' => $package->id,
            'slot_id' => $slot->id,
            'trip_date' => date('Y-m-d', strtotime('+2 days')),
            'passenger_count' => 7, // 7 orang membutuhkan minimal 2 jeep (ceil(7/4) = 2)
            'jeep_quantity' => 1,   // User input 1, controller wajib otomatis menyesuaikan ke 2
            'arrival_type' => 'self_drive',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567899',
            'customer_email' => 'budi@example.com',
            'payment_mode' => 'DP',
        ];

        $response = $this->post('/booking', $postData);
        $response->assertRedirect();

        // Verifikasi data tersimpan di MySQL
        $booking = Booking::where('customer_phone', '081234567899')->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2, $booking->jeep_quantity); // Otomatis disesuaikan ke 2
        $this->assertEquals('DP', $booking->payment_mode);
        $this->assertEquals('DP_PAID', $booking->booking_status);
        $this->assertStringStartsWith('WKR-', $booking->booking_code);
    }

    /**
     * Memverifikasi penolakan webhook dengan signature SHA-512 palsu (HTTP 401).
     */
    public function test_midtrans_webhook_rejects_invalid_signature(): void
    {
        $payload = [
            'order_id' => 'WKR-INVALID123',
            'status_code' => '200',
            'gross_amount' => '450000.00',
            'signature_key' => 'FAKE_HASH_SIGNATURE_KEY',
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson('/webhook/midtrans', $payload);
        $response->assertStatus(401);
    }

    /**
     * Memverifikasi penerimaan webhook dengan signature SHA-512 valid (HTTP 200).
     */
    public function test_midtrans_webhook_accepts_valid_signature_and_updates_status(): void
    {
        $booking = Booking::latest()->first();
        $this->assertNotNull($booking);

        $orderId = $booking->booking_code;
        $statusCode = '200';
        $grossAmount = number_format($booking->paid_amount, 2, '.', '');
        $serverKey = config('services.midtrans.server_key');

        // Hitung signature SHA-512 asli
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'transaction_id' => 'TRX-' . time(),
        ];

        $response = $this->postJson('/webhook/midtrans', $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        // Verifikasi pembayaran tercatat di payments
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'payment_status' => 'SETTLED',
            'payment_type' => 'QRIS',
        ]);
    }
}
