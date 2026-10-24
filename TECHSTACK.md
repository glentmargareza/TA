# Technology Stack Specification (TECHSTACK.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Tugas Akhir)

> **Spesifikasi Arsitektur Resmi**: Proyek ini dibangun menggunakan arsitektur **Laravel 12 Full-Stack MVC (Model-View-Controller)** dengan template engine **Blade**, utilitas styling **Tailwind CSS**, dan basis data relasional **MySQL (XAMPP)**.

---

## 1. Diagram Arsitektur Sistem (Laravel MVC)

```
+-----------------------------------------------------------------------------------------+
|                  ARSITEKTUR RESMI: LARAVEL 12 FULL-STACK MVC (XAMPP)                    |
+-----------------------------------------------------------------------------------------+
| [LAPISAN VIEW / TAMPILAN]                                                               |
| Template Engine           : Laravel Blade (.blade.php)                                  |
| Styling & Design Tokens   : Tailwind CSS (Deep Forest Green #1E3F20 & Cream #F7F7F2)    |
| Tipografi                 : Plus Jakarta Sans, Outfit, Playfair Display                 |
| Interaktivitas Klien      : Vanilla JavaScript / Alpine.js (Kalkulator Pax & Jeep)      |
|                                     │                                                   |
|                             (HTTP Request / Form)                                       |
|                                     ▼                                                   |
| [LAPISAN CONTROLLER & ROUTING]                                                          |
| Framework                 : Laravel 12 (PHP 8.2+)                                       |
| Web Routes                : routes/web.php                                              |
| Controllers               : HomeController, BookingController, TicketController,        |
|                             AdminController                                             |
| Validasi Input            : Laravel Form Request Validation (Kapasitas Maks 4 Pax/Jeep) |
| Keamanan Sesi             : CSRF Protection (@csrf) & Session Auth                      |
| Integrasi Pembayaran      : Midtrans Snap SDK / Webhook SHA-512 Verification            |
| Notifikasi WhatsApp       : Fonnte API Gateway (Pengiriman E-Tiket Ber-QR Code)        |
|                                     │                                                   |
|                              (Eloquent ORM)                                             |
|                                     ▼                                                   |
| [LAPISAN MODEL & BASIS DATA]                                                            |
| ORM                       : Laravel Eloquent ORM                                        |
| Models                    : Package, ScheduleSlot, PickupZone, Booking,                 |
|                             Payment, Jeep, Driver, BookingAssignment                   |
| Database Engine           : MySQL / MariaDB (XAMPP phpMyAdmin)                          |
| Nama Database             : db_jeep_wukirsari                                           |
+-----------------------------------------------------------------------------------------+
```

---

## 2. Struktur Direktori Proyek Laravel MVC

```
WebWukirsari/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php      # Controller Beranda & Detail Rute Paket
│   │   ├── BookingController.php   # Controller Form Pemesanan, Hitung DP, & E-Tiket
│   │   ├── TicketController.php    # Controller Pelacakan Tiket Mandiri Wisatawan
│   │   └── AdminController.php     # Controller Omzet, Penugasan Driver, & Loket
│   └── Models/
│       ├── Package.php             # Model Paket Wisata (Adventure, Lavature, Sunrise)
│       ├── ScheduleSlot.php        # Model Sesi Jam Operasional
│       ├── PickupZone.php          # Model Zona Penjemputan Shuttle
│       ├── Booking.php             # Model Reservasi Wisata
│       ├── Payment.php             # Model Transaksi Pembayaran
│       ├── Jeep.php                # Model Armada Mobil Paguyuban
│       ├── Driver.php              # Model Pengemudi Paguyuban Lokal
│       └── BookingAssignment.php   # Model Penugasan Driver ke Booking
├── database/
│   ├── migrations/                 # 8 Tabel Skema Relasional Resmi
│   └── seeders/
│       └── DatabaseSeeder.php      # Seeder Data Awal Resmi
├── resources/
│   ├── css/
│   │   └── app.css                 # Konfigurasi Tailwind CSS
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php       # Layout Utama (Navbar, Footer, Sticky Mobile CTA)
│       ├── home.blade.php          # Halaman Beranda (Sesuai Desain Mockup)
│       ├── packages/
│       │   └── show.blade.php      # Halaman Rincian Paket
│       ├── booking/
│       │   ├── create.blade.php    # Form Booking Interaktif
│       │   └── success.blade.php   # E-Tiket Boarding Pass dengan QR Code & Cetak
│       ├── ticket/
│       │   └── check.blade.php     # Halaman Pencarian Tiket Mandiri
│       └── admin/
│           └── dashboard.blade.php # Portal Admin Loket Basecamp
├── routes/
│   └── web.php                     # 12 Rute Web Terdaftar
├── public/                         # Web Root Apache (index.php, CSS/JS terkompilasi)
├── .env                            # Konfigurasi Lingkungan (MySQL XAMPP)
├── artisan                         # CLI Laravel
├── composer.json                   # Dependensi PHP Laravel 12
├── tailwind.config.js              # Token Warna Resmi (#1E3F20)
└── vite.config.js                  # Asset Bundler
```

---

## 3. Konfigurasi Lingkungan (`.env`)

```ini
APP_NAME="Jeep Wukirsari Adventure Tour"
APP_ENV=local
APP_KEY=base64:ZtFrmBPxOCCTuY51MBjFUMXOvxNk6aR4So6fgJhurqw=
APP_DEBUG=true
APP_URL=http://localhost/WebWukirsari/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_jeep_wukirsari
DB_USERNAME=root
DB_PASSWORD=

# Payment Gateway Midtrans
MIDTRANS_SERVER_KEY="SB-Mid-server-xxxxxxxxxxxx"
MIDTRANS_CLIENT_KEY="SB-Mid-client-xxxxxxxxxxxx"
MIDTRANS_IS_PRODUCTION=false

# WhatsApp Notification
FONNTE_API_TOKEN="token_api_fonnte"
```

---

## 4. Perintah Operasional (*CLI Commands*)

```bash
# Menjalankan server lokal Laravel
php artisan serve

# Menjalankan migrasi dan data awal (seeder)
php artisan migrate:fresh --seed

# Memeriksa rute web
php artisan route:list

# Membersihkan cache aplikasi
php artisan optimize:clear
```
