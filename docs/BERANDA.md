# Dokumentasi Fitur Beranda – Jeep Wukirsari Adventure Tour

Dokumentasi ini menjelaskan implementasi teknis dan arsitektur fitur Beranda (Home / Landing Page) pada platform pemesanan Jeep Wisata Wukirsari Lereng Gunung Merapi.

---

## 1. Tujuan Fitur

Fitur Beranda berfungsi sebagai etalase digital utama (*landing page*) bagi calon wisatawan untuk:
1. Memperkenalkan profil dan legalitas operasional paguyuban Jeep Wisata Wukirsari di lereng Gunung Merapi.
2. Menyajikan katalog paket petualangan jeep offroad secara interaktif dan transparan (harga, durasi, dan destinasi).
3. Menampilkan ulasan autentik, skor rating kepuasan wisatawan, dan bukti testimoni terverifikasi.
4. Menyediakan informasi titik kumpul basecamp, kontak darurat (*WhatsApp customer service*), dan panduan pemesanan.
5. Mengarahkan wisatawan langsung ke proses pemesanan (*Call-to-Action* form booking) secara mandiri (*self-service*).

---

## 2. Penjelasan Fungsi Fitur

Halaman Beranda dirancang dengan tata letak modern, responsif (*mobile-first*), dan memuat komponen-komponen utama:
- **Scenic Hero Section**: Banner pemandangan Gunung Merapi dengan headline atraktif, tombol *Call to Action* utama ("Booking Sekarang"), dan ringkasan nilai jual unggulan (armada terawat, driver lokal berpengalaman, asuransi perjalanan).
- **Katalog Paket Wisata**: Kartu daftar paket wisata resmi (Short Trip, Medium Trip, Long Trip, Sunrise Tour) yang ditarik langsung secara dinamis dari tabel `packages`.
- **Informasi Rute & Destinasi**: Rincian objek wisata per paket (Bunker Kaliadem, Petilasan Mbah Maridjan, Batu Alien, Kali Kuning, Museum Mini Sisa Hartaku).
- **Testimoni & Rating Wisatawan**: Slider/grid ulasan dari pengunjung yang telah diapprove admin paguyuban, menampilkan nama pemesan, rating bintang 1–5, dan isi komentar.
- **Lokasi Basecamp & Kontak**: Peta interaktif titik koordinat pangkalan Wukirsari Cangkringan Sleman, alamat lengkap, dan tombol chat WhatsApp paguyuban.

---

## 3. File yang Digunakan

| Komponen | Lokasi File | Fungsi |
| :--- | :--- | :--- |
| **Controller** | `app/Http/Controllers/HomeController.php` | Mengambil data paket dan ulasan, mengatur konfigurasi basecamp, serta merender view. |
| **Model** | `app/Models/Package.php` | Representasi data paket wisata, cast atribut array (rute, galeri), dan accessor formatted price. |
| **Model** | `app/Models/Review.php` | Representasi data testimoni wisatawan dan relasi ke User & Package. |
| **View Utama** | `resources/views/home.blade.php` | Template Blade halaman beranda lengkap dengan Tailwind CSS. |
| **View Detail** | `resources/views/packages/show.blade.php` | Halaman detail rute dan fasilitas paket tertentu. |
| **Partial Nav** | `resources/views/layouts/app.blade.php` | Navbar navigasi responsif dan sticky bottom CTA pada perangkat seluler. |
| **Routes** | `routes/web.php` | Pendaftaran routing URL publik beranda dan paket. |

---

## 4. Route yang Digunakan

```php
// Rute publik beranda utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Alias & redirect navigasi paket
Route::redirect('/packages', '/#paket')->name('packages.index');
Route::redirect('/paket-wisata', '/#paket')->name('packages.alias');

// Rute detail paket individual berdasarkan slug unik
Route::get('/packages/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::get('/paket-wisata/{slug}', [HomeController::class, 'showPackage'])->name('packages.show.alias');
```

---

## 5. Controller dan Model yang Digunakan

### Controller: `App\Http\Controllers\HomeController`
- `index()`: Mengambil daftar paket aktif, 6 ulasan terbaru yang telah disetujui, agregasi rata-rata rating, dan konfigurasi basecamp dari storage.
- `showPackage(string $slug)`: Mengambil detail satu paket berdasarkan slug unik, paket rekomendasi lainnya, dan ulasan khusus paket tersebut.

### Models:
1. `App\Models\Package`:
   - Scope / kueri aktif: `Package::where('is_active', true)`
   - Relasi: `hasMany(Booking::class)`, `hasMany(Review::class)`
2. `App\Models\Review`:
   - Scope / kueri aktif: `Review::where('is_approved', true)`
   - Relasi: `belongsTo(User::class)`, `belongsTo(Package::class)`

---

## 6. Query Database yang Diterapkan

### A. Mengambil Seluruh Paket Wisata Aktif
```php
$packages = Package::where('is_active', true)
    ->orderBy('price_per_jeep', 'asc')
    ->get();
```
- **Tujuan**: Menampilkan seluruh paket petualangan yang berstatus aktif di etalase beranda, diurutkan dari tarif termurah ke termahal.
- **Tabel**: `packages`.
- **Hasil**: Kumpulan objek paket (nama, deskripsi, harga per jeep, rute, foto galeri).

### B. Mengambil Ulasan Wisatawan Terverifikasi (Eager Loading)
```php
$reviews = Review::with(['user', 'package'])
    ->where('is_approved', true)
    ->latest()
    ->take(6)
    ->get();
```
- **Tujuan**: Menampilkan 6 ulasan terbaru untuk mencegah beban database berlebih, dengan eager loading relasi `user` dan `package` guna mengeliminasi masalah N+1 Query.
- **Tabel**: `reviews`, `users`, `packages`.

### C. Agregasi Total Ulasan dan Rata-rata Skor Rating
```php
$totalReviewsCount = Review::where('is_approved', true)->count();
$avgRating = Review::where('is_approved', true)->avg('rating') ?: 5.0;
```
- **Tujuan**: Menghitung metrik kepuasan pengunjung secara agregat matematis di level basis data MySQL, ditampilkan sebagai badge bintang ulasan.

### D. Mengambil Detail Paket Wisata Berdasarkan Slug
```php
$package = Package::where('slug', $slug)->firstOrFail();
$otherPackages = Package::where('is_active', true)
    ->where('id', '!=', $package->id)
    ->take(3)
    ->get();
```
- **Tujuan**: Menampilkan data komprehensif paket pada halaman `/packages/{slug}` beserta 3 rekomendasi paket lain.

---

## 7. Relasi Tabel yang Digunakan

```
+------------------+         1 : N         +--------------------+
|     packages     | --------------------> |      reviews       |
|  (id, name, ...) |                       | (id, package_id)   |
+------------------+                       +--------------------+
                                                      |
                                             N : 1    |
                                                      v
                                           +--------------------+
                                           |       users        |
                                           |  (id, name, ...)   |
                                           +--------------------+
```
- `packages.id` berelasi *one-to-many* ke `reviews.package_id`.
- `users.id` berelasi *one-to-many* ke `reviews.user_id`.

---

## 8. Validasi dan Keamanan

1. **Mass Assignment Protection**: Seluruh kolom yang diizinkan untuk manipulasi data dilindungi melalui properti `$fillable` pada Model `Package` dan `Review`.
2. **XSS Escaping**: Semua variabel yang dirender di Blade menggunakan syntax `{{ $variable }}` untuk otomatis lolos sanitasi HTML entities.
3. **Penyaringan Konten Ulasan**: Ulasan yang belum disetujui (`is_approved = 0`) tidak akan pernah muncul di halaman publik beranda berkat filter ketat `where('is_approved', true)`.
4. **404 Not Found Handling**: Menggunakan `firstOrFail()` pada pencarian slug paket untuk mencegah error fatal / exception unhandled jika slug tidak valid.

---

## 9. Cara Pengujian Fitur

1. **Pengujian Tampilan Browser**:
   - Buka URL: `http://localhost/TA/public/` atau `http://127.0.0.1:8000/`.
   - Pastikan kartu paket wisata (Short Trip, Medium Trip, Long Trip, Sunrise Tour) muncul dengan harga dan durasi yang sesuai dengan data tabel `packages`.
   - Klik salah satu paket untuk membuka detail paket pada rute `/packages/{slug}`.
2. **Pengujian Kueri via Artisan Tinker**:
   ```bash
   php artisan tinker --execute="echo 'Paket Aktif: ' . App\Models\Package::where('is_active', true)->count();"
   ```
   *Hasil yang diharapkan*: Mengembalikan bilangan bulat positif sesuai jumlah paket aktif di database.

