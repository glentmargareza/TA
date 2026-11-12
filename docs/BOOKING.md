# Dokumentasi Fitur Pemesanan (Booking) – Jeep Wukirsari Adventure Tour

Dokumentasi ini menjelaskan arsitektur alur pemesanan (*booking flow*), validasi armada, kapasitas penumpang, pengecekan ketersediaan slot jadwal, dan penyimpanan data pemesanan.

---

## 1. Tujuan Fitur

Fitur Pemesanan (*Booking*) dirancang untuk memberikan kemudahan bagi wisatawan dalam melakukan reservasi jeep wisata secara mandiri (*self-service*), transparan, dan akurat. Sistem secara otomatis:
1. Menghitung kebutuhan armada mobil berdasarkan jumlah rombongan penumpang offroad (standar keselamatan maksimal 4 penumpang dewasa per jeep).
2. Memeriksa ketersediaan kuota armada pada sesi jam keberangkatan yang dipilih untuk mencegah *overbooking*.
3. Menyimpan rincian pemesanan, titik koordinat penjemputan shuttle, catatan khusus, dan preferensi metode pembayaran (**DP 30%** atau **Full 100%**).
4. Menerbitkan kode booking unik (`WKR-XXXXXX`) dan mengunci kuota slot selama 30 menit melalui mekanisme `lock_expires_at`.
5. Mengarahkan wisatawan ke halaman pembayaran digital atau bukti e-tiket resmi (*boarding pass*).

---

## 2. Penjelasan Alur Booking

```
+-----------------------------------------------------------------------------------+
|                            ALUR LENGKAP PEMESANAN (BOOKING)                       |
+-----------------------------------------------------------------------------------+
| 1. Form Reservasi (/booking)                                                      |
|    - Pilih Paket Wisata (Short, Medium, Long, Sunrise)                            |
|    - Pilih Tanggal & Sesi Jam Keberangkatan                                       |
|    - Masukkan Jumlah Penumpang -> Auto-kalkulasi Jumlah Jeep: ceil(pax / 4)       |
|    - Pilih Opsi Kedatangan: Datang Sendiri (Basecamp) atau Antar-Jemput Hotel     |
|    - Pilih Skema Pembayaran: DP 30% atau Lunas 100%                               |
|                                     │                                             |
|                               (Submit POST)                                       |
|                                     ▼                                             |
| 2. Validasi Backend & Ketersediaan Slot                                           |
|    - Cek kuota armada: (booked_jeeps + req_jeeps) <= slot.max_jeeps               |
|    - Generate Kode Unik: WKR-XXXXXX                                               |
|    - Kunci slot selama 30 menit (lock_expires_at = now() + 30m)                   |
|    - Simpan ke tabel 'bookings' dengan status 'PENDING'                           |
|                                     │                                             |
|                               (Redirect)                                          |
|                                     ▼                                             |
| 3. Halaman Pembayaran (/booking/{code}/payment)                                   |
|    - Pilih Midtrans (VA/QRIS/E-Wallet), Transfer Bank BPD, atau Bayar Cash Loket  |
|                                     │                                             |
|                             (Setelah Pembayaran)                                  |
|                                     ▼                                             |
| 4. Penerbitan E-Tiket Boarding Pass (/booking/{code}/success)                    |
|    - Menampilkan QR Code loket, ringkasan armada, dan tombol WhatsApp CS          |
+-----------------------------------------------------------------------------------+
```

---

## 3. File yang Digunakan

| Komponen | Lokasi File | Fungsi |
| :--- | :--- | :--- |
| **Controller** | `app/Http/Controllers/BookingController.php` | Menangani pembuatan form, validasi backend, kalkulasi jeep, dan penyimpanan pesanan. |
| **Model** | `app/Models/Booking.php` | Model transaksi pemesanan dengan relasi lengkap ke paket, slot, pembayaran, dan armada. |
| **Model** | `app/Models/ScheduleSlot.php` | Mengatur jam keberangkatan dan validasi ketersediaan armada per sesi. |
| **Model** | `app/Models/Package.php` | Mengambil data paket wisata dan tarif sewa per armada. |
| **Model** | `app/Models/PickupZone.php` | Mengatur zona tarif penjemputan shuttle. |
| **View Form** | `resources/views/booking/create.blade.php` | Formulir pemesanan interaktif dengan peta geolokasi dan kalkulator otomatis. |
| **View Tiket** | `resources/views/booking/success.blade.php` | Halaman e-tiket boarding pass resmi dengan QR code check-in pangkalan. |

---

## 4. Route yang Digunakan

```php
// Halaman form pemesanan
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');

// Proses submit form pemesanan
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Halaman e-tiket sukses
Route::get('/booking/{code}/success', [BookingController::class, 'success'])->name('booking.success');

// Endpoint kalkulator jarak penjemputan
Route::get('/booking/distance', [BookingController::class, 'distance'])->middleware('throttle:60,1')->name('booking.distance');
```

---

## 5. Controller dan Model yang Digunakan

### Controller: `App\Http\Controllers\BookingController`
- `create(Request $request)`: Mengambil paket wisata aktif, daftar sesi jam keberangkatan aktif, koordinat basecamp, dan merender form pemesanan.
- `store(Request $request)`: Memvalidasi input request, menghitung jumlah jeep, mengecek kapasitas slot, menghitung subtotal & biaya jemput, membuat kode unik `WKR-XXXXXX`, dan menyimpan data ke tabel `bookings`.
- `success(string $code)`: Mengambil data pemesanan terkonfirmasi beserta relasi paket, slot, dan pembayaran untuk ditampilkan pada e-tiket.

---

## 6. Query Database yang Diterapkan

### A. Mengambil Paket Wisata Aktif untuk Form Pemesanan
```php
$packages = Package::where('is_active', true)->get();
$selectedPackage = Package::where('slug', $selectedSlug)->first() ?? $packages->first();
```
- **Tujuan**: Memastikan hanya paket yang berstatus aktif yang dapat dipilih oleh wisatawan.

### B. Mengambil Sesi Jam Keberangkatan Aktif
```php
$slots = ScheduleSlot::where('is_active', true)->get();
```
- **Tujuan**: Menampilkan seluruh opsi jadwal keberangkatan resmi pangkalan yang sedang dibuka operasionalnya.

### C. Memeriksa Ketersediaan Kuota Armada Slot
```php
$slot = ScheduleSlot::findOrFail($validated['slot_id']);

if (!$slot->isAvailableFor($validated['trip_date'], $jeepQuantity)) {
    $remaining = $slot->remainingJeepsFor($validated['trip_date']);
    return back()->withInput()->withErrors([
        'slot_id' => "Mohon maaf, kuota armada untuk sesi {$slot->slot_label} pada tanggal {$validated['trip_date']} tersisa {$remaining} Jeep. Silakan pilih sesi jadwal lain."
    ]);
}
```
- **Implementasi pada Model `ScheduleSlot`**:
```php
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
- **Tujuan**: Mencegah pemesanan melebihi batas armada (misal 20 jeep per sesi) dengan menghitung booking sah (`FULL_PAID`, `DP_PAID`) serta booking `PENDING` yang masih dalam masa kunci 30 menit.

### D. Menyimpan Data Pemesanan Baru
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
- **Tujuan**: Menyimpan seluruh entitas pemesanan secara presisi ke tabel `bookings`.

---

## 7. Relasi Tabel yang Digunakan

```
                  +--------------------+
                  |      packages      |
                  +--------------------+
                            │ 1
                            │
                            │ N
+--------------------+      ▼      +--------------------+
|   schedule_slots   | ──(1:N)───> |      bookings      | <───(1:N)─── +------------------+
+--------------------+             +--------------------+              |   pickup_zones   |
                                            │                          +------------------+
                                            │ 1
                                            │
                                            │ N
                                            ▼
                                   +--------------------+
                                   |      payments      |
                                   +--------------------+
```

---

## 8. Validasi dan Keamanan

1. **Aturan Validasi Input Backend**:
   - `trip_date`: `required|date|after_or_equal:today` (tidak dapat memesan tanggal masa lalu).
   - `passenger_count`: `required|integer|min:1`.
   - `customer_name`: `required|string|max:100`.
   - `customer_phone`: `required|string|max:20`.
   - `payment_mode`: `required|in:FULL,DP`.
   - `arrival_type`: `nullable|in:self_drive,pickup`.
2. **Kalkulator Armada Cerdas**:
   - Dihitung secara tegas di backend: `$minJeeps = ($pax === 5 && $childCount >= 1) ? 1 : (int) ceil($pax / 4);`
   - Wisatawan tidak dapat memanipulasi jumlah jeep di bawah batas kapasitas aman offroad.
3. **Mekanisme Lock Expiration**:
   - Status awal adalah `PENDING` dengan `lock_expires_at = now()->addMinutes(30)`.
   - Jika pembayaran tidak diselesaikan dalam 30 menit, slot jeep otomatis dilepaskan kembali ke publik.

---

## 9. Cara Pengujian Fitur

1. **Pengujian Form Pemesanan**:
   - Akses `/booking`.
   - Masukkan 7 orang penumpang. Sistem pada UI dan backend otomatis mengalokasikan 2 unit jeep.
   - Pilih opsi pembayaran DP (30%). Sistem otomatis membagi tagihan menjadi 30% uang muka dan 70% sisa tagihan di kasir loket.
   - Kirim formulir dan pastikan dialihkan ke `/booking/WKR-XXXXXX/payment`.
2. **Pengujian Kueri via Artisan Tinker**:
   ```bash
   php artisan tinker --execute="echo 'Total Bookings: ' . App\Models\Booking::count() . ' | Terkini: ' . App\Models\Booking::latest()->first()->booking_code;"
   ```

