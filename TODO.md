# Task Checklist & Execution Roadmap (TODO.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Vite + React + TS)

> **Catatan untuk AI Agent**: Proyek ini dibangun sepenuhnya menggunakan **Vite (`React + Vite + TypeScript`)**. File ini adalah acuan kerja berurutan (*execution backlog*). Tandai `[x]` setiap kali satu sub-tugas selesai diverifikasi dan diuji. Desain visual wajib mengacu pada referensi resmi di `DESIGN/STYLEGUIDE.md`.

---

## Progress Overview
- [x] **Fase 0: Inisialisasi Proyek Vite & Konfigurasi Styling** (5/5)
- [ ] **Fase 1: Routing & Fondasi Komponen (React Router DOM)** (0/5)
- [ ] **Fase 2: Mock Data & State Management (Zustand)** (0/5)
- [ ] **Fase 3: Antarmuka Publik Sesuai Mockup Desain Resmi** (0/8)
- [ ] **Fase 4: Alur Reservasi & Formulir Cerdas** (0/6)
- [ ] **Fase 5: Integrasi Midtrans Snap JS (DP & Full Payment)** (0/6)
- [ ] **Fase 6: Tiket QR Code & E-Voucher Digital** (0/5)
- [ ] **Fase 7: Dashboard Admin & Kasir Loket Basecamp** (0/6)
- [ ] **Fase 8: Build Produksi XAMPP (dist/), Audit Mobile & Finalisasi** (0/5)

---

## Rincian Tugas per Fase

### Fase 0: Inisialisasi Proyek Vite & Konfigurasi Styling
- [x] `0.1` Inisialisasi proyek Vite dengan template React TypeScript: `npm create vite@latest . -- --template react-ts`
- [x] `0.2` Instalasi dependensi styling: `npm install -D tailwindcss postcss autoprefixer` dan jalankan `npx tailwindcss init -p`
- [x] `0.3` Konfigurasi tema Tailwind di `tailwind.config.js` sesuai warna resmi di `DESIGN/STYLEGUIDE.md` (`#1E3F20` Deep Forest Green, `#F7F7F2` Cream Canvas, `#E8EFE9` Sage, `#F59E0B` Gold)
- [x] `0.4` Instalasi ikon & utilitas: `npm install lucide-react clsx tailwind-merge framer-motion`
- [x] `0.5` Konfigurasi path alias `@/*` pada `vite.config.ts` dan `tsconfig.json`

### Fase 1: Routing & Fondasi Komponen (React Router DOM)
- [ ] `1.1` Instalasi router: `npm install react-router-dom`
- [ ] `1.2` Setup struktur halaman di `src/pages/` (`HomePage`, `PackageDetailPage`, `BookingPage`, `BookingSuccessPage`, `CheckTicketPage`, `AdminDashboardPage`)
- [ ] `1.3` Konfigurasi rute utama di `src/App.tsx` dengan layout responsif
- [ ] `1.4` Buat layout wrapper dasar: `MainLayout` (Navbar + Konten + Footer) dan `AdminLayout`
- [ ] `1.5` Setup font lokal/Google Fonts (`Plus Jakarta Sans`, `Outfit`, `Playfair Display`) pada `index.html`

### Fase 2: Mock Data & State Management (Zustand)
- [ ] `2.1` Instalasi store: `npm install zustand`
- [ ] `2.2` Buat modul data statis paket resmi di `src/data/packages.ts`:
  - **Adventure**: Rp 450.000 / Jeep, 1.5–2 Jam, badge: *Paling Populer*
  - **Lavature**: Rp 550.000 / Jeep, 2–2.5 Jam
  - **Sunrise Tour**: Rp 700.000 / Jeep, 3–4 Jam, mulai pukul 04:30 WIB
- [ ] `2.3` Buat data sesi jam operasional (Sunrise 04:30, Pagi 07:00/09:30, Siang 13:00, Sore 15:30) dan zona jemputan
- [ ] `2.4` Buat store global di `src/store/bookingStore.ts` untuk mengelola pilihan paket, tanggal, pax, armada jeep, dan status DP/Full
- [ ] `2.5` Fungsi kalkulasi kuota & aturan kapasitas: memastikan `pax <= jeep * 4`

### Fase 3: Antarmuka Publik Sesuai Mockup Desain Resmi
- [ ] `3.1` **Navbar Komponen** (`src/components/layout/Navbar.tsx`): Logo "JEEP WUKIRSARI", menu navigasi, search icon, dan tombol pill *"Booking Sekarang ->"*
- [ ] `3.2` **Hero Section** (`src/components/home/HeroSection.tsx`):
  - Pill badge: *"⛰️ Petualangan di Lereng Merapi"*
  - Headline: *"Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari"*
  - Dual CTA: *"Booking Sekarang ->"* (Solid Green) & *"Jelajahi Paket"* (Glass Outline)
  - Slider indicator (01–05) & Pin Lokasi *"📍 Wukirsari, Sleman, Yogyakarta"*
- [ ] `3.3` **Bilah Nilai Tambah (4 Fitur Bar)** (`src/components/home/ValueBar.tsx`): 4 kartu squircle hijau sage (`Jeep 4x4`, `Driver Berpengalaman`, `Fasilitas Lengkap`, `Lokasi Strategis`)
- [ ] `3.4` **Katalog Paket Wisata Populer** (`src/components/home/PackageSection.tsx`):
  - 3 kartu paket (*Adventure*, *Lavature*, *Sunrise Tour*)
  - Badge *"Paling Populer"*, durasi jam, batas kapasitas, tarif tebal, tombol *"Lihat Detail ->"* dan *"Booking Sekarang"*
- [ ] `3.5` **Blok "Mengapa Memilih Jeep Wukirsari?"** (`src/components/home/WhyUsSection.tsx`):
  - Kotak hijau gelap di kiri dengan tombol putih *"Tentang Kami ->"*
  - 4 kartu frosted di kanan (*Harga Transparan*, *Pelayanan Ramah*, *Jalur Aman*, *Pengalaman Terbaik*)
- [ ] `3.6` **Galeri Momen Indah** (`src/components/home/GallerySection.tsx`): Grid 5 foto petualangan jeep lava tour
- [ ] `3.7` **Ulasan Wisatawan (Testimoni)** (`src/components/home/TestimonialSection.tsx`): Kartu testimoni putih bersih dengan 5 bintang emas `#F59E0B`, avatar, nama, dan asal kota
- [ ] `3.8` **Footer Hijau Hutan Tua** (`src/components/layout/Footer.tsx`): Brand, tagline, navigasi, nomor telepon (+62 812), dan media sosial
- [ ] `3.9` **Mobile Sticky Bottom Bar**: Tombol aksi melayang di bagian bawah layar smartphone untuk reservasi instan

### Fase 4: Alur Reservasi & Formulir Cerdas
- [ ] `4.1` Komponen kalender tanggal interaktif dengan indikator visual ketersediaan
- [ ] `4.2` Komponen pemilihan sesi jam dengan info sisa kuota armada
- [ ] `4.3` Stepper formulir booking interaktif:
  - Step 1: Pemilihan paket & jadwal
  - Step 2: Jumlah orang (pax) & rekomendasi jumlah jeep otomatis
  - Step 3: Opsi kedatangan (Datang Sendiri vs Jemputan Shuttle per Zona)
  - Step 4: Data pemesan (Nama, No WhatsApp, Email)
- [ ] `4.4` Validasi form menggunakan `react-hook-form` + `zod`
- [ ] `4.5` Ringkasan rincian tagihan (subtotal paket, biaya jemputan, pilihan DP 30% / 50% vs Full 100%)
- [ ] `4.6` Kunci slot sementara (15 menit countdown timer) saat masuk ke tahap pembayaran

### Fase 5: Integrasi Midtrans Snap JS (DP & Full Payment)
- [ ] `5.1` Pemasangan script Midtrans Snap (`https://app.sandbox.midtrans.com/snap/snap.js`) pada `index.html`
- [ ] `5.2` Service integrasi Snap Popup pembayaran di `src/services/midtrans.ts`
- [ ] `5.3` Opsi pembayaran QRIS (GoPay, ShopeePay, BCA), Virtual Account, & E-Wallet
- [ ] `5.4` Penanganan callback status transaksi (`onSuccess`, `onPending`, `onError`, `onClose`)
- [ ] `5.5` Transisi status pesanan menjadi `DP_PAID` atau `FULL_PAID`
- [ ] `5.6` Halaman sukses booking (`/booking/success`) dengan ringkasan booking code

### Fase 6: Tiket QR Code & E-Voucher Digital
- [ ] `6.1` Instalasi generator QR Code: `npm install qrcode.react`
- [ ] `6.2` Desain tiket elektronik / boarding pass digital bertema hijau hutan di `src/components/tickets/TicketCard.tsx`
- [ ] `6.3` Menampilkan status cap lunas atau status DP dengan nominal sisa yang harus dibayar di lokasi
- [ ] `6.4` Tombol berbagi tiket ke WhatsApp & unduh tiket format gambar/cetak
- [ ] `6.5` Halaman pencarian tiket mandiri (`/cek-tiket`) berdasarkan nomor WhatsApp atau kode booking

### Fase 7: Dashboard Admin & Kasir Loket Basecamp
- [ ] `7.1` Halaman overview operasional harian: total jeep bertugas hari ini & status pesanan
- [ ] `7.2` Tabel manajemen reservasi dengan filter status (Pending, DP Paid, Full Paid, Selesai)
- [ ] `7.3` Fitur Dispatching: input nomor polisi jeep dan nama driver paguyuban
- [ ] `7.4` Scanner QR Loket Basecamp (menggunakan pustaka kamera HTML5 `html5-qrcode`)
- [ ] `7.5` Fitur pelunasan kasir basecamp (tombol satu klik: terima pelunasan sisa DP secara Tunai/QRIS Kasir)
- [ ] `7.6` Ekspor data pemesanan ke format CSV/Excel untuk pembukuan paguyuban

### Fase 8: Build Produksi XAMPP (dist/), Audit Mobile & Finalisasi
- [ ] `8.1` Jalankan kompilasi produksi: `npm run build` dan verifikasi folder `dist/` terbentuk tanpa error
- [ ] `8.2` Konfigurasi file `.htaccess` di dalam folder agar *client-side routing* React Router DOM berfungsi mulus di Apache XAMPP
- [ ] `8.3` Audit performa di smartphone via Lighthouse (Target: Skor performa > 90)
- [ ] `8.4` Pengujian responsivitas pada resolusi 360px, 390px, 768px, dan 1440px
- [ ] `8.5` Dokumentasi serah terima sistem (*README.md*) untuk operator lokal Wukirsari
