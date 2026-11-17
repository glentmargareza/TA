# Dokumentasi Fitur Admin & Dashboard – Jeep Wukirsari Adventure Tour

Dokumentasi ini menjelaskan implementasi teknis, pengelolaan data, otorisasi, dan arsitektur kueri pada Portal Administrasi & Kasir Loket Operasional Paguyuban Jeep Wisata Wukirsari.

---

## 1. Tujuan Fitur

Portal Admin dibangun untuk memfasilitasi pengurus paguyuban dan petugas kasir loket basecamp dalam:
1. Memantau performa bisnis dan operasional harian secara *real-time* (omzet pendapatan, total booking, pelanggan unik, dan kesiapan armada).
2. Melakukan manajemen reservasi wisatawan, verifikasi pembayaran manual, dan penerimaan pelunasan uang muka (**DP 30%** ke **Lunas 100%**) di loket basecamp.
3. Melakukan *dispatching* (penugasan armada jeep dan pengemudi/driver) pada setiap pesanan yang telah terkonfirmasi.
4. Mengelola ketersediaan armada jeep (*Ready*, *On Trip*, *Maintenance*) dan data driver paguyuban.
5. Mengelola katalog paket wisata, rute destinasi, harga, serta kuota sesi jadwal keberangkatan harian.
6. Mengunduh laporan rekapitulasi penjualan dan transaksi operasional dalam format spreadsheet CSV.

---

## 2. Penjelasan Fungsi Fitur

Portal Admin terdiri dari beberapa modul utama:
- **Dashboard & Analisis Operasional**: Kartu ringkasan metrik (Booking Hari Ini, Booking Mendatang, Selesai, Dibatalkan, Total Pelanggan Unik, dan Total Omzet), grafik tren penjualan harian (7, 14, 30 hari), diagram donat status pembayaran, dan status kesiapan unit armada.
- **Manajemen Booking & Kasir Loket**: Daftar tabel pemesanan terlengkap disertai filter status, pencarian kode booking/nama pemesan, modal detail tiket, tombol kasir *"Terima Pelunasan"*, serta tombol konfirmasi cepat ke WhatsApp wisatawan.
- **Manajemen Armada & Driver**: Pengaturan status unit mobil offroad, aktivasi seluruh armada pangkalan dalam satu klik, penambahan nopol/model mobil, dan penonaktifan driver yang berhalangan hadir.
- **Kelola Paket Wisata & Sesi Jadwal**: Pengaturan CRUD paket wisata, penyesuaian tarif per jeep, foto galeri, rute destinasi, serta aktivasi/deaktivasi jam keberangkatan (sesi subuh sunrise hingga sore).
- **Laporan Penjualan (Export CSV)**: Rekapitulasi transaksi berdasarkan rentang tanggal, filter status pembayaran, agregasi total omzet bersih, dan tombol ekspor langsung ke format CSV / Excel.
- **Notifikasi & Log Aktivitas**: Pencatatan otomatis setiap aksi penting (booking baru, pelunasan kasir, penugasan driver, peringatan kuota penuh).

---

## 3. File yang Digunakan

| Komponen | Lokasi File | Fungsi |
| :--- | :--- | :--- |
| **Controller** | `app/Http/Controllers/AdminController.php` | Controller utama yang menangani seluruh alur logika bisnis portal admin. |
| **View Dashboard** | `resources/views/admin/dashboard.blade.php` | Tampilan dashboard metrik, grafik Chart.js, dan 5 booking terkini. |
| **View Booking** | `resources/views/admin/bookings.blade.php` | Tampilan tabel operasional kasir loket dan dispatching driver. |
| **View Armada** | `resources/views/admin/armada.blade.php` | Manajemen unit jeep dan status sopir. |
| **View Paket** | `resources/views/admin/packages.blade.php` | Manajemen katalog paket wisata dan tarif sewa. |
| **View Jadwal** | `resources/views/admin/schedules.blade.php` | Manajemen sesi keberangkatan dan batas maksimal jeep per sesi. |
| **View Laporan** | `resources/views/admin/reports.blade.php` | Rekapitulasi laporan keuangan dan tombol unduh CSV. |
| **View Notifikasi** | `resources/views/admin/notifications.blade.php` | Log kronologis aktivitas operasional basecamp. |
| **View Settings** | `resources/views/admin/settings.blade.php` | Pengaturan profil admin, alamat basecamp, dan banner landing page. |
| **Sidebar** | `resources/views/admin/partials/sidebar.blade.php` | Navigasi menu terpadu dengan penanda halaman aktif (*active state*). |

---

## 4. Route yang Digunakan

Seluruh route admin dikelompokkan dengan prefix `/admin` dan nama route `admin.*`:

```php
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Monitoring
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/armada', [AdminController::class, 'armada'])->name('armada');
    Route::get('/notifikasi', [AdminController::class, 'notifications'])->name('notifications');

    // Aksi Operasional Kasir & Penugasan
    Route::post('/bookings/{id}/settle', [AdminController::class, 'settleDp'])->name('settle');
    Route::post('/bookings/{id}/assign', [AdminController::class, 'assignDriver'])->name('assign');
    Route::post('/bookings/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('cancel');

    // Manajemen Unit Jeep & Driver
    Route::post('/jeep', [AdminController::class, 'storeJeep'])->name('jeeps.store');
    Route::post('/jeep/{id}/status', [AdminController::class, 'updateJeepStatus'])->name('jeeps.status');
    Route::delete('/jeep/{id}', [AdminController::class, 'destroyJeep'])->name('jeeps.destroy');
    Route::post('/jeep/activate-all', [AdminController::class, 'activateAllJeeps'])->name('jeeps.activateAll');
    Route::post('/driver/{id}/status', [AdminController::class, 'toggleDriverStatus'])->name('drivers.status');
    Route::delete('/driver/{id}', [AdminController::class, 'destroyDriver'])->name('drivers.destroy');

    // Laporan & Ekspor Data
    Route::get('/laporan', [AdminController::class, 'reports'])->name('reports');
    Route::get('/laporan/export-csv', [AdminController::class, 'exportReportsCsv'])->name('reports.csv');

    // Kelola Paket & Jadwal
    Route::get('/paket', [AdminController::class, 'packages'])->name('packages');
    Route::post('/paket', [AdminController::class, 'storePackage'])->name('packages.store');
    Route::put('/paket/{id}', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::post('/paket/{id}/toggle', [AdminController::class, 'togglePackage'])->name('packages.toggle');
    Route::delete('/paket/{id}', [AdminController::class, 'destroyPackage'])->name('packages.destroy');

    Route::get('/jadwal', [AdminController::class, 'schedules'])->name('schedules');
    Route::post('/jadwal', [AdminController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/jadwal/{id}', [AdminController::class, 'updateSchedule'])->name('schedules.update');
    Route::post('/jadwal/{id}/toggle', [AdminController::class, 'toggleSchedule'])->name('schedules.toggle');
    Route::delete('/jadwal/{id}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy');
});
```

---

## 5. Controller dan Model yang Digunakan

### Controller: `App\Http\Controllers\AdminController`
Mengorkestrasikan kueri analitik, otentikasi role `admin`, transaksi database atomik pada kasir pelunasan, hingga pembuatan file stream CSV laporan.

### Models:
1. `App\Models\Booking`: Model entitas utama transaksi reservasi.
2. `App\Models\Payment`: Model entitas rincian transaksi uang masuk (Midtrans, Transfer BPD, Kasir Cash).
3. `App\Models\Jeep`: Model data armada fisik dan status operasional.
4. `App\Models\Driver`: Model pengemudi paguyuban.
5. `App\Models\BookingAssignment`: Model relasi *dispatching* armada dan supir ke pesanan.
6. `App\Models\Package`: Model katalog paket wisata.
7. `App\Models\ScheduleSlot`: Model sesi jam keberangkatan.
8. `App\Models\ActivityLog`: Model pencatatan log kronologis peristiwa sistem.
9. `App\Models\User`: Model akun pengguna dan role privilege.

---

## 6. Query Database yang Diterapkan

### A. Total Omzet Terverifikasi
```php
$totalRevenue = Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])->sum('paid_amount');
```
- **Tujuan**: Menghitung akumulasi seluruh uang masuk riil dari pesanan yang berstatus sah (Lunas maupun Uang Muka DP).

### B. Total Pelanggan Unik
```php
$totalCustomersCount = Booking::distinct('customer_phone')->count('customer_phone');
```
- **Tujuan**: Menghitung jumlah wisatawan unik yang pernah melakukan reservasi berdasarkan nomor telepon WhatsApp aktif pemesan.

### C. Monitoring Kesiapan Armada Jeep Pangkalan
```php
$readyJeepsCount = Jeep::where('status', 'READY')->count();
$onTripJeepsCount = Jeep::where('status', 'ON_TRIP')->count();
$maintenanceJeepsCount = Jeep::where('status', 'MAINTENANCE')->count();
$totalJeepsCount = Jeep::count();
```
- **Tujuan**: Menampilkan statistik ketersediaan unit mobil secara *real-time* untuk kebutuhan operasional pangkalan.

### D. Statistik Operasional Booking Harian & Perbandingan Hari Kemarin
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
- **Tujuan**: Menghitung volume pesanan hari ini serta menghitung persentase perubahan pertumbuhan (*growth rate*) dibandingkan hari sebelumnya.

### E. Booking Mendatang & Booking Selesai
```php
$upcomingBookingsCount = Booking::whereDate('trip_date', '>', $today)
    ->whereIn('booking_status', ['PENDING', 'DP_PAID', 'FULL_PAID'])
    ->count();

$completedBookingsCount = Booking::where('booking_status', 'COMPLETED')
    ->orWhere(function ($q) use ($today) {
        $q->whereDate('trip_date', '<', $today)
            ->whereIn('booking_status', ['FULL_PAID', 'DP_PAID']);
    })
    ->count();
```
- **Tujuan**: Memisahkan antrean jadwal keberangkatan yang perlu dipersiapkan armadanya dari trip yang sudah sukses dilaksanakan.

### F. Pengambilan 5 Booking Terkini (Eager Loading Optimal)
```php
$recentBookings = Booking::with(['package', 'slot', 'assignments.jeep', 'assignments.driver', 'payments'])
    ->orderBy('id', 'desc')
    ->take(5)
    ->get();
```
- **Tujuan**: Mengambil 5 reservasi terbaru untuk widget cepat dashboard tanpa menimbulkan masalah N+1 query.

### G. Pengambilan Daftar Booking dengan Filter dan Pagination
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
- **Tujuan**: Menyajikan data tabel pemesanan di loket kasir dengan fitur pencarian cepat dan pemotongan halaman per 15 baris data.

### H. Transaksi Atomik Pelunasan di Loket Kasir Basecamp
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
- **Tujuan**: Memastikan pelunasan sisa tagihan 70% di kasir dicatat secara atomik pada tabel `payments` dan memperbarui `bookings` tanpa risiko inkonsistensi saldo.

---

## 7. Relasi Tabel yang Digunakan

```
+--------------------+        1 : N        +-------------------------+        N : 1        +-------------------+
|      bookings      | ------------------> |   booking_assignments   | <------------------ |       jeeps       |
|  (id, status, ...) |                     |   (jeep_id, driver_id)  |                     |  (id, status)     |
+--------------------+                     +-------------------------+                     +-------------------+
         |                                              |
         | 1 : N                                        | N : 1
         v                                              v
+--------------------+                     +-------------------------+
|      payments      |                     |         drivers         |
|  (id, amount, ...) |                     |  (id, full_name, ...)   |
+--------------------+                     +-------------------------+
```

---

## 8. Validasi dan Keamanan

1. **Role-Based Access Control**:
   Setiap fungsi controller memverifikasi privilege pengguna:
   ```php
   $user = Auth::user();
   if (!$user || $user->role !== 'admin') {
       return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
   }
   ```
2. **CSRF Protection**: Seluruh aksi POST, PUT, dan DELETE dilindungi oleh token `@csrf`.
3. **Database Transactions**: Operasi kasir, penugasan armada, dan pembatalan pemesanan dibungkus dalam `DB::transaction()` dengan proteksi rollback otomatis jika terjadi kegagalan.
4. **Mass Assignment Guard**: Semua kolom diatur dalam `$fillable` pada masing-masing model terkait.
5. **Sanitasi Ekspor CSV**: Pencegahan serangan *CSV Formula Injection* dengan membersihkan awalan simbol berbahaya (`=`, `+`, `-`, `@`) pada data yang diekspor.

---

## 9. Cara Pengujian Fitur

1. **Pengujian Login Admin**:
   - Buka `/login` dan masuk dengan akun admin: `admin@jeepwukirsari.id` / `password`.
   - Pastikan diarahkan ke `/admin` dan seluruh widget data tampil tanpa error.
2. **Pengujian Kueri via Artisan Tinker**:
   ```bash
   php artisan tinker --execute="echo 'Total Omzet: Rp ' . number_format(App\Models\Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])->sum('paid_amount'), 0, ',', '.') . ' | Pelanggan: ' . App\Models\Booking::distinct('customer_phone')->count('customer_phone');"
   ```
   *Hasil yang diharapkan*: Menampilkan nominal omzet rupiah asli dan total pelanggan unik yang sinkron dengan basis data.

