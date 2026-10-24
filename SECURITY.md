# Security Policy & Implementation Guidelines (SECURITY.md)
## Proyek: Platform Pemesanan Jeep Wisata Cangkringan (Lava Tour Merapi)

> **Protokol Keamanan Sistem untuk AI Agent & Pengembang**: Dokumen ini memuat standar keamanan transaksi finansial, perlindungan data pribadi wisatawan (*UU Pelindungan Data Pribadi*), pencegahan eksploitasi konkurensi (double-booking), serta validasi webhook pembayaran.

---

## 1. Verifikasi Keamanan Payment Gateway (Webhook Integrity)

Serangan paling umum pada sistem reservasi adalah manipulasi webhook pembayaran palsu (*forged webhook*) yang mencoba menandai pesanan lunas tanpa adanya pembayaran nyata.

### Aturan Wajib Webhook:
1. **Verifikasi Hash Signature SHA-512**:
   Setiap request webhook dari Midtrans wajib diverifikasi signature key-nya sebelum memproses mutasi basis data:
   $$\text{Signature} = \text{SHA512}(\text{order\_id} + \text{status\_code} + \text{gross\_amount} + \text{ServerKey})$$
   Jika signature dari payload tidak cocok persis dengan hasil kalkulasi di server, request **WAJIB DITOLAK** dengan status `HTTP 401 Unauthorized`.

2. **Idempotency Check**:
   Payment gateway dapat mengirimkan webhook yang sama beberapa kali (*retry attempts*). Sistem harus memastikan bahwa memproses webhook yang sama tidak akan menduplikasi status atau memicu pengiriman pesan WhatsApp berulang kali.

```typescript
// Contoh Snippet Validasi Signature Midtrans
import crypto from 'crypto';

export function verifyMidtransSignature(
  orderId: string,
  statusCode: string,
  grossAmount: string,
  receivedSignature: string
): boolean {
  const serverKey = process.env.MIDTRANS_SERVER_KEY || '';
  const hash = crypto
    .createHash('sha512')
    .update(`${orderId}${statusCode}${grossAmount}${serverKey}`)
    .digest('hex');
  return hash === receivedSignature;
}
```

---

## 2. Pencegahan Overbooking & Penanganan Konkurensi (Race Conditions)

Di musim liburan (Idul Fitri, Tahun Baru), puluhan wisatawan dapat membuka slot jam yang sama secara bersamaan.

### Mekanisme Pencegahan:
1. **Redis Distributed Lock / TTL Key**:
   Saat pengunjung melangkah ke tahap pembayaran, sistem membuat kunci sementara di Redis:
   - Key: `lock:slot:{slot_id}:{trip_date}:{booking_id}`
   - TTL: `900 detik` (15 menit).
2. **Pengecekan Kuota dengan Transaksi Atomik**:
   Perhitungan ketersediaan armada dilakukan di dalam blok transaksi PostgreSQL:
   ```sql
   -- Mengunci baris kuota selama transaksi berlangsung
   SELECT * FROM schedule_slots WHERE id = $1 FOR UPDATE;
   ```
3. **Pelepasan Slot Otomatis (*Auto-Release*)**:
   Jika waktu bayar 15 menit habis tanpa ada konfirmasi webhook sukses, background worker otomatis mengubah status booking menjadi `EXPIRED` dan menghapus kunci kuota.

---

## 3. Pencegahan Injeksi & Validasi Input (Input Sanitization)

1. **Skema Zod Ketat**:
   Setiap endpoint API publik wajib memvalidasi *body request* menggunakan skema Zod.
   - Contoh: Jumlah jeep minimal 1, maksimal 10 per pemesanan online.
   - Nomor telepon wajib sesuai format Indonesia: `^(?:\+62|62|0)8[1-9][0-9]{7,11}$`.
   - Tanggal kunjungan tidak boleh di masa lalu (*past dates*).
2. **Prisma ORM Parameterized Queries**:
   Dilarang menggunakan *raw string concatenation* SQL. Selalu gunakan metode query bawaan Prisma untuk mengeliminasi celah **SQL Injection**.
3. **Sanitasi XSS pada Alamat Jemputan**:
   Input teks bebas seperti catatan khusus atau alamat penjemputan (*pickup address*) wajib disanitasi menggunakan pustaka `isomorphic-dompurify` sebelum disimpan dan ditampilkan kembali di dashboard admin.

---

## 4. Perlindungan Data Wisatawan (UU PDP Compliance)

1. **Minimasi Pengumpulan Data (*Data Minimization*)**:
   Hanya kumpulkan data yang benar-benar esensial untuk operasional wisata:
   - Nama lengkap pemesan.
   - Nomor WhatsApp (untuk pengiriman tiket QR dan koordinasi supir).
   - Email (untuk salinan e-voucher).
   - Jangan pernah meminta nomor KTP / data sensitif lainnya kecuali diwajibkan oleh asuransi wisata.
2. **Enkripsi Data Sensitif**:
   - Kata sandi akun admin dan kasir wajib di-hash menggunakan algoritma **Argon2id** atau **Bcrypt** dengan *salt rounds* minimal 12.
   - Token otentikasi API disimpan aman di variabel lingkungan (`.env.local`), dilarang keras menaruh token di repository publik.
3. **Penyembunyian Nomor HP di Tampilan Publik**:
   Pada fitur pencarian tiket publik (`/cek-tiket`), nomor telepon disamarkan (contoh: `0812****8901`) untuk mencegah pengikisan data (*scraping*) oleh pihak ketiga.

---

## 5. Rate Limiting & Perlindungan Bot (Anti-Abuse)

1. **Rate Limiting Endpoint Booking**:
   Gunakan middleware rate limiter (misalnya `@upstash/ratelimit`):
   - Endpoint `/api/checkout`: Maksimal 5 permintaan pemesanan per 10 menit per IP address.
   - Endpoint `/api/availability`: Maksimal 60 request per menit per IP address.
2. **Proteksi Formulir via Turnstile / ReCAPTCHA v3**:
   Jika terdeteksi lonjakan bot pada form pemesanan, aktifkan Cloudflare Turnstile tanpa mengganggu kenyamanan pengguna di smartphone.

---

## 6. Hak Akses & Otorisasi Berbasis Peran (RBAC)

Sistem membedakan izin akses menjadi 3 tingkatan:

| Peran (*Role*) | Akses yang Diizinkan | Batasan yang Dilarang |
| :--- | :--- | :--- |
| **`CUSTOMER`** | Membuat reservasi, melihat tiket miliknya sendiri, membatalkan pesanan (jika memenuhi S&K). | Dilarang mengakses rute `/admin/*` atau melihat data pemesan lain. |
| **`CASHIER`** | Membuka scanner QR loket basecamp (`/admin/scan`), mencatat pelunasan tunai/QRIS, melihat daftar giliran armada hari berjalan. | Dilarang mengubah konfigurasi harga paket, menghapus log transaksi, atau mengakses laporan omzet bulanan. |
| **`ADMIN`** | Akses penuh ke seluruh menu, kelola paket, atur armada & driver paguyuban, serta ekspor pembukuan keuangan. | Wajib menggunakan autentikasi 2 faktor (2FA) jika diakses di luar jaringan pangkalan. |

---

## 7. Prosedur Pelaporan Celah Keamanan (Vulnerability Disclosure)

Jika Anda menemukan celah keamanan pada sistem Jeep Wisata Cangkringan:
1. Laporkan secara bertanggung jawab ke tim pengembang atau email `security@jeepcangkringan.id`.
2. Jangan mempublikasikan eksploitasi sebelum perbaikan dirilis (*responsible disclosure*).
3. Tim berkomitmen menanggapi laporan dalam waktu maksimal $1 \times 24$ jam.
