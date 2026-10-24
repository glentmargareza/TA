# Platform Pemesanan Jeep Wukirsari Adventure Tour 🚙⛰️
> **Sistem Reservasi Wisata Petualangan Jeep Lereng Gunung Merapi Berbasis Web Mobile-First**

Proyek Tugas Akhir (TA): Sistem Informasi & E-Booking Wisata Jeep Wukirsari (Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta).

---

## 📌 Ringkasan Proyek

Platform ini dirancang untuk mempermudah calon wisatawan dalam memesan paket petualangan jeep off-road menyusuri kawasan lereng Gunung Merapi secara mandiri (*self-service*), transparan, dan terintegrasi pembayaran digital. Sistem ini juga berfungsi sebagai dasbor operasional paguyuban pengemudi jeep lokal untuk penugasan armada (*dispatching*), pengecekan tiket QR di loket basecamp, serta pembukuan keuangan.

### Fitur Utama:
1. **Katalog Paket Wisata Khas Merapi**:
   - 🌟 **Adventure** (Rp 450.000 / Jeep - 1.5 s/d 2 Jam, Jalur Lava & Susur Sungai Kalikuning)
   - 🌟 **Lavature** (Rp 550.000 / Jeep - 2 s/d 2.5 Jam, Bunker Kaliadem & Petilasan Mbah Maridjan)
   - 🌟 **Sunrise Tour** (Rp 700.000 / Jeep - 3 s/d 4 Jam, Fajar Bunker Kaliadem mulai 04:30 WIB)
2. **Mesin Ketersediaan Slot & Kuota Real-time**:
   - Pengecekan armada aktif per tanggal dan slot jam.
   - Mekanisme kunci slot sementara (15 menit) untuk mencegah *double-booking* (*race condition*).
3. **Kalkulator Kapasitas Penumpang Cerdas**:
   - Kapasitas standar keamanan: **Maksimal 4 orang per Jeep**.
   - Rekomendasi otomatis jumlah jeep berdasarkan jumlah peserta.
4. **Integrasi Pembayaran Online (Midtrans Payment Gateway)**:
   - Dukungan pembayaran uang muka (**DP 30% atau 50%**) dan pembayaran penuh (**100% Full Payment**).
   - Metode bayar: QRIS (GoPay, ShopeePay, BCA), Virtual Account (BCA, BRI, BNI, Mandiri).
5. **E-Tiket QR Code & Notifikasi WhatsApp**:
   - Tiket digital otomatis terbit setelah pembayaran terverifikasi.
   - Pengiriman e-voucher ber-QR Code via WhatsApp Gateway (Fonnte).
6. **Dashboard Admin & Kasir Loket Basecamp**:
   - Scanner kamera HP untuk verifikasi tiket dan pelunasan sisa DP saat wisatawan tiba di pangkalan.
   - Penugasan nomor polisi jeep dan nama driver paguyuban.
   - Laporan omzet dan rekapitulasi komisi pengemudi.

---

## 🎨 Desain Sistem & Identitas Visual

Mengacu pada panduan resmi di `DESIGN/STYLEGUIDE.md`:
* **Deep Forest Green (`#1E3F20`)**: Warna utama brand, tombol navigasi & CTA utama.
* **Warm Cream Canvas (`#F7F7F2`)**: Latar belakang halaman bersih dan elegan.
* **Sage Tint (`#E8EFE9`)**: Latar belakang fitur keunggulan dan badge.
* **Sunrise Gold (`#F59E0B`)**: Bintang rating ulasan pelanggan dan aksen fajar.
* **Tipografi**: Plus Jakarta Sans / Inter & Outfit (Heading) dengan aksen kursif Playfair Display.

---

## 📂 Struktur Dokumentasi Proyek

Direktori ini dilengkapi dengan dokumen panduan pengembangan:

| File | Deskripsi |
| :--- | :--- |
| **`PRD.md`** | *Product Requirements Document*: Spesifikasi bisnis, analisis alur, dan skema ERD (9 tabel). |
| **`TODO.md`** | *Task Checklist Backlog*: 9 fase pengembangan bertahap dari inisialisasi hingga rilis. |
| **`DESIGN/STYLEGUIDE.md`** | *Design Tokens*: Palet warna resmi, tipografi, dan tata letak responsif ponsel & desktop. |
| **`TECHSTACK.md`** | *Arsitektur Teknologi*: Spesifikasi pustaka, routing, dan alur build. |
| **`SECURITY.md`** | *Protokol Keamanan*: Validasi hash SHA-512 webhook Midtrans dan anti-overbooking. |
| **`AGENT.md`** | *Directives AI Agent*: Aturan kerja, batasan sistem, dan SOP coding AI. |
| **`SKILL.md`** | *Development Cheatsheet*: Perintah terminal dan checklist pengujian *Definition of Done*. |

---

## 🚀 Panduan Memulai (*Quick Start*)

### Prasyarat:
- Node.js (v18 atau lebih baru)
- Git
- XAMPP (Apache & MySQL)

### Instalasi & Menjalankan Frontend:
```bash
# 1. Masuk ke direktori proyek
cd C:\xampp\htdocs\WebWukirsari

# 2. Pasang dependensi
npm install

# 3. Jalankan server pengembangan lokal
npm run dev
```

Buka peramban di `http://localhost:3000` (atau port yang ditampilkan di terminal).

---

## 👨‍💻 Kontributor
- **Pengembang**: Glent Margareza
- **Institusi**: Tugas Akhir (TA) - Sistem Informasi / Teknik Informatika
- **Repository**: [https://github.com/glentmargareza/TA.git](https://github.com/glentmargareza/TA.git)

---
*© 2026 Jeep Wukirsari Adventure Tour. Hak cipta dilindungi undang-undang.*
