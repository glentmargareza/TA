# Laporan dan Dokumentasi Pengujian Sistem (TESTING.md) – Jeep Wukirsari

Dokumentasi ini memuat seluruh metodologi pengujian, spesifikasi lingkungan, prosedur eksekusi, skenario pengujian fungsional dan keamanan, hasil aktual pengujian (*test matrix*), serta daftar temuan eror dan status perbaikannya pada sistem reservasi Jeep Wukirsari.

---

## 1. Tujuan Pengujian

Pengujian sistem dilakukan untuk:
1. Memastikan keandalan seluruh alur bisnis: Beranda, Dashboard Admin, Pemesanan (Booking), Penjemputan (Pickup Zone), Pembayaran Multi-Kanal, dan integrasi Midtrans Sandbox.
2. Memverifikasi integritas finansial dan perhitungan matematis (kapasitas penumpang per jeep, tarif zona antar-jemput per armada, pembagian DP 30% dan pelunasan 70%).
3. Menguji ketahanan keamanan: autentikasi, otorisasi berbasis peran (*RBAC*), perlindungan *cross-site request forgery* (CSRF), sanitasi XSS, verifikasi tanda tangan kriptografi SHA-512 webhook, serta mekanisme idempotensi.
4. Memvalidasi kesiapan sistem sebelum dipublikasikan ke server *staging/production* untuk pendaftaran dan verifikasi akun merchant Midtrans.

---

## 2. Lingkungan Pengujian (Environment)

| Parameter | Spesifikasi Lingkungan Uji |
| :--- | :--- |
| **Sistem Operasi** | Windows 11 (x64) |
| **Web Server** | Apache / Built-in Laravel Server (XAMPP 8.2) |
| **Bahasa Pemrograman** | PHP 8.2.12 (CLI & FPM) |
| **Framework Backend** | Laravel 12.x (MVC Architecture) |
| **Basis Data** | MySQL / MariaDB 10.4.32 (Port 3306) |
| **Database Uji** | `web-ta` |
| **Frontend Engine** | Blade Template Engine & Tailwind CSS CDN |
| **Payment Gateway** | Midtrans Sandbox Environment (Core API & Snap) |

---

## 3. Tools yang Digunakan

1. **PHPUnit 11.5 / Laravel Testing Suite**: Framework otomatisasi pengujian unit dan fitur backend.
2. **Artisan CLI**: Pembersihan cache, kompilasi view Blade, linting, dan eksekusi kueri langsung via Tinker.
3. **Midtrans Payment Simulator**: Pengujian callback webhook Virtual Account (BCA, BNI, BRI) dan QRIS Sandbox tanpa uang sungguhan.
4. **Browser Developer Tools**: Verifikasi responsivitas antarmuka (desktop & mobile viewport) serta inspeksi network payload HTTP.

---

## 4. Cara Menjalankan Testing Laravel

### A. Menjalankan Seluruh Test Suite Otomatis
Buka terminal pada direktori proyek (`c:\xampp\htdocs\TA`) dan jalankan:
```bash
php artisan test
```

### B. Menjalankan File Test Fitur Spesifik
```bash
php artisan test tests/Feature/BookingPaymentTest.php
```

### C. Menjalankan Test dengan Opsi Filter Kasus Uji Tertentu
```bash
php artisan test --filter=test_booking_creation_and_auto_jeep_capacity
```

### D. Melakukan Uji Linting Sintaks PHP
```bash
Get-ChildItem -Path app\Http\Controllers\*.php | ForEach-Object { php -l $_.FullName }
Get-ChildItem -Path app\Models\*.php | ForEach-Object { php -l $_.FullName }
```

### E. Menguji Kompilasi Seluruh Tampilan Blade
```bash
php artisan view:clear
php artisan view:cache
```

---

## 5. Matriks Skenario dan Hasil Pengujian (Test Matrix)

Berikut adalah rekapitulasi pengujian seluruh fitur sistem yang benar-benar telah dijalankan pada lingkungan uji:

| No | Fitur | Skenario | Hasil yang Diharapkan | Hasil Aktual | Status |
| :---: | :--- | :--- | :--- | :--- | :---: |
| 1 | **Beranda** | Mengakses halaman publik beranda (`GET /`) | HTTP 200, hero banner, identitas usaha, dan navigasi termuat. | HTTP 200, seluruh komponen beranda tampil responsif. | **PASS** |
| 2 | **Beranda** | Memuat data paket wisata dari basis data MySQL | Kartu paket (Short, Medium, Long, Sunrise) muncul sesuai database. | HTTP 200, paket aktif muncul lengkap beserta tarif per jeep. | **PASS** |
| 3 | **Beranda** | Memuat testimoni dan ulasan wisatawan terverifikasi | Ulasan berstatus `is_approved = 1` tampil beserta relasi user dan paket. | Tampil 6 ulasan teratas dengan skor rata-rata bintang dinamis. | **PASS** |
| 4 | **Legal** | Mengakses halaman Syarat & Ketentuan (`GET /syarat-ketentuan`) | HTTP 200, syarat pemesanan, aturan kapasitas, dan kebijakan refund tampil. | HTTP 200, dokumen ketentuan hukum termuat lengkap. | **PASS** |
| 5 | **Legal** | Mengakses halaman Kebijakan Privasi (`GET /kebijakan-privasi`) | HTTP 200, penjelasan pemrosesan data dan kepatuhan Midtrans tampil. | HTTP 200, dokumen privasi dan keamanan termuat lengkap. | **PASS** |
| 6 | **Admin** | Tamu (guest) tanpa login mengakses `/admin` | HTTP 302, akses diblokir dan dialihkan ke form `/login`. | HTTP 302, redirect ke `/login` dengan notifikasi keamanan. | **PASS** |
| 7 | **Admin** | Wisatawan (role: customer) mengakses `/admin` | HTTP 302, ditolak dan dialihkan ke `/login` disertai pesan peringatan. | HTTP 302, user biasa dilarang membuka portal admin pangkalan. | **PASS** |
| 8 | **Admin** | Login dengan akun Admin dan memuat `/admin` | HTTP 200, dashboard memuat statistik omzet, armada, dan pelanggan unik. | HTTP 200, statistik terverifikasi (Omzet Rp 9.950.000, 28 Pelanggan). | **PASS** |
| 9 | **Admin** | Kasir menerima pelunasan DP di loket (`POST /admin/bookings/{id}/settle`) | Booking berubah `FULL_PAID`, sisa tagihan Rp 0, dan dicatat di `payments`. | Transaksi atomik berhasil, tercatat baris `CASH_BASECAMP` di `payments`. | **PASS** |
| 10 | **Booking** | Wisatawan membuka form pemesanan (`GET /booking`) | HTTP 200, form interaktif memuat pilihan paket, sesi jadwal, dan kalkulator. | HTTP 200, form termuat tanpa galat. | **PASS** |
| 11 | **Booking** | Input rombongan 7 orang (melebihi kapasitas aman 1 jeep) | Sistem otomatis menaikkan alokasi armada menjadi 2 unit jeep (`ceil(7/4)`). | Kolom `jeep_quantity` tersimpan `2`, subtotal sewa otomatis dikalikan 2. | **PASS** |
| 12 | **Booking** | Pemeriksaan ketersediaan kuota armada sesi jadwal | Kuota slot divalidasi; jika kuota yang diminta melebihi batas, pesanan ditolak. | Validasi slot berjalan akurat; jika kuota tidak cukup, muncul pesan eror. | **PASS** |
| 13 | **Booking** | Pembuatan pemesanan baru (`POST /booking`) | Data tersimpan di tabel `bookings`, status `PENDING`, kunci slot 30 menit. | Record tersimpan dengan kode unik `WKR-XXXXXX`, redirect ke `/payment`. | **PASS** |
| 14 | **Pickup** | Penjemputan di luar radius gratis (5 km) dengan 2 unit Jeep | Biaya jemput dihitung per jeep: $\text{Tarif Zona} \times \text{Jumlah Jeep}$. | Biaya jemput terakumulasi kelipatan 2 jeep dan tersimpan di `pickup_fee`. | **PASS** |
| 15 | **Pickup** | Penyimpanan foreign key zona penjemputan | Kolom `pickup_zone_id` terhubung ke record resmi tabel `pickup_zones`. | Foreign key `pickup_zone_id` tersimpan valid (tidak bernilai null). | **PASS** |
| 16 | **Tiket** | Reservasi berstatus `PENDING` mencoba membuka e-tiket (`/success`) | Sistem memblokir akses ke tiket dan mengalihkan wisatawan ke `/payment`. | HTTP 302 redirect ke `/payment`, tiket tidak dapat diakses sebelum bayar. | **PASS** |
| 17 | **Tiket** | Reservasi berstatus sah (`FULL_PAID` / `DP_PAID`) membuka e-tiket | HTTP 200, boarding pass, QR Code check-in, dan rincian armada ditampilkan. | HTTP 200, e-tiket resmi tampil estetis dengan QR Code pangkalan. | **PASS** |
| 18 | **Payment** | Endpoint polling status (`GET /booking/{code}/status`) | HTTP 200 JSON memuat status terkini pesanan dan sisa tagihan. | Respon JSON valid dengan status pesanan sinkron dengan database. | **PASS** |
| 19 | **Payment** | Endpoint inisialisasi charge (`POST /booking/{code}/charge`) | Validasi channel pembayaran; tolak channel ilegal atau nominal salah. | HTTP 422 untuk input tidak valid; HTTP 201 untuk request charge sah. | **PASS** |
| 20 | **Midtrans** | Webhook menerima request dengan Signature SHA-512 palsu | HTTP 401 Unauthorized; transaksi ditolak dan log peringatan dicatat. | HTTP 401, tanda tangan palsu digagalkan oleh backend. | **PASS** |
| 21 | **Midtrans** | Webhook menerima notifikasi dengan nominal tidak cocok | HTTP 400 Bad Request; mencegah transaksi di bawah nilai tagihan disahkan. | HTTP 400, nominal mismatch dicegah secara deterministik. | **PASS** |
| 22 | **Midtrans** | Webhook menerima notifikasi ganda (*duplicate retry notification*) | Operasi idempoten: HTTP 200, status pembayaran tidak diproses ulang. | HTTP 200 Idempotent; status tetap `SETTLED` tanpa duplikasi saldo. | **PASS** |
| 23 | **Midtrans** | Webhook status `settlement` untuk pemesanan skema DP 30% | Booking status berubah menjadi `DP_PAID`, sisa tagihan 70% dihitung akurat. | Booking berstatus `DP_PAID`, sisa tagihan di loket tersimpan tepat. | **PASS** |
| 24 | **Midtrans** | Webhook status `settlement` untuk pemesanan skema Full 100% | Booking status berubah menjadi `FULL_PAID`, sisa tagihan menjadi Rp 0. | Booking berstatus `FULL_PAID`, pembayaran lunas 100%, slot terkunci sah. | **PASS** |
| 25 | **Midtrans** | Notifikasi kedaluwarsa terlambat (*late expiry*) setelah pesanan sah lunas | Notifikasi expire dicatat di payment tanpa membatalkan booking yang sudah sah. | Booking sah tetap `FULL_PAID` / `DP_PAID`, tidak dibatalkan sepihak. | **PASS** |

---

## 6. Daftar Error yang Ditemukan dan Status Perbaikannya

Selama audit pengujian dilakukan, ditemukan 2 isu teknis yang langsung diperbaiki:

### Temuan Error 1: Kegagalan Pengujian Akses Tamu pada Halaman Terproteksi
- **Lokasi**: `tests/Feature/BookingPaymentTest.php` (`test_public_pages_load_successfully`).
- **Gejala**: Pengujian mengharapkan HTTP 200 saat mengakses `/cek-tiket` dan `/admin` tanpa sesi login, namun sistem mengembalikan HTTP 302 (Redirect ke `/login`).
- **Penyebab**: Rute `/cek-tiket` dilindungi middleware `auth` dan rute `/admin` dilindungi otorisasi role `admin`. Respons HTTP 302 adalah perilaku pengamanan yang benar dan diinginkan.
- **Tindakan Perbaikan**: Mengoreksi skenario pengujian untuk secara eksplisit memverifikasi bahwa tamu tanpa login wajib dialihkan ke `login` (`assertRedirect(route('login'))`), serta menambahkan pengujian otentikasi login admin yang memverifikasi HTTP 200 saat pengguna memiliki hak akses sah.
- **Status**: **TERATASI (RESOLVED)**.

### Temuan Error 2: Pelunasan Kasir di Loket Tidak Mencatat Record di Tabel `payments`
- **Lokasi**: `app/Http/Controllers/AdminController.php` pada fungsi `settleDp(int $id)`.
- **Gejala**: Pengujian `test_admin_settle_dp_records_atomic_cash_payment` gagal karena tidak ditemukan entri di tabel `payments` dengan `payment_type = 'CASH_BASECAMP'`.
- **Penyebab**: Kode sebelumnya hanya memperbarui kolom pada tabel `bookings` (`booking_status = 'FULL_PAID'`), tetapi melewatkan pencatatan riwayat transaksi keuangan pada tabel `payments`.
- **Tindakan Perbaikan**: Membungkus proses pelunasan kasir dalam `DB::transaction()` dan mengeksekusi `Payment::create([...])` dengan `payment_stage = 'SETTLEMENT'`, `payment_type = 'CASH_BASECAMP'`, `gross_amount = $remaining`, dan `payment_status = 'SETTLED'`.
- **Status**: **TERATASI (RESOLVED)**.

---

## 7. Kesimpulan Hasil Pengujian Teknis

- **Total Pengujian Otomatis**: 21 Kasus Uji (Test Cases).
- **Total Asersi (Assertions)**: 89 Asersi.
- **Hasil Akhir**: **100% PASS (0 Failed, 0 Error, 0 Warning)**.
- **Durasi Eksekusi**: ~3.64 detik.
- **Integritas Database**: Tidak ada kebocoran data, tidak ada *unhandled exception*, dan seluruh relasi antartabel bekerja harmonis.

