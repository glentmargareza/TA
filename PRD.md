# Product Requirements Document (PRD)
## Platform Pemesanan Jeep Wukirsari Adventure Tour (Lereng Merapi, Sleman, Yogyakarta)

---

| Dokumen Versi | 1.2.0 (Penyelarasan Desain Resmi & Branding) |
| :--- | :--- |
| **Status** | Disetujui untuk Pengembangan (Development Ready) |
| **Brand Resmi** | **Jeep Wukirsari Adventure Tour** |
| **Lokasi Operasional** | Wukirsari, Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta |
| **Target Rilis MVP** | Q3 / Q4 2026 |
| **Pemilik Proyek** | Pengelola & Paguyuban Jeep Wukirsari |
| **Klasifikasi** | Dokumen Teknis & Produk Internal |

---

## 1. Ringkasan Eksekutif & Latar Belakang

### 1.1 Latar Belakang
**Jeep Wukirsari Adventure Tour** beroperasi di kawasan Wukirsari (Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta), tepat di kaki Gunung Merapi. Layanan ini menawarkan pengalaman wisata petualangan off-road menyusuri jalur lahar dingin, bebatuan vulkanik, bunker bersejarah, spot matahari terbit (*sunrise*), hingga sensasi manuver air di Sungai Kalikuning menggunakan armada tangguh Jeep 4x4 (Toyota Land Cruiser FJ40, Suzuki Jimny Katana, Mitsubishi Willys).

Untuk meningkatkan kenyamanan wisatawan dan efisiensi paguyuban, dibangun platform digital terpadu dengan desain bernuansa alam (*Lush Forest Green & Warm Cream*) yang selaras dengan citra keindahan alam lereng Merapi.

### 1.2 Tujuan Produk
1. Memfasilitasi reservasi online mandiri (*self-service booking*) melalui antarmuka web modern yang cepat dan responsif (dioptimalkan untuk pengguna smartphone).
2. Menampilkan katalog paket resmi (**Adventure**, **Lavature**, dan **Sunrise Tour**) dengan rincian durasi, fasilitas, dan harga per jeep yang transparan.
3. Menyediakan kalender ketersediaan armada *real-time* untuk mencegah penumpukan antrean atau *overbooking*.
4. Mengintegrasikan pembayaran digital otomatis (pilihan pembayaran penuh 100% atau Uang Muka / DP 30%-50%) melalui Payment Gateway (QRIS & Virtual Account).
5. Menerbitkan e-voucher QR Code instan yang dikirimkan langsung ke nomor WhatsApp wisatawan.
6. Menyediakan dasbor operasional bagi admin dan paguyuban untuk penugasan pengemudi (*driver dispatching*), scanner tiket check-in di loket basecamp, dan laporan pembukuan.

---

## 2. Rincian Paket Wisata Resmi (Sesuai Referensi Desain)

Katalog paket utama yang terpasang di sistem mengacu pada spesifikasi berikut:

| Nama Paket | Label / Badge | Estimasi Durasi | Kapasitas | Tarif Resmi | Rute & Atraksi Utama |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Adventure** | *Paling Populer* / *Paket Pendek* | 1.5 – 2.0 Jam | Maks. 4 Orang | **Rp 450.000** / Jeep | Jalur lava, Museum Sisa Hartaku, Batu Alien, dan manuver air Sungai Kalikuning. |
| **Lavature** | *Paket Sedang* | 2.0 – 2.5 Jam | Maks. 4 Orang | **Rp 550.000** / Jeep | Rute Adventure + Bunker Kaliadem & Petilasan Mbah Maridjan dengan jalur lebih menantang. |
| **Sunrise Tour** | *Paket Spesial Fajar* | 3.0 – 4.0 Jam (Mulai 04:30) | Maks. 4 Orang | **Rp 700.000** / Jeep | Menyaksikan matahari terbit di atas Gunung Merapi dari Bunker Kaliadem, udara sejuk fajar, & rute lengkap. |

---

## 3. Identitas Visual & Antarmuka Pengguna (UI/UX)

Sistem mengadopsi panduan desain dari **[DESIGN/STYLEGUIDE.md](file:///C:/xampp/htdocs/WebWukirsari/DESIGN/STYLEGUIDE.md)**:

* **Palet Warna Utama**:
  - `wukirsari-primary`: `#1E3F20` (Deep Forest Green - tombol utama, navbar badge, footer)
  - `wukirsari-dark`: `#142B16` (Latar belakang footer & banner kontras)
  - `wukirsari-sage`: `#E8EFE9` (Latar belakang bilah fitur & ikon squircle)
  - `wukirsari-cream`: `#F7F7F2` (Latar belakang canvas utama seluruh halaman)
  - `sunrise-gold`: `#F59E0B` (Aksen bintang ulasan & matahari fajar)
* **Tipografi**:
  - `Plus Jakarta Sans` / `Inter` untuk body teks, tombol, harga, dan formulir booking.
  - `Outfit` / `Plus Jakarta Sans Bold` untuk judul Display Hero & judul seksi.
  - Aksen kursif halus (*Playfair Italic*) pada sub-judul *"Petualangan di Lereng Merapi"* dan *"Mengapa Memilih"*.
* **Susunan Halaman Beranda (Landing Page)**:
  1. **Navbar**: Logo Jeep Wukirsari, Menu (Beranda, Paket Wisata, Tentang Kami, Galeri, Kontak), Tombol *"Booking Sekarang ->"*.
  2. **Hero Section**: Foto Gunung Merapi megah & Jeep 4x4 hijau, pill badge, judul hero, tombol ganda (*Booking Sekarang* & *Jelajahi Paket*), slider 01–05, pin lokasi *"📍 Wukirsari, Sleman, Yogyakarta"*.
  3. **Bilah Keunggulan (4 Fitur Bar)**: *Jeep 4x4*, *Driver Berpengalaman*, *Fasilitas Lengkap*, *Lokasi Strategis*.
  4. **Katalog Paket Wisata Populer**: 3 kartu paket (*Adventure*, *Lavature*, *Sunrise Tour*) lengkap dengan harga, durasi, dan aksi ganda.
  5. **Blok "Mengapa Memilih Jeep Wukirsari?"**: Kotak hijau gelap di kiri (*Tentang Kami ->*) dan 4 kartu fitur di kanan (*Harga Transparan*, *Pelayanan Ramah*, *Jalur Aman*, *Pengalaman Terbaik*).
  6. **Galeri Momen Indah**: Grid 5 foto dokumentasi rute lava tour.
  7. **Ulasan Wisatawan (Testimoni)**: Bintang emas 5.0, nama pemesan, asal kota, dan testimoni.
  8. **Footer**: Hijau tua dengan tautan navigasi, kontak telepon/email, dan media sosial resmi.

---

## 4. Alur Kerja Sistem yang Disempurnakan (User Flow)

```mermaid
flowchart TD
    A([Wisatawan Buka Web]) --> B[Lihat Paket: Adventure, Lavature, Sunrise]
    B --> C[Klik 'Booking Sekarang']
    C --> D[Pilih Tanggal & Sesi Jam: 04:30 / 07:00 / 09:30 / 13:00 / 15:30]
    
    D --> E{Cek Kuota Armada Real-time}
    E -- Kuota Penuh --> F[Tampilkan Rekomendasi Jam Lain]
    F --> D
    
    E -- Kuota Tersedia --> G[Input Jumlah Orang & Jeep]
    G --> H{Validasi: Pax <= Jeep * 4?}
    H -- Melebihi Kapasitas --> I[Saran Otomatis Tambah Jumlah Jeep]
    I --> G
    
    H -- Sesuai Aturan --> J{Pilih Kedatangan}
    J -- Datang ke Basecamp --> K[Isi Nama & WhatsApp Pemesan]
    J -- Layanan Antar-Jemput --> L[Pilih Zona Jemput: Cangkringan / Sleman-Jogja / YIA]
    L --> M[Kalkulasi Biaya Jemputan]
    M --> K
    
    K --> N[Review Ringkasan & Kunci Slot 15 Menit]
    N --> O{Pilihan Pembayaran}
    O -- Uang Muka DP 30% atau 50% --> P[Generate Tagihan Gateway DP]
    O -- Bayar Penuh 100% --> Q[Generate Tagihan Gateway Full]
    
    P & Q --> R[Bayar via QRIS / Virtual Account]
    R --> S{Verifikasi Webhook Midtrans}
    S -- Waktu Habis / Gagal --> T[Status EXPIRED, Lepas Kunci Slot]
    S -- Sukses --> U[Status PAID, Terbitkan E-Tiket QR Code]
    
    U --> V[Kirim E-Tiket Otomatis via WhatsApp & Email]
    V --> W[Hari-H: Wisatawan Datang ke Basecamp Wukirsari]
    W --> X[Kasir Basecamp Scan QR Code Tiket]
    
    X --> Y{Cek Status Pelunasan}
    Y -- Masih DP --> Z[Pelunasan Sisa di Loket: Cash / QRIS Kasir]
    Z --> AA[Penugasan Driver Paguyuban & Nomor Polisi Jeep]
    Y -- Sudah Lunas --> AA
    
    AA --> Tour([Mulai Petualangan Jeep Wukirsari!])
```

---

## 5. Rancangan Skema Basis Data Relasional (ERD)

```mermaid
erDiagram
    USERS ||--o{ BOOKINGS : "places"
    PACKAGES ||--o{ BOOKINGS : "booked_in"
    SCHEDULE_SLOTS ||--o{ BOOKINGS : "scheduled_at"
    BOOKINGS ||--o{ PAYMENTS : "has"
    BOOKINGS ||--o| PICKUP_DETAILS : "includes"
    PICKUP_ZONES ||--o{ PICKUP_DETAILS : "categorized_by"
    BOOKINGS ||--o{ BOOKING_ASSIGNMENTS : "assigned_to"
    JEEPS ||--o{ BOOKING_ASSIGNMENTS : "jeep_used"
    DRIVERS ||--o{ BOOKING_ASSIGNMENTS : "driver_assigned"

    USERS {
        uuid id PK
        string full_name
        string phone_number "WhatsApp aktif pemesan"
        string email
        string role "customer | admin | cashier"
        string password_hash
        datetime created_at
    }

    PACKAGES {
        uuid id PK
        string name "Adventure | Lavature | Sunrise Tour"
        string slug
        text description
        decimal price_per_jeep "450000 | 550000 | 700000"
        int duration_minutes "90 | 150 | 210"
        string category_badge "Paling Populer | Paket Pendek | Sunrise"
        string cover_image_url
        boolean is_active
    }

    SCHEDULE_SLOTS {
        uuid id PK
        time start_time
        time end_time
        string slot_label "Sunrise (04:30) | Pagi | Siang | Sore"
        int max_jeeps_capacity
        boolean is_active
    }

    BOOKINGS {
        uuid id PK
        string booking_code UK "Contoh: WKR-20261024-001"
        uuid user_id FK
        uuid package_id FK
        uuid slot_id FK
        date trip_date
        int jeep_quantity
        int passenger_count
        string arrival_type "self_drive | pickup"
        decimal package_subtotal
        decimal pickup_fee
        decimal total_amount
        decimal paid_amount
        decimal remaining_amount
        string payment_mode "DP | FULL"
        string status "PENDING | DP_PAID | FULL_PAID | CANCELLED | COMPLETED"
        datetime lock_expires_at
        datetime created_at
    }

    PICKUP_ZONES {
        uuid id PK
        string zone_name "Lokal Cangkringan | Kota Jogja & Sleman | Bandara YIA"
        decimal additional_fee
        text area_description
    }

    PICKUP_DETAILS {
        uuid id PK
        uuid booking_id FK
        uuid zone_id FK
        text pickup_address
        string contact_person
        string contact_phone
    }

    PAYMENTS {
        uuid id PK
        uuid booking_id FK
        string payment_gateway_ref
        string payment_stage "DOWN_PAYMENT | SETTLEMENT | FULL_PAYMENT"
        decimal amount
        string payment_method "QRIS | VA_BCA | CASH_BASECAMP"
        string status "PENDING | SETTLED | EXPIRED | FAILED"
        datetime paid_at
    }

    JEEPS {
        uuid id PK
        string plate_number "Contoh: AB 4567 XX"
        string model_name "Toyota Land Cruiser FJ40 / Suzuki Jimny"
        string status "READY | MAINTENANCE | ON_TRIP"
    }

    DRIVERS {
        uuid id PK
        string full_name
        string phone_number
        string license_number
        boolean is_active
    }

    BOOKING_ASSIGNMENTS {
        uuid id PK
        uuid booking_id FK
        uuid jeep_id FK
        uuid driver_id FK
        datetime assigned_at
    }
```

---

## 6. Fitur Administrasi & Operasional Paguyuban

1. **Manajemen Armada & Giliran Pengemudi (Dispatching)**:
   - Pengelola dapat menugaskan nomor polisi mobil dan pengemudi lokal Wukirsari ke setiap pesanan yang telah berstatus `PAID` / `DP_PAID`.
   - Sistem rotasi antrean untuk menjamin keadilan bagi seluruh anggota paguyuban.
2. **Scanner Loket Basecamp**:
   - Petugas kasir loket memindai QR Code e-voucher wisatawan saat tiba di lokasi.
   - Jika wisatawan baru membayar DP, sistem otomatis menampilkan nominal sisa tagihan untuk dilunasi (Tunai atau QRIS Loket).
   - Tombol satu klik: *"Pelunasan Diterima"*, status berubah menjadi `FULL_PAID`.
3. **Peringatan Kondisi Cuaca & Status Merapi**:
   - Tombol mitigasi darurat cuaca (*Force Majeure*): Memungkinkan admin menjadwalkan ulang (*reschedule*) atau membatalkan trip dengan pengembalian dana (*refund*) resmi jika terjadi hujan badai atau peningkatan status aktivitas vulkanik.
