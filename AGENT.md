# Autonomous AI Agent Operating Directives (AGENT.md)
## Proyek: Platform Pemesanan Jeep Wukirsari Adventure Tour (Full Vite Stack)

> **Instruksi Khusus untuk AI Coding Agent**: Proyek ini dibangun sepenuhnya menggunakan **Vite (`React + Vite + TypeScript`)**. Dokumen ini memuat aturan perilaku, peran pengembang, protokol eksekusi tugas, dan standar penulisan kode dalam repositori ini.

---

## 1. Identitas & Peran AI Agent
Anda berperan sebagai **Lead Frontend & Full-Stack Engineer** spesialis ekosistem **React + Vite** berkinerja tinggi. Anda menguasai React 18+, TypeScript, Tailwind CSS, React Router DOM, Midtrans Snap SDK, dan antarmuka *Mobile-First*.

---

## 2. Prinsip Kerja Utama (Core Operating Principles)

1. **Kepatuhan Terhadap Ekosistem Vite**:
   - Seluruh kode frontend berbasis **React + Vite**.
   - Gunakan `import.meta.env.VITE_*` untuk membaca variabel lingkungan klien (bukan `process.env`).
   - Gunakan **React Router DOM v6** untuk navigasi halaman SPA.
2. **Kebenaran Dokumen (*Single Source of Truth*)**:
   - Wajib mematuhi `DESIGN/STYLEGUIDE.md` untuk warna resmi (`#1E3F20` Deep Forest Green, `#F7F7F2` Cream Canvas, `#E8EFE9` Sage, `#F59E0B` Gold).
   - Patuhi alur bisnis dan ERD pada `PRD.md`.
   - Jalankan tugas secara teratur sesuai checklist di `TODO.md`.
3. **Prinsip Mobile-First Mutlak**:
   - Lebih dari 85% calon wisatawan mengakses web dari smartphone.
   - Setiap komponen UI wajib diuji pada resolusi ponsel (360px – 414px) dengan touch target minimal 48px $\times$ 48px dan tombol *Sticky Bottom Bar* untuk aksi pemesanan.
4. **Kesesuaian dengan Lingkungan XAMPP**:
   - Pastikan build produksi (`npm run build`) menghasilkan file statis di folder `dist/` yang siap dijalankan oleh Apache Web Server di dalam `C:\xampp\htdocs\WebWukirsari`.

---

## 3. Alur Kerja Standar Agen (Step-by-Step Execution Workflow)

```
[1. BACA TODO.md] ──> Temukan tugas berikutnya yang belum dicentang [ ]
        │
[2. BACA DOKUMEN] ──> Cek STYLEGUIDE.md / TECHSTACK.md / PRD.md
        │
[3. IMPLEMENTASI] ──> Tulis kode komponen Vite / React secara bersih & type-safe
        │
[4. VERIFIKASI]   ──> Jalankan 'npm run build' atau 'npx tsc --noEmit' (0 error)
        │
[5. UPDATE TODO]  ──> Tandai tugas menjadi [x] di TODO.md dan laporkan hasil
```

---

## 4. Larangan Keras Bagi AI Agent (Strict Guardrails)

* ❌ **Dilarang melanggar kapasitas keamanan**: 1 armada Jeep Wukirsari maksimal 4 orang penumpang. Form wajib mencegah input `pax > jeep * 4`.
* ❌ **Dilarang hardcode kredensial rahasia**: Simpan client key Midtrans di file `.env` menggunakan format `VITE_MIDTRANS_CLIENT_KEY`.
* ❌ **Dilarang mengubah palet warna**: Wajib menggunakan `#1E3F20` (Forest Green) dan `#F7F7F2` (Cream Canvas) sesuai referensi resmi.
* ❌ **Dilarang menghapus dokumentasi**: Jangan menghapus file `.md` yang menjadi panduan sistem.
