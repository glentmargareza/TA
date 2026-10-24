# Technology Stack Specification (TECHSTACK.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Tugas Akhir)

> **Spesifikasi Arsitektur Resmi (Opsi A - Modern Decoupled)**: Proyek ini menggunakan arsitektur hibrida modern yang menggabungkan antarmuka **Frontend React + Vite** berkecepatan tinggi dengan **Backend PHP & Basis Data MySQL (XAMPP)** untuk pemrosesan data, integrasi payment gateway, dan manajemen basis data.

---

## 1. Diagram Arsitektur Sistem

```
+-----------------------------------------------------------------------------------------+
|                  ARSITEKTUR HYBRID: REACT VITE + PHP MYSQL (XAMPP)                      |
+-----------------------------------------------------------------------------------------+
| [FRONTEND CLIENT]                                                                       |
| Framework & Bundler       : React 18+ & Vite 5+ (TypeScript)                            |
| Styling & Design Tokens   : Tailwind CSS (Deep Forest Green #1E3F20 & Cream #F7F7F2)    |
| Navigasi & State          : React Router DOM v6 & Zustand                               |
| Ikon & Animasi            : Lucide React + Framer Motion                                |
| HTTP Client               : Axios (Memanggil endpoint REST API PHP)                     |
|                                     │                                                   |
|                               (HTTP/JSON)                                               |
|                                     ▼                                                   |
| [BACKEND REST API - PHP]                                                                |
| Runtime & Server          : PHP 8.1+ berjalan di Apache Web Server (XAMPP)              |
| Lokasi Endpoint           : Folder /api/ di dalam root proyek                           |
| Koneksi Basis Data        : PDO (PHP Data Objects) - Aman dari SQL Injection            |
| Pembayaran                : Midtrans PHP SDK / cURL Snap API (DP 30%/50% & Full)        |
| Webhook Security          : Verifikasi SHA-512 Hash Signature di PHP                    |
| WhatsApp Gateway          : cURL ke Fonnte API untuk pengiriman E-Tiket ber-QR Code     |
|                                     │                                                   |
|                               (SQL Queries)                                             |
|                                     ▼                                                   |
| [BASIS DATA RELASIONAL]                                                                 |
| Database Engine           : MySQL / MariaDB (Dikelola via phpMyAdmin XAMPP)             |
| Nama Database             : db_jeep_wukirsari                                           |
+-----------------------------------------------------------------------------------------+
```

---

## 2. Struktur Folder Proyek

```
WebWukirsari/
├── api/                        # BACKEND: RESTful API PHP (XAMPP)
│   ├── config/
│   │   ├── database.php        # Koneksi PDO MySQL
│   │   └── midtrans.php        # Kredensial & Pengaturan Server Midtrans
│   ├── packages/
│   │   └── index.php           # GET: Daftar paket (Adventure, Lavature, Sunrise)
│   ├── bookings/
│   │   ├── create.php          # POST: Buat pesanan baru & validasi max 4 pax/jeep
│   │   ├── check-slot.php      # GET: Cek kuota armada per tanggal & jam
│   │   └── detail.php          # GET: Rincian tiket berdasarkan kode booking
│   ├── payments/
│   │   ├── create-snap.php     # POST: Generate token transaksi Midtrans Snap
│   │   └── webhook.php         # POST: Callback Midtrans (Verifikasi SHA-512)
│   ├── tickets/
│   │   └── scan.php            # POST: Verifikasi tiket QR & pelunasan di loket
│   └── database.sql            # Script SQL untuk diimpor ke phpMyAdmin
├── public/                     # Aset statis publik
├── src/                        # FRONTEND: React + TypeScript
│   ├── assets/                 # Gambar logo & banner
│   ├── components/             # Reusable UI Components
│   │   ├── layout/             # Navbar, Footer Hijau Tua, Sticky Mobile Bar
│   │   ├── home/               # HeroSection, ValueBar, PackageSection, WhyUs, Gallery, Reviews
│   │   ├── booking/            # CalendarPicker, PaxCounter, TimeSlot, SummaryCard
│   │   └── tickets/            # TicketCard, PrintVoucher
│   ├── pages/                  # Halaman Aplikasi
│   │   ├── HomePage.tsx        # Halaman Beranda sesuai mockup resmi
│   │   ├── BookingPage.tsx     # Alur Formulir Checkout & DP
│   │   ├── SuccessPage.tsx     # Halaman Sukses & Download Tiket
│   │   ├── CheckTicketPage.tsx # Pencarian Tiket Mandiri Wisatawan
│   │   └── admin/              # Dashboard Pengelola & Scanner Kasir
│   ├── services/
│   │   └── api.ts              # Konfigurasi Axios menuju /api/
│   ├── types/                  # Definisi tipe TypeScript
│   ├── App.tsx                 # Konfigurasi React Router DOM
│   ├── main.tsx                # Entry point React
│   └── index.css               # Direktif Tailwind CSS
├── index.html
├── package.json
├── tailwind.config.js
├── vite.config.ts
└── README.md
```

---

## 3. Konfigurasi Basis Data MySQL (`api/config/database.php`)

```php
<?php
// api/config/database.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Database {
    private $host = "localhost";
    private $db_name = "db_jeep_wukirsari";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $exception) {
            echo json_encode([
                "success" => false,
                "message" => "Koneksi database gagal: " . $exception->getMessage()
            ]);
            exit();
        }
        return $this->conn;
    }
}
```

---

## 4. Keunggulan Arsitektur Opsi A untuk Sidang Tugas Akhir (TA)

1. **Memenuhi Standar Kurikulum Akademik**: Menggunakan bahasa **PHP** dan database **MySQL di XAMPP** yang menjadi standar baku pengujian Tugas Akhir di kampus.
2. **Kualitas Tampilan Modern Kelas Atas**: Dosen penguji akan terkesan karena antarmuka web tidak kaku seperti web PHP klasik, melainkan menggunakan **React + Vite + Tailwind CSS** yang interaktif, responsif di HP, dan transisi halamannya instan tanpa refresh browser.
3. **Pemisahan Tanggung Jawab (*Separation of Concerns*)**: Konsep pemisahan Frontend (tampilan) dan Backend (logika bisnis & API) merupakan nilai tambah tinggi dalam metodologi rekayasa perangkat lunak saat sidang skripsi/TA.
