# Panduan Arsitektur & Penerapan Komponen Blade – Web Wukirsari

Dokumentasi ini merupakan panduan implementasi teknis sistem komponen (*Blade Component Design System*) untuk antarmuka Admin dan Publik pada aplikasi **Jeep Wukirsari Adventure Tour**.

---

## 1. Latar Belakang & Tujuan

Saat ini terdapat banyak perulangan kode HTML dan kelas Tailwind CSS di berbagai file view (`resources/views/admin/*.blade.php`, `resources/views/booking/*.blade.php`, `resources/views/auth/*.blade.php`, dll). 

Tujuan penerapan komponen ini adalah:
1. **DRY (*Don't Repeat Yourself*)**: Menghindari duplikasi boilerplate HTML (seperti tag `<!DOCTYPE>`, `<head>`, config Tailwind, modal backdrop, notifikasi toast, dan SVG ikon).
2. **Konsistensi UI/UX**: Menstandarisasi warna tema (`wukirsari-primary`, `emerald`, `merapiText`), tipografi (`Poppins` & `Inter`), ukuran border-radius, dan perilaku animasi interaktif di seluruh halaman.
3. **Kemudahan Pemeliharaan (*Maintainability*)**: Jika ada perbaikan pada status pembayaran, logika ikon, atau validasi form, pengembang cukup memperbarui 1 file komponen tanpa harus mengedit puluhan file view.
4. **Fleksibilitas (*Reusability*)**: Komponen dirancang modular agar dapat digunakan bersama di area Admin maupun area Publik wisatawan.

---

## 2. Struktur Direktori Komponen

```text
resources/views/
├── layouts/
│   ├── app.blade.php            # Master Layout Wisatawan / Publik
│   └── admin.blade.php          # Master Layout Khusus Admin (menggantikan boilerplate 11 file admin)
│
└── components/
    │
    │── [KOMPONEN UNIVERSAL - BISA DI PAKAI DI MANAPUN]
    │   ├── alert.blade.php              # <x-alert /> (Inline Alert Banner)
    │   ├── toast.blade.php              # <x-toast /> (Pop-up Toast Melayang - Standar POS-Apotek)
    │   ├── badge.blade.php              # <x-badge />
    │   ├── button.blade.php             # <x-button />
    │   ├── input.blade.php              # <x-input />
    │   ├── select.blade.php             # <x-select />
    │   ├── textarea.blade.php           # <x-textarea />
    │   ├── modal.blade.php              # <x-modal />
    │   ├── empty-state.blade.php        # <x-empty-state />
    │   ├── stat-card.blade.php          # <x-stat-card />
    │   ├── rating-stars.blade.php       # <x-rating-stars />
    │   ├── image-upload.blade.php       # <x-image-upload />
    │   ├── icon.blade.php               # <x-icon />
    │   └── action-button.blade.php      # <x-action-button />
    │
    └── admin/
        │── [KOMPONEN KHUSUS PANEL ADMIN]
        ├── header.blade.php             # <x-admin.header />
        ├── table.blade.php              # <x-admin.table />
        ├── filter-card.blade.php        # <x-admin.filter-card />
        └── tabs.blade.php               # <x-admin.tabs />
```

---

## 3. Katalog Komponen & Referensi Kode

### A. Layout Khusus Admin (`layouts/admin.blade.php`)
Menggantikan duplikasi tag `<!DOCTYPE html>`, CDN Tailwind, konfigurasi font, tag `<body>`, pemanggilan sidebar, dan container pembungkus di seluruh view admin.

```html
@props(['title' => 'Admin Panel | Jeep Wukirsari'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wukirsari: {
                            primary: '#143B1E',
                            dark: '#0D2714',
                            accent: '#1E5E30',
                            light: '#EAF3EC',
                            border: '#D7E5D9',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-display, h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }
    </style>
    {{ $head ?? '' }}
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">
    @include('admin.partials.sidebar')

    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        {{ $slot }}
    </div>

    @include('admin.partials.confirm-modal')
    <x-toast />
    {{ $scripts ?? '' }}
</body>
</html>
```

---

### B. Komponen Ikon Tunggal (`components/icon.blade.php`)
Membungkus path SVG sehingga template bersih dan mudah dibaca.

```html
@props([
    'name',
    'class' => 'w-4 h-4'
])

@php
    $icons = [
        'pencil' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>',
        'eye' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
        'trash' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>',
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        'x-mark' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        'printer' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>',
        'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
        'whatsapp' => '<path fill="currentColor" d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>',
        'star' => '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>',
        'star-outline' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    ];

    $isCurrentColor = in_array($name, ['whatsapp', 'star']);
@endphp

<svg {{ $attributes->merge(['class' => $class]) }} 
     @if($isCurrentColor) fill="currentColor" @else fill="none" stroke="currentColor" @endif 
     viewBox="0 0 24 24" 
     aria-hidden="true">
    {!! $icons[$name] ?? '<circle cx="12" cy="12" r="10" stroke-width="2"/>' !!}
</svg>
```

---

### C. Komponen Tombol Aksi Tabel (`components/action-button.blade.php`)
Membungkus tombol kecil di kolom aksi tabel (Detail, Edit, Hapus, Cetak, Pelunasan, Assign Driver, Kelola Foto, Kalender) dengan sistem warna semantik tonal lembut (*soft-tonal palette*) agar tidak flat dan langsung memiliki identitas visual.

```html
@props([
    'type' => 'view', // 'view' | 'edit' | 'delete' | 'cancel' | 'print' | 'settle' | 'assign' | 'photo' | 'calendar' | 'toggle'
    'title' => '',
    'as' => 'button', // 'button' | 'a' | 'submit'
    'icon' => null,
])

@php
    $configs = [
        'view' => [
            'icon' => 'eye',
            'classes' => 'bg-sky-50 border-sky-200 text-sky-700 hover:bg-sky-100 hover:border-sky-300 hover:text-sky-900',
            'defaultTitle' => 'Lihat Detail',
        ],
        'edit' => [
            'icon' => 'pencil',
            'classes' => 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100 hover:border-amber-300 hover:text-amber-900',
            'defaultTitle' => 'Ubah Data',
        ],
        'delete' => [
            'icon' => 'trash',
            'classes' => 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300 hover:text-rose-800',
            'defaultTitle' => 'Hapus Data',
        ],
        'cancel' => [
            'icon' => 'x-mark',
            'classes' => 'bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300 hover:text-rose-800',
            'defaultTitle' => 'Batalkan',
        ],
        'print' => [
            'icon' => 'printer',
            'classes' => 'bg-slate-100 border-slate-300 text-slate-700 hover:bg-slate-200 hover:border-slate-400 hover:text-slate-900',
            'defaultTitle' => 'Cetak',
        ],
        'settle' => [
            'icon' => 'cash',
            'classes' => 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100 hover:border-emerald-400 hover:text-emerald-950',
            'defaultTitle' => 'Pelunasan Kasir Loket',
        ],
        'assign' => [
            'icon' => 'car',
            'classes' => 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300 hover:text-emerald-900',
            'defaultTitle' => 'Atur Supir / Armada',
        ],
        'photo' => [
            'icon' => 'photo',
            'classes' => 'bg-purple-50 border-purple-200 text-purple-700 hover:bg-purple-100 hover:border-purple-300 hover:text-purple-900',
            'defaultTitle' => 'Kelola Foto / Media',
        ],
        'calendar' => [
            'icon' => 'calendar',
            'classes' => 'bg-indigo-50 border-indigo-200 text-indigo-700 hover:bg-indigo-100 hover:border-indigo-300 hover:text-indigo-900',
            'defaultTitle' => 'Atur Jadwal',
        ],
        'toggle' => [
            'icon' => 'check',
            'classes' => 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300 hover:text-emerald-900',
            'defaultTitle' => 'Ubah Status',
        ],
    ];

    $config = $configs[$type] ?? $configs['view'];
    $iconName = $icon ?? $config['icon'];
    $tooltip = $title ?: $config['defaultTitle'];
    $baseClasses = 'p-1.5 rounded-xl border text-xs shadow-2xs transition-all cursor-pointer inline-flex items-center justify-center shrink-0 ' . $config['classes'];
@endphp

@if($as === 'a')
    <a title="{{ $tooltip }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        <x-icon :name="$iconName" class="w-4 h-4" />
    </a>
@elseif($as === 'submit')
    <button type="submit" title="{{ $tooltip }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        <x-icon :name="$iconName" class="w-4 h-4" />
    </button>
@else
    <button type="button" title="{{ $tooltip }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        <x-icon :name="$iconName" class="w-4 h-4" />
    </button>
@endif
```

---

### D. Komponen Status Badge (`components/badge.blade.php`)
Menstandarisasi badge warna untuk status pembayaran, ketersediaan, dan kesiapan armada.

```html
@props([
    'status' => 'PENDING',
    'label' => null,
    'dot' => false,
])

@php
    $styles = [
        'FULL_PAID' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'text' => 'Lunas (100%)', 'dotColor' => 'bg-emerald-500'],
        'DP_PAID'   => ['bg' => 'bg-sky-50 text-sky-800 border-sky-200', 'text' => 'DP Terbayar (30%)', 'dotColor' => 'bg-sky-500'],
        'PENDING'   => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'text' => 'Menunggu Bayar', 'dotColor' => 'bg-amber-500'],
        'COMPLETED' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200', 'text' => 'Selesai', 'dotColor' => 'bg-purple-500'],
        'CANCELLED' => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200', 'text' => 'Dibatalkan', 'dotColor' => 'bg-rose-500'],
        'READY'     => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'text' => 'Siap Jalan', 'dotColor' => 'bg-emerald-500'],
        'ON_TRIP'   => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'text' => 'Sedang Bertugas', 'dotColor' => 'bg-blue-500'],
        'ACTIVE'    => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'text' => 'Aktif', 'dotColor' => 'bg-emerald-500'],
        'INACTIVE'  => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'text' => 'Non-Aktif', 'dotColor' => 'bg-slate-400'],
    ];

    $cfg = $styles[$status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'text' => $status, 'dotColor' => 'bg-slate-400'];
    $displayText = $label ?? $cfg['text'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold border ' . $cfg['bg']]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dotColor'] }}"></span>
    @endif
    <span>{{ $displayText }}</span>
</span>
```

---

### E.1. Komponen Inline Alert (`components/alert.blade.php`)
Digunakan untuk pesan inline box di dalam halaman formulir.

```html
@props([
    'type' => 'success', // 'success' | 'danger' | 'warning' | 'info'
    'message' => null,
    'dismissible' => true,
])

@php
    $themes = [
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-900', 'icon' => 'check', 'iconColor' => 'text-emerald-600'],
        'danger'  => ['bg' => 'bg-rose-50 border-rose-200 text-rose-900', 'icon' => 'x-mark', 'iconColor' => 'text-rose-600'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200 text-amber-900', 'icon' => 'x-mark', 'iconColor' => 'text-amber-600'],
        'info'    => ['bg' => 'bg-sky-50 border-sky-200 text-sky-900', 'icon' => 'eye', 'iconColor' => 'text-sky-600'],
    ];
    $t = $themes[$type] ?? $themes['success'];
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-2xl border text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs ' . $t['bg']]) }}>
    <div class="flex items-center gap-2.5">
        <span class="w-6 h-6 rounded-full bg-white/80 flex items-center justify-center shrink-0">
            <x-icon :name="$t['icon']" class="w-3.5 h-3.5 {{ $t['iconColor'] }}" />
        </span>
        <div>
            @if($message)
                <span>{{ $message }}</span>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
    @if($dismissible)
    <button type="button" onclick="this.closest('[class*=\'rounded-2xl\']').remove()" class="text-current opacity-70 hover:opacity-100 font-bold text-base cursor-pointer px-1">&times;</button>
    @endif
</div>
```

---

### E.2. Komponen Pop-up Toast Melayang (`components/toast.blade.php`)
Diadopsi langsung dari standar **`C:\xampp\htdocs\pos-apotek`**: Notifikasi pop-up melayang di tengah atas layar (*top-center eye-level*), dengan animasi memantul (*spring-in*), kartu berbayang tebal (*shadow-2xl*), indikator countdown progress bar, dan fitur otomatis berhenti (*pause*) saat kursor diarahkan ke kartu.

```html
{{--
    Komponen Toast Pop-up Reusable (Mengadopsi Standar POS-Apotek)
    Menangani session flash ('success', 'error', 'warning', 'info')
    serta menyediakan fungsi global JS window.showToast(message, type, duration)
--}}
<div id="global-toast-container" class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] pointer-events-none flex flex-col items-center gap-3 max-w-[92vw] sm:max-w-md w-full px-4">
    @php
        $flashTypes = [
            'success' => [
                'icon' => 'check',
                'border' => 'border-emerald-200 shadow-emerald-500/15',
                'iconWrapper' => 'bg-emerald-100 text-emerald-700 ring-4 ring-emerald-50',
                'titleColor' => 'text-emerald-800',
                'barColor' => 'bg-emerald-600',
                'title' => 'Berhasil',
            ],
            'error' => [
                'icon' => 'x-mark',
                'border' => 'border-rose-200 shadow-rose-500/15',
                'iconWrapper' => 'bg-rose-100 text-rose-700 ring-4 ring-rose-50',
                'titleColor' => 'text-rose-800',
                'barColor' => 'bg-rose-600',
                'title' => 'Gagal',
            ],
            'warning' => [
                'icon' => 'x-mark',
                'border' => 'border-amber-200 shadow-amber-500/15',
                'iconWrapper' => 'bg-amber-100 text-amber-700 ring-4 ring-amber-50',
                'titleColor' => 'text-amber-800',
                'barColor' => 'bg-amber-600',
                'title' => 'Perhatian',
            ],
            'info' => [
                'icon' => 'eye',
                'border' => 'border-sky-200 shadow-sky-500/15',
                'iconWrapper' => 'bg-sky-100 text-sky-700 ring-4 ring-sky-50',
                'titleColor' => 'text-sky-800',
                'barColor' => 'bg-sky-600',
                'title' => 'Informasi',
            ],
        ];
    @endphp

    @foreach ($flashTypes as $type => $config)
        @if (session($type))
            <div class="toast-card pointer-events-auto w-full bg-white/95 backdrop-blur-md rounded-2xl border shadow-2xl overflow-hidden transform translate-y-0 opacity-100 scale-100 transition-all duration-300 {{ $config['border'] }}"
                 role="status"
                 data-toast-type="{{ $type }}">
                <div class="p-3.5 sm:p-4 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl {{ $config['iconWrapper'] }} flex items-center justify-center shrink-0">
                        <x-icon :name="$config['icon']" class="w-5 h-5" />
                    </div>

                    <div class="flex-1 min-w-0 pt-0.5">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider {{ $config['titleColor'] }}">{{ $config['title'] }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">Baru saja</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-700 leading-snug break-words">
                            {{ session($type) }}
                        </p>
                    </div>

                    <button type="button"
                            onclick="dismissToast(this.closest('.toast-card'))"
                            class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0 -mr-1 -mt-1 cursor-pointer"
                            aria-label="Tutup notifikasi">
                        <x-icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                {{-- Animated Progress Bar Countdown --}}
                <div class="h-1 w-full bg-slate-100 overflow-hidden">
                    <div class="toast-progress-bar h-full {{ $config['barColor'] }}" style="animation: toastProgressBar 3800ms linear forwards;"></div>
                </div>
            </div>
        @endif
    @endforeach
</div>

<style>
    @keyframes toastProgressBar {
        from { width: 100%; }
        to { width: 0%; }
    }
    @keyframes toastSpringIn {
        0% { transform: translateY(-26px) scale(0.92); opacity: 0; }
        65% { transform: translateY(4px) scale(1.015); opacity: 1; }
        100% { transform: translateY(0) scale(1); opacity: 1; }
    }
    .toast-card {
        animation: toastSpringIn 420ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transition: all 350ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .toast-card:hover .toast-progress-bar {
        animation-play-state: paused !important;
    }
</style>

<script>
    function dismissToast(toastEl) {
        if (!toastEl) return;
        toastEl.classList.remove('translate-y-0', 'opacity-100', 'scale-100');
        toastEl.classList.add('-translate-y-4', 'opacity-0', 'scale-95');
        setTimeout(() => {
            if (toastEl && toastEl.parentElement) toastEl.remove();
        }, 320);
    }

    // Auto-dismiss setelah 3.8 detik
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toast-card').forEach(function (card) {
            let timeoutId = setTimeout(function () { dismissToast(card); }, 3800);
            card.addEventListener('mouseenter', () => clearTimeout(timeoutId));
            card.addEventListener('mouseleave', () => {
                timeoutId = setTimeout(() => dismissToast(card), 1500);
            });
        });
    });

    // Helper JS Global: window.showToast("Pesan", "success")
    window.showToast = function (message, type = 'success', duration = 3800) {
        const container = document.getElementById('global-toast-container');
        if (!container) return;
        // Logic pembuatan element DOM secara dinamis...
    };
</script>
```

---

### F. Komponen Kartu Statistik Metrik (`components/stat-card.blade.php`)
Digunakan pada ringkasan dashboard, paket, armada, pengeluaran, ulasan, dan laporan. Mendukung `label` maupun `title`, serta palet warna `variant` (`emerald`, `sky`, `amber`, `indigo`, `rose`, `slate`).

```html
@props([
    'label' => null,
    'title' => null,
    'value',
    'subtext' => null,
    'unit' => null,
    'trend' => null,
    'icon' => null,
    'iconWrapper' => null,
    'variant' => null,
])

@php
    $cardLabel = $label ?? $title ?? '';
    
    $variantWrappers = [
        'emerald' => 'bg-emerald-100 text-emerald-800',
        'sky'     => 'bg-sky-100 text-sky-800',
        'blue'    => 'bg-sky-100 text-sky-800',
        'amber'   => 'bg-amber-100 text-amber-800',
        'indigo'  => 'bg-indigo-100 text-indigo-800',
        'purple'  => 'bg-purple-100 text-purple-800',
        'rose'    => 'bg-rose-100 text-rose-800',
        'red'     => 'bg-rose-100 text-rose-800',
        'slate'   => 'bg-slate-100 text-slate-800',
    ];

    $resolvedWrapper = $iconWrapper ?? ($variant ? ($variantWrappers[$variant] ?? 'bg-emerald-50 text-emerald-800') : 'bg-emerald-50 text-emerald-800');
@endphp

<div {{ $attributes->merge(['class' => 'bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between min-w-0 transition-all hover:shadow-sm']) }}>
    <div class="min-w-0 flex-1">
        <span class="text-[11px] sm:text-xs font-semibold text-slate-500 block truncate">{{ $cardLabel }}</span>
        <div class="flex items-baseline gap-1 mt-0.5 sm:mt-1">
            <span class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight font-display">
                {{ $value }}
            </span>
            @if($unit)
            <span class="text-[10px] sm:text-xs font-normal text-slate-400">{{ $unit }}</span>
            @endif
        </div>
        @if($subtext)
        <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-slate-600 font-medium mt-1 truncate">
            {{ $subtext }}
        </span>
        @endif
    </div>
    @if($icon || isset($iconSlot))
    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl {{ $resolvedWrapper }} flex items-center justify-center shrink-0 ml-3 shadow-2xs">
        @if($icon)
            <x-icon :name="$icon" class="w-5 h-5 sm:w-6 sm:h-6" />
        @else
            {{ $iconSlot }}
        @endif
    </div>
    @endif
</div>
```

---

### G. Komponen Modal Dialog (`components/modal.blade.php`)
Menghilangkan ratusan baris markup overlay backdrop, card, header, body, dan tombol batal/simpan.

```html
@props([
    'id',
    'title' => 'Formulir',
    'subtitle' => null,
    'maxWidth' => 'max-w-xl',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
    <div class="bg-white rounded-3xl {{ $maxWidth }} w-full max-h-[90vh] overflow-hidden shadow-2xl border border-slate-100 flex flex-col transform transition-all duration-200 scale-95 opacity-0">
        
        <!-- Header -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10 shrink-0">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base font-display">{{ $title }}</h3>
                @if($subtitle)
                <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
        </div>

        <!-- Body (Scrollable) -->
        <div class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if(isset($footer))
        <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2 shrink-0">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
```

---

### H. Komponen Form Input (`components/input.blade.php`)

```html
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => '',
])

<div>
    @if($label)
    <label for="{{ $name }}" class="block text-xs font-bold text-slate-700 mb-1.5">
        {{ $label }}
        @if($required) <span class="text-rose-500">*</span> @endif
    </label>
    @endif
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}" 
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           {{ $attributes->merge(['class' => 'w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 shadow-2xs transition-all']) }}>
    @error($name)
    <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
    @enderror
</div>
```

---

### I. Komponen Filter Card (`components/admin/filter-card.blade.php` / `components/card-filter.blade.php`)
Diadopsi langsung dari arsitektur filter **`C:\xampp\htdocs\pos-apotek`**: Menyediakan card pembungkus form filter responsif, integrasi tombol submit Filter bernuansa hijau alam Wukirsari (`bg-gradient-to-r from-emerald-700 to-[#102b17]`), tombol Reset otomatis yang mendeteksi parameter query aktif, serta fitur **Live Search AJAX** bawaan yang aktif secara default (`:liveSearch="true"`).

**Fitur Live Search:**
1. **Debounce 300ms** pada input teks/pencarian sehingga tidak membebani server saat mengetik.
2. **Immediate filter** saat nilai dropdown `<select>`, `<input type="date">`, radio, atau checkbox berubah.
3. **Paging AJAX**: Klik tombol pagination halaman 2, 3, dst. otomatis diambil via AJAX tanpa refresh layar.
4. **Auto-resolver Target**: Otomatis mendeteksi kartu tabel pembungkus (`data-table-container="true"` atau `.table-custom-container`) di halaman.
5. **Sinkronisasi Otomatis**: Menyinkronkan teks summary/badge total, bilah aksi `<x-slot:actions>`, dan memicu event `live-filter:updated` untuk re-binding modal konfirmasi `data-confirm-...`.

**Halaman yang Menggunakan Komponen Ini:**
- `resources/views/admin/bookings.blade.php` (Filter booking: search, trip date, package, status)
- `resources/views/admin/expenses.blade.php` (Filter pengeluaran: search, category, period, custom dates, action slot summary)
- `resources/views/admin/reviews.blade.php` (Filter ulasan: radio pill rating bintang 1-5, live text search)
- `resources/views/admin/reports.blade.php` (Filter laporan: tab Laba Rugi dengan period mode header slot & tab Penjualan)

```html
<x-admin.filter-card 
    :action="route('admin.bookings')" 
    :resetUrl="route('admin.bookings')"
    :liveSearch="true"
    gridCols="grid-cols-1 sm:grid-cols-2 lg:grid-cols-5">

    <div>
        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Cari Booking / Nama</label>
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Contoh: JKR-1025 / Budi" class="...">
    </div>

    <div>
        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Tanggal Trip</label>
        <input type="date" name="trip_date" value="{{ $tripDate ?? '' }}" class="...">
    </div>
    <!-- Field lainnya; tombol Filter & Reset otomatis ter-render pada kolom terakhir -->
</x-admin.filter-card>
```

---

## 4. Contoh Perbandingan Sebelum vs Sesudah Refactoring

### Contoh di Tabel Admin Booking:

**Sebelum:**
```html
<td class="py-3 px-4 text-center whitespace-nowrap">
    <div class="flex items-center justify-center gap-1.5">
        <button type="button" data-assign="..." onclick="openAssignModal(this)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" title="Atur / Ganti Driver">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        </button>
        <button type="button" data-booking="..." onclick="openBookingDetailModal(this)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-300 text-slate-600 hover:text-blue-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" title="Lihat detail">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </button>
    </div>
</td>
<td>
    @if($b->booking_status === 'FULL_PAID')
        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">Lunas (100%)</span>
    @elseif($b->booking_status === 'DP_PAID')
        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-800 border border-sky-200">DP (30%)</span>
    @endif
</td>
```

**Sesudah (Sangat Ringkas, Bersih, dan Terstandar):**
```html
<td class="py-3 px-4 text-center whitespace-nowrap">
    <div class="flex items-center justify-center gap-1.5">
        <x-action-button type="edit" title="Atur Driver" data-assign="..." onclick="openAssignModal(this)" />
        <x-action-button type="view" title="Lihat Detail" data-booking="..." onclick="openBookingDetailModal(this)" />
    </div>
</td>
<td>
    <x-badge :status="$b->booking_status" />
</td>
```

---

## 5. Rencana Tahapan Migrasi (*Adoption Roadmap*)

1. **Fase 1: Komponen Fondasi (Universal)**
   - Buat file `icon.blade.php`, `badge.blade.php`, `alert.blade.php`, `toast.blade.php`, dan `action-button.blade.php`.
   - Pasang `<x-toast />` di `layouts/app.blade.php` dan `layouts/admin.blade.php`.
2. **Fase 2: Master Layout Admin**
   - Buat `layouts/admin.blade.php`.
   - Bersihkan header dan footer boilerplate di seluruh 11 view admin.
3. **Fase 3: Kartu Statistik & Modal Dialog**
   - Terapkan `<x-stat-card>` di dashboard, armada, pengeluaran, dan ulasan.
   - Refactor modal tambah/edit paket dan modal pengeluaran menjadi `<x-modal>`.
4. **Fase 4: Form Input & Unggah Berkas**
   - Terapkan `<x-input>`, `<x-select>`, dan `<x-image-upload>` pada form reservasi, upload bukti transfer, dan pengaturan admin.

---

## 6. Contoh Penerapan di Halaman Wisatawan / Publik (Non-Admin)

Komponen-komponen universal di atas dirancang 100% kompatibel dan siap dipakai langsung di halaman publik:

### A. Halaman Cek Status Tiket (`ticket/check.blade.php`)
```html
{{-- Input Pencarian Kode Tiket --}}
<x-input name="code" placeholder="Masukkan Kode Booking / No WhatsApp..." required />

{{-- Badge Status Reservasi (Menggantikan 25 baris @if/@elseif warna status) --}}
<x-badge :status="$booking->booking_status" />

{{-- Tampilan Saat Tiket Tidak Ditemukan --}}
@if($hasSearched && !$booking)
    <x-empty-state 
        title="Tiket Tidak Ditemukan" 
        description="Periksa kembali format kode reservasi atau nomor WhatsApp yang Anda masukkan." />
@endif
```

### B. Halaman Pemesanan Jeep (`booking/create.blade.php`)
```html
{{-- Form Biodata Pemesan --}}
<x-input name="customer_name" label="Nama Lengkap" placeholder="Sesuai KTP / Paspor" required />
<x-input name="customer_phone" label="Nomor WhatsApp" type="tel" placeholder="08xxxxxxxxxx" required />

{{-- Modal Syarat & Ketentuan Pemesanan --}}
<x-modal id="modalTerms" title="Syarat & Ketentuan Petualangan Jeep" maxWidth="max-w-2xl">
    <p class="text-xs text-slate-600 leading-relaxed">
        Harap tiba di lokasi basecamp 15 menit sebelum sesi jadwal keberangkatan...
    </p>
</x-modal>
```

### C. Halaman Pembayaran & Upload Bukti (`booking/payment.blade.php`)
```html
{{-- Alert Petunjuk Pembayaran --}}
<x-alert type="warning">
    Selesaikan pembayaran uang muka (DP 30%) atau Lunas sebelum batas waktu berakhir.
</x-alert>

{{-- Upload Bukti Transfer Bank / Manual --}}
<x-image-upload name="payment_proof" label="Unggah Bukti Transfer / Resi ATM" />
```

### D. Halaman Ulasan & Testimoni Wisatawan (`reviews/index.blade.php`)
```html
{{-- Kartu Ringkasan Rating di Banner Atas --}}
<x-stat-card label="Rata-Rata Penilaian" :value="number_format($avgRating, 1)" unit="/ 5.0" />

{{-- Komponen Bintang Rating Tiap Kartu Testimoni --}}
<x-rating-stars :rating="$review->rating" />

{{-- State Ketika Belum Ada Ulasan Pada Bintang Tertentu --}}
<x-empty-state 
    title="Belum Ada Ulasan" 
    description="Belum ada ulasan untuk filter bintang ini. Jadilah yang pertama memberikan testimoni!" />
```

### E. Halaman Masuk Akun (`auth/login.blade.php`)
```html
{{-- Field Email & Sandi --}}
<x-input name="email" label="Alamat Email" type="email" placeholder="nama@email.com" required />
<x-input name="password" label="Kata Sandi" type="password" placeholder="Masukkan kata sandi..." required />
```
