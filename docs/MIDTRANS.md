# Dokumentasi Integrasi Midtrans – Jeep Wukirsari Adventure Tour

Dokumentasi ini menjelaskan konfigurasi, arsitektur teknis, protokol keamanan tanda tangan SHA-512, penanganan webhook, serta pengujian Sandbox Payment Gateway Midtrans.

---

## 1. Tujuan Integrasi

Integrasi Midtrans Payment Gateway bertujuan untuk:
1. Menyediakan pembayaran otomatis tanpa memerlukan konfirmasi manual dari admin loket.
2. Mendukung berbagai kanal pembayaran digital populer di Indonesia:
   - **Bank Transfer (Virtual Account)**: BCA, BNI, BRI, Mandiri (E-Channel / Bill).
   - **QRIS Interaktif**: GoPay, ShopeePay, BCA Mobile, Livin' Mandiri, OVO, Dana.
   - **E-Wallet**: GoPay Direct Deeplink & QRIS.
3. Menjamin penerimaan dana secara *real-time* melalui notifikasi Webhook (*HTTP POST Callback*).
4. Mencegah pemalsuan data pembayaran (*man-in-the-middle attack*) menggunakan verifikasi kriptografi SHA-512.

---

## 2. Konfigurasi Environment (`.env`)

Konfigurasi kredensial Midtrans disimpan pada berkas `.env` dan diakses melalui `config/services.php`:

```dotenv
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxx
MIDTRANS_MERCHANT_ID=Gxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

Konfigurasi pada `config/services.php`:
```php
'midtrans' => [
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id'   => env('MIDTRANS_MERCHANT_ID'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'  => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'        => env('MIDTRANS_IS_3DS', true),
],
```

---

## 3. Alur Transaksi Midtrans

```
+---------------------------------------------------------------------------------------+
|                            ALUR TRANSAKSI MIDTRANS CORE API                           |
+---------------------------------------------------------------------------------------+
| 1. Client Browser meminta pembayaran:                                                 |
|    POST /booking/{code}/charge  { payment_method: 'bca_va' }                          |
|                                       │                                               |
|                                       ▼                                               |
| 2. Server (PaymentController / MidtransCoreApiService):                               |
|    - Cek status booking dan kunci atomic cache lock                                   |
|    - Ambil total nominal booking (paid_amount)                                        |
|    - Kirim payload charge ke Midtrans Sandbox API:                                    |
|      POST https://api.sandbox.midtrans.com/v2/charge                                  |
|                                       │                                               |
|                                       ▼                                               |
| 3. Midtrans mengembalikan response VA Number / QRIS string                            |
|    - Server menyimpan ke tabel 'payments' dengan status 'PENDING'                     |
|    - Response JSON dikembalikan ke browser untuk ditampilkan ke wisatawan             |
|                                       │                                               |
|                             (Wisatawan Membayar)                                      |
|                                       │                                               |
|                                       ▼                                               |
| 4. Midtrans mengirim HTTP POST Notifikasi ke Webhook Server kita:                     |
|    POST /webhook/midtrans                                                             |
|    - Verifikasi Signature SHA-512                                                     |
|    - Validasi Nominal Integer                                                         |
|    - Cek Idempotensi                                                                  |
|    - Transaksi DB Atomik (lockForUpdate):                                             |
|        * Payment -> SETTLED                                                           |
|        * Booking -> FULL_PAID atau DP_PAID                                            |
|        * Bebaskan lock_expires_at                                                     |
|                                       │                                               |
|                                       ▼                                               |
| 5. Frontend Polling (/booking/{code}/status) mendeteksi pembayaran sukses,             |
|    otomatis mengarahkan ke halaman e-tiket /booking/{code}/success                    |
+---------------------------------------------------------------------------------------+
```

---

## 4. Verifikasi Tanda Tangan Digital (Signature Key SHA-512)

Untuk memastikan bahwa request notifikasi benar-benar berasal dari Midtrans dan bukan pihak ketiga berbahaya, server melakukan verifikasi tanda tangan kriptografis:

$$\text{Signature} = \text{hash}\Big(\text{"sha512"}, \text{order\_id} + \text{status\_code} + \text{gross\_amount} + \text{server\_key}\Big)$$

### Kode Implementasi:
```php
// app/Http/Controllers/PaymentWebhookController.php
$orderId           = $payload['order_id'] ?? null;
$statusCode        = $payload['status_code'] ?? null;
$grossAmount       = $payload['gross_amount'] ?? null;
$receivedSignature = $payload['signature_key'] ?? null;
$serverKey         = config('services.midtrans.server_key');

$calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

if ($calculatedSignature !== $receivedSignature) {
    Log::warning("Midtrans Webhook Signature Mismatch: Order ID {$orderId}");
    return response()->json(['message' => 'Tanda tangan otentikasi tidak valid (Unauthorized).'], 401);
}
```

---

## 5. Mekanisme Idempotensi (Pencegahan Double Processing)

Payment Gateway dapat mengirimkan notifikasi webhook berulang kali (*retry mechanism*) jika jaringan mengalami latensi. Sistem Jeep Wukirsari mengimplementasikan **Idempotensi**:

```php
// app/Http/Controllers/PaymentWebhookController.php
if ($payment && $payment->payment_status === 'SETTLED' && in_array($transactionStatus, ['settlement', 'capture'])) {
    Log::info("Midtrans Webhook: Transaksi {$orderId} sudah diselesaikan sebelumnya (Idempotent).");
    return response()->json(['status' => 'success', 'message' => 'Transaksi sudah diselesaikan sebelumnya.'], 200);
}
```
Jika webhook kedua atau ketiga diterima untuk order yang sudah berstatus `SETTLED`, server langsung membalas HTTP 200 OK tanpa melakukan pembaruan saldo atau status berulang.

---

## 6. Transaksi Database Atomik & Penguncian Baris (`lockForUpdate`)

Untuk menjamin konsistensi ACID, seluruh pembaruan status dibungkus dalam blok `DB::transaction()` dengan penguncian baris (*pessimistic row locking*):

```php
return DB::transaction(function () use ($booking, $payment, $orderId, $transactionStatus, $fraudStatus, $receivedGrossInt, $paymentType, $payload) {
    // Kunci baris booking agar tidak dapat diubah proses konkuren lain
    $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->first();

    if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
        if ($fraudStatus === 'accept' || empty($fraudStatus)) {
            $totalAmountInt = (int) round($lockedBooking->total_amount);

            // Penentuan Lunas vs DP
            if (($payment && $payment->payment_stage === 'FULL_PAYMENT') || $lockedBooking->payment_mode === 'FULL' || $receivedGrossInt >= $totalAmountInt) {
                $newBookingStatus = 'FULL_PAID';
                $newPaidAmount = $totalAmountInt;
                $newRemainingAmount = 0;
            } else {
                $newBookingStatus = 'DP_PAID';
                $newPaidAmount = $receivedGrossInt;
                $newRemainingAmount = max(0, $totalAmountInt - $receivedGrossInt);
            }

            // Perbarui status booking dan lepaskan kunci hold 30 menit
            $lockedBooking->update([
                'booking_status'   => $newBookingStatus,
                'paid_amount'      => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'lock_expires_at'  => null,
            ]);

            // Perbarui record pembayaran
            if ($payment) {
                $payment->update([
                    'payment_status'  => 'SETTLED',
                    'transaction_ref' => $payload['transaction_id'] ?? $payment->transaction_ref,
                    'paid_at'         => now(),
                ]);
            }
        }
    }
});
```

---

## 7. Status Transisi Midtrans ke Sistem

| Event Midtrans (`transaction_status`) | Nilai `fraud_status` | Status `payments` | Status `bookings` | Tindakan Sistem |
| :--- | :--- | :--- | :--- | :--- |
| `settlement` | `accept` / null | `SETTLED` | `FULL_PAID` / `DP_PAID` | Dana masuk sah. Terbitkan e-tiket. |
| `capture` | `accept` | `SETTLED` | `FULL_PAID` / `DP_PAID` | Pembayaran kartu kredit disetujui. |
| `pending` | - | `PENDING` | `PENDING` | Menunggu transfer dari pelanggan. |
| `expire` | - | `EXPIRED` | `CANCELLED` | Waktu bayar habis. Kuota slot dibebaskan. |
| `cancel` | - | `FAILED` | `CANCELLED` | Transaksi dibatalkan oleh user/admin. |
| `deny` | - | `FAILED` | `CANCELLED` | Transaksi ditolak oleh bank penerbit. |

---

## 8. Panduan Pengujian di Midtrans Simulator Sandbox

Untuk menguji transaksi tanpa uang sungguhan:
1. Jalankan proses booking hingga masuk ke halaman pembayaran `/booking/{code}/payment`.
2. Pilih metode **BCA Virtual Account** atau **BNI Virtual Account**.
3. Salin Nomor Virtual Account yang muncul di layar.
4. Buka **Midtrans Payment Simulator**:
   - URL: `https://simulator.sandbox.midtrans.com/`
   - Pilih menu: **Virtual Account** -> Pilih bank terkait (misal BCA).
   - Tempel nomor VA dan klik **Inquire & Pay**.
5. Midtrans Simulator akan mengirimkan webhook callback ke aplikasi Anda.
6. Halaman pembayaran di browser wisatawan akan otomatis mendeteksi status settlement dan berpindah ke e-tiket resmi.

---

## 9. Verifikasi Kueri Database

```bash
php artisan tinker --execute="echo App\Models\Payment::with('booking')->where('payment_status', 'SETTLED')->latest()->first()->toJson(JSON_PRETTY_PRINT);"
```
*Hasil yang diharapkan*: Mengembalikan record pembayaran sukses beserta rincian order ID dan relasi pemesanan terkait.

