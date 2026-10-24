# Task Checklist & Execution Roadmap (TODO.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Laravel 12 MVC)

> **Catatan untuk AI Agent**: Proyek ini dibangun menggunakan **Framework Laravel 12 Full-Stack MVC (Blade, Tailwind CSS, & MySQL)**. File ini adalah acuan kerja berurutan (*execution backlog*). Tandai `[x]` setiap kali satu sub-tugas selesai diverifikasi dan diuji. Desain visual wajib mengacu pada referensi resmi di `DESIGN/STYLEGUIDE.md`.

---

## Progress Overview
- [x] **Fase 0: Inisialisasi Proyek Laravel 12 & Konfigurasi Lingkungan** (5/5)
- [x] **Fase 1: Basis Data, Migrasi & Seeder (MySQL XAMPP)** (6/6)
- [x] **Fase 2: Eloquent Models & Hubungan Relasional (ORM)** (5/5)
- [x] **Fase 3: Routing & Controllers (Laravel MVC)** (5/5)
- [x] **Fase 4: Antarmuka Publik Blade Sesuai Mockup Desain Resmi** (8/8)
- [x] **Fase 5: Formulir Pemesanan Interaktif & Kalkulator Kapasitas** (6/6)
- [x] **Fase 6: E-Tiket Boarding Pass QR Code & Pelacakan Mandiri** (5/5)
- [x] **Fase 7: Dashboard Admin & Kasir Loket Basecamp** (6/6)
- [x] **Fase 8: Integrasi Payment Gateway Midtrans & Testing Akhir** (5/5)

---

## Rincian Tugas per Fase

### Fase 0: Inisialisasi Proyek Laravel 12 & Konfigurasi Lingkungan
- [x] `0.1` Inisialisasi proyek Laravel 12 via Composer di `C:\xampp\htdocs\WebWukirsari`
- [x] `0.2` Konfigurasi file `.env` untuk koneksi MySQL XAMPP (`db_jeep_wukirsari`)
- [x] `0.3` Konfigurasi tema Tailwind CSS di `tailwind.config.js` sesuai warna resmi di `DESIGN/STYLEGUIDE.md` (`#1E3F20` Deep Forest Green, `#F7F7F2` Cream Canvas, `#E8EFE9` Sage, `#F59E0B` Gold)
- [x] `0.4` Konfigurasi Google Fonts (`Plus Jakarta Sans`, `Outfit`, `Playfair Display`) pada template Blade
- [x] `0.5` Pembersihan seluruh file lama (React, TypeScript, dist, api) agar repositori bersih

### Fase 1: Basis Data, Migrasi & Seeder (MySQL XAMPP)
- [x] `1.1` Migrasi `packages` (Tabel paket wisata resmi)
- [x] `1.2` Migrasi `schedule_slots` (Tabel sesi jam operasional)
- [x] `1.3` Migrasi `pickup_zones` (Tabel zona jemputan shuttle)
- [x] `1.4` Migrasi `jeeps` & `drivers` (Tabel armada paguyuban dan pengemudi)
- [x] `1.5` Migrasi `bookings`, `payments`, dan `booking_assignments`
- [x] `1.6` Database Seeder (`database/seeders/DatabaseSeeder.php`): Mengisi data resmi paket Adventure (Rp 450rb), Lavature (Rp 550rb), Sunrise Tour (Rp 700rb), serta jam keberangkatan

### Fase 2: Eloquent Models & Hubungan Relasional (ORM)
- [x] `2.1` Model `Package.php` (Relasi `hasMany` ke Bookings)
- [x] `2.2` Model `ScheduleSlot.php` & `PickupZone.php`
- [x] `2.3` Model `Booking.php` (Relasi ke Package, Slot, PickupZone, Payments, Assignments)
- [x] `2.4` Model `Payment.php` (Relasi `belongsTo` ke Booking)
- [x] `2.5` Model `Jeep.php`, `Driver.php`, & `BookingAssignment.php`

### Fase 3: Routing & Controllers (Laravel MVC)
- [x] `3.1` Setup rute web di `routes/web.php` (12 rute terverifikasi via `php artisan route:list`)
- [x] `3.2` Controller `HomeController.php`: Mengambil data paket aktif dari database untuk beranda & halaman detail
- [x] `3.3` Controller `BookingController.php`: Menangani form booking, kalkulasi aturan keamanan maks 4 pax/jeep, perhitungan DP 30%, dan pembuatan kode booking unik
- [x] `3.4` Controller `TicketController.php`: Pencarian status tiket mandiri oleh pengunjung
- [x] `3.5` Controller `AdminController.php`: Manajemen dashboard, hitung omzet, alokasi driver, dan pelunasan kasir loket

### Fase 4: Antarmuka Publik Blade Sesuai Mockup Desain Resmi
- [x] `4.1` Layout Utama (`resources/views/layouts/app.blade.php`): Navbar, logo gunung, menu navigasi, dan tombol pill *"Booking Sekarang ->"*
- [x] `4.2` Hero Section (`home.blade.php`): Latar Gunung Merapi megah, Jeep hijau 4x4, badge *"Petualangan di Lereng Merapi"*, dual CTA, slider 01–05, dan pin lokasi *"📍 Wukirsari, Sleman, Yogyakarta"*
- [x] `4.3` Bilah 4 Keunggulan: 4 kartu squircle hijau sage (`Jeep 4x4`, `Driver Berpengalaman`, `Fasilitas Lengkap`, `Lokasi Strategis`)
- [x] `4.4` Katalog Paket Wisata: 3 kartu paket dinamis dari database (*Adventure*, *Lavature*, *Sunrise Tour*)
- [x] `4.5` Blok "Mengapa Memilih Jeep Wukirsari?": Kotak hijau gelap di kiri (*Tentang Kami ->*) dan 4 kartu fitur di kanan
- [x] `4.6` Galeri Momen Indah: Grid 5 foto petualangan jeep lava tour
- [x] `4.7` Ulasan Wisatawan (Testimoni): Kartu ulasan pelanggan dengan 5 bintang emas `#F59E0B`
- [x] `4.8` Footer Hijau Tua: Brand, tagline, navigasi, nomor telepon, dan informasi pangkalan siaga 24 jam

### Fase 5: Formulir Pemesanan Interaktif & Kalkulator Kapasitas
- [x] `5.1` Halaman Form Booking (`resources/views/booking/create.blade.php`)
- [x] `5.2` Logika JavaScript interaktif: Rekomendasi otomatis jumlah armada jika penumpang melebihi kapasitas 4 orang (`ceil(pax / 4)`)
- [x] `5.3` Pilihan jadwal kalender tanggal dan sesi jam keberangkatan
- [x] `5.4` Opsi kedatangan: Datang Sendiri (Rp 0) vs Shuttle per Zona (Lokal Cangkringan, Sleman/Jogja Kota, Bandara YIA)
- [x] `5.5` Skema pembayaran: Opsi Bayar Penuh (100%) vs Uang Muka (DP 30%)
- [x] `5.6` Validasi sisi server dan penyimpanan ke tabel `bookings` MySQL

### Fase 6: E-Tiket Boarding Pass QR Code & Pelacakan Mandiri
- [x] `6.1` Halaman E-Tiket (`resources/views/booking/success.blade.php`): Desain boarding pass dengan aksen perforasi gerigi
- [x] `6.2` Tampilan Kode Booking unik, detail jadwal, status bayar (Lunas vs DP), dan QR Code loket
- [x] `6.3` Tombol cetak / simpan tiket PDF
- [x] `6.4` Tombol konfirmasi otomatis langsung ke WhatsApp CS Basecamp
- [x] `6.5` Halaman pencarian tiket mandiri (`resources/views/ticket/check.blade.php`)

### Fase 7: Dashboard Admin & Kasir Loket Basecamp
- [x] `7.1` Halaman Dashboard Admin (`resources/views/admin/dashboard.blade.php`)
- [x] `7.2` Kartu ringkasan: Total omzet terverifikasi, armada siap pangkalan, dan booking menunggu pelunasan
- [x] `7.3` Tabel seluruh reservasi terkini
- [x] `7.4` Form penugasan armada mobil dan driver paguyuban ke pesanan
- [x] `7.5` Tombol satu klik kasir *"Terima Pelunasan"* untuk mencatat pembayaran sisa DP di pangkalan
- [x] `7.6` Flash notification saat pelunasan berhasil dicatat

### Fase 8: Integrasi Payment Gateway Midtrans & Testing Akhir
- [x] `8.1` Pemasangan Midtrans Snap Token via Controller (`midtrans/midtrans-php` & Snap API)
- [x] `8.2` Webhook handler Midtrans dengan verifikasi tanda tangan SHA-512 dan mutasi status atomik
- [x] `8.3` Integrasi WhatsApp CS & konfirmasi e-tiket otomatis
- [x] `8.4` Pengujian alur transaksi di lingkungan lokal via PHPUnit Feature Test (100% Pass)
- [x] `8.5` Finalisasi repositori, dokumentasi, dan panduan pengoperasian Tugas Akhir
