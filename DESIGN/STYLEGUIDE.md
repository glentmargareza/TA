# Design System & UI/UX Style Guide (DESIGN/STYLEGUIDE.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Lereng Merapi)

> **Dokumen Resmi Panduan Desain Antarmuka (UI/UX)**: Dokumen ini disusun berdasarkan referensi desain resmi visual web *Jeep Wukirsari Adventure Tour*. Seluruh pengembang dan AI Agent **WAJIB** menerapkan palet warna, tipografi, tata letak (*layout*), dan gaya komponen sesuai spesifikasi di bawah ini.

---

## 1. Filosofi Desain & Karakter Visual

* **Tema Visual**: *Lush Nature, Mountain Adventure, Eco-Tourism Elegance, & Premium Outdoor*.
* **Karakter Tone**: Menghadirkan kesejukan lereng Gunung Merapi di kawasan Wukirsari/Cangkringan dengan perpaduan **Deep Pine Forest Green** (hijau hutan pinus), **Sage Cream Canvas** (krem lembut alami), dan **Golden Sunrise Glow** (aksen matahari fajar).
* **Prinsip Tampilan**: Bersih (*clean*), luas (*spacious*), fotografi alam beresolusi tinggi, kartu membulat modern (*soft rounded corners*), serta mudah dioperasikan di layar ponsel (*Mobile-First*) maupun desktop.

---

## 2. Palet Warna Resmi (Official Color Palette)

```
+-----------------------------------------------------------------------------------------+
|                    PALET WARNA RESMI JEEP WUKIRSARI ADVENTURE TOUR                     |
+-----------------------------------------------------------------------------------------+
| [Deep Forest Green]       #1E3F20 (rgb 30, 63, 32)   -> Warna Brand Utama, Tombol CTA   |
| [Forest Dark / Footer]    #142B16 (rgb 20, 43, 22)   -> Background Footer & Dark Banner |
| [Forest Hover]            #265029 (rgb 38, 80, 41)   -> Interaksi Hover Tombol Primary  |
| [Sage Tint / Soft Light]  #E8EFE9 (rgb 232, 239, 233)-> Background Fitur Bar & Badges  |
| [Warm Cream Canvas]       #F7F7F2 (rgb 247, 247, 242)-> Background Halaman Utama       |
| [Pure White Surface]      #FFFFFF                    -> Latar Kartu Paket, Testimoni    |
| [Deep Charcoal Text]      #19231A (rgb 25, 35, 26)   -> Teks Judul (Heading H1/H2/H3)   |
| [Muted Forest Gray]       #526054 (rgb 82, 96, 84)   -> Teks Deskripsi & Metadata       |
| [Sunrise Gold / Stars]    #F59E0B / #EAB308          -> Bintang Testimoni & Aksen Fajar |
| [Border Soft Sage]        #E2EAE3 (rgb 226, 234, 227)-> Garis Pemisah & Border Kartu    |
+-----------------------------------------------------------------------------------------+
```

### Konfigurasi Tailwind CSS (`tailwind.config.ts`)
```typescript
// tailwind.config.ts
export default {
  theme: {
    extend: {
      colors: {
        wukirsari: {
          primary: '#1E3F20',    // Deep Forest Green (Tombol Utama & Brand)
          dark: '#142B16',       // Footer & Dark Container
          hover: '#265029',      // Hover State
          sage: '#E8EFE9',       // Background Fitur & Pill
          cream: '#F7F7F2',      // Warm Background Utama
          border: '#E2EAE3',     // Soft Border
        },
        merapiText: {
          primary: '#19231A',    // Judul & Teks Kontras Tinggi
          secondary: '#526054',  // Deskripsi & Caption
          muted: '#7E8E81',      // Sub-label & Placeholder
        },
        sunrise: {
          gold: '#F59E0B',       // Aksen Bintang & Golden Hour
          amber: '#D97706',
        }
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
        'pill': '9999px',
      }
    }
  }
}
```

---

## 3. Tipografi & Gaya Tulisan (Typography System)

* **Font Utama (UI, Body, Buttons, Metadata)**: `Plus Jakarta Sans` atau `Inter`
* **Font Display / Heading (Judul Hero & Judul Seksi)**: `Outfit` atau `Plus Jakarta Sans` (Bobot `font-bold` / `font-extrabold`)
* **Font Aksen Halus (Sub-heading & Script/Italic Tagline)**: `Playfair Display` (Italic) atau `Caveat` untuk teks seperti *"Petualangan di Lereng Merapi"* dan *"Mengapa Memilih"*

| Level Teks | Mobile (<768px) | Desktop (>=1024px) | Bobot | Warna | Karakteristik |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Hero Title** | `text-2xl (28px)` | `text-5xl (48px)` | Extrabold (800) | `#FFFFFF` | Line-height `1.15`, text shadow halus |
| **Section Title** | `text-xl (24px)` | `text-3xl (32px)` | Bold (700) | `#19231A` | Margin bottom rapat |
| **Card Title** | `text-lg (18px)` | `text-xl (20px)` | SemiBold (600) | `#19231A` | Contoh: *"Adventure"*, *"Lavature"* |
| **Price Tag** | `text-base (16px)` | `text-lg (18px)` | Bold (700) | `#19231A` | Contoh: *"Rp 450.000 / Jeep"* |
| **Body / Paragraf** | `text-sm (14px)` | `text-base (15px)` | Normal (400) | `#526054` | Line-height `1.6` |
| **Metadata / Badge**| `text-xs (12px)` | `text-xs (12px)` | Medium (500) | `#526054` | Durasi jam & batas kapasitas |

---

## 4. Rincian Tata Letak & Blok Antarmuka (Layout Structure)

Berdasarkan referensi desain resmi, halaman utama tersusun dari 7 blok utama:

```
+-----------------------------------------------------------------------------------------+
| 1. NAVBAR : [Logo Jeep Wukirsari]  Beranda  Paket  Tentang  Galeri  Kontak  [Booking ->]|
+-----------------------------------------------------------------------------------------+
| 2. HERO SECTION :                                                                       |
|    - Sub-badge: "⛰️ Petualangan di Lereng Merapi"                                       |
|    - Headline: "Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari"                  |
|    - Deskripsi & Dual CTA: [Booking Sekarang ->]  [Jelajahi Paket]                      |
|    - Slider dots (01 02 03 04 05) & Badge Lokasi "📍 Wukirsari, Sleman, Yogyakarta"     |
+-----------------------------------------------------------------------------------------+
| 3. VALUE BAR (4 Fitur): [Jeep 4x4] [Driver Berpengalaman] [Fasilitas] [Lokasi Strategis]|
+-----------------------------------------------------------------------------------------+
| 4. PAKET WISATA POPULER (3 Kartu):                                                      |
|    - [Adventure - Rp 450.000]   [Lavature - Rp 550.000]   [Sunrise Tour - Rp 700.000]   |
+-----------------------------------------------------------------------------------------+
| 5. VALUE PROPOSITION : "Mengapa Memilih Jeep Wukirsari?"                                |
|    - Kotak Hijau Kiri (Tentang Kami ->)  |  4 Kartu Fitur Kanan (Transparan, Ramah, dll)|
+-----------------------------------------------------------------------------------------+
| 6. GALERI FOTO & TESTIMONI :                                                            |
|    - Grid 5 Foto Momen Indah Panorama & Manuver Jeep                                    |
|    - Kartu Testimoni Pelanggan + 5 Bintang Emas Sunrise                                 |
+-----------------------------------------------------------------------------------------+
| 7. FOOTER HIJAU TUA : Brand, Deskripsi, Navigasi, Kontak (+62 812), dan Social Media    |
+-----------------------------------------------------------------------------------------+
```

---

## 5. Spesifikasi Gaya Komponen (Component Styling)

### 5.1 Tombol (Button Variants)
* **Primary Button (Hijau Hutan)**:
  - Gaya: `bg-[#1E3F20] text-white font-medium rounded-full py-2.5 px-6 hover:bg-[#265029] transition-all flex items-center gap-2 shadow-sm`
  - Contoh: Tombol *"Booking Sekarang ->"* di Navbar, Hero, dan Kartu Paket.
* **Secondary Outline Button**:
  - Gaya: `border border-[#1E3F20] text-[#1E3F20] bg-transparent font-medium rounded-full py-2.5 px-6 hover:bg-[#E8EFE9] transition-all`
  - Contoh: Tombol *"Lihat Detail ->"*.
* **Hero Glass Button**:
  - Gaya: `border border-white/60 text-white bg-black/20 backdrop-blur-sm rounded-full py-2.5 px-6 hover:bg-white/20 transition-all`
  - Contoh: Tombol *"Jelajahi Paket"*.
* **White Pill Button (Di Dalam Kotak Hijau)**:
  - Gaya: `bg-white text-[#1E3F20] font-semibold rounded-full py-2.5 px-6 hover:bg-gray-100 shadow-md`
  - Contoh: Tombol *"Tentang Kami ->"*.

### 5.2 Kartu Paket Wisata (Package Card)
* **Wadah Kartu**:
  - Background putih murni `bg-white`, sudut membulat `rounded-2xl`, border halus `border border-[#E2EAE3]`, bayangan lembut `shadow-sm hover:shadow-md transition-shadow`.
* **Badge Kategori**:
  - Badge *"Paling Populer"*: `bg-[#1E3F20] text-white text-xs px-3 py-1 rounded-full absolute top-3 left-3`.
* **Rasio Foto**: `aspect-[16/10]` dengan sudut atas membulat `rounded-t-2xl object-cover`.
* **Metadata Baris**:
  - Menggunakan ikon SVG jam (*clock*) & orang (*users*), font teks `text-xs text-[#526054]`.
* **Tombol Aksi Bawah**:
  - Split 2 kolom: `[Lihat Detail ->]` (outline) di kiri dan `[Booking Sekarang]` (solid green) di kanan.

### 5.3 Bilah Nilai Tambah (Value Highlight Bar)
* Bar horizontal berlatar `bg-[#F1F5F2]` dengan 4 item:
  - Wadah ikon: Squircle hijau muda `w-12 h-12 bg-[#E0EBE2] rounded-xl flex items-center justify-center text-[#1E3F20]`.
  - Teks judul: `text-sm font-bold text-[#19231A]`.
  - Teks subjudul: `text-xs text-[#526054]`.

### 5.4 Kartu Testimoni (Testimonial Card)
* Background putih `bg-white`, border `border-[#E2EAE3]`, padding `p-5 rounded-2xl`.
* Foto profil lingkaran `w-10 h-10 rounded-full`, nama bold, dan asal kota (contoh: *Andi Pratama - Yogyakarta*).
* Bintang penilaian: 5 bintang emas `#F59E0B`.

---

## 6. Adaptasi Responsif (Mobile-First Breakdown)

| Bagian | Tampilan Ponsel (< 768px) | Tampilan Desktop (>= 1024px) |
| :--- | :--- | :--- |
| **Navbar** | Hamburger drawer + Logo ringkas | Menu penuh horizontal + Search + CTA Booking |
| **Hero** | Banner vertikal dengan tinggi optimal, teks judul responsif `text-2xl` | Fullscreen banner sinematik dengan latar Gunung Merapi |
| **Fitur Bar** | Scroll horizontal 4 kartu atau grid 2x2 | 1 baris horizontal rapi 4 kolom |
| **Paket Wisata** | Carousel horizontal swipeable atau tumpukan vertikal | Grid 3 kolom rapi sejajar |
| **Seksi "Mengapa"** | Kotak hijau menumpuk di atas 4 kartu fitur | Grid 2 kolom seimbang (Kiri: Kotak Hijau, Kanan: 4 Kartu) |
| **Sticky Bottom Bar** | **Wajib Aktif**: Bar melayang di bawah layar HP berisi harga & tombol booking | Tersembunyi (bergantung pada tombol di dalam kartu) |

---

## 7. Aset Gambar & Ilustrasi

* **Hero Image**: Foto Jeep Willys / Toyota Land Cruiser FJ40 warna hijau tentara di jalur berbatu lereng Gunung Merapi dengan cahaya fajar (*sunrise golden hour*).
* **Watermark Ornamen**: Siluet daun tanaman herbal/pohon tropis lereng Merapi berwarna sage transparan (`opacity-10`) di latar belakang seksi *"Mengapa Memilih"*.
* **Format Gambar**: Wajib menggunakan format **WebP** dengan *responsive srcset* untuk efisiensi loading di jaringan seluler.
