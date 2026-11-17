# Dokumentasi Master Query Database – Jeep Wukirsari Adventure Tour

Dokumentasi ini memuat seluruh implementasi kueri basis data (*database queries*) yang diterapkan pada sistem reservasi Jeep Wukirsari, mencakup 6 domain fitur:
1. **Query Beranda**
2. **Query Admin**
3. **Query Booking**
4. **Query Pickup Zone**
5. **Query Payment**
6. **Query Midtrans**

Seluruh kueri di bawah ini menggunakan **Laravel Eloquent ORM** dan **Query Builder** yang sinkron langsung dengan kode sumber proyek.

---

# BAGIAN 1: QUERY BERANDA

### Query 1.1: Pengambilan Seluruh Paket Wisata Aktif
- **Nama Fitur**: Beranda (Landing Page).
- **Tujuan Query**: Mengambil seluruh katalog paket wisata yang sedang dibuka operasionalnya untuk ditampilkan pada kartu paket di halaman beranda, diurutkan dari harga termurah.
- **Lokasi File Implementasi**: `app/Http/Controllers/HomeController.php` (baris 13).
- **Model dan Tabel yang Digunakan**: `App\Models\Package` (tabel: `packages`).
- **Relasi Antartabel**: Tidak memerlukan relasi (tabel tunggal).
- **Contoh Kode Query**:
  ```php
  $packages = Package::where('is_active', true)
      ->orderBy('price_per_jeep', 'asc')
      ->get();
  ```
- **Penjelasan Cara Kerja**: Memfilter baris data di mana kolom `is_active = 1` dan menyusun hasil berdasarkan kolom `price_per_jeep` secara *ascending*.
- **Hasil Data yang Diharapkan**: Koleksi objek `Package` (Short Trip Rp 450.000, Medium Trip A/B Rp 550.000, Sunrise Rp 600.000, Long Trip Rp 700.000).

---

### Query 1.2: Pengambilan Ulasan Wisatawan Terverifikasi (Eager Loading)
- **Nama Fitur**: Beranda (Landing Page - Testimoni Wisatawan).
- **Tujuan Query**: Mengambil 6 testimoni terbaru yang telah diverifikasi dan disetujui admin untuk ditampilkan pada carousel/grid testimoni tanpa menyebabkan masalah N+1 query.
- **Lokasi File Implementasi**: `app/Http/Controllers/HomeController.php` (baris 14–18).
- **Model dan Tabel yang Digunakan**: `App\Models\Review` (tabel: `reviews`), berelasi ke `App\Models\User` (tabel: `users`) dan `App\Models\Package` (tabel: `packages`).
- **Relasi Antartabel**: 
  - `reviews.user_id` -> `users.id` (BelongsTo)
  - `reviews.package_id` -> `packages.id` (BelongsTo)
- **Contoh Kode Query**:
  ```php
  $reviews = Review::with(['user', 'package'])
      ->where('is_approved', true)
      ->latest()
      ->take(6)
      ->get();
  ```
- **Penjelasan Cara Kerja**: Mengambil data dari tabel `reviews` dengan kondisi `is_approved = 1`, mengurutkan dari waktu paling baru (`created_at DESC`), membatasi maksimal 6 data (`LIMIT 6`), serta melakukan *eager load* data user penulis ulasan dan paket wisata terkait dalam 2 kueri pelengkap yang dioptimasi.
- **Hasil Data yang Diharapkan**: Koleksi 6 objek `Review` lengkap dengan objek nama pengguna dan nama paket yang diulas.

---

### Query 1.3: Agregasi Total Ulasan dan Rata-rata Skor Rating
- **Nama Fitur**: Beranda (Statistik Kepuasan Pelanggan).
- **Tujuan Query**: Menghitung secara matematis jumlah ulasan yang masuk dan nilai rata-rata bintang (skala 1.0 – 5.0) dari pengunjung.
- **Lokasi File Implementasi**: `app/Http/Controllers/HomeController.php` (baris 19–20).
- **Model dan Tabel yang Digunakan**: `App\Models\Review` (tabel: `reviews`).
- **Relasi Antartabel**: Tidak menggunakan relasi (agregasi langsung).
- **Contoh Kode Query**:
  ```php
  $totalReviewsCount = Review::where('is_approved', true)->count();
  $avgRating = Review::where('is_approved', true)->avg('rating') ?: 5.0;
  ```
- **Penjelasan Cara Kerja**: Menjalankan fungsi SQL agregat `COUNT(*)` dan `AVG(rating)` langsung pada tabel `reviews` dengan filter `is_approved = 1`. Jika belum ada ulasan, nilai default ditetapkan ke `5.0`.
- **Hasil Data yang Diharapkan**: Nilai integer untuk jumlah ulasan dan nilai float (misal `4.85`) untuk skor rata-rata.

---

### Query 1.4: Pengambilan Detail Paket Wisata Berdasarkan Slug
- **Nama Fitur**: Detail Paket Wisata (`/packages/{slug}`).
- **Tujuan Query**: Menampilkan informasi lengkap satu paket wisata beserta 3 paket alternatif lainnya.
- **Lokasi File Implementasi**: `app/Http/Controllers/HomeController.php` (baris 86–87).
- **Model dan Tabel yang Digunakan**: `App\Models\Package` (tabel: `packages`).
- **Relasi Antartabel**: Tidak menggunakan relasi.
- **Contoh Kode Query**:
  ```php
  $package = Package::where('slug', $slug)->firstOrFail();
  $otherPackages = Package::where('is_active', true)
      ->where('id', '!=', $package->id)
      ->take(3)
      ->get();
  ```
- **Penjelasan Cara Kerja**: Mencari baris paket yang memiliki nilai kolom `slug` persis sama dengan parameter URL. Jika tidak ditemukan, otomatis melempar `ModelNotFoundException` (HTTP 404). Kueri kedua mengambil 3 paket lain di luar paket yang sedang dibuka.
- **Hasil Data yang Diharapkan**: 1 objek `Package` utama dan koleksi 3 objek `Package` rekomendasi.

---

# BAGIAN 2: QUERY ADMIN

### Query 2.1: Agregasi Total Omzet Terverifikasi
- **Nama Fitur**: Dashboard Admin (Kartu Finansial).
- **Tujuan Query**: Menghitung total omzet pendapatan riil yang sudah terbayar dan sah masuk ke paguyuban.
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 33).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` (tabel: `bookings`).
- **Relasi Antartabel**: Tidak menggunakan relasi.
- **Contoh Kode Query**:
  ```php
  $totalRevenue = Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])
      ->sum('paid_amount');
  ```
- **Penjelasan Cara Kerja**: Menjumlahkan nilai kolom `paid_amount` (`SUM(paid_amount)`) khusus untuk pesanan yang berstatus `FULL_PAID` atau `DP_PAID`. Pesanan `PENDING` atau `CANCELLED` tidak dihitung.
- **Hasil Data yang Diharapkan**: Nilai float/desimal akumulasi omzet rupiah (misal: `12500000.00`).

---

### Query 2.2: Agregasi Jumlah Pelanggan Unik
- **Nama Fitur**: Dashboard Admin (Statistik Pelanggan).
- **Tujuan Query**: Menghitung jumlah wisatawan unik yang pernah melakukan pemesanan berdasarkan nomor kontak WhatsApp/telepon pemesan.
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 34).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` (tabel: `bookings`).
- **Relasi Antartabel**: Tidak menggunakan relasi.
- **Contoh Kode Query**:
  ```php
  $totalCustomersCount = Booking::distinct('customer_phone')->count('customer_phone');
  ```
- **Penjelasan Cara Kerja**: Mengeksekusi perintah SQL `SELECT COUNT(DISTINCT customer_phone) FROM bookings` untuk mengeliminasi nomor ganda jika ada wisatawan yang memesan lebih dari satu kali.
- **Hasil Data yang Diharapkan**: Bilangan bulat total pelanggan unik (misal: `28`).

---

### Query 2.3: Monitoring Kesiapan Unit Armada Jeep Pangkalan
- **Nama Fitur**: Dashboard Admin & Monitoring Armada.
- **Tujuan Query**: Memantau jumlah armada yang siap di pangkalan (*Ready*), sedang bertugas jalan (*On Trip*), dan yang sedang dalam perbaikan (*Maintenance*).
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 37–40).
- **Model dan Tabel yang Digunakan**: `App\Models\Jeep` (tabel: `jeeps`).
- **Relasi Antartabel**: Tidak menggunakan relasi.
- **Contoh Kode Query**:
  ```php
  $readyJeepsCount = Jeep::where('status', 'READY')->count();
  $onTripJeepsCount = Jeep::where('status', 'ON_TRIP')->count();
  $maintenanceJeepsCount = Jeep::where('status', 'MAINTENANCE')->count();
  $totalJeepsCount = Jeep::count();
  ```
- **Penjelasan Cara Kerja**: Menghitung baris data unit jeep pada tabel `jeeps` berdasarkan nilai enumerasi kolom `status`.
- **Hasil Data yang Diharapkan**: 4 variabel integer status ketersediaan unit armada paguyuban.

---

### Query 2.4: Metrik Booking Harian Hari Ini & Kemarin
- **Nama Fitur**: Dashboard Admin (Kartu Pertumbuhan Pesanan).
- **Tujuan Query**: Menghitung volume booking hari ini dan membandingkannya dengan kemarin untuk menghitung persentase tren pertumbuhan.
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 47–59).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` (tabel: `bookings`).
- **Relasi Antartabel**: Tidak menggunakan relasi.
- **Contoh Kode Query**:
  ```php
  $today = now()->toDateString();
  $yesterday = now()->subDay()->toDateString();

  $todayBookingsCount = Booking::where(function ($q) use ($today) {
      $q->whereDate('trip_date', $today)->orWhereDate('created_at', $today);
  })->where('booking_status', '!=', 'CANCELLED')->count();

  $yesterdayBookingsCount = Booking::where(function ($q) use ($yesterday) {
      $q->whereDate('trip_date', $yesterday)->orWhereDate('created_at', $yesterday);
  })->where('booking_status', '!=', 'CANCELLED')->count();
  ```
- **Penjelasan Cara Kerja**: Menghitung pesanan aktif yang jadwal tripnya atau waktu pembuatannya jatuh pada hari ini / kemarin, tidak berstatus `CANCELLED`.
- **Hasil Data yang Diharapkan**: Dua nilai integer jumlah pesanan dan persentase perubahannya.

---

### Query 2.5: Pengambilan 5 Booking Terkini (Eager Loading Optimal)
- **Nama Fitur**: Dashboard Admin (Widget Tabel Ringkas).
- **Tujuan Query**: Mengambil 5 reservasi terbaru lengkap dengan paket, slot waktu, armada jeep, pengemudi, dan pembayaran terkait tanpa masalah N+1.
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 136–139).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` berserta relasinya.
- **Relasi Antartabel**:
  - `bookings.package_id` -> `packages.id` (BelongsTo)
  - `bookings.slot_id` -> `schedule_slots.id` (BelongsTo)
  - `bookings.id` -> `booking_assignments.booking_id` -> `jeeps` & `drivers` (HasMany & BelongsTo)
  - `bookings.id` -> `payments.booking_id` (HasMany)
- **Contoh Kode Query**:
  ```php
  $recentBookings = Booking::with(['package', 'slot', 'assignments.jeep', 'assignments.driver', 'payments'])
      ->orderBy('id', 'desc')
      ->take(5)
      ->get();
  ```
- **Penjelasan Cara Kerja**: Melakukan query utama pada tabel `bookings` (`ORDER BY id DESC LIMIT 5`), dilanjutkan dengan pemuatan relasi menggunakan klausa SQL `IN (...)`.
- **Hasil Data yang Diharapkan**: Koleksi 5 objek `Booking` terbaru lengkap dengan seluruh data relasi operasionalnya.

---

### Query 2.6: Manajemen Daftar Booking dengan Filter dan Pagination
- **Nama Fitur**: Kasir Loket & Daftar Reservasi (`admin/bookings`).
- **Tujuan Query**: Menampilkan daftar seluruh pemesanan dengan dukungan filter status, kata kunci pencarian, dan pemotongan halaman 15 baris per halaman.
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 214–235).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` dan relasi terkait.
- **Contoh Kode Query**:
  ```php
  $bookings = Booking::with(['package', 'slot', 'pickupZone', 'assignments.jeep', 'assignments.driver', 'payments'])
      ->when($request->status, fn ($q, $st) => $q->where('booking_status', $st))
      ->when($request->search, function ($q, $s) {
          $q->where('booking_code', 'like', "%{$s}%")
            ->orWhere('customer_name', 'like', "%{$s}%")
            ->orWhere('customer_phone', 'like', "%{$s}%");
      })
      ->orderBy('id', 'desc')
      ->paginate(15);
  ```
- **Penjelasan Cara Kerja**: Menggunakan metode `when()` untuk menyuntikkan klausa kondisi SQL dinamis jika filter tersedia, dan memecah dataset dengan `paginate(15)` (`LIMIT 15 OFFSET ...`).
- **Hasil Data yang Diharapkan**: Objek `LengthAwarePaginator` berisi 15 reservasi per halaman.

---

### Query 2.7: Transaksi Pelunasan Kasir Loket Basecamp
- **Nama Fitur**: Kasir Loket (Pelunasan Sisa Tagihan 70%).
- **Tujuan Query**: Menerima sisa pembayaran di kasir loket secara atomik, mencatat record payment cash, dan mengupdate status booking menjadi lunas.
- **Lokasi File Implementasi**: `app/Http/Controllers/AdminController.php` (baris 255–285).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking`, `App\Models\Payment`, `App\Models\ActivityLog`.
- **Contoh Kode Query**:
  ```php
  DB::transaction(function () use ($booking) {
      $remaining = $booking->remaining_amount;

      Payment::create([
          'booking_id' => $booking->id,
          'transaction_ref' => 'CASH-SETTLE-' . strtoupper(Str::random(6)),
          'payment_stage' => 'SETTLEMENT',
          'gross_amount' => $remaining,
          'payment_type' => 'CASH',
          'payment_status' => 'SETTLED',
          'paid_at' => now(),
      ]);

      $booking->update([
          'booking_status' => 'FULL_PAID',
          'paid_amount' => $booking->paid_amount + $remaining,
          'remaining_amount' => 0.00,
      ]);

      ActivityLog::logBookingSettled($booking, $remaining);
  });
  ```
- **Penjelasan Cara Kerja**: Menjalankan blok `DB::transaction()` untuk memastikan pembuatan baris di tabel `payments` dan pembaruan `bookings` bersifat ACID (seluruhnya berhasil atau dibatalkan otomatis jika ada galat).
- **Hasil Data yang Diharapkan**: Sisa tagihan menjadi Rp 0, status booking menjadi `FULL_PAID`.

---

# BAGIAN 3: QUERY BOOKING

### Query 3.1: Pengambilan Paket Wisata untuk Form Reservasi
- **Nama Fitur**: Form Booking (`/booking`).
- **Tujuan Query**: Mengambil daftar paket wisata aktif dan paket yang dipilih secara default via query string.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php` (baris 58–59).
- **Model dan Tabel yang Digunakan**: `App\Models\Package` (tabel: `packages`).
- **Contoh Kode Query**:
  ```php
  $packages = Package::where('is_active', true)->get();
  $selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();
  ```
- **Penjelasan Cara Kerja**: Mengambil semua paket dengan status aktif (`is_active = 1`), kemudian mencari paket spesifik berdasarkan slug atau mengambil entri pertama sebagai default fallback.
- **Hasil Data yang Diharapkan**: Koleksi paket dan objek paket yang terpilih.

---

### Query 3.2: Pengambilan Sesi Jam Keberangkatan Aktif
- **Nama Fitur**: Form Booking (Pilihan Waktu).
- **Tujuan Query**: Menyajikan daftar jam keberangkatan pangkalan resmi yang dapat dipilih wisatawan.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php` (baris 61).
- **Model dan Tabel yang Digunakan**: `App\Models\ScheduleSlot` (tabel: `schedule_slots`).
- **Contoh Kode Query**:
  ```php
  $slots = ScheduleSlot::where('is_active', true)->get();
  ```
- **Penjelasan Cara Kerja**: Mengambil record jam sesi yang dibuka operasionalnya (`is_active = 1`).
- **Hasil Data yang Diharapkan**: Koleksi objek `ScheduleSlot` (Sunrise 04:30, Sesi 1 07:00, Sesi 2 09:00, dst.).

---

### Query 3.3: Pemeriksaan Ketersediaan Kuota Armada Slot
- **Nama Fitur**: Proses Simpan Pemesanan (Slot Capacity Guard).
- **Tujuan Query**: Mencegah *overbooking* armada pangkalan dengan memeriksa sisa jeep pada tanggal dan sesi yang dipilih.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php` dan `app/Models/ScheduleSlot.php`.
- **Model dan Tabel yang Digunakan**: `App\Models\ScheduleSlot` (tabel: `schedule_slots`) dan `App\Models\Booking` (tabel: `bookings`).
- **Relasi Antartabel**: `schedule_slots.id` -> `bookings.slot_id` (HasMany).
- **Contoh Kode Query**:
  ```php
  // Pada ScheduleSlot.php
  public function bookedJeepsCount(string $tripDate): int
  {
      return (int) $this->bookings()
          ->where('trip_date', $tripDate)
          ->whereIn('booking_status', ['PENDING', 'DP_PAID', 'FULL_PAID'])
          ->where(function ($q) {
              $q->whereNull('lock_expires_at')->orWhere('lock_expires_at', '>', now());
          })
          ->sum('jeep_quantity');
  }

  public function isAvailableFor(string $tripDate, int $requiredJeeps = 1): bool
  {
      $booked = $this->bookedJeepsCount($tripDate);
      return ($booked + $requiredJeeps) <= $this->max_jeeps;
  }
  ```
- **Penjelasan Cara Kerja**: Menjumlahkan seluruh `jeep_quantity` pada slot dan tanggal tersebut yang berstatus sah atau masih berstatus `PENDING` dengan masa kunci yang belum lewat (`lock_expires_at > now()`). Jika `booked + required > max_jeeps`, pemesanan ditolak.
- **Hasil Data yang Diharapkan**: Nilai boolean `true` jika kuota mencukupi, atau `false` jika penuh.

---

### Query 3.4: Penyimpanan Entitas Pemesanan Baru
- **Nama Fitur**: Form Booking (Submit Reservasi).
- **Tujuan Query**: Menyimpan data reservasi wisatawan secara komprehensif ke tabel basis data.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php` (baris 250–275).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` (tabel: `bookings`).
- **Contoh Kode Query**:
  ```php
  $booking = Booking::create([
      'booking_code' => $bookingCode,
      'customer_name' => $validated['customer_name'],
      'customer_phone' => $validated['customer_phone'],
      'customer_email' => $validated['customer_email'] ?? null,
      'package_id' => $package->id,
      'slot_id' => $validated['slot_id'],
      'trip_date' => $validated['trip_date'],
      'jeep_quantity' => $jeepQuantity,
      'passenger_count' => $validated['passenger_count'],
      'arrival_type' => $arrivalType,
      'pickup_zone_id' => $pickupZoneId,
      'pickup_address' => $address,
      'pickup_lat' => $pickupLat,
      'pickup_lng' => $pickupLng,
      'pickup_distance_km' => $pickupKm,
      'package_subtotal' => $packageSubtotal,
      'pickup_fee' => $pickupFee,
      'total_amount' => $totalAmount,
      'paid_amount' => $payableAmount,
      'remaining_amount' => $remainingAmount,
      'payment_mode' => $validated['payment_mode'],
      'booking_status' => 'PENDING',
      'lock_expires_at' => now()->addMinutes(30),
      'snap_token' => $snapToken,
  ]);
  ```
- **Penjelasan Cara Kerja**: Menjalankan query SQL `INSERT INTO bookings (...) VALUES (...)` dengan perlindungan mass assignment `$fillable`.
- **Hasil Data yang Diharapkan**: Objek entitas baru `Booking` dengan status `PENDING` dan kunci slot 30 menit.

---

# BAGIAN 4: QUERY PICKUP ZONE

### Query 4.1: Pengambilan Seluruh Zona Penjemputan
- **Nama Fitur**: Penjemputan Shuttle Wisatawan.
- **Tujuan Query**: Mengambil daftar zona penjemputan resmi yang tercatat di sistem beserta tarif dasarnya.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php`.
- **Model dan Tabel yang Digunakan**: `App\Models\PickupZone` (tabel: `pickup_zones`).
- **Contoh Kode Query**:
  ```php
  $zones = PickupZone::orderBy('additional_fee', 'asc')->get();
  ```
- **Penjelasan Cara Kerja**: Menarik seluruh baris dari tabel `pickup_zones` diurutkan dari tarif termurah ke termahal.
- **Hasil Data yang Diharapkan**: 4 zona penjemputan resmi: Basecamp (Rp 0), Cangkringan & Pakem (Rp 50.000), Sleman & Kota (Rp 150.000), Bandara YIA (Rp 300.000).

---

### Query 4.2: Pencarian Zona Berdasarkan ID
- **Nama Fitur**: Kalkulasi Biaya Shuttle Berdasarkan Pilihan Zona.
- **Tujuan Query**: Mengambil tarif dasar zona penjemputan saat wisatawan memilih zona tertentu.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php`.
- **Model dan Tabel yang Digunakan**: `App\Models\PickupZone` (tabel: `pickup_zones`).
- **Contoh Kode Query**:
  ```php
  $zone = PickupZone::find($request->input('pickup_zone_id'));
  ```
- **Penjelasan Cara Kerja**: Mengambil satu baris data berdasarkan Primary Key `id`.
- **Hasil Data yang Diharapkan**: Objek `PickupZone` yang memuat kolom `additional_fee`.

---

### Query 4.3: Perhitungan Biaya Pickup dan Penyimpanan ke Data Booking
- **Nama Fitur**: Kalkulasi Biaya Antar-Jemput (Rumus Jeep).
- **Tujuan Query**: Mengalikan tarif penjemputan dengan jumlah armada jeep dan menyimpannya pada kolom pemesanan.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php` (baris 160–190).
- **Model dan Tabel yang Digunakan**: `App\Models\PickupZone`, `App\Models\Booking`.
- **Contoh Kode Query**:
  ```php
  if ($request->filled('pickup_zone_id')) {
      $zone = PickupZone::find($request->input('pickup_zone_id'));
      if ($zone) {
          $pickupZoneId = $zone->id;
          $pickupFee = (float) ($zone->additional_fee * $jeepQuantity);
      }
  } else {
      $pickupFee = $pickupKm > self::FREE_KM ? (float) (self::FEE_PER_JEEP * $jeepQuantity) : 0.00;
  }

  $totalAmount = $packageSubtotal + $pickupFee;
  ```
- **Penjelasan Cara Kerja**: Memastikan ketentuan bahwa biaya jemput dihitung per unit armada mobil offroad yang diberangkatkan, kemudian menjumlahkannya dengan subtotal sewa jeep untuk memperoleh nominal total booking.
- **Hasil Data yang Diharapkan**: Nilai desimal `pickup_fee` yang tersimpan pada tabel `bookings`.

---

# BAGIAN 5: QUERY PAYMENT

### Query 5.1: Pengambilan Pemesanan yang Akan Dibayar Beserta Histori Transaksi
- **Nama Fitur**: Halaman Pembayaran (`/booking/{code}/payment`).
- **Tujuan Query**: Mengambil detail tagihan dan seluruh catatan riwayat transaksi pembayaran sebelumnya yang terhubung dengan pesanan ini.
- **Lokasi File Implementasi**: `app/Http/Controllers/BookingController.php` (baris 257–263).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking` dan `App\Models\Payment`.
- **Relasi Antartabel**: `bookings.id` -> `payments.booking_id` (HasMany).
- **Contoh Kode Query**:
  ```php
  $booking = Booking::with(['package', 'slot', 'pickupZone', 'payments' => function ($q) {
          $q->orderBy('id', 'desc');
      }])
      ->where('booking_code', $code)
      ->firstOrFail();
  ```
- **Penjelasan Cara Kerja**: Mengambil data booking berdasarkan kolom `booking_code` dan melakukan eager loading relasi `payments` yang diurutkan dari transaksi paling baru.
- **Hasil Data yang Diharapkan**: 1 objek `Booking` lengkap dengan relasi paket dan riwayat pembayarannya.

---

### Query 5.2: Pembuatan Record Transaksi Pembayaran Baru
- **Nama Fitur**: Inisialisasi Pembayaran Gateway / Kasir.
- **Tujuan Query**: Mencatat baris transaksi baru di tabel `payments` dengan referensi order ID dan status awal `PENDING`.
- **Lokasi File Implementasi**: `app/Services/MidtransCoreApiService.php` dan `app/Http/Controllers/PaymentController.php`.
- **Model dan Tabel yang Digunakan**: `App\Models\Payment` (tabel: `payments`).
- **Contoh Kode Query**:
  ```php
  $payment = Payment::create([
      'booking_id' => $booking->id,
      'order_id' => $orderId,
      'transaction_ref' => $transactionId,
      'payment_stage' => $booking->payment_mode === 'DP' ? 'DOWN_PAYMENT' : 'FULL_PAYMENT',
      'payment_type' => 'bank_transfer',
      'payment_method' => $method,
      'gross_amount' => $booking->paid_amount,
      'payment_status' => 'PENDING',
      'va_numbers' => $vaArray,
  ]);
  ```
- **Penjelasan Cara Kerja**: Menulis record transaksi baru ke basis data dengan nominal rupiah yang diambil dari kolom `paid_amount` pada pemesanan.
- **Hasil Data yang Diharapkan**: Objek entitas `Payment` dengan status `PENDING`.

---

### Query 5.3: Polling Status Pembayaran Terkini & Auto-Expire
- **Nama Fitur**: Real-time Payment Status Polling (`/booking/{code}/status`).
- **Tujuan Query**: Mengambil status mutakhir pemesanan dan mengeksekusi pembatalan otomatis jika waktu hold 30 menit telah habis.
- **Lokasi File Implementasi**: `app/Http/Controllers/PaymentController.php` (baris 88–96).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking`, `App\Models\Payment`.
- **Contoh Kode Query**:
  ```php
  $booking = Booking::with('payments')->where('booking_code', $code)->firstOrFail();

  if ($booking->booking_status === 'PENDING' && $booking->lock_expires_at && $booking->lock_expires_at->isPast()) {
      $booking->update(['booking_status' => 'CANCELLED']);
      $booking->payments()->where('payment_status', 'PENDING')->update(['payment_status' => 'EXPIRED']);
  }
  ```
- **Penjelasan Cara Kerja**: Jika booking masih `PENDING` dan waktu sekarang telah melampaui `lock_expires_at`, status booking otomatis diubah menjadi `CANCELLED` dan status pembayarannya menjadi `EXPIRED`.
- **Hasil Data yang Diharapkan**: Respon JSON status pembayaran untuk kontrol navigasi frontend.

---

# BAGIAN 6: QUERY MIDTRANS

### Query 6.1: Pencarian Record Transaksi Berdasarkan Order ID Webhook
- **Nama Fitur**: Webhook Notifikasi Midtrans (`/webhook/midtrans`).
- **Tujuan Query**: Menemukan record pembayaran yang cocok dengan `order_id` yang dikirimkan oleh server Midtrans.
- **Lokasi File Implementasi**: `app/Http/Controllers/PaymentWebhookController.php` (baris 48–51).
- **Model dan Tabel yang Digunakan**: `App\Models\Payment` (tabel: `payments`).
- **Contoh Kode Query**:
  ```php
  $payment = Payment::with('booking')->where('order_id', $orderId)->first();
  ```
- **Penjelasan Cara Kerja**: Mencari baris record di tabel `payments` berdasarkan kolom indeks unik `order_id` dan memuat relasi pemesanannya.
- **Hasil Data yang Diharapkan**: Objek `Payment` atau `null` jika order ID tidak cocok.

---

### Query 6.2: Cek Idempotensi Transaksi Berhasil
- **Nama Fitur**: Idempotensi Webhook.
- **Tujuan Query**: Mencegah pemrosesan ulang transaksi yang sudah berstatus `SETTLED` agar tidak terjadi duplikasi saldo atau entri berulang.
- **Lokasi File Implementasi**: `app/Http/Controllers/PaymentWebhookController.php` (baris 80–83).
- **Model dan Tabel yang Digunakan**: `App\Models\Payment` (tabel: `payments`).
- **Contoh Kode Query**:
  ```php
  if ($payment && $payment->payment_status === 'SETTLED' && in_array($transactionStatus, ['settlement', 'capture'])) {
      return response()->json(['status' => 'success', 'message' => 'Transaksi sudah diselesaikan sebelumnya.'], 200);
  }
  ```
- **Penjelasan Cara Kerja**: Mengevaluasi apakah record `payment` sudah bernilai `SETTLED`. Jika ya, langsung merespon HTTP 200 OK tanpa menulis ulang database.
- **Hasil Data yang Diharapkan**: Operasi idempoten tanpa duplikasi efek samping.

---

### Query 6.3: Penguncian Baris Pesimistik (Row Locking) & Eksekusi Settlement
- **Nama Fitur**: Midtrans Settlement Atomik.
- **Tujuan Query**: Mengunci baris data pesanan agar tidak mengalami *race condition* dan mengupdate status pesanan secara sah menjadi `FULL_PAID` atau `DP_PAID`.
- **Lokasi File Implementasi**: `app/Http/Controllers/PaymentWebhookController.php` (baris 97–152).
- **Model dan Tabel yang Digunakan**: `App\Models\Booking`, `App\Models\Payment`.
- **Contoh Kode Query**:
  ```php
  return DB::transaction(function () use ($booking, $payment, $orderId, $transactionStatus, $fraudStatus, $receivedGrossInt, $paymentType, $payload) {
      // Kunci baris booking untuk update eksklusif (SELECT ... FOR UPDATE)
      $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->first();

      if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
          if ($fraudStatus === 'accept' || empty($fraudStatus)) {
              $totalAmountInt = (int) round($lockedBooking->total_amount);

              if (($payment && $payment->payment_stage === 'FULL_PAYMENT') || $lockedBooking->payment_mode === 'FULL' || $receivedGrossInt >= $totalAmountInt) {
                  $newBookingStatus = 'FULL_PAID';
                  $newPaidAmount = $totalAmountInt;
                  $newRemainingAmount = 0;
              } else {
                  $newBookingStatus = 'DP_PAID';
                  $newPaidAmount = $receivedGrossInt;
                  $newRemainingAmount = max(0, $totalAmountInt - $receivedGrossInt);
              }

              $lockedBooking->update([
                  'booking_status'   => $newBookingStatus,
                  'paid_amount'      => $newPaidAmount,
                  'remaining_amount' => $newRemainingAmount,
                  'lock_expires_at'  => null,
              ]);

              if ($payment) {
                  $payment->update([
                      'payment_status'  => 'SETTLED',
                      'transaction_ref' => $payload['transaction_id'] ?? $orderId,
                      'paid_at'         => now(),
                  ]);
              }
          }
      }
  });
  ```
- **Penjelasan Cara Kerja**: Menggunakan `lockForUpdate()` di dalam transaksi atomik untuk menjamin baris data tidak termodifikasi oleh koneksi lain selama proses settlement berlangsung. Melepaskan `lock_expires_at` (diset menjadi `null`) karena armada sudah resmi terpesan.
- **Hasil Data yang Diharapkan**: Status pesanan berubah menjadi `FULL_PAID` atau `DP_PAID` dan transaksi payment berstatus `SETTLED`.

---

### Query 6.4: Penanganan Pembayaran Kedaluwarsa atau Dibatalkan
- **Nama Fitur**: Webhook Penanganan Pembatalan (Expire / Cancel / Deny).
- **Tujuan Query**: Mengubah status payment menjadi `EXPIRED` atau `FAILED` dan membatalkan pesanan jika belum ada transaksi sah lain.
- **Lokasi File Implementasi**: `app/Http/Controllers/PaymentWebhookController.php` (baris 155–190).
- **Model dan Tabel yang Digunakan**: `App\Models\Payment`, `App\Models\Booking`.
- **Contoh Kode Query**:
  ```php
  $newPaymentStatus = ($transactionStatus === 'expire') ? 'EXPIRED' : 'FAILED';
  if ($payment) {
      $payment->update(['payment_status' => $newPaymentStatus]);
  }

  // Lindungi booking jika sudah sah dibayar sebelumnya
  if (in_array($lockedBooking->booking_status, ['FULL_PAID', 'DP_PAID', 'WAITING_CONFIRMATION'])) {
      return response()->json(['message' => 'Status transaksi gagal dicatat tanpa merusak booking sah.']);
  }

  $hasOtherPending = $lockedBooking->payments()
      ->where('id', '!=', $payment ? $payment->id : 0)
      ->where('payment_status', 'PENDING')
      ->exists();

  if (!$hasOtherPending) {
      $lockedBooking->update(['booking_status' => 'CANCELLED']);
  }
  ```
- **Penjelasan Cara Kerja**: Memperbarui status pembayaran yang gagal, dan membatalkan status booking hanya jika pesanan belum sah lunas dan tidak ada transaksi pembayaran tertunda lainnya.
- **Hasil Data yang Diharapkan**: Pembayaran gagal dicatat secara presisi tanpa merusak pesanan yang sudah berstatus lunas.

