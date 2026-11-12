# Dokumentasi Fitur Pickup Zone – Jeep Wukirsari Adventure Tour

Dokumentasi ini menjelaskan ketentuan teknis, pemetaan zona penjemputan, rumus perhitungan tarif, dan integrasinya dengan subtotal pemesanan pada sistem Jeep Wukirsari.

---

## 1. Tujuan Fitur

Fitur Pickup Zone dirancang untuk memberikan kenyamanan akomodasi bagi wisatawan yang menginap di hotel, resort, vila, stasiun, atau bandara tanpa harus memiliki kendaraan pribadi untuk menuju ke basecamp pangkalan Wukirsari Cangkringan. Fitur ini:
1. Menyediakan opsi kedatangan fleksibel: Datang Sendiri (*self-drive*) atau Antar-Jemput (*pickup shuttle*).
2. Menghitung jarak rute jalan riil dari titik penjemputan ke pangkalan basecamp Bedoyo Wukirsari (-7.6437244, 110.4338597).
3. Memberikan batas penjemputan gratis untuk area terdekat (radius <= 5 km).
4. Menghitung biaya antar-jemput secara proporsional sesuai **jumlah unit armada jeep** yang dikerahkan.
5. Menyimpan data koordinat, alamat, dan komponen biaya penjemputan ke dalam entitas pemesanan secara transparan.

---

## 2. Ketentuan dan Rumus Perhitungan Biaya Pickup

### A. Rumus Baku Biaya Penjemputan
Sesuai standar operasional paguyuban, biaya penjemputan dihitung berdasarkan **jumlah armada jeep** yang dikirimkan untuk menjemput rombongan, **bukan** berdasarkan jumlah penumpang:

$$\text{Biaya Pickup} = \text{Tarif Pickup Zone} \times \text{Jumlah Jeep}$$

> [!IMPORTANT]
> **Mengapa Berdasarkan Jumlah Jeep?**
> Karena setiap armada jeep memiliki kapasitas bahan bakar, biaya operasional BBM bolak-balik, dan tenaga supir tersendiri saat melakukan perjalanan penjemputan ke penginapan wisatawan. Jika rombongan berjumlah 8 orang (membutuhkan 2 jeep), maka 2 unit jeep harus diberangkatkan menjemput, sehingga tarif antar-jemput dikalikan 2.

### B. Hubungannya dengan Total Booking
$$\text{Package Subtotal} = \text{Harga Paket per Jeep} \times \text{Jumlah Jeep}$$
$$\text{Total Akhir Booking} = \text{Package Subtotal} + \text{Biaya Pickup}$$

Skema Pembayaran:
- Jika memilih **Full (100%)**: Nominal bayar = $\text{Total Akhir Booking}$.
- Jika memilih **DP (30%)**: Uang muka = $\text{round}(\text{Total Akhir Booking} \times 0.30)$, sisa tagihan = $\text{Total Akhir} - \text{Uang Muka}$.

---

## 3. Daftar Zona Penjemputan Resmi (Tabel `pickup_zones`)

Berdasarkan data resmi pada tabel `pickup_zones`:

| ID | Nama Zona Penjemputan | Tarif Tambahan per Jeep | Keterangan Jangkauan |
| :---: | :--- | :--- | :--- |
| **1** | **Datang Sendiri ke Basecamp Wukirsari** | **Rp 0** (Gratis) | Wisatawan berkumpul langsung di pangkalan pendaftaran Jeep Wukirsari. |
| **2** | **Area Cangkringan & Pakem (Lokal)** | **Rp 50.000** / Jeep | Area hotel/resort lereng Merapi Kaliurang, Cangkringan, dan Pakem (jarak > 5 km hingga 15 km). |
| **3** | **Area Sleman & Kota Yogyakarta / Stasiun** | **Rp 150.000** / Jeep | Shuttle penjemputan pusat Kota Yogyakarta, Hotel Malioboro, Stasiun Tugu, dan Stasiun Lempuyangan. |
| **4** | **Area Bandara YIA Kulon Progo** | **Rp 300.000** / Jeep | Layanan khusus antar-jemput dari Yogyakarta International Airport (YIA). |

---

## 4. File yang Digunakan

| Komponen | Lokasi File | Fungsi |
| :--- | :--- | :--- |
| **Model** | `app/Models/PickupZone.php` | Model Eloquent tabel `pickup_zones` dengan relasi ke `Booking`. |
| **Model** | `app/Models/Booking.php` | Menyimpan atribut `pickup_zone_id`, `pickup_fee`, `pickup_address`, `pickup_lat`, `pickup_lng`, `pickup_distance_km`. |
| **Controller** | `app/Http/Controllers/BookingController.php` | Logika kalkulasi jarak geolokasi, penentuan tarif per jeep, dan persistensi ke database. |
| **View Form** | `resources/views/booking/create.blade.php` | Antarmuka pemilihan mode kedatangan, pencarian hotel otomatis, dan pratinjau kalkulasi rincian biaya. |

---

## 5. Route yang Digunakan

```php
// Hitung jarak rute dari koordinat penjemputan ke basecamp
Route::get('/booking/distance', [BookingController::class, 'distance'])->middleware('throttle:60,1')->name('booking.distance');

// Autocomplete pencarian tempat / hotel
Route::get('/api/places-autocomplete', [BookingController::class, 'placesSearch'])->middleware('throttle:60,1')->name('places.autocomplete');

// Detail tempat berdasarkan place_id
Route::get('/api/place-details', [BookingController::class, 'placesDetail'])->middleware('throttle:60,1')->name('places.details');

// Reverse geocoding koordinat GPS ke nama jalan
Route::get('/lokasi-jemput/reverse', [BookingController::class, 'reverseLocation'])->middleware('throttle:60,1')->name('booking.reverse');
```

---

## 6. Query Database yang Diterapkan

### A. Mengambil Data Seluruh Zona Penjemputan
```php
$pickupZones = PickupZone::orderBy('additional_fee', 'asc')->get();
```
- **Tujuan**: Menampilkan opsi zona penjemputan dan tarif dasar tambahan.

### B. Mengambil Data Zona Berdasarkan ID
```php
$zone = PickupZone::find($request->input('pickup_zone_id'));
```
- **Tujuan**: Mengambil tarif `additional_fee` saat wisatawan memilih zona penjemputan tertentu.

### C. Menghitung dan Menyimpan Biaya Pickup ke Pemesanan
```php
// app/Http/Controllers/BookingController.php
$arrivalType  = $validated['arrival_type'] ?? 'self_drive';
$pickupFee    = 0.00;
$pickupZoneId = null;

if ($arrivalType === 'pickup') {
    $pickupLat = (float) $validated['pickup_lat'];
    $pickupLng = (float) $validated['pickup_lng'];
    $pickupKm  = $this->routeDistance($pickupLat, $pickupLng)['km'];

    // Jika zona dipilih secara eksplisit:
    if ($request->filled('pickup_zone_id')) {
        $zone = PickupZone::find($request->input('pickup_zone_id'));
        if ($zone) {
            $pickupZoneId = $zone->id;
            // Rumus: Tarif Zona x Jumlah Jeep
            $pickupFee = (float) ($zone->additional_fee * $jeepQuantity);
        }
    }

    // Jika melalui perhitungan jarak dinamis:
    if ($pickupZoneId === null) {
        $pickupFee = $pickupKm > self::FREE_KM ? (float) (self::FEE_PER_JEEP * $jeepQuantity) : 0.00;
        if ($pickupKm <= self::FREE_KM) {
            $pickupZoneId = 1;
        } elseif ($pickupKm <= 15) {
            $pickupZoneId = 2;
        } elseif ($pickupKm <= 35) {
            $pickupZoneId = 3;
        } else {
            $pickupZoneId = 4;
        }
    }
} else {
    $pickupZoneId = 1; // Datang Sendiri
    $pickupFee = 0.00;
}
```
- **Tujuan**: Mengintegrasikan `pickup_fee` ke dalam `total_amount` dan menyimpan foreign key `pickup_zone_id` pada tabel `bookings`.

### D. Mengambil Pemesanan dengan Relasi Pickup Zone (Eager Loading)
```php
$booking = Booking::with('pickupZone')->where('booking_code', $code)->firstOrFail();
```
- **Tujuan**: Menampilkan nama zona penjemputan resmi pada ringkasan e-tiket dan invoice.

---

## 7. Relasi Tabel yang Digunakan

```
+--------------------+                       +--------------------+
|    pickup_zones    |        1 : N          |      bookings      |
|  (id, zone_name,   | --------------------> | (id, booking_code, |
|   additional_fee)  |                       |  pickup_zone_id,   |
+--------------------+                       |  pickup_fee, ...)  |
                                             +--------------------+
```
- `pickup_zones.id` (Primary Key) berelasi *one-to-many* ke `bookings.pickup_zone_id` (Foreign Key nullable).

---

## 8. Contoh Kasus Perhitungan

**Skenario**:
- Wisatawan: Keluarga 6 Orang (membutuhkan 2 unit Jeep).
- Paket: Short Trip (Rp 450.000 / Jeep).
- Lokasi Jemput: Hotel di Kaliurang (Zona 2 - Tarif Rp 50.000 / Jeep).
- Pilihan Bayar: DP (30%).

**Perhitungan Matang**:
1. $\text{Package Subtotal} = 2 \text{ Jeep} \times \text{Rp } 450.000 = \text{Rp } 900.000$
2. $\text{Biaya Pickup} = 2 \text{ Jeep} \times \text{Rp } 50.000 = \text{Rp } 100.000$
3. $\text{Total Akhir Booking} = \text{Rp } 900.000 + \text{Rp } 100.000 = \text{Rp } 1.000.000$
4. $\text{Uang Muka (DP 30\%)} = \text{Rp } 1.000.000 \times 0.30 = \text{Rp } 300.000$
5. $\text{Sisa Tagihan Pelunasan di Loket} = \text{Rp } 1.000.000 - \text{Rp } 300.000 = \text{Rp } 700.000$

---

## 9. Cara Pengujian Fitur

1. **Pengujian Kueri via Artisan Tinker**:
   ```bash
   php artisan tinker --execute="echo App\Models\PickupZone::select('id','zone_name','additional_fee')->get()->toJson(JSON_PRETTY_PRINT);"
   ```
   *Hasil yang diharapkan*: Menampilkan 4 zona resmi dengan nominal tarif dasar masing-masing.
2. **Pengujian Form Booking**:
   - Pilih penjemputan di luar 5 km dengan 2 unit jeep.
   - Periksa tabel ringkasan harga: Biaya penjemputan harus bertambah sesuai kelipatan armada jeep.

