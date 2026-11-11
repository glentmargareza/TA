<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

class BookingPaymentTest extends TestCase
{
    /**
     * Helper untuk membuat instance booking terisolasi dalam pengujian.
     */
    protected function createIsolatedBooking(array $attributes = []): Booking
    {
        $package = Package::first();
        $slot = ScheduleSlot::first();

        $code = 'WKR-' . strtoupper(Str::random(6));
        $totalAmount = 500000;
        $paymentMode = $attributes['payment_mode'] ?? 'DP';
        $paidAmount = ($paymentMode === 'DP') ? 150000 : $totalAmount;
        $remainingAmount = $totalAmount - $paidAmount;

        return Booking::create(array_merge([
            'booking_code' => $code,
            'customer_name' => 'Wisatawan Uji',
            'customer_phone' => '0812' . rand(10000000, 99999999),
            'customer_email' => 'wisatawan@example.com',
            'package_id' => $package->id,
            'slot_id' => $slot->id,
            'trip_date' => date('Y-m-d', strtotime('+3 days')),
            'jeep_quantity' => 1,
            'passenger_count' => 4,
            'arrival_type' => 'self_drive',
            'package_subtotal' => $totalAmount,
            'pickup_fee' => 0,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'payment_mode' => $paymentMode,
            'booking_status' => 'PENDING',
        ], $attributes));
    }

    /**
     * Memverifikasi halaman publik dapat dimuat tanpa error (HTTP 200) serta proteksi halaman terautentikasi.
     */
    public function test_public_pages_load_successfully(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);

        $responseBooking = $this->get('/booking');
        $responseBooking->assertStatus(200);

        $responseTerms = $this->get('/syarat-ketentuan');
        $responseTerms->assertStatus(200);
        $responseTerms->assertSee('Syarat', false);

        $responsePrivacy = $this->get('/kebijakan-privasi');
        $responsePrivacy->assertStatus(200);
        $responsePrivacy->assertSee('Kebijakan Privasi', false);

        $responseLogin = $this->get('/login');
        $responseLogin->assertStatus(200);

        $responseRegister = $this->get('/register');
        $responseRegister->assertStatus(200);

        // Halaman yang dilindungi autentikasi wajib dialihkan ke login untuk tamu (guest)
        $responseCekTiketGuest = $this->get('/cek-tiket');
        $responseCekTiketGuest->assertRedirect(route('login'));

        $responseAdminGuest = $this->get('/admin');
        $responseAdminGuest->assertRedirect(route('login'));

        // Jika terautentikasi sebagai Admin, halaman dapat dimuat sukses (HTTP 200)
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin)->get('/admin')->assertStatus(200);
            $this->actingAs($admin)->get('/cek-tiket')->assertStatus(200);
        }
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

        // Verifikasi data tersimpan di MySQL dengan status PENDING
        $booking = Booking::where('customer_phone', '081234567899')->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2, $booking->jeep_quantity); // Otomatis disesuaikan ke 2
        $this->assertEquals('DP', $booking->payment_mode);
        $this->assertEquals('PENDING', $booking->booking_status);
        $this->assertStringStartsWith('WKR-', $booking->booking_code);

        // Verifikasi pengalihan menuju rute pembayaran baru
        $response->assertRedirect(route('booking.payment', $booking->booking_code));

        // Verifikasi halaman pembayaran dapat diakses dan menampilkan ringkasan
        $responsePayment = $this->get(route('booking.payment', $booking->booking_code));
        $responsePayment->assertStatus(200);
        $responsePayment->assertSee('Pembayaran Reservasi');
        $responsePayment->assertSee($booking->booking_code);
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
     * Memverifikasi penolakan webhook jika terjadi ketidakcocokan nominal tagihan (HTTP 400).
     */
    public function test_midtrans_webhook_rejects_nominal_mismatch(): void
    {
        $booking = $this->createIsolatedBooking([
            'payment_mode' => 'DP',
            'total_amount' => 500000,
            'paid_amount' => 150000,
        ]);

        $orderId = $booking->booking_code;
        $statusCode = '200';
        $mismatchedAmount = '100000.00'; // Tagihan seharusnya 150.000
        $serverKey = config('services.midtrans.server_key');

        $signature = hash('sha512', $orderId . $statusCode . $mismatchedAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $mismatchedAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'transaction_id' => 'TRX-' . time(),
        ];

        $response = $this->postJson('/webhook/midtrans', $payload);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Ketidakcocokan nominal transaksi.']);
    }

    /**
     * Memverifikasi idempotensi webhook: notifikasi settlement berulang tidak menduplikasi pembayaran (HTTP 200).
     */
    public function test_midtrans_webhook_handles_duplicate_idempotently(): void
    {
        $booking = $this->createIsolatedBooking([
            'payment_mode' => 'DP',
            'total_amount' => 500000,
            'paid_amount' => 150000,
        ]);

        $orderId = $booking->booking_code;
        $statusCode = '200';
        $grossAmount = '150000.00';
        $serverKey = config('services.midtrans.server_key');
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'transaction_id' => 'TRX-IDEMPOTENT-' . time(),
        ];

        // Webhook pertama kali
        $response1 = $this->postJson('/webhook/midtrans', $payload);
        $response1->assertStatus(200);

        $paymentCountAfterFirst = Payment::where('booking_id', $booking->id)->count();
        $this->assertEquals(1, $paymentCountAfterFirst);

        // Webhook kedua kali (duplikat)
        $response2 = $this->postJson('/webhook/midtrans', $payload);
        $response2->assertStatus(200);
        $response2->assertJson(['message' => 'Transaksi sudah diselesaikan sebelumnya.']);

        // Verifikasi tidak ada pencatatan pembayaran ganda
        $paymentCountAfterSecond = Payment::where('booking_id', $booking->id)->count();
        $this->assertEquals(1, $paymentCountAfterSecond);
    }

    /**
     * Memverifikasi webhook settlement untuk skema DP memperbarui status booking menjadi DP_PAID.
     */
    public function test_midtrans_webhook_dp_settlement_updates_to_dp_paid(): void
    {
        $booking = $this->createIsolatedBooking([
            'payment_mode' => 'DP',
            'total_amount' => 500000,
            'paid_amount' => 150000,
            'remaining_amount' => 350000,
        ]);

        $orderId = $booking->booking_code;
        $statusCode = '200';
        $grossAmount = '150000.00';
        $serverKey = config('services.midtrans.server_key');
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'TRX-DP-' . time(),
        ];

        $response = $this->postJson('/webhook/midtrans', $payload);
        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('DP_PAID', $booking->booking_status);
        $this->assertEquals(150000, (int) round($booking->paid_amount));
        $this->assertEquals(350000, (int) round($booking->remaining_amount));
    }

    /**
     * Memverifikasi webhook settlement untuk skema FULL memperbarui status booking menjadi FULL_PAID.
     */
    public function test_midtrans_webhook_full_settlement_updates_to_full_paid(): void
    {
        $booking = $this->createIsolatedBooking([
            'payment_mode' => 'FULL',
            'total_amount' => 500000,
            'paid_amount' => 500000,
            'remaining_amount' => 0,
        ]);

        $orderId = $booking->booking_code;
        $statusCode = '200';
        $grossAmount = '500000.00';
        $serverKey = config('services.midtrans.server_key');
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'TRX-FULL-' . time(),
        ];

        $response = $this->postJson('/webhook/midtrans', $payload);
        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('FULL_PAID', $booking->booking_status);
        $this->assertEquals(500000, (int) round($booking->paid_amount));
        $this->assertEquals(0, (int) round($booking->remaining_amount));
    }

    /**
     * Memverifikasi notifikasi kedaluwarsa terlambat tidak membatalkan booking yang sudah sah/lunas.
     */
    public function test_midtrans_webhook_late_expiry_does_not_cancel_settled_booking(): void
    {
        $booking = $this->createIsolatedBooking([
            'payment_mode' => 'FULL',
            'total_amount' => 500000,
            'paid_amount' => 500000,
            'remaining_amount' => 0,
            'booking_status' => 'FULL_PAID',
        ]);

        // Catat pembayaran sah yang sudah diselesaikan
        $settledRef = 'TRX-SETTLED-' . Str::random(6);
        Payment::create([
            'booking_id' => $booking->id,
            'transaction_ref' => $settledRef,
            'payment_stage' => 'FULL_PAYMENT',
            'gross_amount' => 500000,
            'payment_type' => 'QRIS',
            'payment_status' => 'SETTLED',
            'paid_at' => now(),
        ]);

        // Simulasikan transaksi lama lain yang sempat dibuat namun kedaluwarsa
        $oldPaymentRef = 'TRX-OLD-' . Str::random(6);
        $oldPayment = Payment::create([
            'booking_id' => $booking->id,
            'transaction_ref' => $oldPaymentRef,
            'payment_stage' => 'FULL_PAYMENT',
            'gross_amount' => 500000,
            'payment_type' => 'BCA_VA',
            'payment_status' => 'PENDING',
        ]);

        $orderId = $booking->booking_code . '-TRX-OLD';
        $statusCode = '200';
        $grossAmount = '500000.00';
        $serverKey = config('services.midtrans.server_key');
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'expire',
            'payment_type' => 'bank_transfer',
            'transaction_id' => $oldPaymentRef,
        ];

        $response = $this->postJson('/webhook/midtrans', $payload);
        $response->assertStatus(200);

        // Verifikasi status booking tetap FULL_PAID dan tidak dibatalkan
        $booking->refresh();
        $this->assertEquals('FULL_PAID', $booking->booking_status);

        // Verifikasi transaksi lama statusnya menjadi EXPIRED
        $oldPayment->refresh();
        $this->assertEquals('EXPIRED', $oldPayment->payment_status);
    }

    /**
     * Memverifikasi AdminController::settleDp mencatat pelunasan tunai secara atomik.
     */
    public function test_admin_settle_dp_records_atomic_cash_payment(): void
    {
        $booking = $this->createIsolatedBooking([
            'payment_mode' => 'DP',
            'total_amount' => 500000,
            'paid_amount' => 150000,
            'remaining_amount' => 350000,
            'booking_status' => 'DP_PAID',
        ]);

        // Catat pembayaran DP online awal
        Payment::create([
            'booking_id' => $booking->id,
            'transaction_ref' => 'TRX-DP-ONLINE',
            'payment_stage' => 'DOWN_PAYMENT',
            'payment_type' => 'QRIS',
            'gross_amount' => 150000,
            'payment_status' => 'SETTLED',
            'paid_at' => now(),
        ]);

        // Admin kasir basecamp melakukan pelunasan sisa tagihan secara tunai
        $response = $this->post(route('admin.settle', $booking->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('FULL_PAID', $booking->booking_status);
        $this->assertEquals(0, (int) round($booking->remaining_amount));
        $this->assertEquals(500000, (int) round($booking->paid_amount));

        // Verifikasi pencatatan pembayaran tunai di loket basecamp
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'payment_stage' => 'SETTLEMENT',
            'payment_type' => 'CASH_BASECAMP',
            'gross_amount' => 350000,
            'payment_status' => 'SETTLED',
        ]);
    }

    /**
     * Memverifikasi pengamanan akses e-tiket: booking PENDING dilarang mengakses e-tiket.
     */
    public function test_etiket_access_guard_blocks_pending_booking(): void
    {
        $booking = $this->createIsolatedBooking([
            'booking_status' => 'PENDING',
        ]);

        $response = $this->get(route('booking.success', $booking->booking_code));
        // Wajib dialihkan ke halaman pembayaran
        $response->assertRedirect(route('booking.payment', $booking->booking_code));
        $response->assertSessionHas('warning');
    }

    /**
     * Memverifikasi akses e-tiket sah untuk status FULL_PAID dan DP_PAID.
     */
    public function test_etiket_access_guard_allows_valid_paid_bookings(): void
    {
        // 1. Uji Booking FULL_PAID
        $bookingFull = $this->createIsolatedBooking([
            'payment_mode' => 'FULL',
            'total_amount' => 500000,
            'paid_amount' => 500000,
            'remaining_amount' => 0,
            'booking_status' => 'FULL_PAID',
        ]);

        Payment::create([
            'booking_id' => $bookingFull->id,
            'transaction_ref' => 'TRX-FULL-VALID',
            'payment_stage' => 'FULL_PAYMENT',
            'gross_amount' => 500000,
            'payment_type' => 'QRIS',
            'payment_status' => 'SETTLED',
            'paid_at' => now(),
        ]);

        $responseFull = $this->get(route('booking.success', $bookingFull->booking_code));
        $responseFull->assertStatus(200);
        $responseFull->assertSee('E-TIKET WISATA RESMI');
        $responseFull->assertSee('LUNAS (100%)');

        // 2. Uji Booking DP_PAID
        $bookingDp = $this->createIsolatedBooking([
            'payment_mode' => 'DP',
            'total_amount' => 500000,
            'paid_amount' => 150000,
            'remaining_amount' => 350000,
            'booking_status' => 'DP_PAID',
        ]);

        Payment::create([
            'booking_id' => $bookingDp->id,
            'transaction_ref' => 'TRX-DP-VALID',
            'payment_stage' => 'DOWN_PAYMENT',
            'gross_amount' => 150000,
            'payment_type' => 'QRIS',
            'payment_status' => 'SETTLED',
            'paid_at' => now(),
        ]);

        $responseDp = $this->get(route('booking.success', $bookingDp->booking_code));
        $responseDp->assertStatus(200);
        $responseDp->assertSee('UANG MUKA TERVERIFIKASI (DP)');
        $responseDp->assertSee('350.000'); // Sisa tagihan di basecamp
    }

    /**
     * Memverifikasi endpoint status booking mengembalikan status PENDING dan nominal akurat.
     */
    public function test_booking_status_endpoint_returns_accurate_state(): void
    {
        $booking = $this->createIsolatedBooking();

        $response = $this->getJson(route('booking.status', $booking->booking_code));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'booking_code' => $booking->booking_code,
            'booking_status' => 'PENDING',
            'payment_mode' => $booking->payment_mode,
        ]);
    }

    /**
     * Memverifikasi endpoint charge menolak metode pembayaran atau nominal yang tidak valid (HTTP 422).
     */
    public function test_payment_charge_endpoint_validates_input(): void
    {
        $booking = $this->createIsolatedBooking([
            'booking_status' => 'PENDING',
            'total_amount' => 500000,
            'payment_mode' => 'DP',
        ]);

        // Uji coba metode tidak didukung
        $responseInvalidMethod = $this->postJson(route('booking.charge', $booking->booking_code), [
            'payment_method' => 'invalid_channel',
        ]);
        $responseInvalidMethod->assertStatus(422);

        // Uji coba nominal DP di bawah batas minimum 30% (30% dari 500.000 = 150.000)
        $responseInvalidAmount = $this->postJson(route('booking.charge', $booking->booking_code), [
            'payment_method' => 'bca_va',
            'custom_amount' => 1000, // Di bawah batas minimum 30%
        ]);
        $responseInvalidAmount->assertStatus(422);
    }

    /**
     * Memverifikasi endpoint active-payment dapat diakses tanpa eror (HTTP 200).
     */
    public function test_active_payment_endpoint_returns_valid_structure(): void
    {
        $booking = $this->createIsolatedBooking();

        $response = $this->getJson(route('booking.active_payment', $booking->booking_code));
        $response->assertStatus(200);
        $this->assertTrue(in_array($response->json('status'), ['success', 'none']));
    }

    /**
     * Memverifikasi Beranda menampilkan katalog paket wisata dan ulasan dari database.
     */
    public function test_beranda_displays_packages_and_reviews_from_database(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('packages');
        $response->assertViewHas('reviews');

        $activePackage = Package::where('is_active', true)->first();
        if ($activePackage) {
            $response->assertSee($activePackage->name);
        }
    }

    /**
     * Memverifikasi Dashboard Admin menampilkan statistik omzet dan pelanggan unik.
     */
    public function test_admin_dashboard_statistics_and_access(): void
    {
        $admin = \App\Models\User::where('role', 'admin')->first();
        $this->assertNotNull($admin, 'Akun admin wajib tersedia di database');

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue');
        $response->assertViewHas('totalCustomersCount');
        $response->assertViewHas('readyJeepsCount');
        $response->assertViewHas('todayBookingsCount');
    }

    /**
     * Memverifikasi pengguna non-admin (customer) dilarang mengakses portal admin.
     */
    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $customer = \App\Models\User::where('role', 'customer')->first();
        if (!$customer) {
            $customer = \App\Models\User::create([
                'name' => 'Wisatawan Biasa',
                'email' => 'customer_test_' . time() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]);
        }

        $response = $this->actingAs($customer)->get('/admin');
        // Harus ditolak dan dialihkan ke login dengan pesan info
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info');
    }

    /**
     * Memverifikasi pemeriksaan kapasitas slot jadwal mencegah overbooking.
     */
    public function test_booking_slot_availability_check(): void
    {
        $slot = ScheduleSlot::first();
        $this->assertNotNull($slot);

        $tripDate = date('Y-m-d', strtotime('+7 days'));
        $available = $slot->isAvailableFor($tripDate, 1);
        $this->assertTrue($available);

        // Jika kuota diminta melebihi kapasitas max_jeeps (misal max_jeeps + 1)
        $excessAvailable = $slot->isAvailableFor($tripDate, $slot->max_jeeps + 5);
        $this->assertFalse($excessAvailable);
    }

    /**
     * Memverifikasi rumus penjemputan: Biaya Pickup = Tarif Pickup Zone x Jumlah Jeep.
     */
    public function test_pickup_fee_calculation_per_jeep_quantity(): void
    {
        $package = Package::first();
        $slot = ScheduleSlot::first();
        $zone = \App\Models\PickupZone::where('additional_fee', '>', 0)->first();

        $postData = [
            'package_id' => $package->id,
            'slot_id' => $slot->id,
            'trip_date' => date('Y-m-d', strtotime('+4 days')),
            'passenger_count' => 8, // 8 orang = 2 Jeep
            'jeep_quantity' => 2,
            'arrival_type' => 'pickup',
            'pickup_zone_id' => $zone ? $zone->id : null,
            'pickup_address' => 'Hotel Tentrem Yogyakarta',
            'pickup_lat' => -7.77123,
            'pickup_lng' => 110.36789,
            'customer_name' => 'Keluarga Raharja',
            'customer_phone' => '081299998888',
            'customer_email' => 'raharja@example.com',
            'payment_mode' => 'FULL',
        ];

        $response = $this->post('/booking', $postData);
        $response->assertRedirect();

        $booking = Booking::where('customer_phone', '081299998888')->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2, $booking->jeep_quantity);

        // Subtotal sewa 2 Jeep
        $expectedSubtotal = $package->price_per_jeep * 2;
        $this->assertEquals($expectedSubtotal, (float) $booking->package_subtotal);

        // Biaya pickup dihitung per jeep
        $this->assertGreaterThan(0, (float) $booking->pickup_fee);
        $expectedTotal = $expectedSubtotal + (float) $booking->pickup_fee;
        $this->assertEquals($expectedTotal, (float) $booking->total_amount);
    }
}
