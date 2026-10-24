# Security Policy & Implementation Guidelines (SECURITY.md)
## Proyek: Platform Pemesanan Jeep Wisata Wukirsari (Lava Tour Merapi)
### Framework: Laravel 12 Full-Stack MVC & MySQL

> **Protokol Keamanan Sistem untuk AI Agent & Pengembang**: Dokumen ini memuat standar keamanan transaksi finansial, perlindungan data pribadi wisatawan (*UU Pelindungan Data Pribadi*), pencegahan eksploitasi konkurensi (double-booking), CSRF protection, serta validasi webhook pembayaran berbasis arsitektur **Laravel 12**.

---

## 1. Verifikasi Keamanan Payment Gateway (Midtrans Webhook Integrity)

Serangan paling umum pada sistem reservasi adalah manipulasi webhook pembayaran palsu (*forged webhook*) yang mencoba menandai pesanan lunas tanpa adanya pembayaran nyata.

### Aturan Wajib Webhook:
1. **Verifikasi Hash Signature SHA-512**:
   Setiap request webhook dari Midtrans wajib diverifikasi signature key-nya di Laravel Controller sebelum memproses mutasi basis data:
   $$\text{Signature} = \text{SHA512}(\text{order\_id} + \text{status\_code} + \text{gross\_amount} + \text{ServerKey})$$
   Jika signature dari payload tidak cocok persis dengan hasil kalkulasi di server, request **WAJIB DITOLAK** dengan status `HTTP 401 Unauthorized`.

2. **Implementasi Controller PHP Laravel**:
   ```php
   namespace App\Http\Controllers;

   use Illuminate\Http\Request;
   use App\Models\Booking;
   use App\Models\Payment;
   use Illuminate\Support\Facades\DB;

   class PaymentWebhookController extends Controller
   {
       public function handleMidtrans(Request $request)
       {
           $orderId = $request->input('order_id');
           $statusCode = $request->input('status_code');
           $grossAmount = $request->input('gross_amount');
           $receivedSignature = $request->input('signature_key');
           $serverKey = config('services.midtrans.server_key');

           // Hitung hash signature SHA-512
           $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

           if ($calculatedSignature !== $receivedSignature) {
               return response()->json(['message' => 'Invalid signature key'], 401);
           }

           $transactionStatus = $request->input('transaction_status');
           $fraudStatus = $request->input('fraud_status');

           DB::transaction(function () use ($orderId, $transactionStatus, $fraudStatus, $grossAmount) {
               $booking = Booking::where('booking_code', $orderId)->lockForUpdate()->first();
               if (!$booking) {
                   return;
               }

               if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                   if ($fraudStatus == 'accept' || empty($fraudStatus)) {
                       $newStatus = ($booking->payment_mode === 'DP') ? 'DP_PAID' : 'FULL_PAID';
                       $booking->update([
                           'status' => $newStatus,
                           'paid_amount' => $grossAmount,
                           'remaining_amount' => max(0, $booking->total_amount - $grossAmount),
                       ]);
                   }
               } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                   $booking->update(['status' => 'CANCELLED']);
               }
           });

           return response()->json(['status' => 'success']);
       }
   }
   ```

3. **Pengecualian CSRF untuk Webhook**:
   Pada Laravel 12, daftarkan URL webhook Midtrans pada `bootstrap/app.php` agar tidak diblokir oleh middleware CSRF:
   ```php
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->validateCsrfTokens(except: [
           'webhook/midtrans',
           'api/payment-callback',
       ]);
   })
   ```

---

## 2. Pencegahan Overbooking & Penanganan Konkurensi (Race Conditions)

Di musim liburan (libur lebaran, tahun baru), banyak wisatawan dapat memesan slot jam yang sama secara bersamaan.

### Mekanisme Pencegahan:
1. **Pengecekan Kuota dengan Transaksi Atomik MySQL**:
   Perhitungan ketersediaan armada dilakukan di dalam blok `DB::transaction` menggunakan pesimistic locking `lockForUpdate()`:
   ```php
   DB::transaction(function () use ($validated, $slotId, $jeepQuantity) {
       $slot = ScheduleSlot::where('id', $slotId)->lockForUpdate()->firstOrFail();
       
       $bookedJeeps = Booking::where('slot_id', $slotId)
           ->where('trip_date', $validated['trip_date'])
           ->whereIn('status', ['PENDING', 'DP_PAID', 'FULL_PAID'])
           ->sum('jeep_quantity');

       if (($bookedJeeps + $jeepQuantity) > $slot->max_jeeps_capacity) {
           throw new \Exception('Maaf, kuota armada pada jam ini sudah penuh.');
       }

       // Lanjutkan pembuatan data booking
   });
   ```
2. **Batas Waktu Pembayaran (Lock TTL 15 Menit)**:
   Saat pesanan berstatus `PENDING` dibuat, kolom `lock_expires_at` diisi `now()->addMinutes(15)`.
3. **Pelepasan Slot Otomatis (Auto-Release Task)**:
   Perintah Artisan terjadwal (`php artisan bookings:cleanup-expired`) memeriksa dan mengubah pesanan `PENDING` yang melewati batas waktu menjadi `CANCELLED`.

---

## 3. Pencegahan Injeksi & Validasi Input (Input Sanitization)

1. **Form Request Validation Ketat**:
   Seluruh input dari pengguna divalidasi ketat pada Controller Laravel:
   - Jumlah penumpang & jeep divalidasi sesuai aturan kapasitas 1 jeep maks 4 orang:
     `$minJeeps = (int) ceil($pax / 4);`
   - Nomor WhatsApp wajib berformat nomor telepon Indonesia valid (`regex:/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/`).
   - Tanggal kunjungan minimal adalah hari ini (`date|after_or_equal:today`).
2. **Eloquent Parameterized Queries**:
   Eloquent ORM Laravel secara otomatis menggunakan *PDO prepared statements* berparameter, sehingga mengeliminasi risiko celah **SQL Injection**.
3. **Pencegahan Cross-Site Scripting (XSS)**:
   Seluruh teks yang dicetak di Blade View wajib menggunakan kurung kurawal ganda `{{ $var }}` yang otomatis melakukan sanitasi `htmlspecialchars`. Dilarang menggunakan unescaped syntax `{!! $var !!}` pada data input pengguna.
4. **Proteksi Cross-Site Request Forgery (CSRF)**:
   Setiap formulir HTML berjenis POST, PUT, DELETE diwajibkan menyertakan token `@csrf`.

---

## 4. Perlindungan Data Wisatawan (UU Pelindungan Data Pribadi)

1. **Prinsip Minimasi Data (*Data Minimization*)**:
   Hanya data penting operasional yang dikumpulkan:
   - Nama lengkap pemesan.
   - Nomor WhatsApp (pengiriman tiket digital QR & koordinasi supir).
   - Catatan kebutuhan khusus.
2. **Penyembunyian Nomor HP di Tampilan Publik (Data Masking)**:
   Pada fitur pengecekan tiket mandiri (`/cek-tiket`), nomor telepon wisatawan disamarkan sebagian untuk mencegah penambangan data (*scraping*):
   ```php
   // Contoh: 081234567890 -> 0812****7890
   $maskedPhone = substr($phone, 0, 4) . '****' . substr($phone, -4);
   ```
3. **Enkripsi Kredensial**:
   Kata sandi staf loket & admin di-hash dengan standar `Bcrypt` atau `Argon2id` melalui helper bawaan Laravel `Hash::make()`.

---

## 5. Rate Limiting & Perlindungan Bot (Anti-Abuse)

1. **Rate Limiting Middleware Laravel**:
   Membatasi frekuensi pembuatan booking untuk mencegah spam pesanan palsu:
   ```php
   Route::middleware(['throttle:5,10'])->group(function () {
       Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
   });
   ```
2. **Pengecekan Kuota API**:
   Endpoint publik dibatasi maksimal 60 permintaan per menit (`throttle:60,1`).

---

## 6. Hak Akses & Otorisasi Berbasis Peran (RBAC)

| Peran (*Role*) | Hak Akses yang Diizinkan | Batasan yang Dilarang |
| :--- | :--- | :--- |
| **`CUSTOMER`** | Reservasi paket, melihat e-tiket mandiri via kode booking / WhatsApp. | Dilarang mengakses dashboard admin atau melihat data booking pengunjung lain. |
| **`CASHIER`** | Scanner QR loket basecamp, pencatatan pelunasan sisa tagihan DP (Tunai / QRIS Kasir). | Dilarang mengubah struktur harga paket atau menghapus log transaksi. |
| **`ADMIN`** | Akses penuh dashboard `/admin`, penugasan armada & driver paguyuban, ringkasan omzet & ekspor laporan. | Wajib menggunakan autentikasi akun resmi dengan password terenkripsi. |

---

## 7. Prosedur Pelaporan Celah Keamanan (Vulnerability Disclosure)

Jika Anda menemukan celah keamanan pada sistem Jeep Wukirsari Adventure Tour:
1. Laporkan secara bertanggung jawab ke tim pengembang atau email `security@jeepwukirsari.id`.
2. Jangan mempublikasikan eksploitasi sebelum perbaikan dirilis (*responsible disclosure*).
3. Tim berkomitmen menanggapi dan merilis perbaikan dalam waktu maksimal $1 \times 24$ jam.
