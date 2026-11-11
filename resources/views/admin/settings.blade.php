<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem & Operasional | Admin Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2 font-display">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    Pengaturan Sistem & Operasional
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola informasi profil, data usaha Jeep Wukirsari, kebijakan trip, pembayaran, dan keamanan akun.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Admin Loket: {{ $user->name ?? 'Administrator' }}</span>
                </span>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1 overflow-y-auto">

            <!-- Flash Success Message -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs sm:text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        ✓
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- Flash Error Message -->
            @if(isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs font-semibold shadow-xs space-y-1">
                <div class="flex items-center gap-2 font-bold text-rose-800">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Terdapat beberapa kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-5 font-normal">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- KONTENER UTAMA PENGATURAN (SESUAI GAMBAR) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs divide-y divide-slate-100 overflow-hidden">

                <!-- 1. PROFIL ADMIN -->
                <div class="setting-item group" id="section-1">
                    <button type="button" onclick="toggleSection(1)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Person Outline -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">1. Profil Admin</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Mengelola informasi akun administrator.</p>
                                <p class="text-xs text-slate-400 mt-1">Nama lengkap, username, email, foto profil, nomor HP.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 1 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-1">
                        <form action="{{ route('admin.settings.profile') }}" method="POST" class="max-w-3xl space-y-5">
                            @csrf
                            @method('PUT')

                            <div class="flex items-center gap-4 pb-4 border-b border-slate-200">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-700 to-[#143B1E] text-white flex items-center justify-center font-black text-xl font-display shadow-xs">
                                    {{ strtoupper(substr($user->name ?? 'A', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm font-display">{{ $user->name ?? 'Admin Loket' }}</h4>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Role: Administrator Paguyuban
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Administrator <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Username / ID Login</label>
                                    <input type="text" value="admin_loket" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-xs text-slate-500 font-semibold cursor-not-allowed">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Akun (Login) <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Handphone / WhatsApp</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '081234567890') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Profil Admin</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 2. INFORMASI JEEP WUKIRSARI -->
                <div class="setting-item group" id="section-2">
                    <button type="button" onclick="toggleSection(2)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Building / Business -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">2. Informasi Jeep Wukirsari</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Mengelola identitas dan informasi usaha.</p>
                                <p class="text-xs text-slate-400 mt-1">Nama usaha, logo, alamat, nomor WhatsApp, deskripsi, lokasi Google Maps.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 2 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-2">
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="max-w-3xl space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Resmi Usaha / Paguyuban</label>
                                <input type="text" name="name" value="{{ old('name', $basecamp['name'] ?? 'Paguyuban Jeep Wukirsari Adventure') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp Resmi (CS & Konfirmasi)</label>
                                    <input type="text" name="cs_phone" value="{{ old('cs_phone', $basecamp['cs_phone'] ?? '+62 812-3456-7890') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Operasional Pelayanan Basecamp</label>
                                    <input type="text" name="operational_hours" value="{{ old('operational_hours', $basecamp['operational_hours'] ?? '06:00 - 18:00 WIB') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Basecamp Wukirsari</label>
                                <textarea name="basecamp_address" rows="2" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('basecamp_address', $basecamp['basecamp_address'] ?? 'Jl. Bebeng - Cangkringan, Wukirsari, Sleman, Yogyakarta') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Link Peta Titik Kumpul (Google Maps URL)</label>
                                <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $basecamp['google_maps_url'] ?? '') }}" placeholder="https://maps.google.com/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Headline Beranda / Slogan</label>
                                    <input type="text" name="hero_headline" value="{{ old('hero_headline', $basecamp['hero_headline'] ?? 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Badge Tagline</label>
                                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $basecamp['hero_badge'] ?? 'Offroad Lereng Merapi') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Informasi Jeep Wukirsari</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 3. FOTO BACKGROUND BERANDA (HERO SLIDER) -->
                <div class="setting-item group" id="section-3">
                    <button type="button" onclick="toggleSection(3)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Landscape / Camera -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80 shadow-2xs">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">3. Foto Background Beranda</h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wide">Hero Banner</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Ubah foto latar belakang utama beranda dan slide foto destinasi wisata.</p>
                                <p class="text-xs text-slate-400 mt-1">Upload foto langsung dari perangkat (HP/PC) atau gunakan link gambar dengan live preview.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 3 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-3">
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-6">
                            @csrf

                            <!-- CARD 1: FOTO LATAR BELAKANG UTAMA (SLIDE 01) -->
                            <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-7 h-7 rounded-lg bg-emerald-700 text-white font-black text-xs flex items-center justify-center shadow-xs">01</span>
                                        <div>
                                            <h4 class="font-extrabold text-slate-900 text-sm">Background Utama Beranda (Slide 01)</h4>
                                            <p class="text-[11px] text-slate-500">Foto utama yang pertama kali dilihat pengunjung saat membuka website</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ !empty($basecamp['hero_background']) ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                        {{ !empty($basecamp['hero_background']) ? 'Foto Kustom Aktif' : 'Foto Default (Beranda)' }}
                                    </span>
                                </div>

                                <!-- Live Cinematic Preview Box -->
                                <div class="relative w-full h-48 sm:h-64 rounded-2xl overflow-hidden bg-slate-900 border border-slate-300 shadow-inner group">
                                    <img id="preview_hero_bg" 
                                         src="{{ !empty($basecamp['hero_background']) ? (str_starts_with($basecamp['hero_background'], 'http') ? $basecamp['hero_background'] : asset($basecamp['hero_background'])) : asset('img/beranda.jpeg') }}" 
                                         alt="Preview Background Beranda" 
                                         class="w-full h-full object-cover object-center filter brightness-90 transition-all duration-300">
                                    
                                    <!-- Mockup Overlay to show exactly how it looks with Beranda text -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/40 to-transparent flex flex-col justify-end p-5 sm:p-6 text-white pointer-events-none">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Pratinjau Tampilan Beranda</span>
                                        <h5 class="text-base sm:text-xl font-extrabold font-display leading-tight drop-shadow-md max-w-xl">
                                            {{ $basecamp['hero_headline'] ?? 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari' }}
                                        </h5>
                                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/40 backdrop-blur-md border border-white/20 text-[10px] text-emerald-300 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span id="preview_hero_loc_text">{{ $basecamp['hero_location_0'] ?? 'Susur Kali Gendol' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Controls & Options -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Foto Baru dari Device</label>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <label for="input_hero_bg_file" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold cursor-pointer transition shadow-2xs hover:shadow">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                <span>Upload dari Device (HP/PC)</span>
                                            </label>
                                            <input type="file" id="input_hero_bg_file" name="hero_background_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewHeroBgFile(this)">
                                            
                                            <button type="button" id="btn_reset_bg_file" onclick="cancelHeroBgFile()" class="hidden px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-xl font-medium transition cursor-pointer">
                                                Batal File
                                            </button>
                                        </div>
                                        <p id="label_hero_bg_filename" class="text-[11px] text-slate-500 mt-1.5">Format didukung: JPG, PNG, WEBP (Maksimal 5 MB)</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Label Lokasi Destinasi (Slide 01)</label>
                                        <input type="text" name="hero_location_0" id="input_hero_location_0" value="{{ old('hero_location_0', $basecamp['hero_location_0'] ?? 'Susur Kali Gendol') }}" oninput="updateHeroLocPreview(this.value)" placeholder="Contoh: Susur Kali Gendol" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                </div>

                                <!-- Optional Online URL -->
                                <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                    <div class="flex-1 w-full">
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Atau gunakan URL Gambar Online (Opsional):</label>
                                        <input type="url" name="hero_background" id="input_hero_bg_url" value="{{ old('hero_background', $basecamp['hero_background'] ?? '') }}" placeholder="https://images.unsplash.com/..." oninput="previewHeroBgUrl(this.value)" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                    
                                    @if(!empty($basecamp['hero_background']))
                                    <div class="shrink-0 pt-1 sm:pt-4">
                                        <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 font-bold cursor-pointer">
                                            <input type="checkbox" name="reset_hero_background" value="1" onchange="toggleResetDefaultBg(this)" class="rounded text-rose-600 focus:ring-rose-500">
                                            <span>Kembalikan ke Foto Default</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- CARD 2: SLIDE FOTO DESTINASI WISATA LAINNYA (02 - 05) -->
                            <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-sm">Slide Foto Destinasi Tambahan (02 &ndash; 05)</h4>
                                        <p class="text-[11px] text-slate-500">Foto berganti otomatis saat slider beranda berjalan atau tombol angka diklik</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        $slidesConfig = [
                                            1 => ['num' => '02', 'default_loc' => 'Spot Sunrise', 'default_img' => 'img/sunrise_trip.jpeg'],
                                            2 => ['num' => '03', 'default_loc' => 'Jalur Offroad', 'default_img' => 'img/long_trip.jpeg'],
                                            3 => ['num' => '04', 'default_loc' => 'Museum Kampung Hilang', 'default_img' => 'img/medium_trip_a.jpeg'],
                                            4 => ['num' => '05', 'default_loc' => 'Manuver Air', 'default_img' => 'img/manuver_air.png'],
                                        ];
                                    @endphp

                                    @foreach($slidesConfig as $idx => $cfg)
                                        @php
                                            $slideVal = $basecamp["hero_slide_{$idx}"] ?? null;
                                            $slideSrc = !empty($slideVal) ? (str_starts_with($slideVal, 'http') ? $slideVal : asset($slideVal)) : asset($cfg['default_img']);
                                            $slideLoc = $basecamp["hero_location_{$idx}"] ?? $cfg['default_loc'];
                                        @endphp
                                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/60 space-y-2.5">
                                            <div class="flex items-center justify-between">
                                                <span class="px-2 py-0.5 rounded-lg bg-emerald-700 text-white font-extrabold text-[11px]">Slide {{ $cfg['num'] }}</span>
                                                <span class="text-[10px] text-slate-400 font-medium">Default: {{ $cfg['default_loc'] }}</span>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <div class="relative w-20 h-16 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 shrink-0 shadow-inner">
                                                    <img id="preview_slide_{{ $idx }}" src="{{ $slideSrc }}" alt="Slide {{ $cfg['num'] }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 min-w-0 space-y-1.5">
                                                    <label for="input_slide_{{ $idx }}_file" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-[11px] font-bold cursor-pointer transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                        <span>Ganti Foto</span>
                                                    </label>
                                                    <input type="file" id="input_slide_{{ $idx }}_file" name="hero_slide_{{ $idx }}_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" data-slide-index="{{ $idx }}" onchange="previewExtraSlideFile(this)">
                                                    <p id="label_slide_{{ $idx }}_name" class="text-[10px] text-slate-500 truncate">Pilih foto dari device</p>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lokasi Slide {{ $cfg['num'] }}</label>
                                                <input type="text" name="hero_location_{{ $idx }}" value="{{ old("hero_location_{$idx}", $slideLoc) }}" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            </div>

                                            <div>
                                                <input type="url" name="hero_slide_{{ $idx }}" id="input_slide_{{ $idx }}_url" value="{{ old("hero_slide_{$idx}", $slideVal ?? '') }}" placeholder="Atau URL gambar online..." data-slide-index="{{ $idx }}" oninput="previewExtraSlideUrl(this)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            </div>

                                            @if(!empty($slideVal))
                                            <div class="pt-1">
                                                <label class="inline-flex items-center gap-1 text-[11px] text-rose-600 hover:text-rose-700 font-semibold cursor-pointer">
                                                    <input type="checkbox" name="reset_hero_slide_{{ $idx }}" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                                    <span>Kembalikan ke default slide ini</span>
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-between border-t border-slate-200">
                                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    <span>Buka & Lihat Beranda</span>
                                </a>

                                <button type="submit" class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Perubahan Background</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 4. PENGATURAN OPERASIONAL -->
                <div class="setting-item group" id="section-4">
                    <button type="button" onclick="toggleSection(4)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Sliders / Settings -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">4. Pengaturan Operasional</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Mengatur ketentuan operasional wisata.</p>
                                <p class="text-xs text-slate-400 mt-1">Jam operasional, maksimal 4 penumpang/jeep, batas pickup, ketentuan pembatalan.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 4 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-4">
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="max-w-3xl space-y-5">
                            @csrf
                            
                            <!-- Kapasitas Jeep -->
                            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">Maksimal Kapasitas Penumpang per Jeep</h4>
                                    <p class="text-[11px] text-slate-600 mt-0.5">Standar operasional & keselamatan off-road lereng Merapi.</p>
                                </div>
                                <span class="px-3 py-1.5 rounded-xl bg-emerald-800 text-white font-black text-xs font-display shrink-0">
                                    4 Penumpang / Jeep
                                </span>
                                <input type="hidden" name="max_pax_per_jeep" value="4">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Persentase Uang Muka (DP Minimum)</label>
                                    <select name="dp_percentage" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="30" {{ ($basecamp['dp_percentage'] ?? 30) == 30 ? 'selected' : '' }}>30% dari Total Tagihan (Standar Paguyuban)</option>
                                        <option value="50" {{ ($basecamp['dp_percentage'] ?? 30) == 50 ? 'selected' : '' }}>50% dari Total Tagihan</option>
                                        <option value="100" {{ ($basecamp['dp_percentage'] ?? 30) == 100 ? 'selected' : '' }}>100% Pembayaran Lunas di Awal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Waktu Lapor / Check-In di Loket</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="checkin_lead_minutes" min="10" max="120" value="{{ old('checkin_lead_minutes', $basecamp['checkin_lead_minutes'] ?? 30) }}" class="w-24 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <span class="text-xs text-slate-600 font-semibold">Menit sebelum jam trip</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas & Ketentuan Pickup (Jemput Hotel / Stasiun)</label>
                                <p class="text-xs text-slate-500 mb-2">Penjemputan berlaku untuk zona terdaftar (Cangkringan, Kaliurang, Kota Yogyakarta) dengan penambahan armada shuttle.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ketentuan & Kebijakan Pembatalan</label>
                                <textarea name="cancellation_terms" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('cancellation_terms', $basecamp['cancellation_terms'] ?? 'Pembatalan maksimal H-1 sebelum jam keberangkatan dengan pengembalian dana DP 50% atau perubahan jadwal gratis 1x.') }}</textarea>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Pengaturan Operasional</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 5. METODE PEMBAYARAN -->
                <div class="setting-item group" id="section-5">
                    <button type="button" onclick="toggleSection(5)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Credit Card -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">5. Metode Pembayaran</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Mengelola konfigurasi pembayaran.</p>
                                <p class="text-xs text-slate-400 mt-1">QRIS, informasi rekening, konfigurasi Xendit, status integrasi pembayaran.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 5 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-5">
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="max-w-3xl space-y-4">
                            @csrf

                            <!-- Status Payment Gateway Card -->
                            <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                        💳
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs">Integrasi Gateway Pembayaran</h4>
                                        <p class="text-[11px] text-slate-500">Mendukung QRIS, GoPay, OVO, ShopeePay, Virtual Account & Xendit/Midtrans.</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Aktif & Terhubung</span>
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kanal Payment Gateway Aktif</label>
                                <input type="text" name="payment_channel" value="{{ old('payment_channel', $basecamp['payment_channel'] ?? 'Midtrans Payment Gateway (QRIS, VA, E-Wallet)') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfigurasi & Informasi QRIS</label>
                                <input type="text" name="qris_info" value="{{ old('qris_info', $basecamp['qris_info'] ?? 'QRIS Dinamis Otomatis via Midtrans / QRIS Statis Loket Pangkalan') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Informasi Rekening Bank Kasir Loket</label>
                                <input type="text" name="bank_account_info" value="{{ old('bank_account_info', $basecamp['bank_account_info'] ?? 'Bank Mandiri: 137-00-1234567-8 a/n Paguyuban Jeep Wukirsari') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>

                            <!-- Google Places & Maps API -->
                            @php
                                $hasGoogleKey = !empty(config('services.google.maps_api_key') ?: (env('GOOGLE_MAPS_API_KEY') ?: (env('GOOGLE_MAPS_SERVER_KEY') ?: env('GOOGLE_MAPS_BROWSER_KEY'))));
                            @endphp
                            <div class="p-3.5 {{ $hasGoogleKey ? 'bg-slate-50' : 'bg-amber-50/70 border-amber-200' }} rounded-xl border border-slate-200/80 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $hasGoogleKey ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} flex items-center justify-center font-bold">
                                        📍
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">Google Places & Maps API</span>
                                        <span class="text-slate-500 text-[11px]">
                                            {{ $hasGoogleKey ? 'Places API (New) & Geocoding terhubung.' : 'Google Places belum aktif, hasil pencarian terbatas (menggunakan fallback gratis Photon & OpenStreetMap).' }}
                                        </span>
                                    </div>
                                </div>
                                @if($hasGoogleKey)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Aktif</span>
                                </span>
                                @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 flex items-center gap-1 shrink-0" title="Isi GOOGLE_MAPS_API_KEY di .env">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                    <span>Google Places belum aktif, hasil pencarian terbatas</span>
                                </span>
                                @endif
                            </div>

                            <!-- Framework & Environtment -->
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center font-bold">
                                        ⚙️
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">Arsitektur Aplikasi</span>
                                        <span class="text-slate-500 text-[11px]">Laravel 12 MVC & Tailwind CSS</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">
                                    v12.x Stable
                                </span>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Konfigurasi Pembayaran</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 6. NOTIFIKASI -->
                <div class="setting-item group" id="section-6">
                    <button type="button" onclick="toggleSection(6)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Bell -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">6. Notifikasi</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Mengatur pemberitahuan sistem.</p>
                                <p class="text-xs text-slate-400 mt-1">Notifikasi booking baru, pembayaran berhasil, pembatalan, notifikasi WhatsApp.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 6 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-6">
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="max-w-3xl space-y-4">
                            @csrf
                            <input type="hidden" name="is_notification_form" value="1">

                            <div class="space-y-3">
                                <!-- 1. Booking Baru -->
                                <label class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between cursor-pointer hover:border-emerald-300 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm font-bold">
                                            🎉
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 text-xs block">Notifikasi Pesanan Tiket Baru</span>
                                            <span class="text-[11px] text-slate-500 block">Pemberitahuan instan di lonceng notifikasi saat ada wisatawan memesan.</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="notify_booking_new" value="1" {{ ($basecamp['notify_booking_new'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                </label>

                                <!-- 2. Pembayaran Berhasil -->
                                <label class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between cursor-pointer hover:border-emerald-300 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center text-sm font-bold">
                                            💰
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 text-xs block">Notifikasi Pembayaran Berhasil</span>
                                            <span class="text-[11px] text-slate-500 block">Pemberitahuan pelunasan atau DP yang diverifikasi otomatis dari Midtrans.</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="notify_payment_success" value="1" {{ ($basecamp['notify_payment_success'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                </label>

                                <!-- 3. Pembatalan -->
                                <label class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between cursor-pointer hover:border-emerald-300 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center text-sm font-bold">
                                            ✕
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 text-xs block">Notifikasi Pembatalan Tiket</span>
                                            <span class="text-[11px] text-slate-500 block">Peringatan saat pemesanan dibatalkan dan kuota jeep dikembalikan.</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="notify_cancellation" value="1" {{ ($basecamp['notify_cancellation'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                </label>

                                <!-- 4. WhatsApp -->
                                <label class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between cursor-pointer hover:border-emerald-300 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm font-bold">
                                            💬
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 text-xs block">Integrasi Notifikasi WhatsApp Wisatawan</span>
                                            <span class="text-[11px] text-slate-500 block">Kirim tautan e-tiket & panduan titik kumpul via WhatsApp secara otomatis.</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="notify_wa" value="1" {{ ($basecamp['notify_wa'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                                </label>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Pengaturan Notifikasi</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 7. KEAMANAN AKUN -->
                <div class="setting-item group" id="section-7">
                    <button type="button" onclick="toggleSection(7)" class="w-full text-left p-6 sm:p-7 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                            <!-- Icon Shield Check -->
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100/80">
                                <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg font-display tracking-tight">7. Keamanan Akun</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Menjaga keamanan akun administrator.</p>
                                <p class="text-xs text-slate-400 mt-1">Ganti password, konfirmasi password, logout.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 transition-transform duration-200 arrow-btn" id="arrow-7">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Form Content Section 7 -->
                    <div class="hidden border-t border-slate-100 bg-slate-50/40 p-6 sm:p-8" id="content-7">
                        <div class="max-w-3xl space-y-6">
                            
                            <!-- Form Ganti Password -->
                            <form action="{{ route('admin.settings.profile') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">

                                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Ganti Kata Sandi Administrator</span>
                                </h4>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Password Saat Ini <span class="text-rose-500">*</span></label>
                                    <input type="password" name="current_password" required placeholder="Masukkan password saat ini untuk verifikasi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru (Min 6 Karakter) <span class="text-rose-500">*</span></label>
                                        <input type="password" name="new_password" required placeholder="Password baru" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Password Baru <span class="text-rose-500">*</span></label>
                                        <input type="password" name="new_password_confirmation" required placeholder="Ulangi password baru" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                </div>

                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        <span>Perbarui Kata Sandi</span>
                                    </button>
                                </div>
                            </form>

                            <!-- Zona Logout -->
                            <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-rose-50/60 border border-rose-200/60">
                                <div>
                                    <h5 class="font-extrabold text-rose-900 text-xs">Keluar Sesi Administrator</h5>
                                    <p class="text-[11px] text-rose-700 mt-0.5">Akhiri sesi login loket ini dan amankan akun Anda.</p>
                                </div>
                                <form action="{{ route('logout') }}" method="POST"
                                      data-confirm-title="Keluar Sesi Administrator"
                                      data-confirm-message="Apakah Anda yakin ingin keluar dari portal admin loket Jeep Wukirsari?"
                                      data-confirm-type="danger"
                                      data-confirm-text="Ya, Keluar"
                                      data-confirm-cancel="Batal">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span>Keluar / Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <!-- SCRIPT ACCORDION TOGGLE & HERO BG PREVIEW -->
    <script>
        function toggleSection(num) {
            const content = document.getElementById('content-' + num);
            const arrow = document.getElementById('arrow-' + num);
            if (!content || !arrow) return;

            const isHidden = content.classList.contains('hidden');

            // Close all other sections for accordion behavior
            for (let i = 1; i <= 7; i++) {
                const otherContent = document.getElementById('content-' + i);
                const otherArrow = document.getElementById('arrow-' + i);
                if (otherContent && otherArrow) {
                    otherContent.classList.add('hidden');
                    otherArrow.classList.remove('rotate-180', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                }
            }

            // If it was hidden, open it
            if (isHidden) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
            }
        }

        // Live Preview Functions for Hero Background (Slide 01)
        const defaultHeroBgSrc = "{{ !empty($basecamp['hero_background']) ? (str_starts_with($basecamp['hero_background'], 'http') ? $basecamp['hero_background'] : asset($basecamp['hero_background'])) : asset('img/beranda.jpeg') }}";

        function previewHeroBgFile(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const previewImg = document.getElementById('preview_hero_bg');
                const btnReset = document.getElementById('btn_reset_bg_file');
                const labelName = document.getElementById('label_hero_bg_filename');

                if (previewImg) {
                    previewImg.src = URL.createObjectURL(file);
                }
                if (btnReset) btnReset.classList.remove('hidden');
                if (labelName) labelName.textContent = 'File dipilih: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            }
        }

        function cancelHeroBgFile() {
            const input = document.getElementById('input_hero_bg_file');
            const btnReset = document.getElementById('btn_reset_bg_file');
            const labelName = document.getElementById('label_hero_bg_filename');
            const previewImg = document.getElementById('preview_hero_bg');
            const urlInput = document.getElementById('input_hero_bg_url');

            if (input) input.value = '';
            if (btnReset) btnReset.classList.add('hidden');
            if (labelName) labelName.textContent = 'Format didukung: JPG, PNG, WEBP (Maksimal 5 MB)';

            if (urlInput && urlInput.value.trim().length > 5) {
                previewImg.src = urlInput.value.trim();
            } else {
                previewImg.src = defaultHeroBgSrc;
            }
        }

        function previewHeroBgUrl(url) {
            const fileInput = document.getElementById('input_hero_bg_file');
            if (fileInput && fileInput.files && fileInput.files.length > 0) return; // File upload takes precedence

            const previewImg = document.getElementById('preview_hero_bg');
            if (previewImg) {
                if (url && url.trim().length > 5) {
                    previewImg.src = url.trim();
                } else {
                    previewImg.src = defaultHeroBgSrc;
                }
            }
        }

        function updateHeroLocPreview(text) {
            const locText = document.getElementById('preview_hero_loc_text');
            if (locText) {
                locText.textContent = text && text.trim().length > 0 ? text : 'Susur Kali Gendol';
            }
        }

        function toggleResetDefaultBg(checkbox) {
            const previewImg = document.getElementById('preview_hero_bg');
            if (checkbox.checked) {
                if (previewImg) previewImg.src = "{{ asset('img/beranda.jpeg') }}";
            } else {
                if (previewImg) previewImg.src = defaultHeroBgSrc;
            }
        }

        // Extra slides helpers (02 - 05)
        function previewExtraSlideFile(input) {
            const idx = input.getAttribute('data-slide-index');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const previewImg = document.getElementById('preview_slide_' + idx);
                const labelName = document.getElementById('label_slide_' + idx + '_name');

                if (previewImg) previewImg.src = URL.createObjectURL(file);
                if (labelName) labelName.textContent = file.name;
            }
        }

        function previewExtraSlideUrl(input) {
            const idx = input.getAttribute('data-slide-index');
            const url = input.value;
            const fileInput = document.getElementById('input_slide_' + idx + '_file');
            if (fileInput && fileInput.files && fileInput.files.length > 0) return;

            const previewImg = document.getElementById('preview_slide_' + idx);
            if (previewImg && url && url.trim().length > 5) {
                previewImg.src = url.trim();
            }
        }
    </script>

</body>
</html>
