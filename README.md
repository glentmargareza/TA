# Platform Pemesanan Jeep Wukirsari Adventure Tour 🚙⛰️
> **Sistem Informasi & E-Booking Wisata Jeep Lereng Gunung Merapi Berbasis Web**  
> **Tugas Akhir (TA) - Framework Laravel 12 (MVC), Blade, Tailwind CSS, & Basis Data MySQL (XAMPP)**

---

## 📌 Ringkasan Proyek

Platform ini dibangun untuk mendigitalisasi proses reservasi paket wisata petualangan jeep di kawasan **Wukirsari, Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta**. Sistem ini memudahkan wisatawan memilih paket secara mandiri (*self-service*), melakukan pemesanan jadwal dengan kalkulator armada cerdas (maks. 4 orang per jeep), opsi pembayaran uang muka (**DP 30%**) atau lunas (**Full 100%**), penerbitan e-tiket QR Code, serta menyediakan dasbor operasional bagi paguyuban untuk penugasan pengemudi (*driver dispatching*) dan pencatatan pelunasan di loket basecamp.

---

## 🏗️ Arsitektur Proyek (Laravel MVC murni)

Sistem dibangun menggunakan standar arsitektur **Model-View-Controller (MVC)** pada framework **Laravel 12**:

```
+───────────────────────────────────────────────────────────────+
|               ARSITEKTUR TUGAS AKHIR: LARAVEL MVC             |
+───────────────────────────────────────────────────────────────+
| [MODEL - Eloquent ORM]                                        |
| - Package.php, ScheduleSlot.php, PickupZone.php               |
| - Booking.php, Payment.php, Jeep.php, Driver.php              |
| - Terhubung langsung ke MySQL di XAMPP (db_jeep_wukirsari)    |
|                              │                                |
|                        (Data Flow)                            |
|                              ▼                                |
| [CONTROLLER - Logika Bisnis & Validasi]                       |
| - HomeController.php      : Beranda & Detail Paket            |
| - BookingController.php   : Validasi Kapasitas, Auto-Jeep, DP |
| - TicketController.php    : Pelacakan E-Tiket Mandiri         |
| - AdminController.php     : Omzet, Penugasan Driver & Loket   |
|                              │                                |
|                       (Render View)                           |
|                              ▼                                |
| [VIEW - Blade Template & Tailwind CSS]                        |
| - layouts/app.blade.php   : Navbar, Footer, Mobile Sticky CTA |
| - home.blade.php          : Tampilan Beranda sesuai Mockup    |
| - booking/create.blade.php: Form Booking Interaktif           |
| - booking/success.blade.php: E-Tiket Boarding Pass & QR       |
| - admin/dashboard.blade.php: Portal Admin & Kasir Loket       |
+───────────────────────────────────────────────────────────────+
```

---

## 🌟 Fitur Utama Sistem

1. **Katalog Paket Wisata Khas Merapi**:
   - **Adventure** (Rp 450.000 / Jeep - 1.5 s/d 2 Jam, rute lahar & susur Kali Kuning).
   - **Lavature** (Rp 550.000 / Jeep - 2 s/d 2.5 Jam, Bunker Kaliadem & Petilasan Mbah Maridjan).
   - **Sunrise Tour** (Rp 700.000 / Jeep - 3 s/d 4 Jam, sunrise fajar Merapi mulai 04:30 WIB).
2. **Kalkulator Kapasitas Cerdas**:
   - Aturan keselamatan offroad: **Maksimal 4 penumpang per armada jeep**.
   - Sistem secara otomatis menghitung jumlah mobil: `ceil(jumlah_penumpang / 4)`.
3. **Fleksibilitas Pembayaran (DP vs Full)**:
   - Pilihan bayar Uang Muka (**DP 30%**) atau Bayar Penuh (**Full 100%**).
   - Rincian biaya transparan (subtotal sewa + biaya antar-jemput shuttle bila ada).
4. **E-Tiket QR Code & Bukti Booking**:
   - E-voucher berdesain *boarding pass* estetis dengan garis perforasi.
   - Tombol cetak tiket dan tombol konfirmasi otomatis langsung ke WhatsApp CS.
5. **Dashboard Operasional Paguyuban & Kasir**:
   - Statistik omzet terverifikasi dan jumlah armada siap pangkalan.
   - Penugasan nomor polisi mobil dan driver lokal ke pesanan.
   - Tombol kasir satu klik *"Terima Pelunasan"* bagi wisatawan yang memilih DP.

---

## 🚀 Panduan Menjalankan Sistem di Komputer Lokal

### Prasyarat:
- XAMPP (Apache & MySQL aktif)
- PHP >= 8.2
- Composer

### Langkah 1: Setup Database MySQL di XAMPP
1. Buka **XAMPP Control Panel**, nyalakan **Apache** dan **MySQL**.
2. Masuk ke **phpMyAdmin**: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
3. Buat database bernama: `db_jeep_wukirsari`.
4. Jalankan migrasi dan seeder resmi Laravel melalui terminal:
   ```bash
   cd C:\xampp\htdocs\WebWukirsari
   php artisan migrate:fresh --seed
   ```
   *(Seluruh tabel dan data paket resmi akan terisi otomatis).*

### Langkah 2: Menjalankan Server Laravel
Jalankan perintah built-in server Laravel:
```bash
php artisan serve
```
Akses web melalui peramban:
* 🌐 **Beranda Wisatawan**: [http://127.0.0.1:8000/](http://127.0.0.1:8000/)
* 📋 **Form Pemesanan**: [http://127.0.0.1:8000/booking](http://127.0.0.1:8000/booking)
* 🔍 **Cek Tiket Mandiri**: [http://127.0.0.1:8000/cek-tiket](http://127.0.0.1:8000/cek-tiket)
* 🛠️ **Portal Admin Loket**: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

*(Atau via Apache XAMPP langsung di: `http://localhost/WebWukirsari/public/`)*

---

## 👨‍💻 Informasi Proyek
- **Pengembang**: Glent Margareza
- **Kategori**: Tugas Akhir (TA) - Sistem Informasi / Teknik Informatika
- **Repository GitHub**: [https://github.com/glentmargareza/TA.git](https://github.com/glentmargareza/TA.git)

---
*© 2026 Jeep Wukirsari Adventure Tour. Hak cipta dilindungi undang-undang.*
