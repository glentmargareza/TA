---
name: jeep-wukirsari-laravel-workflow
description: Prosedur operasional standar dan panduan otomatisasi untuk pengembangan, migrasi, pengujian, dan manajemen platform reservasi Jeep Wukirsari Adventure Tour berbasis Laravel 12 Full-Stack MVC (Blade, Tailwind CSS, MySQL XAMPP).
---

# Jeep Wukirsari Development Skill (SKILL.md)

Skill ini memuat instruksi operasional standar bagi AI Agent & Pengembang dalam menjalankan siklus pengembangan berbasis **Laravel 12 Full-Stack MVC** (Blade, Tailwind CSS, MySQL XAMPP) pada platform **Jeep Wukirsari Adventure Tour**.

---

## 1. Quick Reference: Perintah Terminal Laravel Artisan

| Perintah | Deskripsi | Catatan |
| :--- | :--- | :--- |
| `php artisan serve` | Menjalankan local development server | Port default: `http://127.0.0.1:8000` |
| `php artisan migrate:fresh --seed` | Reset dan isi ulang database MySQL | Menjalankan 8 tabel & seeder paket, slot, zona, armada, driver |
| `php artisan route:list` | Memeriksa seluruh daftar rute web yang terdaftar | Verifikasi rute publik, booking, tiket, dan admin |
| `php artisan make:controller [Name]` | Membuat controller baru | Tersimpan di `app/Http/Controllers/` |
| `php artisan make:model [Name] -m` | Membuat model Eloquent beserta migrasinya | Tersimpan di `app/Models/` & `database/migrations/` |
| `php artisan optimize:clear` | Menghapus cache view, route, config, dan compiled | Berguna jika ada perubahan blade/route yang tidak langsung muncul |
| `npm run build` / `npm run dev` | Kompilasi aset Vite (Tailwind CSS) | Digunakan jika melakukan build bundler lokal |

---

## 2. Prosedur Eksekusi Fase Pengembangan

### Langkah 1: Pengelolaan Database & Migrasi MySQL XAMPP
1. Pastikan layanan Apache dan MySQL pada panel kontrol XAMPP berstatus **Running**.
2. Konfigurasi kredensial koneksi di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_jeep_wukirsari
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Eksekusi migrasi dan seeder awal:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Data bawaan yang di-seed: 3 Paket Wisata (Adventure Rp 450rb, Lavature Rp 550rb, Sunrise Tour Rp 700rb), 4 Slot Waktu Operasional, 4 Zona Penjemputan, Armada Jeep, dan Pengemudi Paguyuban.*

### Langkah 2: Standar Arsitektur Controller & Model
1. **Aturan Kapasitas Keamanan (Safety Rule)**:
   - Maksimal 4 penumpang per 1 unit armada jeep.
   - Formula kalkulasi di controller:
     ```php
     $minJeeps = (int) ceil($pax / 4);
     if ($jeeps < $minJeeps) {
         return back()->withErrors(['jeep_quantity' => "Kapasitas 1 jeep maks 4 orang. Untuk {$pax} orang dibutuhkan minimal {$minJeeps} jeep."]);
     }
     ```
2. **Kalkulasi Pembayaran (Payment Mode)**:
   - Skema `FULL`: Bayar 100% di awal (`paid_amount = total_amount`, `remaining_amount = 0`).
   - Skema `DP`: Uang muka 30% di awal (`paid_amount = round(total_amount * 0.3)`, sisa 70% dilunasi tunai/QRIS di loket basecamp).

### Langkah 3: Antarmuka Blade & Styling Sesuai Mockup
1. Gunakan layout utama pada [resources/views/layouts/app.blade.php](file:///c:/xampp/htdocs/WebWukirsari/resources/views/layouts/app.blade.php).
2. Terapkan palet warna resmi dari [DESIGN/STYLEGUIDE.md](file:///c:/xampp/htdocs/WebWukirsari/DESIGN/STYLEGUIDE.md):
   - `bg-[#1E3F20]` (*Deep Forest Green*) untuk elemen identitas utama & header.
   - `bg-[#F7F7F2]` (*Warm Cream*) sebagai warna latar kanvas halaman.
   - `bg-[#E8EFE9]` (*Sage Tint*) untuk kartu fitur & icon container.
   - `text-[#F59E0B]` (*Sunrise Gold*) untuk rating bintang & aksen fajar.
3. Pastikan setiap form HTML memiliki proteksi CSRF token: `@csrf`.
4. **Standar Ikon**: Dilarang menggunakan emoji untuk icon UI. Seluruh icon wajib menggunakan format SVG murni (inline `<svg>`) agar tajam, profesional, dan warnanya seragam dengan Tailwind CSS.

---

## 3. Checklist Verifikasi Selesai (Definition of Done)
- [ ] Server `php artisan serve` berjalan normal di `http://127.0.0.1:8000` tanpa exception.
- [ ] Seluruh migrasi (`php artisan migrate`) terpasang tanpa error SQL.
- [ ] Seluruh icon antarmuka menggunakan vektor SVG murni (bebas dari karakter emoji).
- [ ] Halaman Beranda (`/`) menampilkan Hero Merapi, 4 Value Bar, 3 Paket Resmi, Why Us, Galeri, dan Testimoni.
- [ ] Alur Pemesanan (`/booking`) menghitung otomatis kuota jeep (maks 4 pax) dan kalkulasi DP/Full.
- [ ] E-Tiket Boarding Pass (`/booking/{code}/success`) menampilkan QR Code, detail slot, dan kontak WhatsApp CS.
- [ ] Fitur Cek Tiket Mandiri (`/cek-tiket`) dapat mencari reservasi berdasarkan kode booking atau nomor WhatsApp.
- [ ] Dasbor Admin (`/admin`) menampilkan statistik omzet, penugasan driver, dan pelunasan kasir satu-klik.
