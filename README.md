# Platform Pemesanan Jeep Wukirsari Adventure Tour 🚙⛰️
> **Sistem Informasi & E-Booking Wisata Jeep Lereng Gunung Merapi Berbasis Web (Mobile-First)**  
> **Tugas Akhir (TA) - Arsitektur Hybrid: Frontend React Vite + Backend PHP MySQL (XAMPP)**

---

## 📌 Ringkasan Proyek

Platform ini dirancang khusus untuk memodernisasi layanan pemesanan paket wisata petualangan jeep di kawasan **Wukirsari, Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta**. Platform ini menyederhanakan alur reservasi wisatawan secara mandiri (*self-service*) dan menyediakan dasbor operasional paguyuban untuk penugasan pengemudi (*driver dispatching*), scanner tiket pelunasan di loket basecamp, serta pembukuan harian.

---

## 🏗️ Arsitektur Sistem (Opsi A - Modern Decoupled)

Untuk memenuhi standar pengujian akademik Tugas Akhir (TA) dan standar industri perangkat lunak modern, proyek ini memisahkan antara lapisan presentasi (*Frontend*) dan lapisan pemrosesan data (*Backend*):

```
+───────────────────────────────────────────────────────────────+
|               ARSITEKTUR PROYEK TUGAS AKHIR (TA)              |
+───────────────────────────────────────────────────────────────+
| [FRONTEND]                                                    |
| - React 18 + Vite 5 + TypeScript + Tailwind CSS               |
| - Desain Mobile-First bertema Deep Forest Green (#1E3F20)     |
| - Tampilan kartu paket, kalender slot, & formulir checkout    |
|                              │                                |
|                        (HTTP JSON REST)                       |
|                              ▼                                |
| [BACKEND REST API - PHP]                                      |
| - PHP 8.1+ berjalan di Apache Server (XAMPP)                  |
| - Folder: /api/ (Koneksi PDO aman dari SQL Injection)         |
| - Integrasi Payment Gateway Midtrans (DP 30%/50% & Full)      |
| - Webhook SHA-512 Security & WhatsApp Gateway Fonnte          |
|                              │                                |
|                         (SQL Query)                           |
|                              ▼                                |
| [BASIS DATA RELASIONAL]                                       |
| - MySQL / MariaDB (Dikelola melalui phpMyAdmin di XAMPP)      |
| - Database: `db_jeep_wukirsari`                               |
+───────────────────────────────────────────────────────────────+
```

---

## 🌟 Fitur Utama Sistem

1. **Katalog Paket Wisata Resmi Khas Merapi**:
   - **Adventure** (Rp 450.000 / Jeep - 1.5 s/d 2 Jam, rute lahar & manuver Sungai Kalikuning).
   - **Lavature** (Rp 550.000 / Jeep - 2 s/d 2.5 Jam, Bunker Kaliadem & Petilasan Mbah Maridjan).
   - **Sunrise Tour** (Rp 700.000 / Jeep - 3 s/d 4 Jam, fajar Merapi mulai pukul 04:30 WIB).
2. **Kalkulator Kapasitas Cerdas**:
   - Regulasi keamanan: **Maksimal 4 orang per Jeep**.
   - Sistem otomatis menghitung rekomendasi jumlah armada: `ceil(jumlah_penumpang / 4)`.
3. **Pembayaran Fleksibel (Midtrans Payment Gateway)**:
   - Pilihan bayar Uang Muka (**DP 30% atau 50%**) atau Bayar Lunas (**Full 100%**).
   - QRIS Dinamis (GoPay, ShopeePay, BCA) & Virtual Account (BCA, BRI, Mandiri, BNI).
4. **E-Tiket QR Code & WhatsApp Otomatis**:
   - E-voucher digital diterbitkan otomatis setelah pembayaran lunas / DP terverifikasi.
   - Dikirimkan langsung ke nomor WhatsApp wisatawan via Fonnte Gateway.
5. **Dashboard Admin & Kasir Loket Basecamp**:
   - Fitur scan QR tiket di loket menggunakan kamera HP kasir.
   - Validasi sisa tagihan bagi pemesan DP (metode: Tunai / QRIS Loket).
   - Penugasan armada (nomor polisi) dan pengemudi paguyuban lokal.

---

## 🚀 Panduan Menjalankan Sistem di Komputer Lokal

### 1. Menyiapkan Basis Data di XAMPP
1. Buka aplikasi **XAMPP Control Panel**, lalu aktifkan **Apache** dan **MySQL**.
2. Buka browser dan masuk ke **phpMyAdmin**: `http://localhost/phpmyadmin/`.
3. Buat database baru dengan nama: `db_jeep_wukirsari`.
4. Masuk ke menu **Import**, pilih file:
   📁 `C:\xampp\htdocs\WebWukirsari\api\database.sql`
5. Klik tombol **Import / Kirim**. Seluruh tabel dan data awal resmi paket akan terpasang otomatis.

### 2. Menjalankan Frontend React Vite
Buka terminal (PowerShell atau Command Prompt) di direktori proyek:
```bash
# 1. Pindah ke direktori proyek
cd C:\xampp\htdocs\WebWukirsari

# 2. Pasang dependensi pustaka
npm install

# 3. Jalankan server pengembangan Vite
npm run dev
```
Akses web melalui browser: `http://localhost:3000` (atau port yang tertera di terminal).

### 3. Menguji Backend API PHP
Endpoint backend PHP dapat langsung diuji di browser:
- `http://localhost/WebWukirsari/api/packages/` (Menampilkan daftar paket format JSON dari MySQL).

---

## 📂 Struktur Dokumentasi Lengkap

| File | Deskripsi |
| :--- | :--- |
| **`PRD.md`** | *Product Requirements Document*: Spesifikasi produk, analisis celah logika, dan ERD 9 tabel relasional. |
| **`TODO.md`** | *Task Checklist*: Panduan tugas bertahap Fase 0 s/d Fase 8. |
| **`DESIGN/STYLEGUIDE.md`** | *Design Tokens*: Palet Deep Forest Green `#1E3F20`, Cream `#F7F7F2`, dan tipografi. |
| **`TECHSTACK.md`** | *Spesifikasi Teknologi*: Arsitektur hybrid React Vite + PHP API. |
| **`SECURITY.md`** | *Protokol Keamanan*: Verifikasi tanda tangan SHA-512 webhook dan proteksi overbooking. |
| **`AGENT.md`** | *Directives AI Agent*: Batasan logika dan konvensi penulisan kode. |
| **`SKILL.md`** | *Dev Cheatsheet*: Perintah terminal dan checklist pengujian *Definition of Done*. |

---

## 👨‍💻 Informasi Proyek
- **Pengembang**: Glent Margareza
- **Kategori**: Tugas Akhir (TA) - Sistem Informasi / Teknik Informatika
- **Repository GitHub**: [https://github.com/glentmargareza/TA.git](https://github.com/glentmargareza/TA.git)

---
*© 2026 Jeep Wukirsari Adventure Tour. Hak cipta dilindungi undang-undang.*
