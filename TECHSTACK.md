# Technology Stack Specification (TECHSTACK.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Lereng Merapi)

> **Spesifikasi Arsitektur Resmi (Full Vite Stack)**: Sesuai arahan teknis terbaru, proyek ini menggunakan **Vite (`React + Vite + TypeScript`)** secara penuh sebagai pondasi utama pengembangan web. Dokumen ini mendefinisikan versi pustaka, struktur proyek SPA, konfigurasi Tailwind CSS sesuai mockup resmi, serta alur build produksi.

---

## 1. Ringkasan Arsitektur Teknologi (Full Vite Ecosystem)

```
+-----------------------------------------------------------------------------------------+
|                        ARSITEKTUR STACK JEEP WUKIRSARI (FULL VITE)                      |
+-----------------------------------------------------------------------------------------+
| BUILD TOOL & DEV SERVER    : Vite 5+ (Super-fast Hot Module Replacement / HMR)          |
| UI LIBRARY & BAHASA        : React 18+ & TypeScript (Strict Type Safety)                |
| CLIENT-SIDE ROUTING        : React Router DOM v6                                        |
| DESAIN SISTEM & STYLING    : Tailwind CSS + PostCSS + Autoprefixer                      |
| PALET TEMA RESMI           : Deep Forest Green (#1E3F20) & Sage Cream (#F7F7F2)         |
| IKON & ANIMASI             : Lucide React + Framer Motion (Smooth Sheet & Drawer)       |
| CLIENT STATE & CACHE       : Zustand (Store Global) & TanStack React Query              |
| FORM & VALIDASI            : React Hook Form + Zod                                      |
| BACKEND / API HANDLER      : Express.js REST API (Node.js) / PHP API (XAMPP Native)     |
| BASIS DATA RELASIONAL      : PostgreSQL / MySQL (phpMyAdmin di XAMPP)                   |
| PAYMENT GATEWAY            : Midtrans Snap JS SDK (QRIS, VA BCA/BRI/Mandiri, E-Wallet)  |
| WHATSAPP GATEWAY           : Fonnte API Gateway (Pengiriman E-Tiket Ber-QR Code)        |
+-----------------------------------------------------------------------------------------+
```

---

## 2. Keuntungan Menggunakan Vite pada Proyek Ini
1. **Kecepatan Server Lokal Kilat**: *Cold start* server dev di Vite aktif dalam hitungan milidetik, tanpa waktu kompilasi yang lambat.
2. **Kesesuaian dengan Lingkungan XAMPP**: Hasil kompilasi Vite (`npm run build` $\rightarrow$ folder `dist/`) berupa file statis HTML, CSS, dan JS murni yang dapat langsung dijalankan oleh Apache Web Server di dalam `C:\xampp\htdocs\WebWukirsari`.
3. **Sangat Ringan di Ponsel Pengguna**: Ukuran bundle SPA sangat ramping, transisi antar-halaman (*routing*) terjadi instan tanpa reload browser sama sekali (*app-like experience*).

---

## 3. Konfigurasi Tema Tailwind CSS (`tailwind.config.js`)

Tema warna wajib mengacu pada **[DESIGN/STYLEGUIDE.md](file:///C:/xampp/htdocs/WebWukirsari/DESIGN/STYLEGUIDE.md)**:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        wukirsari: {
          primary: "#1E3F20", // Deep Forest Green (Tombol Utama, Brand & Navbar)
          dark: "#142B16",    // Dark Footer & Container
          hover: "#265029",   // Hover State
          sage: "#E8EFE9",    // Background Fitur Bar & Pill
          cream: "#F7F7F2",   // Background Halaman Utama
          border: "#E2EAE3",  // Garis Pemisah Halus
        },
        merapiText: {
          primary: "#19231A",   // Teks Judul & Headline
          secondary: "#526054", // Teks Paragraf & Deskripsi
          muted: "#7E8E81",     // Caption & Sub-label
        },
        sunrise: {
          gold: "#F59E0B",      // Bintang Testimoni & Glow Fajar
          amber: "#D97706",
        },
      },
      fontFamily: {
        sans: ["'Plus Jakarta Sans'", "sans-serif"],
        display: ["'Outfit'", "sans-serif"],
        accent: ["'Playfair Display'", "serif"],
      },
      borderRadius: {
        "2xl": "1rem",
        "3xl": "1.5rem",
        pill: "9999px",
      },
    },
  },
  plugins: [],
};
```

---

## 4. Konfigurasi Vite & Path Alias (`vite.config.ts`)

```typescript
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    port: 3000,
    open: true,
  },
});
```

---

## 5. Konvensi Struktur Direktori Proyek (Vite Structure)

```
WebWukirsari/
├── index.html                  # File HTML utama pembungkus aplikasi
├── package.json                # Daftar dependensi Vite & React
├── tsconfig.json               # Konfigurasi TypeScript & Path Alias (@/*)
├── vite.config.ts              # Konfigurasi Vite dev server & build
├── tailwind.config.js          # Konfigurasi token warna wukirsari
├── postcss.config.js
├── public/
│   ├── images/
│   │   ├── packages/           # Foto Adventure, Lavature, Sunrise Tour
│   │   └── hero-merapi.jpg
│   └── favicon.ico
├── src/
│   ├── main.tsx                # Titik masuk React root (ReactDOM.createRoot)
│   ├── App.tsx                 # Konfigurasi React Router DOM & Layout Utama
│   ├── index.css               # Import Tailwind (@tailwind base; components; utilities;)
│   ├── components/             # Reusable UI Components
│   │   ├── layout/             # Navbar, Footer Hijau Tua, MobileStickyBottom
│   │   ├── home/               # HeroSection, ValueBar, PackageSection, WhyUs, Gallery, Reviews
│   │   ├── booking/            # CalendarPicker, PaxCounter, TimeSlotDrawer, SummaryCard
│   │   └── ui/                 # Button, Badge, Modal, Input, Card
│   ├── pages/                  # Halaman Aplikasi (React Router)
│   │   ├── HomePage.tsx        # Beranda sesuai referensi desain
│   │   ├── PackageDetailPage.tsx # Detail Paket & Rute
│   │   ├── BookingPage.tsx     # Alur Formulir Checkout & DP
│   │   ├── BookingSuccessPage.tsx # Halaman Tiket Digital & Download
│   │   ├── CheckTicketPage.tsx # Pencarian Tiket Mandiri Wisatawan
│   │   └── admin/              # Dashboard Pengelola & Scanner Loket
│   │       ├── DashboardOverview.tsx
│   │       ├── BookingManager.tsx
│   │       └── QrScannerPage.tsx
│   ├── services/               # API Calls & Integrasi Luar
│   │   ├── api.ts              # Axios instance
│   │   ├── midtrans.ts         # Snap JS integration
│   │   └── bookingService.ts   # Cek ketersediaan slot & create order
│   ├── store/                  # Global State (Zustand)
│   │   └── bookingStore.ts     # State keranjang & pilihan jadwal
│   ├── types/                  # Definisi TypeScript Interfaces & Enums
│   └── utils/                  # Formatter Rupiah, Tanggal Indonesia, Validator
```

---

## 6. Variabel Lingkungan Vite (`.env.example`)

> Di Vite, semua variabel lingkungan yang dapat diakses di sisi klien wajib diawali dengan prefix `VITE_`.

```bash
# Konfigurasi Aplikasi
VITE_APP_NAME="Jeep Wukirsari Adventure Tour"
VITE_API_BASE_URL="http://localhost:5000/api"

# Payment Gateway Midtrans (Client Key)
VITE_MIDTRANS_CLIENT_KEY="SB-Mid-client-xxxxxxxxxxxx"
VITE_MIDTRANS_IS_PRODUCTION=false

# Kontak & Operasional
VITE_LOCATION_LABEL="Wukirsari, Sleman, Yogyakarta"
VITE_BASECAMP_MAPS_URL="https://maps.app.goo.gl/ContohBasecampJeepWukirsari"
VITE_CS_WHATSAPP="6281234567890"
VITE_MAX_PAX_PER_JEEP=4
```

---

## 7. Perintah Menjalankan Proyek (*Run Scripts*)

```bash
# Menjalankan dev server Vite lokal
npm run dev

# Membangun bundle produksi ke folder dist/
npm run build

# Meninjau hasil build lokal
npm run preview
```
