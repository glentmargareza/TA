# Task Checklist & Execution Roadmap (TODO.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Tugas Akhir)

> **Catatan untuk AI Agent**: File ini adalah acuan kerja berurutan (*execution backlog*). Tandai `[x]` setiap kali satu sub-tugas selesai diverifikasi dan diuji. Desain visual wajib mengacu pada referensi resmi di `DESIGN/STYLEGUIDE.md`.

---

## Progress Overview
- [x] **Fase 0: Inisialisasi Proyek Vite & Konfigurasi Styling** (5/5)
- [x] **Fase 1: Routing & Fondasi Komponen (React Router DOM)** (5/5)
- [ ] **Fase 2: Mock Data & State Management (Zustand)** (0/5)
- [x] **Fase 3: Antarmuka Publik Sesuai Mockup Desain Resmi** (8/8)
- [x] **Fase 4: Alur Reservasi & Formulir Cerdas** (6/6)
- [ ] **Fase 5: Integrasi Midtrans Snap JS (DP & Full Payment)** (0/6)
- [x] **Fase 6: Tiket QR Code & E-Voucher Digital** (5/5)
- [x] **Fase 7: Dashboard Admin & Kasir Loket Basecamp** (6/6)
- [ ] **Fase 8: Integrasi Backend PHP MySQL, Audit Mobile & Finalisasi** (0/5)

---

## Rincian Tugas per Fase

### Fase 0: Inisialisasi Proyek Vite & Konfigurasi Styling
- [x] `0.1` Inisialisasi proyek Vite dengan template React TypeScript: `npm create vite@latest . -- --template react-ts`
- [x] `0.2` Instalasi dependensi styling: `tailwindcss`, `postcss`, dan `autoprefixer`
- [x] `0.3` Konfigurasi tema Tailwind di `tailwind.config.js` sesuai warna resmi di `DESIGN/STYLEGUIDE.md` (`#1E3F20` Deep Forest Green, `#F7F7F2` Cream Canvas, `#E8EFE9` Sage, `#F59E0B` Gold)
- [x] `0.4` Instalasi ikon & utilitas: `lucide-react`, `clsx`, `tailwind-merge`, `framer-motion`
- [x] `0.5` Konfigurasi path alias `@/*` pada `vite.config.ts`, `tsconfig.json`, dan `tsconfig.app.json`

### Fase 1: Routing & Fondasi Komponen (React Router DOM)
- [x] `1.1` Instalasi router: `npm install react-router-dom`
- [x] `1.2` Setup struktur halaman di `src/pages/` (`HomePage`, `PackageDetailPage`, `BookingPage`, `BookingSuccessPage`, `CheckTicketPage`, `AdminDashboardPage`)
- [x] `1.3` Konfigurasi rute utama di `src/App.tsx` dengan layout responsif
- [x] `1.4` Buat layout wrapper dasar: `MainLayout` (Navbar + Konten + Footer) dan `AdminLayout`
- [x] `1.5` Setup Google Fonts (`Plus Jakarta Sans`, `Outfit`, `Playfair Display`) pada `index.html`

### Fase 2: Mock Data & State Management (Zustand)
- [ ] `2.1` Instalasi store: `npm install zustand`
- [ ] `2.2` Buat modul data statis paket resmi di `src/data/packages.ts`:
  - **Adventure**: Rp 450.000 / Jeep, 1.5–2 Jam, badge: *Paling Populer*
  - **Lavature**: Rp 550.000 / Jeep, 2–2.5 Jam
  - **Sunrise Tour**: Rp 700.000 / Jeep, 3–4 Jam, mulai pukul 04:30 WIB
- [ ] `2.3` Buat data sesi jam operasional (Sunrise 04:30, Pagi 08:00/10:30, Siang 13:30) dan zona jemputan
- [ ] `2.4` Buat store global di `src/store/bookingStore.ts` untuk mengelola pilihan paket, tanggal, pax, armada jeep, dan status DP/Full
- [ ] `2.5` Fungsi kalkulasi kuota & aturan kapasitas: memastikan `pax <= jeep * 4`

### Fase 3: Antarmuka Publik Sesuai Mockup Desain Resmi
- [x] `3.1` **Navbar Komponen** (`src/components/layout/Navbar.tsx`): Logo "JEEP WUKIRSARI", menu navigasi, dan tombol pill *"Booking Sekarang ->"*
- [x] `3.2` **Hero Section** (`src/components/home/HeroSection.tsx`):
  - Pill badge: *"⛰️ Petualangan di Lereng Merapi"*
  - Headline: *"Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari"*
  - Dual CTA: *"Booking Sekarang ->"* (Solid Green) & *"Jelajahi Paket"* (Glass Outline)
  - Slider indicator (01–05) & Pin Lokasi *"📍 Wukirsari, Sleman, Yogyakarta"*
- [x] `3.3` **Bilah Nilai Tambah (4 Fitur Bar)** (`src/components/home/ValueBar.tsx`): 4 kartu squircle hijau sage (`Jeep 4x4`, `Driver Berpengalaman`, `Fasilitas Lengkap`, `Lokasi Strategis`)
- [x] `3.4` **Katalog Paket Wisata Populer** (`src/components/home/PackageSection.tsx`):
  - 3 kartu paket (*Adventure*, *Lavature*, *Sunrise Tour*)
  - Badge *"Paling Populer"*, durasi jam, batas kapasitas, tarif tebal, tombol *"Lihat Detail ->"* dan *"Booking Sekarang"*
- [x] `3.5` **Blok "Mengapa Memilih Jeep Wukirsari?"** (`src/components/home/WhyUsSection.tsx`):
  - Kotak hijau gelap di kiri dengan tombol putih *"Tentang Kami ->"*
  - 4 kartu frosted di kanan (*Harga Transparan*, *Pelayanan Ramah*, *Jalur Aman*, *Pengalaman Terbaik*)
- [x] `3.6` **Galeri Momen Indah** (`src/components/home/GallerySection.tsx`): Grid 5 foto petualangan jeep lava tour
- [x] `3.7` **Ulasan Wisatawan (Testimoni)** (`src/components/home/TestimonialSection.tsx`): Kartu testimoni putih bersih dengan 5 bintang emas `#F59E0B`, avatar, nama, dan asal kota
- [x] `3.8` **Footer Hijau Hutan Tua** (`src/components/layout/Footer.tsx`): Brand, tagline, navigasi, nomor telepon (+62 812), dan media sosial
- [x] `3.9` **Mobile Sticky Bottom Bar**: Tombol aksi melayang di bagian bawah layar smartphone untuk reservasi instan

### Fase 4: Alur Reservasi & Formulir Cerdas
- [x] `4.1` Komponen tanggal & sesi jam trip
- [x] `4.2` Stepper formulir booking interaktif (Pilih paket, jadwal, pax, armada, penjemputan, kontak)
- [x] `4.3` Validasi aturan keselamatan: otomatis merekomendasikan jumlah jeep `ceil(pax / 4)`
- [x] `4.4` Opsi kedatangan: Datang Sendiri (Rp 0) vs Shuttle per Zona (Lokal Cangkringan, Sleman/Jogja Kota, Bandara YIA)
- [x] `4.5` Ringkasan rincian tagihan (subtotal paket, biaya jemputan, pilihan DP 30% vs Full 100%)
- [x] `4.6` Perhitungan nominal wajib dibayar sekarang dan sisa tagihan di loket

### Fase 5: Integrasi Midtrans Snap JS (DP & Full Payment)
- [ ] `5.1` Pemasangan script Midtrans Snap (`https://app.sandbox.midtrans.com/snap/snap.js`) pada `index.html`
- [ ] `5.2` Service integrasi Snap Popup pembayaran di `src/services/midtrans.ts`
- [ ] `5.3` Opsi pembayaran QRIS (GoPay, ShopeePay, BCA), Virtual Account, & E-Wallet
- [ ] `5.4` Penanganan callback status transaksi (`onSuccess`, `onPending`, `onError`, `onClose`)
- [ ] `5.5` Endpoint PHP verifikasi webhook Midtrans di `api/payments/webhook.php`
- [ ] `5.6` Transisi status pesanan menjadi `DP_PAID` atau `FULL_PAID`

### Fase 6: Tiket QR Code & E-Voucher Digital
- [x] `6.1` Komponen E-Voucher / Boarding Pass digital bertema hijau hutan di `src/pages/BookingSuccessPage.tsx`
- [x] `6.2` Garis perforasi estetis & Kode Booking unik (`WKR-XXXXXX`)
- [x] `6.3` Menampilkan status cap lunas atau status DP dengan nominal sisa yang harus dibayar di lokasi
- [x] `6.4` Tombol berbagi tiket ke WhatsApp CS & tombol cetak/simpan tiket
- [x] `6.5` Halaman pencarian tiket mandiri (`/cek-tiket`) berdasarkan nomor WhatsApp atau kode booking

### Fase 7: Dashboard Admin & Kasir Loket Basecamp
- [x] `7.1` Halaman overview operasional harian: total omzet terverifikasi, jeep siap berangkat, dan booking pending
- [x] `7.2` Tabel manajemen reservasi dengan filter status (Pending, DP Paid, Full Paid, Selesai)
- [x] `7.3` Penugasan nomor polisi jeep dan nama driver paguyuban
- [x] `7.4` Tombol kasir loket: terima pelunasan sisa DP dengan satu klik
- [x] `7.5` Navigasi terpisah di `/admin` dengan layout sidebar gelap eksklusif
- [x] `7.6` Tombol aktivasi scanner kamera loket basecamp

### Fase 8: Integrasi Backend PHP MySQL, Audit Mobile & Finalisasi
- [ ] `8.1` Hubungkan form booking frontend ke endpoint `api/bookings/create.php`
- [ ] `8.2` Jalankan import `api/database.sql` ke MySQL di phpMyAdmin XAMPP
- [ ] `8.3` Audit performa di smartphone via Lighthouse (Target: Skor performa > 90)
- [ ] `8.4` Pengujian responsivitas pada resolusi 360px, 390px, 768px, dan 1440px
- [ ] `8.5` Dokumentasi serah terima sistem (*README.md*) untuk operator lokal Wukirsari
