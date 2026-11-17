# Dokumentasi Fitur Pembayaran (Payment) – Jeep Wukirsari Adventure Tour

Dokumentasi ini menjelaskan arsitektur alur pembayaran, pengelolaan transaksi multi-kanal, daur hidup status pembayaran, dan riwayat transaksi pada sistem Jeep Wukirsari.

---

## 1. Tujuan Fitur

Fitur Pembayaran (*Payment*) dirancang untuk menyediakan gerbang transaksi yang aman, fleksibel, dan transparan bagi wisatawan untuk menyelesaikan tagihan reservasi mereka. Fitur ini:
1. Mengambil data pesanan yang valid beserta rincian nominal tagihan (subtotal paket, biaya antar-jemput, dan sisa pelunasan).
2. Menyediakan 3 metode pembayaran resmi:
   - **Payment Gateway Digital (Midtrans)**: Virtual Account Bank (BCA, BNI, BRI, Mandiri), QRIS interaktif, dan E-Wallet (GoPay, ShopeePay).
   - **Transfer Manual Bank BPD DIY**: Transfer rekening giro paguyuban disertai unggah bukti struk transfer.
   - **Bayar Tunai di Basecamp (Cash)**: Pelunasan langsung di loket kasir pangkalan sebelum keberangkatan armada.
3. Mengelola daur hidup status pembayaran secara atomik (`PENDING`, `SETTLED`, `EXPIRED`, `FAILED`).
4. Menyimpan histori transaksi lengkap dalam tabel `payments` dengan relasi langsung ke tabel `bookings`.

---

## 2. Penjelasan Alur Pembayaran

```
+------------------------------------------------------------------------------------+
|                             ALUR TRANSAKSI PEMBAYARAN                              |
+------------------------------------------------------------------------------------+
| Wisatawan membuka: /booking/{booking_code}/payment                                 |
|                                     │                                              |
|                               (Pilih Metode)                                       |
|                                     │                                              |
|         ┌───────────────────────────┼───────────────────────────┐                  |
|         ▼                           ▼                           ▼                  |
|   A. MIDTRANS                 B. TRANSFER MANUAL          C. CASH BASECAMP         |
|   - Pilih VA / QRIS           - Transfer ke Rekening      - Konfirmasi bayar       |
|   - Hit API /charge             BPD DIY Paguyuban           di loket fisik         |
|   - Tampil Nomor VA / QR      - Unggah foto struk bukti   - Status: PENDING        |
|   - Status: PENDING           - Status:                   - Kasir klik:            |
|   - Webhook notifikasi          WAITING_CONFIRMATION        "Terima Pelunasan"     |
|         │                           │                           │                  |
|         └───────────────────────────┼───────────────────────────┘                  |
|                                     ▼                                              |
|                          STATUS: SETTLED / LUNAS                                   |
|                                     │                                              |
|                                     ▼                                              |
|                      E-TIKET RESMI DITERBITKAN (/success)                          |
+------------------------------------------------------------------------------------+
```

---

## 3. Skema dan Daur Hidup Status Pembayaran

### A. Skema Pembayaran
- **FULL (Lunas 100%)**: Seluruh total biaya dibayarkan di awal. Status booking langsung menjadi `FULL_PAID` dengan sisa tagihan `0.00`.
- **DP (Uang Muka 30%)**: Wisatawan membayar uang muka sebesar 30% dari total tagihan. Status booking menjadi `DP_PAID`, dan sisa 70% dilunasi saat tiba di loket pangkalan sebelum naik mobil jeep.

### B. Pemetaan Status Transaksi
| Status Pembayaran (`payments`) | Status Reservasi (`bookings`) | Keterangan |
| :--- | :--- | :--- |
| `PENDING` | `PENDING` | Menunggu pembayaran diselesaikan wisatawan dalam batas 30 menit. |
| `SETTLED` (DP 30%) | `DP_PAID` | Uang muka 30% telah diverifikasi sah. Slot jeep terkunci permanen. |
| `SETTLED` (Full 100%) | `FULL_PAID` | Pembayaran lunas 100%. Tiket siap digunakan. |
| `SETTLED` (Sisa Pelunasan) | `FULL_PAID` | Pelunasan tagihan 70% di kasir loket diselesaikan. |
| - | `WAITING_CONFIRMATION` | Bukti struk transfer manual telah diunggah dan menunggu verifikasi admin. |
| `EXPIRED` / `FAILED` | `CANCELLED` | Pembayaran kedaluwarsa atau dibatalkan. Kuota slot dibebaskan. |

---

## 4. File yang Digunakan

| Komponen | Lokasi File | Fungsi |
| :--- | :--- | :--- |
| **Controller** | `app/Http/Controllers/PaymentController.php` | Menangani inisialisasi charge Midtrans, status polling, dan proteksi concurrency. |
| **Controller** | `app/Http/Controllers/BookingController.php` | Menangani render halaman pembayaran, upload bukti transfer BPD, dan konfirmasi cash. |
| **Model** | `app/Models/Payment.php` | Model transaksi keuangan (`order_id`, `gross_amount`, `payment_status`, `payment_type`). |
| **Model** | `app/Models/Booking.php` | Model entitas reservasi dengan kolom finansial (`total_amount`, `paid_amount`, `remaining_amount`). |
| **View** | `resources/views/booking/payment.blade.php` | Halaman antarmuka pembayaran multi-metode dengan polling status otomatis. |

---

## 5. Route yang Digunakan

```php
// Halaman utama transaksi pembayaran
Route::get('/booking/{code}/payment', [BookingController::class, 'payment'])->name('booking.payment');

// Konfirmasi unggah bukti transfer bank manual (BPD DIY)
Route::post('/booking/{code}/confirm-transfer', [BookingController::class, 'confirmTransfer'])->name('booking.confirm_transfer');

// Konfirmasi pembayaran tunai di loket basecamp
Route::post('/booking/{code}/confirm-cash', [BookingController::class, 'confirmCash'])->name('booking.confirm_cash');

// Inisialisasi charge transaksi digital Midtrans Core API
Route::post('/booking/{code}/charge', [PaymentController::class, 'charge'])->name('booking.charge');

// Endpoint polling status pembayaran terkini
Route::get('/booking/{code}/status', [PaymentController::class, 'status'])->name('booking.status');

// Endpoint detail pembayaran aktif (VA number / QRIS payload)
Route::get('/booking/{code}/active-payment', [PaymentController::class, 'activePayment'])->name('booking.active_payment');
```

---

## 6. Query Database yang Diterapkan

### A. Mengambil Data Pemesanan yang Akan Dibayar Beserta Histori Transaksi
```php
$booking = Booking::with(['package', 'slot', 'pickupZone', 'payments' => function ($q) {
        $q->orderBy('id', 'desc');
    }])
    ->where('booking_code', $code)
    ->firstOrFail();
```
- **Tujuan**: Mengambil seluruh detail reservasi secara lengkap dengan memuat riwayat pembayaran terurut dari transaksi paling akhir.

### B. Membuat Transaksi Pembayaran Baru
```php
$payment = Payment::create([
    'booking_id' => $booking->id,
    'order_id' => $orderId,
    'transaction_ref' => $transactionId,
    'payment_stage' => $booking->payment_mode === 'DP' ? 'DOWN_PAYMENT' : 'FULL_PAYMENT',
    'payment_type' => 'bank_transfer',
    'payment_method' => 'bca_va',
    'gross_amount' => $booking->paid_amount,
    'payment_status' => 'PENDING',
    'va_numbers' => $vaNumbersArray,
]);
```
- **Tujuan**: Menyimpan referensi transaksi pembayaran, nomor rekening Virtual Account, atau payload QRIS ke database.

### C. Mengambil Pembayaran Terkini yang Sedang Berjalan
```php
$latestPayment = $booking->payments()
    ->where('payment_status', 'PENDING')
    ->latest()
    ->first();
```
- **Tujuan**: Mengambil status instruksi pembayaran aktif agar wisatawan dapat melanjutkan proses pembayaran tanpa membuat order baru.

### D. Memperbarui Status Transaksi Menjadi Berhasil (Settled)
```php
$payment->update([
    'payment_status' => 'SETTLED',
    'paid_at' => now(),
]);
```
- **Tujuan**: Menandai transaksi keuangan telah sah dibayar dan mencatat timestamp waktu pembayaran.

### E. Pelunasan Kasir Basecamp (Transaksi Tunai)
```php
DB::transaction(function () use ($booking) {
    $remaining = $booking->remaining_amount;

    Payment::create([
        'booking_id' => $booking->id,
        'order_id' => 'CASH-' . $booking->booking_code . '-' . time(),
        'transaction_ref' => 'CASH-BASECAMP',
        'payment_stage' => 'SETTLEMENT',
        'gross_amount' => $remaining,
        'payment_type' => 'CASH',
        'payment_status' => 'SETTLED',
        'paid_at' => now(),
    ]);

    $booking->update([
        'booking_status' => 'FULL_PAID',
        'paid_amount' => $booking->paid_amount + $remaining,
        'remaining_amount' => 0.00,
    ]);
});
```
- **Tujuan**: Memastikan pencatatan kasir loket tersimpan atomik pada tabel `payments` dan mengupdate status `booking_status` menjadi `FULL_PAID`.

---

## 7. Relasi Tabel yang Digunakan

```
+--------------------+        1 : N        +--------------------+
|      bookings      | ------------------> |      payments      |
|  (id, total_amount,|                     |  (id, booking_id,  |
|   paid_amount,     |                     |   order_id,        |
|   remaining_amount,|                     |   gross_amount,    |
|   booking_status)  |                     |   payment_status)  |
+--------------------+                     +--------------------+
```
- `bookings.id` memiliki relasi *one-to-many* ke `payments.booking_id`.
- Satu pemesanan dapat memiliki lebih dari satu baris pembayaran (misal pembayaran DP 30% dan pembayaran pelunasan sisa 70%).

---

## 8. Validasi dan Keamanan

1. **Proteksi Concurrency & Double Charge**:
   Menggunakan *atomic cache lock* 10 detik pada controller sebelum menghubungi payment gateway:
   ```php
   $lock = Cache::lock("charge_lock_{$booking->id}", 10);
   if (!$lock->get()) {
       return response()->json(['message' => 'Transaksi sedang diproses. Mohon tunggu.'], 429);
   }
   ```
2. **Integritas Nominal Pembayaran**:
   Nominal yang dikirim ke gateway dihitung langsung dari kolom database (`$booking->paid_amount`), bukan diambil mentah dari request client, sehingga mencegah manipulasi angka harga di browser.
3. **Penyimpanan Bukti Transfer yang Aman**:
   Foto struk transfer manual disimpan di direktori privat/storage Laravel dengan nama file terenkripsi unik (`Str::random(40)`).

---

## 9. Cara Pengujian Fitur

1. **Pengujian Halaman Pembayaran**:
   - Lakukan booking dan buka halaman pembayaran.
   - Pilih metode BCA Virtual Account atau QRIS.
   - Nomor VA atau QRIS Code akan langsung muncul tanpa me-refresh halaman.
2. **Pengujian Kueri via Artisan Tinker**:
   ```bash
   php artisan tinker --execute="echo 'Total Payments: ' . App\Models\Payment::count() . ' | Settled: ' . App\Models\Payment::where('payment_status', 'SETTLED')->count();"
   ```
   *Hasil yang diharapkan*: Menampilkan total record pembayaran di tabel `payments`.

