# Panduan Deployment & Persiapan Verifikasi Midtrans (DEPLOYMENT.md) – Jeep Wukirsari

Panduan ini menyajikan instruksi langkah demi langkah (*step-by-step*) untuk mempublikasikan platform **Jeep Wukirsari Adventure Tour** ke server publik (*staging/production*). Tujuan utama deployment tahap ini adalah agar website dapat diakses secara publik dan memenuhi seluruh persyaratan kepatuhan (*compliance*) verifikasi merchant **Midtrans Payment Gateway**.

---

## ⚠️ PERINGATAN KEAMANAN PENTING
1. **DILARANG** menjalankan `php artisan migrate:fresh` atau `php artisan db:wipe` di server hosting karena akan menghapus seluruh data paket dan riwayat pemesanan.
2. **DILARANG** menuliskan Server Key, Client Key, kata sandi basis data, atau kredensial produksi apa pun langsung ke dalam berkas dokumentasi atau repositori Git.
3. Seluruh kredensial sensitif **WAJIB** disimpan secara terisolasi pada berkas `.env` server.
4. **JANGAN** mengaktifkan transaksi Production sebelum akun Midtrans resmi diverifikasi dan disetujui.

---

## BAGIAN A: PERSYARATAN SERVER (PREREQUISITES)

### 1. Spesifikasi Server
- **Sistem Operasi**: Linux Ubuntu 22.04 LTS / Debian 12 / Cloud VPS / Shared Hosting (cPanel) dengan akses SSH atau Terminal.
- **Web Server**: Nginx atau Apache HTTP Server dengan modul `mod_rewrite` aktif.
- **Arsitektur**: 64-bit, RAM minimal 1 GB, Disk minimal 10 GB SSD.

### 2. Versi PHP & Ekstensi yang Dibutuhkan
- **Versi**: **PHP >= 8.2** (Rekomendasi: PHP 8.2.x atau PHP 8.3.x).
- **Ekstensi PHP Wajib**:
  - `bcmath` (Kalkulasi presisi angka desimal keuangan)
  - `ctype`, `fileinfo`, `filter`, `tokenizer` (Validasi input & upload file)
  - `curl` (Koneksi HTTP request ke Midtrans Core API)
  - `dom`, `xml` (Manipulasi XML / SVG)
  - `hash`, `openssl` (Enkripsi kata sandi & kalkulasi signature SHA-512)
  - `mbstring` (Manipulasi teks multibyte)
  - `pdo_mysql` (Driver koneksi basis data MySQL/MariaDB)
  - `session` (Manajemen sesi wisatawan dan admin)

Perintah instalasi pada Ubuntu 22.04:
```bash
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-curl \
    php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-zip php8.2-intl
```

### 3. Basis Data
- **MySQL >= 8.0** atau **MariaDB >= 10.4**.
- Karakter set: `utf8mb4` dengan collation `utf8mb4_unicode_ci`.

### 4. Package Manager & Tools
- **Composer**: Versi 2.x (`composer --version`).
- **Git**: Versi 2.x (`git --version`).
- **Node.js & npm** (Opsional jika aset dikompilasi di server lokal sebelum diunggah): Node.js v18 LTS atau v20 LTS.

### 5. Domain & Sertifikat SSL / HTTPS
- **Domain Publik**: Domain kustom (misal: `jeepwukirsari.id` atau `booking.jeepwukirsari.id`).
- **Sertifikat SSL**: Let's Encrypt SSL atau Cloudflare SSL (**Wajib aktif** karena webhook Midtrans dan Geolocation API peramban menolak koneksi HTTP non-aman).

---

## BAGIAN B: PERSIAPAN KODE SUMBER (SOURCE CODE DEPLOYMENT)

### 1. Kloning Source Code ke Server
Pastikan repositori dikloning ke direktori web server (misal `/var/www/jeep-wukirsari` atau `public_html` pada cPanel):
```bash
cd /var/www
git clone https://github.com/glentmargareza/TA.git jeep-wukirsari
cd jeep-wukirsari
git checkout main # Gunakan branch main atau dev untuk staging
```

### 2. Menginstal Dependency Composer
Jalankan instalasi dependensi PHP tanpa paket pengembangan (*dev dependencies*) untuk performa maksimal:
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Menyiapkan Berkas Environment (`.env`)
Salin template berkas `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Generate kunci aplikasi enkripsi Laravel:
```bash
php artisan key:generate
```

### 4. Konfigurasi Parameter Kunci pada `.env`
Buka berkas `.env` menggunakan editor (`nano .env`):
```dotenv
APP_NAME="Jeep Wukirsari Adventure Tour"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

# Basis Data Hosting
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_db_hosting
DB_USERNAME=nama_user_db
DB_PASSWORD=kata_sandi_db_kuat

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Titik Koordinat Basecamp Pangkalan Wukirsari
BASECAMP_LAT=-7.6437244
BASECAMP_LNG=110.4338597

# Konfigurasi Midtrans Sandbox (Fase Awal Verifikasi)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxxxxxxxxx
MIDTRANS_MERCHANT_ID=Gxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Rekening Resmi Paguyuban (Transfer BPD DIY)
BPD_DIY_BANK_NAME="Bank BPD DIY"
BPD_DIY_ACCOUNT_NUMBER="1234567890"
BPD_DIY_ACCOUNT_NAME="PAGUYUBAN JEEP WUKIRSARI"
BPD_DIY_BRANCH="Cabang Sleman / Cangkringan"
```

### 5. Membangun Aset Frontend & Storage Symlink
Hubungkan folder penyimpanan publik ke direktori storage:
```bash
php artisan storage:link
```
Jika aset dikompilasi menggunakan Vite:
```bash
npm ci
npm run build
```
*(Catatan: Proyek ini menggunakan Tailwind CSS CDN dan Vanilla JavaScript mandiri, sehingga aplikasi siap berjalan tanpa dependensi compile berat di server).*

### 6. Pengaturan Izin Hak Akses Direktori (Permissions)
Pastikan web server (misal `www-data`) memiliki izin tulis ke folder `storage` dan `bootstrap/cache`:
```bash
sudo chown -R www-data:www-data /var/www/jeep-wukirsari
sudo chmod -R 775 /var/www/jeep-wukirsari/storage
sudo chmod -R 775 /var/www/jeep-wukirsari/bootstrap/cache
```

### 7. Optimasi Cache Konfigurasi & Rute
Jalankan perintah optimasi produksi Laravel:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## BAGIAN C: PENGELOLAAN BASIS DATA (DATABASE SETUP)

### 1. Pencadangan Basis Data Lokal (Backup)
Sebelum melakukan migrasi ke server hosting, cadangkan database lokal MariaDB:
```bash
# Melalui terminal Windows / XAMPP:
cd C:\xampp\mysql\bin
mysqldump -u root -p web-ta > C:\xampp\htdocs\TA\backup_web_ta.sql
```

### 2. Mengimpor Skema dan Data Awal ke Server Hosting
- **Opsi A (Melalui Terminal SSH VPS)**:
  ```bash
  mysql -u nama_user_db -p nama_db_hosting < backup_web_ta.sql
  ```
- **Opsi B (Melalui phpMyAdmin cPanel)**:
  1. Buka cPanel hosting -> Pilih **phpMyAdmin**.
  2. Pilih database tujuan (`nama_db_hosting`).
  3. Klik tab **Import** -> Pilih file `backup_web_ta.sql` -> Klik **Go**.
- **Opsi C (Melalui Artisan Migrate Aman)**:
  Jika mengawali dari database kosong tanpa file dump:
  ```bash
  php artisan migrate --force
  php artisan db:seed --force
  ```
  *(Peringatan: Gunakan `--force`, JANGAN PERNAH gunakan `migrate:fresh`)*.

---

## BAGIAN D: KONFIGURASI WEB SERVER (NGINX / APACHE)

### Konfigurasi Nginx (`/etc/nginx/sites-available/jeep-wukirsari`)
Pastikan `root` diarahkan ke subfolder `/public`:
```nginx
server {
    listen 80;
    server_name domain-anda.com www.domain-anda.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name domain-anda.com www.domain-anda.com;

    root /var/www/jeep-wukirsari/public;
    index index.php index.html;

    # SSL Certificate (Certbot / Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/domain-anda.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domain-anda.com/privkey.pem;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Konfigurasi Apache (`.htaccess` di folder `public/`)
Pastikan berkas `public/.htaccess` bawaan Laravel tersedia untuk mengarahkan seluruh request ke `index.php`.

---

## BAGIAN E: PERSYARATAN KHUSUS UNTUK PENDAFTARAN & VERIFIKASI MIDTRANS

Midtrans memiliki panduan verifikasi (*Merchant Verification Checklist*) yang sangat ketat sebelum akun disetujui. Tim auditor Midtrans akan mengakses website secara langsung untuk memeriksa kriteria berikut:

| Kriteria Midtrans | Lokasi / Bukti pada Sistem Jeep Wukirsari | Status Kesiapan |
| :--- | :--- | :---: |
| **Akses Publik & HTTPS** | Website dapat dibuka bebas via internet dengan sertifikat SSL (ikon gembok hijau). | **SIAP** |
| **Identitas Bisnis Jelas** | Nama: **Paguyuban Jeep Wukirsari Adventure Tour**.<br>Alamat: Jl. Bebeng - Cangkringan, Wukirsari, Sleman, D.I. Yogyakarta. | **SIAP** |
| **Katalog Produk / Paket** | Menampilkan kartu paket resmi (Short Trip, Medium Trip, Long Trip, Sunrise) lengkap dengan foto dan rute destinasi. | **SIAP** |
| **Harga Mata Uang Rupiah** | Seluruh harga dicantumkan jelas dalam format Rupiah (contoh: **Rp 450.000 / Jeep**). | **SIAP** |
| **Informasi Layanan** | Menjelaskan kapasitas offroad (maks. 4 orang per jeep), opsi kedatangan, dan opsi DP 30% / Lunas 100%. | **SIAP** |
| **Halaman Kontak Resmi** | Nomor WhatsApp Customer Service (`+62 812-3456-7890`), alamat pangkalan, dan peta Google Maps terpasang. | **SIAP** |
| **Syarat & Ketentuan** | Halaman publik resmi di route: `https://domain-anda.com/syarat-ketentuan`. | **SIAP** |
| **Kebijakan Privasi** | Halaman publik resmi di route: `https://domain-anda.com/kebijakan-privasi`. | **SIAP** |
| **Kebijakan Refund / Batal** | Klausul pengembalian dana 100% force majeure dan aturan pembatalan tercantum jelas di `/syarat-ketentuan`. | **SIAP** |

### Pengaturan URL Webhook di Portal Dashboard Midtrans:
1. Masuk ke **Midtrans Dashboard** ([https://dashboard.midtrans.com/](https://dashboard.midtrans.com/)).
2. Pilih mode **Sandbox** (pada sudut kiri atas).
3. Buka menu: **Settings** -> **Configuration**.
4. Isi field **Payment Notification URL**:
   ```
   https://domain-anda.com/webhook/midtrans
   ```
5. Isi field **Finish Redirect URL**:
   ```
   https://domain-anda.com/booking
   ```
6. Klik tombol **Save Changes**.

---

## BAGIAN F: CHECKLIST PEMERIKSAAN SETELAH DEPLOYMENT (POST-DEPLOYMENT CHECKLIST)

Lakukan pengujian menyeluruh pada tautan domain publik:

- [ ] **Protokol HTTPS**: Buka `http://domain-anda.com`, pastikan otomatis dialihkan ke `https://domain-anda.com`.
- [ ] **Halaman Beranda**: Paket wisata muncul dinamis dari database, slider foto beroperasi, ulasan bintang tampil.
- [ ] **Halaman Syarat & Ketentuan**: Link footer di `/syarat-ketentuan` terbuka sukses (HTTP 200).
- [ ] **Halaman Kebijakan Privasi**: Link footer di `/kebijakan-privasi` terbuka sukses (HTTP 200).
- [ ] **Alur Pemesanan (Booking)**: Buka `/booking`, coba pesan paket, pastikan kuota slot diperiksa dan redirect ke pembayaran.
- [ ] **Halaman Pembayaran**: Halaman `/booking/{code}/payment` menampilkan instruksi pembayaran Virtual Account / QRIS.
- [ ] **Proteksi Halaman Admin**: Akses `/admin` tanpa login wajib diarahkan ke `/login` (tidak boleh terbuka publik).
- [ ] **Aksesibilitas Webhook**: Lakukan test ping HTTP POST ke `https://domain-anda.com/webhook/midtrans`, pastikan backend merespon HTTP 400 Bad Request jika tanpa parameter (bukan 404 Not Found atau 500 Server Error).
- [ ] **Pemeriksaan Log**: Periksa file `storage/logs/laravel.log`, pastikan tidak ada error fatal PHP.

---

## BAGIAN G: TAHAPAN FINAL AKTIVASI AKUN PRODUCTION MIDTRANS

Setelah tim compliance Midtrans menyetujui akun merchant Anda, lakukan migrasi dari Sandbox ke Production dengan langkah aman berikut:

1. **Uji Transaksi Terakhir di Sandbox**:
   - Simulasikan pembayaran menggunakan simulator Midtrans hingga e-tiket sukses terbit.
2. **Pengajuan Aktivasi Akun**:
   - Pada Dashboard Midtrans, klik **Go to Production** dan selesaikan pengunggahan dokumen legalitas (KTP penanggung jawab, NPWP, dan rekening bank penampungan dana).
3. **Pengambilan Kredensial Produksi**:
   - Beralih ke mode **Production** pada dashboard Midtrans.
   - Buka **Settings** -> **Access Keys**.
   - Salin nilai **Production Server Key** dan **Production Client Key**.
4. **Pembaruan Konfigurasi di Server (`.env`)**:
   Ubah parameter pada server hosting:
   ```dotenv
   MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxxxxxxxxxxxx
   MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxxxxxx
   MIDTRANS_IS_PRODUCTION=true
   ```
5. **Penyegaran Cache Laravel**:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```
6. **Uji Transaksi Produksi Terkendali**:
   - Lakukan satu transaksi riil dengan nominal kecil (misal DP Rp 150.000 menggunakan QRIS atau transfer bank asli).
   - Verifikasi apakah saldo masuk ke rekening Midtrans dan e-tiket otomatis terbit di sistem.
7. **Jadwal Pemeliharaan & Backup Berkala**:
   - Pasang cron job harian pada server untuk pencadangan basis data MySQL otomatis:
     ```bash
     0 2 * * * mysqldump -u nama_user_db -p'kata_sandi' nama_db_hosting | gzip > /var/backups/db_jeep_$(date +\%F).sql.gz
     ```

