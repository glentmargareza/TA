# DEADCODE_CLEANUP.md

> Instruksi untuk AI Agent: menghapus **dead code** beserta **turunannya** secara aman.
> Baca seluruh file ini sebelum mengubah apa pun.

---

## 1. Tujuan

Hapus semua kode yang tidak pernah dijalankan atau tidak pernah dipakai, termasuk semua hal yang menjadi tidak terpakai setelah kode tersebut dihapus (efek berantai / *cascading*), **tanpa mengubah perilaku aplikasi**.

## 2. Definisi

**Dead code** adalah kode yang:
- Tidak pernah dieksekusi (*unreachable*): setelah `return`, `throw`, `break`, `continue`; cabang `if (false)`; kondisi yang selalu salah/benar.
- Tidak pernah dipanggil/direferensikan: fungsi, method, class, komponen, konstanta, variabel, tipe/interface, enum.
- Dinonaktifkan manual: blok kode yang di-comment, `TODO: hapus`, feature flag yang sudah permanen ON/OFF.

**Turunan dead code** (hapus juga setelah dead code utamanya hilang):
- `import` / `require` / `use` yang tidak lagi dipakai
- Parameter fungsi yang tidak lagi dipakai
- Variabel / konstanta / tipe yang hanya dipakai oleh kode yang sudah dihapus
- File, folder, asset (gambar, font, JSON) yang tidak direferensikan lagi
- Dependency di `package.json` / `requirements.txt` / `pom.xml` / dll. yang tidak diimport lagi
- Style (CSS/SCSS class, ID, variabel CSS) yang tidak dipakai di markup
- Route, endpoint, migration, env variable, konfigurasi yang menunjuk ke kode yang sudah hilang
- Test yang hanya menguji kode yang dihapus
- Dokumentasi / komentar yang menjelaskan kode yang sudah hilang

---

## 3. Aturan Keamanan (WAJIB)

1. **Jangan hapus apa pun tanpa bukti** bahwa kode itu tidak terpakai (hasil tool atau pencarian referensi). Jika ragu, **jangan hapus**; masukkan ke daftar "Perlu Konfirmasi".
2. **Jangan ubah perilaku.** Ini pembersihan, bukan refactor. Dilarang mengganti logika, rename, atau memformat ulang kode yang tidak terkait.
3. **Waspadai pemakaian dinamis.** Cek dulu sebelum menghapus:
   - Referensi lewat string: `require(name)`, `import()`, `getattr`, reflection, `eval`, `window[name]`
   - Nama class CSS yang dibuat dinamis: `` `btn-${type}` ``
   - Dependency injection, decorator, annotation (`@Component`, `@Bean`, `@Route`)
   - Konfigurasi eksternal: YAML/JSON, `.env`, router berbasis file (Next.js, Nuxt, dll.)
4. **Jangan hapus public API** (export library, endpoint publik, fungsi yang dipakai pihak luar repo) kecuali diminta eksplisit.
5. **Jangan sentuh:** `node_modules`, `vendor`, `dist`, `build`, `.git`, file hasil generate, file lock (`package-lock.json`, dll.), dan folder yang dikecualikan pengguna.
6. **Entry point dianggap terpakai:** `main`, `index`, `app`, file test, file konfigurasi build, script di `package.json`, hook lifecycle framework.
7. **Satu kategori per langkah**, lalu verifikasi sebelum lanjut.

---

## 4. Alur Kerja

### Fase 0: Persiapan
- Pastikan repo bersih (`git status`). Buat branch baru, misalnya `chore/remove-dead-code`.
- Identifikasi bahasa, framework, dan package manager yang dipakai.
- Jalankan build, lint, dan test **sebelum** mengubah apa pun; catat hasilnya sebagai baseline.

### Fase 1: Deteksi
Gunakan tool otomatis bila tersedia, lalu verifikasi manual dengan pencarian referensi (`grep` / `rg` / find usages).

| Ekosistem | Tool yang disarankan |
|---|---|
| JavaScript / TypeScript | `knip`, `ts-prune`, `depcheck`, ESLint (`no-unused-vars`, `no-unreachable`), `tsc --noUnusedLocals --noUnusedParameters` |
| Python | `vulture`, `autoflake`, `ruff` (F401, F841), `pyflakes` |
| Java / Kotlin | IDE inspection, `-Xlint`, `detekt`, `PMD` |
| PHP | `phpstan`, `psalm`, `php-cs-fixer` |
| Go | `staticcheck`, `deadcode`, `go vet`, `unused` |
| CSS / SCSS | `PurgeCSS`, `uncss`, `stylelint` |
| Umum | `rg "namaSimbol"` untuk menghitung jumlah referensi |

Jika tool tidak tersedia, cari manual: untuk setiap simbol yang dicurigai, hitung referensinya di seluruh repo (termasuk file non-kode seperti HTML, JSON, YAML, MD).

### Fase 2: Klasifikasi
Bagi temuan menjadi tiga kelompok:

- **Aman dihapus**: 0 referensi, tidak dinamis, bukan public API.
- **Perlu konfirmasi**: kemungkinan dipakai secara dinamis / eksternal / public API.
- **Jangan hapus**: dipakai, atau entry point.

### Fase 3: Penghapusan (urutan wajib)
1. Kode **unreachable** dan kode yang di-comment
2. Fungsi / method / class / komponen yang tidak dipanggil
3. Variabel, konstanta, tipe yang tidak dipakai
4. **Turunan**: parameter, import, helper yang kini yatim
5. File dan asset yang tidak direferensikan
6. Style CSS yang tidak dipakai
7. Dependency yang tidak diimport
8. Test, dokumentasi, dan konfigurasi yang ikut yatim

### Fase 4: Iterasi (cascading)
Setelah setiap putaran penghapusan, **ulangi Fase 1**. Menghapus satu hal sering membuat hal lain menjadi tidak terpakai. Berhenti saat satu putaran penuh tidak menemukan temuan baru.

### Fase 5: Verifikasi
- Jalankan ulang build, lint, dan test; hasilnya harus sama dengan atau lebih baik dari baseline.
- Jalankan aplikasi dan cek alur utama jika memungkinkan.
- Jika ada yang gagal, **kembalikan** perubahan terakhir (`git checkout` / `git revert`) dan pindahkan item tersebut ke "Perlu Konfirmasi".

### Fase 6: Commit
Satu commit per kategori, dengan pesan yang jelas, contoh:
```
chore: hapus import yang tidak dipakai
chore: hapus fungsi formatDate yang tidak dipanggil
chore: hapus dependency lodash yang tidak diimport
```

---

## 5. Format Laporan Akhir

Setelah selesai, tulis laporan dengan format berikut:

```markdown
## Laporan Pembersihan Dead Code

### Ringkasan
- File diubah: N | File dihapus: N
- Baris dihapus: ± N
- Dependency dihapus: N
- Build / Lint / Test: ✅ lulus (atau ❌ jelaskan)

### Dihapus
| Kategori | Item | Lokasi | Bukti tidak terpakai |
|---|---|---|---|
| Fungsi | `formatDate` | `src/utils/date.js:12` | 0 referensi |
| Import | `useState` | `src/App.jsx:1` | tidak dipakai di file |
| Dependency | `moment` | `package.json` | tidak ada import |

### Perlu Konfirmasi (TIDAK dihapus)
| Item | Lokasi | Alasan ragu |
|---|---|---|
| `legacyExport` | `src/api.js:40` | kemungkinan public API |

### Catatan
- Hal penting yang perlu diperhatikan pengguna.
```

---

## 6. Larangan

- ❌ Menghapus kode hanya karena "terlihat tidak dipakai".
- ❌ Menghapus lalu menebak; selalu verifikasi.
- ❌ Mengubah logika, nama, atau format kode yang bukan dead code.
- ❌ Menghapus file test untuk membuat test lulus.
- ❌ Menginstal atau mengubah versi dependency tanpa diminta.
- ❌ Melanjutkan jika build / test rusak dan penyebabnya belum jelas.

## 7. Prinsip Akhir

> **Aman lebih penting daripada bersih.** Lebih baik meninggalkan satu baris dead code daripada menghapus satu baris kode yang masih hidup.
