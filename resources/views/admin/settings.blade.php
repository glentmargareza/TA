<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem & Operasional | Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    Pengaturan Sistem & Operasional Paguyuban
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola kredensial akun admin, informasi kontak basecamp, aturan pemesanan trip, serta integrasi sistem.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Role: Administrator Loket</span>
                </span>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1">

            <!-- Flash Notification Success -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        ✓
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- Flash Notification Error -->
            @if(isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs font-semibold shadow-xs space-y-1">
                <div class="flex items-center gap-2 font-bold text-rose-800">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Terdapat beberapa kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-5 font-normal">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- 1. KREDENSIAL & AKUN ADMINISTRATOR -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Header Card -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3.5 bg-slate-50/60">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Profil & Keamanan Akun Admin</h3>
                                <p class="text-xs text-slate-500">Kelola identitas, alamat email masuk, dan kata sandi loket.</p>
                            </div>
                        </div>

                        <!-- Form Profile Admin -->
                        <form action="{{ route('admin.settings.profile') }}" method="POST" class="p-5 sm:p-6 space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Admin Loket</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Akun (Untuk Login)</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">No. Telepon / WhatsApp Admin</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '081234567890') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                                </div>
                            </div>

                            <!-- Divider: Ganti Password -->
                            <div class="pt-4 border-t border-slate-100">
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Ganti Kata Sandi (Kosongkan jika tidak diubah)</span>
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Password Saat Ini</label>
                                        <input type="password" name="current_password" placeholder="Masukkan password lama untuk konfirmasi perubahan" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Password Baru (Min 6 Karakter)</label>
                                            <input type="password" name="new_password" placeholder="Password baru" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Ulangi Password Baru</label>
                                            <input type="password" name="new_password_confirmation" placeholder="Konfirmasi password baru" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end">
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Perubahan Akun</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 2. INFORMASI BASECAMP & KONTAK CS LOKET -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Header Card -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3.5 bg-slate-50/60">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Informasi & Kontak Basecamp</h3>
                                <p class="text-xs text-slate-500">Data pangkalan resmi, kontak customer service, dan jam operasional loket.</p>
                            </div>
                        </div>

                        <!-- Form Informasi Basecamp -->
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="p-5 sm:p-6 space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Resmi Paguyuban Basecamp</label>
                                <input type="text" name="name" value="{{ old('name', $basecamp['name']) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">No. WhatsApp CS Resmi Basecamp</label>
                                    <input type="text" name="cs_phone" value="{{ old('cs_phone', $basecamp['cs_phone']) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                                    <span class="text-[10px] text-slate-400 block mt-1">*Digunakan pada tombol konfirmasi WhatsApp di e-tiket pemesan.</span>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Operasional Pelayanan Loket</label>
                                    <input type="text" name="operational_hours" value="{{ old('operational_hours', $basecamp['operational_hours']) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Pangkalan Basecamp Wukirsari</label>
                                <textarea name="basecamp_address" rows="2" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium leading-relaxed">{{ old('basecamp_address', $basecamp['basecamp_address']) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Link Peta Titik Kumpul (Google Maps)</label>
                                <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $basecamp['google_maps_url'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                            </div>

                            <!-- Konfigurasi Teks Beranda / Landing Page -->
                            <div class="pt-4 border-t border-slate-100 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span>Konfigurasi Teks Beranda (Landing Page)</span>
                                    </h4>
                                    <a href="{{ route('home') }}" class="text-[11px] font-bold text-emerald-700 hover:underline">
                                        Lihat Beranda →
                                    </a>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Badge Tagline Hero Beranda</label>
                                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $basecamp['hero_badge'] ?? 'Offroad Lereng Merapi') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Headline Judul Utama Beranda</label>
                                    <input type="text" name="hero_headline" value="{{ old('hero_headline', $basecamp['hero_headline'] ?? 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Subjudul / Deskripsi Singkat Beranda</label>
                                    <textarea name="hero_subtitle" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium leading-relaxed">{{ old('hero_subtitle', $basecamp['hero_subtitle'] ?? 'Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.') }}</textarea>
                                </div>
                            </div>

                            <!-- Hidden field fallback to keep operational values in sync if submitted here -->
                            <input type="hidden" name="dp_percentage" value="{{ $basecamp['dp_percentage'] ?? 30 }}">
                            <input type="hidden" name="max_pax_per_jeep" value="{{ $basecamp['max_pax_per_jeep'] ?? 4 }}">
                            <input type="hidden" name="checkin_lead_minutes" value="{{ $basecamp['checkin_lead_minutes'] ?? 30 }}">

                            <div class="pt-3 flex justify-end">
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Info Basecamp</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 3. KEBIJAKAN OPERASIONAL PEMESANAN & ARMADA -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Header Card -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3.5 bg-slate-50/60">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Kebijakan Operasional Pemesanan</h3>
                                <p class="text-xs text-slate-500">Standar keselamatan kapasitas jeep, skema uang muka, dan waktu lapor.</p>
                            </div>
                        </div>

                        <!-- Form Kebijakan Booking -->
                        <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="p-5 sm:p-6 space-y-4">
                            @csrf
                            <input type="hidden" name="name" value="{{ $basecamp['name'] }}">
                            <input type="hidden" name="cs_phone" value="{{ $basecamp['cs_phone'] }}">
                            <input type="hidden" name="operational_hours" value="{{ $basecamp['operational_hours'] }}">
                            <input type="hidden" name="basecamp_address" value="{{ $basecamp['basecamp_address'] }}">
                            <input type="hidden" name="google_maps_url" value="{{ $basecamp['google_maps_url'] ?? '' }}">
                            <input type="hidden" name="hero_badge" value="{{ $basecamp['hero_badge'] ?? 'Offroad Lereng Merapi' }}">
                            <input type="hidden" name="hero_headline" value="{{ $basecamp['hero_headline'] ?? 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari' }}">
                            <input type="hidden" name="hero_subtitle" value="{{ $basecamp['hero_subtitle'] ?? 'Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.' }}">

                            <div class="space-y-4 text-xs">
                                <!-- Aturan Kapasitas Jeep -->
                                <div class="p-3.5 bg-emerald-50/70 rounded-xl border border-emerald-200/80">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-emerald-950 text-xs">Kapasitas Maksimal Penumpang per Jeep</span>
                                        <span class="px-2.5 py-1 rounded-md bg-emerald-700 text-white font-bold text-xs">4 Orang / Jeep</span>
                                    </div>
                                    <p class="text-slate-600 text-[11px] mt-1 leading-relaxed">
                                        Standar Keselamatan Jalur Wisata Off-Road Merapi: Sistem secara otomatis menghitung kalkulasi armada (contoh: 5–8 penumpang otomatis direkomendasikan 2 unit Jeep).
                                    </p>
                                    <input type="hidden" name="max_pax_per_jeep" value="4">
                                </div>

                                <!-- Skema Pembayaran DP -->
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                                    <label class="block font-bold text-slate-800 mb-1 text-xs">Persentase Uang Muka (DP Minimum)</label>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <select name="dp_percentage" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white font-bold text-slate-800 text-xs focus:ring-2 focus:ring-emerald-500">
                                            <option value="30" {{ ($basecamp['dp_percentage'] ?? 30) == 30 ? 'selected' : '' }}>30% dari Total Biaya (Standar Paguyuban)</option>
                                            <option value="50" {{ ($basecamp['dp_percentage'] ?? 30) == 50 ? 'selected' : '' }}>50% dari Total Biaya</option>
                                            <option value="100" {{ ($basecamp['dp_percentage'] ?? 30) == 100 ? 'selected' : '' }}>100% (Wajib Pembayaran Lunas di Awal)</option>
                                        </select>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mt-1">*Sisa tagihan akan dilunasi secara tunai atau QRIS loket saat wisatawan tiba di pangkalan basecamp.</span>
                                </div>

                                <!-- Waktu Check-In Loket -->
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                                    <label class="block font-bold text-slate-800 mb-1 text-xs">Batas Waktu Lapor Loket Sebelum Trip</label>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <input type="number" name="checkin_lead_minutes" min="10" max="120" value="{{ old('checkin_lead_minutes', $basecamp['checkin_lead_minutes'] ?? 30) }}" class="w-24 px-3 py-1.5 rounded-xl border border-slate-200 font-bold text-slate-800 text-xs">
                                        <span class="text-xs text-slate-600 font-semibold">Menit sebelum jadwal sesi dimulai</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mt-1">*Tercantum pada e-tiket sebagai panduan ketepatan waktu wisatawan.</span>
                                </div>

                                <!-- Mode Penugasan Driver -->
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-2.5">
                                    <span class="text-base">👤</span>
                                    <div>
                                        <span class="font-bold text-slate-800 block">Penugasan Driver & Armada</span>
                                        <p class="text-slate-600 text-[11px] mt-0.5 leading-relaxed">
                                            Sistem menggunakan algoritma rotasi berkeadilan. Namun, Admin loket dapat <strong>memilih driver secara manual</strong> jika pelanggan mengajukan catatan/permintaan khusus di formulir pemesanan.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end">
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Kebijakan Operasional</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 4. STATUS INTEGRASI & DIAGNOSTIK SERVER -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Header Card -->
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3.5 bg-slate-50/60">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-purple-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Status Integrasi & Diagnostik Sistem</h3>
                                <p class="text-xs text-slate-500">Konektivitas payment gateway, database, dan environment produksi.</p>
                            </div>
                        </div>

                        <!-- Content Status Integrasi -->
                        <div class="p-5 sm:p-6 space-y-3.5 text-xs">
                            <!-- 1. Midtrans Payment Gateway -->
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                        💳
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">Midtrans Payment Gateway</span>
                                        <span class="text-slate-500 text-[11px]">Snap API & Webhook SHA-512 Signature</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Aktif / Siaga</span>
                                </span>
                            </div>

                            <!-- 2. WhatsApp CS Link Service -->
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                        💬
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">WhatsApp CS Direct Dispatch</span>
                                        <span class="text-slate-500 text-[11px]">Konfirmasi tiket otomatis & kontak pemesan</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Terkoneksi</span>
                                </span>
                            </div>

                            <!-- 3. Basis Data MySQL -->
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold">
                                        🗄️
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">Database Server MySQL</span>
                                        <span class="text-slate-500 text-[11px]">Skema: web-ta (10 Tabel Relasional Aktif)</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    <span>Normal</span>
                                </span>
                            </div>

                            <!-- 4. Framework & Environtment -->
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
                        </div>

                        <!-- Footer Info -->
                        <div class="p-5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between text-xs text-slate-500">
                            <span>Sistem Informasi Reservasi Jeep Wukirsari Cangkringan</span>
                            <span class="font-mono text-[11px] font-bold text-slate-600">&copy; {{ date('Y') }} Paguyuban Jeep</span>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

</body>
</html>
