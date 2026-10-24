---
name: jeep-wukirsari-vite-workflow
description: Standard operating procedure and automation skill for developing, testing, and managing the Jeep Wukirsari booking platform using Vite, React, and TypeScript.
---

# Jeep Wukirsari Development Skill (SKILL.md)

Skill ini memuat instruksi operasional bagi AI Agent dalam menjalankan siklus pengembangan berbasis **Vite + React + TypeScript** pada platform **Jeep Wukirsari Adventure Tour**.

---

## 1. Quick Reference: Perintah Terminal Vite

| Perintah | Deskripsi | Catatan |
| :--- | :--- | :--- |
| `npm run dev` | Menjalankan local development server | Port default: `http://localhost:3000` atau `5173`. |
| `npm run build` | Melakukan kompilasi bundle produksi | Menghasilkan folder `dist/` untuk XAMPP Apache. |
| `npm run preview` | Meninjau hasil kompilasi lokal | Mensimulasikan performa web produksi. |
| `npx tsc --noEmit` | Memeriksa error tipe TypeScript | Wajib 0 error sebelum commit/selesai. |

---

## 2. Prosedur Eksekusi Fase Pengembangan

### Langkah 1: Inisialisasi Scaffolding Vite
1. Eksekusi perintah pembentukan proyek React TypeScript:
   ```bash
   npm create vite@latest . -- --template react-ts
   ```
2. Pasang Tailwind CSS dan utilitasnya:
   ```bash
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init -p
   ```
3. Pasang library pendukung:
   ```bash
   npm install react-router-dom lucide-react zustand clsx tailwind-merge framer-motion qrcode.react
   ```

### Langkah 2: Konfigurasi Styling Sesuai Mockup
1. Salin konfigurasi tema warna dari `TECHSTACK.md` ke `tailwind.config.js`.
2. Pastikan file `src/index.css` memuat tiga direktif dasar Tailwind:
   ```css
   @tailwind base;
   @tailwind components;
   @tailwind utilities;
   ```

### Langkah 3: Pengujian Build XAMPP
1. Jalankan `npm run build`.
2. Buat file `dist/.htaccess` agar routing React Router tidak menghasilkan error 404 saat pengguna me-refresh halaman di Apache XAMPP:
   ```apache
   <IfModule mod_rewrite.c>
     RewriteEngine On
     RewriteBase /WebWukirsari/dist/
     RewriteRule ^index\.html$ - [L]
     RewriteCond %{REQUEST_FILENAME} !-f
     RewriteCond %{REQUEST_FILENAME} !-d
     RewriteRule . /WebWukirsari/dist/index.html [L]
   </IfModule>
   ```

---

## 3. Checklist Verifikasi Selesai (Definition of Done)
- [ ] Server `npm run dev` berjalan tanpa warning fatal di konsol.
- [ ] `npm run build` berhasil menghasilkan file statis di folder `dist/`.
- [ ] Tampilan antarmuka mobile (<768px) memenuhi touch target 48px dan memiliki floating sticky button.
- [ ] Warna sesuai dengan palet resmi Deep Forest Green `#1E3F20`.
