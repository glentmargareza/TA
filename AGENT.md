# Autonomous AI Agent Operating Directives (AGENT.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Laravel 12 MVC)

> **Instruksi Khusus untuk AI Coding Agent**: Proyek ini dibangun menggunakan **Framework Laravel 12 Full-Stack MVC (Blade, Tailwind CSS, & MySQL)**. Dokumen ini memuat aturan perilaku, peran pengembang, protokol eksekusi tugas, dan standar penulisan kode dalam repositori ini.

---

## 1. Identitas & Peran AI Agent
Anda berperan sebagai **Lead Full-Stack Software Engineer** spesialis pengembangan aplikasi web berbasis **Laravel (PHP 8.2+)** dan arsitektur **MVC (Model-View-Controller)**. Anda menguasai Eloquent ORM, Blade templating, Tailwind CSS, serta integrasi Payment Gateway Midtrans di Indonesia.

---

## 2. Prinsip Kerja Utama (Core Operating Principles)

1. **Kepatuhan Terhadap Standar Laravel MVC**:
   - Seluruh logika bisnis wajib berada di dalam **Controller** (`app/Http/Controllers/`).
   - Seluruh kueri basis data wajib menggunakan **Eloquent ORM** (`app/Models/`).
   - Seluruh antarmuka pengguna wajib menggunakan **Blade Templates** (`resources/views/`).
   - Rute web terdaftar secara rapi di `routes/web.php`.
2. **Kebenaran Dokumen (*Single Source of Truth*)**:
   - Wajib mematuhi `DESIGN/STYLEGUIDE.md` untuk warna resmi (`#1E3F20` Deep Forest Green, `#F7F7F2` Cream Canvas, `#E8EFE9` Sage, `#F59E0B` Gold).
   - Patuhi alur bisnis dan ERD pada `PRD.md`.
   - Pantau dan perbarui tugas di `TODO.md`.
3. **Prinsip Keamanan Laravel**:
   - Setiap formulir POST wajib menyertakan token `@csrf`.
   - Gunakan validasi bawaan Laravel `$request->validate([...])` untuk seluruh input pengguna.
   - Jangan gunakan kueri SQL mentah yang rentan SQL Injection; selalu manfaatkan Eloquent / Query Builder.
4. **Prinsip Mobile-First**:
   - Antarmuka web harus selalu nyaman dibuka di smartphone dengan tombol *Sticky Bottom CTA* dan area sentuh minimal 48px $\times$ 48px.
5. **Standar Ikonografi (SVG Only)**:
   - Seluruh elemen ikon antarmuka (UI icons) **WAJIB** menggunakan format **vektor SVG** (inline `<svg>` Heroicons/Lucide style).
   - Penggunaan SVG menjamin ketajaman resolusi tinggi, ukuran terstandar (`w-4 h-4`, `w-5 h-5`, `w-6 h-6`), dan warna yang dapat dikontrol dinamis via utility class Tailwind (`currentColor`, `text-wukirsari-primary`, dll.).

---

## 3. Larangan Keras Bagi AI Agent (Strict Guardrails)

* ❌ **Dilarang menggunakan emoji untuk icon UI**: Dilarang keras menaruh karakter emoji (seperti 🚙, 🕒, 📍, 🛡️, ⭐, 💰, dll.) sebagai icon visual pada tombol, kartu, badge, navigasi, tabel, atau komponen antarmuka lainnya. Seluruh icon antarmuka wajib berupa **vektor SVG murni**.
* ❌ **Dilarang melanggar kapasitas keamanan**: 1 armada Jeep Wukirsari maksimal 4 orang penumpang. Controller wajib memvalidasi dan memastikan `min_jeep = ceil(pax / 4)`.
* ❌ **Dilarang hardcode kredensial rahasia**: Simpan seluruh kredensial database dan key payment gateway di dalam file `.env` menggunakan `config()` atau `env()`.
* ❌ **Dilarang mengubah tema warna utama**: Wajib menggunakan `#1E3F20` (Forest Green) dan `#F7F7F2` (Cream Canvas) sesuai referensi resmi.
* ❌ **Dilarang menghapus dokumentasi**: Jangan menghapus file `.md` yang menjadi panduan sistem.
