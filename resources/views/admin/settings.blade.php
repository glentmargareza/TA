<x-admin-layout title="Pengaturan Sistem & Operasional | Admin Jeep Wukirsari">

    <!-- Header Banner -->
    <x-admin.header title="Pengaturan Sistem & Operasional" subtitle="Kelola informasi akun, identitas usaha, tampilan website beranda, dan aturan operasional trip.">
        <x-slot:actions>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200 hover:bg-slate-50 active:scale-95 text-slate-700 text-xs font-bold transition-all shadow-2xs">
                <x-icon name="home" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-700 shrink-0" />
                <span>Lihat Beranda</span>
            </a>
        </x-slot:actions>
    </x-admin.header>

        <main class="p-3.5 sm:p-6 md:p-8 space-y-4 sm:space-y-6 flex-1 max-w-6xl w-full mx-auto overflow-y-auto">

            <!-- NAVIGASI TAB UTAMA (SWIPEABLE HORIZONTAL DI MOBILE) -->
            <div class="bg-white p-1 sm:p-1.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-1 overflow-x-auto no-scrollbar scroll-smooth">
                
                <!-- Tab 1: Akun & Keamanan -->
                <button type="button" onclick="switchSettingsTab('akun')" id="tab-btn-akun" 
                        class="settings-tab-btn inline-flex items-center gap-1.5 sm:gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer whitespace-nowrap active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profil & Keamanan</span>
                </button>

                <!-- Tab 2: Identitas Usaha -->
                <button type="button" onclick="switchSettingsTab('usaha')" id="tab-btn-usaha" 
                        class="settings-tab-btn inline-flex items-center gap-1.5 sm:gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer whitespace-nowrap active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Identitas Usaha</span>
                </button>

                <!-- Tab 3: Tampilan Beranda -->
                <button type="button" onclick="switchSettingsTab('beranda')" id="tab-btn-beranda" 
                        class="settings-tab-btn inline-flex items-center gap-1.5 sm:gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer whitespace-nowrap active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Tampilan Beranda</span>
                </button>

                <!-- Tab 4: Operasional Trip -->
                <button type="button" onclick="switchSettingsTab('operasional')" id="tab-btn-operasional" 
                        class="settings-tab-btn inline-flex items-center gap-1.5 sm:gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer whitespace-nowrap active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    <span>Operasional Trip</span>
                </button>

                <!-- Tab 5: Pembayaran & Notifikasi -->
                <button type="button" onclick="switchSettingsTab('pembayaran')" id="tab-btn-pembayaran" 
                        class="settings-tab-btn inline-flex items-center gap-1.5 sm:gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer whitespace-nowrap active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Pembayaran & Notifikasi</span>
                </button>
            </div>

            <!-- ========================================== -->
            <!-- TAB 1: PROFIL & KEAMANAN AKUN              -->
            <!-- ========================================== -->
            <div id="tab-content-akun" class="settings-tab-content space-y-4 sm:space-y-6">
                
                <!-- Card 1: Profil Admin -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Profil Administrator</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Kelola identitas akun dan kontak admin yang bertugas.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-[11px] font-bold bg-emerald-100 text-emerald-800 self-start sm:self-auto">
                            Role: Admin Paguyuban
                        </span>
                    </div>

                    <form action="{{ route('admin.settings.profile') }}" method="POST" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-emerald-800 to-[#0D2714] text-white flex items-center justify-center font-black text-base sm:text-lg font-display shadow-xs shrink-0">
                                {{ strtoupper(substr($user->name ?? 'A', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">{{ $user->name ?? 'Admin Loket' }}</h4>
                                <p class="text-[11px] sm:text-xs text-slate-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Administrator <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Username / ID Login</label>
                                <input type="text" value="admin_loket" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-xs sm:text-sm text-slate-500 font-semibold cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Akun (Login) <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Handphone / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '081234567890') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Profil Admin</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card 2: Keamanan & Ganti Password -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Ganti Kata Sandi</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Perbarui kata sandi secara berkala untuk menjaga keamanan akun admin.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.profile') }}" method="POST" class="p-4 sm:p-6 space-y-3.5 sm:space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Saat Ini <span class="text-rose-500">*</span></label>
                            <input type="password" name="current_password" required placeholder="Masukkan password saat ini untuk verifikasi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru (Min. 6 Karakter) <span class="text-rose-500">*</span></label>
                                <input type="password" name="new_password" required placeholder="Password baru" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru <span class="text-rose-500">*</span></label>
                                <input type="password" name="new_password_confirmation" required placeholder="Ulangi password baru" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Perbarui Kata Sandi</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card 3: Keluar Sesi -->
                <div class="bg-rose-50/60 rounded-2xl border border-rose-200/70 p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                    <div>
                        <h4 class="font-bold text-rose-950 text-xs sm:text-sm">Keluar Sesi Administrator</h4>
                        <p class="text-[11px] sm:text-xs text-rose-700 mt-0.5">Akhiri sesi login saat ini dan amankan akun loket Anda.</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST"
                          data-confirm-title="Keluar Sesi Administrator"
                          data-confirm-message="Apakah Anda yakin ingin keluar dari portal admin loket Jeep Wukirsari?"
                          data-confirm-type="danger"
                          data-confirm-text="Ya, Keluar"
                          data-confirm-cancel="Batal"
                          class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white font-bold text-xs sm:text-sm shadow-xs transition-all cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar / Logout</span>
                        </button>
                    </form>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- TAB 2: IDENTITAS USAHA & BASECAMP          -->
            <!-- ========================================== -->
            <div id="tab-content-usaha" class="settings-tab-content space-y-4 sm:space-y-6 hidden">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Identitas Usaha & Basecamp</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Informasi nama paguyuban, alamat titik kumpul, jam buka, dan kontak WhatsApp resmi.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="p-4 sm:p-6 space-y-3.5 sm:space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Resmi Usaha / Paguyuban <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $basecamp['name'] ?? 'Paguyuban Jeep Wukirsari Adventure') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp Resmi (CS & Konfirmasi) <span class="text-rose-500">*</span></label>
                                <input type="text" name="cs_phone" value="{{ old('cs_phone', $basecamp['cs_phone'] ?? '+62 812-3456-7890') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Operasional Pelayanan Basecamp <span class="text-rose-500">*</span></label>
                                <input type="text" name="operational_hours" value="{{ old('operational_hours', $basecamp['operational_hours'] ?? '06:00 - 18:00 WIB') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Basecamp Wukirsari <span class="text-rose-500">*</span></label>
                            <textarea name="basecamp_address" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('basecamp_address', $basecamp['basecamp_address'] ?? 'Jl. Bebeng - Cangkringan, Wukirsari, Sleman, Yogyakarta') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Link Peta Titik Kumpul (Google Maps URL)</label>
                            <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $basecamp['google_maps_url'] ?? '') }}" placeholder="https://maps.google.com/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Headline Beranda / Slogan Utama</label>
                                <input type="text" name="hero_headline" value="{{ old('hero_headline', $basecamp['hero_headline'] ?? 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Badge Tagline</label>
                                <input type="text" name="hero_badge" value="{{ old('hero_badge', $basecamp['hero_badge'] ?? 'Offroad Lereng Merapi') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Identitas Usaha</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: TAMPILAN BERANDA (HERO & GALERI)    -->
            <!-- ========================================== -->
            <div id="tab-content-beranda" class="settings-tab-content space-y-4 sm:space-y-6 hidden">
                
                <!-- Form Banner & Slider Beranda -->
                <form action="{{ route('admin.settings.basecamp') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                    @csrf

                    <!-- Background Utama Beranda (Slide 01) -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 bg-slate-50/50">
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Background Utama Beranda (Slide 01)</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Foto utama yang pertama kali dilihat wisatawan saat membuka website.</p>
                            </div>
                            <span class="px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] font-bold self-start sm:self-auto {{ !empty($basecamp['hero_background']) ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                {{ !empty($basecamp['hero_background']) ? 'Foto Kustom Aktif' : 'Foto Default' }}
                            </span>
                        </div>

                        <div class="p-4 sm:p-6 space-y-3.5 sm:space-y-4">
                            <!-- Live Cinematic Preview Box (Responsive Height) -->
                            <div class="relative w-full h-44 sm:h-64 rounded-2xl overflow-hidden bg-slate-900 border border-slate-200 shadow-inner">
                                <img id="preview_hero_bg" 
                                     src="{{ !empty($basecamp['hero_background']) ? (str_starts_with($basecamp['hero_background'], 'http') ? $basecamp['hero_background'] : asset($basecamp['hero_background'])) : asset('img/beranda.jpeg') }}" 
                                     alt="Preview Background Beranda" 
                                     class="w-full h-full object-cover object-center filter brightness-90 transition-all duration-300">
                                
                                <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/40 to-transparent flex flex-col justify-end p-4 sm:p-6 text-white pointer-events-none">
                                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-emerald-400">Pratinjau Tampilan Beranda</span>
                                    <h5 class="text-sm sm:text-xl font-extrabold font-display leading-snug drop-shadow-md max-w-xl line-clamp-2">
                                        {{ $basecamp['hero_headline'] ?? 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari' }}
                                    </h5>
                                </div>
                            </div>

                            <!-- Upload Controls (Mobile Friendly) -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 pt-1">
                                <label for="input_hero_bg_file" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white rounded-xl text-xs sm:text-sm font-bold cursor-pointer transition shadow-2xs">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span>Upload dari Perangkat (HP/PC)</span>
                                </label>
                                <input type="file" id="input_hero_bg_file" name="hero_background_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewHeroBgFile(this)">
                                
                                <button type="button" id="btn_reset_bg_file" onclick="cancelHeroBgFile()" class="hidden px-3.5 py-2 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-xl font-medium transition cursor-pointer self-start sm:self-auto">
                                    Batal File
                                </button>
                            </div>
                            <p id="label_hero_bg_filename" class="text-[11px] text-slate-500">Format didukung: JPG, PNG, WEBP (Maksimal 5 MB)</p>

                            <!-- Optional Online URL -->
                            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 sm:gap-3">
                                <div class="flex-1 w-full">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Atau gunakan URL Gambar Online (Opsional):</label>
                                    <input type="url" name="hero_background" id="input_hero_bg_url" value="{{ old('hero_background', $basecamp['hero_background'] ?? '') }}" placeholder="https://images.unsplash.com/..." oninput="previewHeroBgUrl(this.value)" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                
                                @if(!empty($basecamp['hero_background']))
                                <div class="shrink-0 pt-0.5 sm:pt-4">
                                    <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 font-bold cursor-pointer">
                                        <input type="checkbox" name="reset_hero_background" value="1" onchange="toggleResetDefaultBg(this)" class="rounded text-rose-600 focus:ring-rose-500">
                                        <span>Reset ke Foto Default</span>
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Slide Foto Destinasi Tambahan (02 - 05) -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Slide Foto Destinasi Tambahan (02 &ndash; 05)</h3>
                            <p class="text-[11px] sm:text-xs text-slate-500">Foto yang berganti otomatis saat slider beranda berjalan.</p>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
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
                                    @endphp
                                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/60 space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="px-2 py-0.5 rounded-lg bg-emerald-800 text-white font-extrabold text-[10px] sm:text-[11px]">Slide {{ $cfg['num'] }}</span>
                                            <span class="text-[11px] sm:text-xs font-semibold text-slate-600">{{ $cfg['default_loc'] }}</span>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <div class="relative w-20 h-16 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 shrink-0 shadow-inner">
                                                <img id="preview_slide_{{ $idx }}" src="{{ $slideSrc }}" alt="Slide {{ $cfg['num'] }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0 space-y-1.5">
                                                <label for="input_slide_{{ $idx }}_file" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white rounded-lg text-[11px] font-bold cursor-pointer transition">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                    <span>Ganti Foto</span>
                                                </label>
                                                <input type="file" id="input_slide_{{ $idx }}_file" name="hero_slide_{{ $idx }}_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" data-slide-index="{{ $idx }}" onchange="previewExtraSlideFile(this)">
                                                <p id="label_slide_{{ $idx }}_name" class="text-[10px] text-slate-500 truncate">Pilih foto dari device</p>
                                            </div>
                                        </div>

                                        <div>
                                            <input type="url" name="hero_slide_{{ $idx }}" id="input_slide_{{ $idx }}_url" value="{{ old("hero_slide_{$idx}", $slideVal ?? '') }}" placeholder="Atau URL Online: https://..." data-slide-index="{{ $idx }}" oninput="previewExtraSlideUrl(this)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        </div>

                                        @if(!empty($slideVal))
                                        <div class="pt-0.5">
                                            <label class="inline-flex items-center gap-1 text-[11px] text-rose-600 hover:text-rose-700 font-semibold cursor-pointer">
                                                <input type="checkbox" name="reset_hero_slide_{{ $idx }}" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                                <span>Kembalikan default</span>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Perubahan Banner Beranda</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Galeri 5 Foto Momen Beranda -->
                <form action="{{ route('admin.settings.basecamp') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                    @csrf

                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                        <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Galeri 5 Foto Momen Petualangan</h3>
                            <p class="text-[11px] sm:text-xs text-slate-500">Foto dokumentasi wisata yang tampil pada bagian "Jelajahi Momen Indah Bersama Kami".</p>
                        </div>

                        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Galeri Beranda</label>
                                    <input type="text" name="gallery_title" value="{{ old('gallery_title', $basecamp['gallery_title'] ?? 'Jelajahi Momen Indah Bersama Kami') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Subjudul / Deskripsi Galeri</label>
                                    <input type="text" name="gallery_subtitle" value="{{ old('gallery_subtitle', $basecamp['gallery_subtitle'] ?? 'Kumpulan foto perjalanan seru di berbagai rute wisata.') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>

                            <!-- Grid 5 Foto Galeri (Responsive) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4 pt-2">
                                @for($i = 1; $i <= 5; $i++)
                                @php
                                    $defaultImg = asset("img/{$i}.jpeg");
                                    $currentImg = !empty($basecamp["gallery_image_{$i}"]) ? (str_starts_with($basecamp["gallery_image_{$i}"], 'http') ? $basecamp["gallery_image_{$i}"] : asset($basecamp["gallery_image_{$i}"])) : $defaultImg;
                                    $isCustom = !empty($basecamp["gallery_image_{$i}"]);
                                    $captionVal = $basecamp["gallery_caption_{$i}"] ?? "Momen Petualangan {$i}";
                                @endphp
                                <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200 bg-slate-50/60 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2 py-0.5 rounded-lg bg-emerald-800 text-white font-extrabold text-[10px]">Foto 0{{ $i }}</span>
                                        <span class="text-[10px] font-bold {{ $isCustom ? 'text-amber-700' : 'text-slate-400' }}">
                                            {{ $isCustom ? 'Kustom' : 'Default' }}
                                        </span>
                                    </div>

                                    <div class="relative w-full h-36 sm:h-32 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 shadow-inner">
                                        <img id="preview_gallery_{{ $i }}" src="{{ $currentImg }}" alt="Galeri {{ $i }}" class="w-full h-full object-cover">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="input_gallery_{{ $i }}_file" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white rounded-lg text-xs font-bold cursor-pointer transition w-full justify-center">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            <span>Upload Foto</span>
                                        </label>
                                        <input type="file" id="input_gallery_{{ $i }}_file" name="gallery_image_{{ $i }}_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" data-gallery-index="{{ $i }}" onchange="previewGalleryFile(this)">
                                        <p id="label_gallery_{{ $i }}_filename" class="text-[10px] text-slate-500 truncate text-center">JPG, PNG, WEBP</p>
                                    </div>

                                    <div>
                                        <input type="text" name="gallery_caption_{{ $i }}" value="{{ old("gallery_caption_{$i}", $captionVal) }}" placeholder="Keterangan foto..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    </div>

                                    @if($isCustom)
                                    <div class="pt-0.5">
                                        <label class="inline-flex items-center gap-1 text-[10px] text-rose-600 font-semibold cursor-pointer">
                                            <input type="checkbox" name="reset_gallery_{{ $i }}" value="1" data-gallery-index="{{ $i }}" onchange="toggleResetGallery(this)" class="rounded text-rose-600 focus:ring-rose-500">
                                            <span>Kembalikan default</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                                @endfor
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Simpan Perubahan Galeri</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

            <!-- ========================================== -->
            <!-- TAB 4: OPERASIONAL TRIP                    -->
            <!-- ========================================== -->
            <div id="tab-content-operasional" class="settings-tab-content space-y-4 sm:space-y-6 hidden">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Ketentuan Operasional & Kebijakan Trip</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Atur batas muatan penumpang, minimal pembayaran DP, dan kebijakan pembatalan.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                        @csrf
                        
                        <!-- Standar Kapasitas Jeep (Mobile Stack) -->
                        <div class="p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 items-start">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">Maksimal Kapasitas Penumpang per Jeep</h4>
                                    <p class="text-[11px] text-slate-600 mt-0.5">Standar resmi keselamatan off-road lereng Merapi.</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-xl bg-emerald-800 text-white font-black text-xs font-display shrink-0 shadow-2xs self-start sm:self-auto">
                                4 Penumpang / Jeep
                            </span>
                            <input type="hidden" name="max_pax_per_jeep" value="4">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Persentase Uang Muka (DP Minimum)</label>
                                <select name="dp_percentage" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="30" {{ ($basecamp['dp_percentage'] ?? 30) == 30 ? 'selected' : '' }}>30% dari Total Tagihan (Standar Paguyuban)</option>
                                    <option value="50" {{ ($basecamp['dp_percentage'] ?? 30) == 50 ? 'selected' : '' }}>50% dari Total Tagihan</option>
                                    <option value="100" {{ ($basecamp['dp_percentage'] ?? 30) == 100 ? 'selected' : '' }}>100% Pembayaran Lunas di Awal</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Waktu Lapor / Check-In di Loket</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="checkin_lead_minutes" min="10" max="120" value="{{ old('checkin_lead_minutes', $basecamp['checkin_lead_minutes'] ?? 30) }}" class="w-24 sm:w-28 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <span class="text-xs text-slate-600 font-semibold">Menit sebelum jam trip</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Ketentuan & Kebijakan Pembatalan / Refund</label>
                            <textarea name="cancellation_terms" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('cancellation_terms', $basecamp['cancellation_terms'] ?? 'Pembatalan maksimal H-1 sebelum jam keberangkatan dengan pengembalian dana DP 50% atau perubahan jadwal gratis 1x.') }}</textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Pengaturan Operasional</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 5: PEMBAYARAN & NOTIFIKASI             -->
            <!-- ========================================== -->
            <div id="tab-content-pembayaran" class="settings-tab-content space-y-4 sm:space-y-6 hidden">
                
                <!-- Card 1: Metode Pembayaran -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Metode & Kanal Pembayaran</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Konfigurasi rekening kasir, QRIS loket, dan payment gateway.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1.5 self-start sm:self-auto">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            <span>Payment Gateway Terhubung</span>
                        </span>
                    </div>

                    <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="p-4 sm:p-6 space-y-3.5 sm:space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kanal Payment Gateway Aktif</label>
                            <input type="text" name="payment_channel" value="{{ old('payment_channel', $basecamp['payment_channel'] ?? 'Midtrans Payment Gateway (QRIS, VA, E-Wallet)') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfigurasi & Informasi QRIS</label>
                            <input type="text" name="qris_info" value="{{ old('qris_info', $basecamp['qris_info'] ?? 'QRIS Dinamis Otomatis via Midtrans / QRIS Statis Loket Pangkalan') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Informasi Rekening Bank Kasir Loket</label>
                            <input type="text" name="bank_account_info" value="{{ old('bank_account_info', $basecamp['bank_account_info'] ?? 'Bank Mandiri: 137-00-1234567-8 a/n Paguyuban Jeep Wukirsari') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <!-- Status Google Places -->
                        @php
                            $hasGoogleKey = !empty(config('services.google.maps_api_key') ?: (env('GOOGLE_MAPS_API_KEY') ?: (env('GOOGLE_MAPS_SERVER_KEY') ?: env('GOOGLE_MAPS_BROWSER_KEY'))));
                        @endphp
                        <div class="p-3.5 {{ $hasGoogleKey ? 'bg-slate-50' : 'bg-amber-50/70 border-amber-200' }} rounded-xl border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 items-start sm:items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $hasGoogleKey ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} flex items-center justify-center font-bold shrink-0">
                                    <svg class="w-4 h-4 {{ $hasGoogleKey ? 'text-emerald-800' : 'text-amber-800' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 text-xs block">Google Places & Maps API</span>
                                    <span class="text-slate-500 text-[11px]">
                                        {{ $hasGoogleKey ? 'Places API (New) & Geocoding terhubung.' : 'Google Places belum aktif, menggunakan fallback gratis Photon & OpenStreetMap.' }}
                                    </span>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] font-bold self-start sm:self-auto shrink-0 {{ $hasGoogleKey ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                                {{ $hasGoogleKey ? 'Aktif' : 'Fallback OSM' }}
                            </span>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Konfigurasi Pembayaran</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card 2: Pengaturan Notifikasi Sistem -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 font-display">Pemberitahuan & Notifikasi Sistem</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500">Atur jenis notifikasi yang ingin dicatat ke linimasa dan dikirimkan ke wisatawan.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.basecamp') }}" method="POST" class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        @csrf
                        <input type="hidden" name="is_notification_form" value="1">

                        <div class="space-y-2.5 sm:space-y-3">
                            <!-- 1. Booking Baru -->
                            <label class="p-3.5 sm:p-4 rounded-xl bg-slate-50/70 border border-slate-200/90 flex items-center justify-between gap-3 cursor-pointer hover:border-emerald-300 transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 pr-2">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-sm font-bold shrink-0">
                                        <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 text-xs block truncate">Notifikasi Pesanan Tiket Baru</span>
                                        <span class="text-[11px] text-slate-500 block line-clamp-1">Pemberitahuan otomatis saat wisatawan memesan trip.</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notify_booking_new" value="1" {{ ($basecamp['notify_booking_new'] ?? true) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500 shrink-0">
                            </label>

                            <!-- 2. Pembayaran Berhasil -->
                            <label class="p-3.5 sm:p-4 rounded-xl bg-slate-50/70 border border-slate-200/90 flex items-center justify-between gap-3 cursor-pointer hover:border-emerald-300 transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 pr-2">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center text-sm font-bold shrink-0">
                                        <svg class="w-4 h-4 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 text-xs block truncate">Notifikasi Pembayaran Masuk</span>
                                        <span class="text-[11px] text-slate-500 block line-clamp-1">Pemberitahuan saat DP atau pelunasan berhasil diverifikasi.</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notify_payment_success" value="1" {{ ($basecamp['notify_payment_success'] ?? true) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500 shrink-0">
                            </label>

                            <!-- 3. Pembatalan -->
                            <label class="p-3.5 sm:p-4 rounded-xl bg-slate-50/70 border border-slate-200/90 flex items-center justify-between gap-3 cursor-pointer hover:border-emerald-300 transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 pr-2">
                                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center text-sm font-bold shrink-0">
                                        <svg class="w-4 h-4 text-rose-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 text-xs block truncate">Notifikasi Pembatalan Tiket</span>
                                        <span class="text-[11px] text-slate-500 block line-clamp-1">Pemberitahuan saat booking dibatalkan dan kuota jeep dikembalikan.</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notify_cancellation" value="1" {{ ($basecamp['notify_cancellation'] ?? true) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500 shrink-0">
                            </label>

                            <!-- 4. WhatsApp -->
                            <label class="p-3.5 sm:p-4 rounded-xl bg-slate-50/70 border border-slate-200/90 flex items-center justify-between gap-3 cursor-pointer hover:border-emerald-300 transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 pr-2">
                                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center text-sm font-bold shrink-0">
                                        <svg class="w-4 h-4 text-teal-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 text-xs block truncate">Integrasi WhatsApp Wisatawan</span>
                                        <span class="text-[11px] text-slate-500 block line-clamp-1">Kirim tiket resmi & panduan titik kumpul via WA otomatis.</span>
                                    </div>
                                </div>
                                <input type="checkbox" name="notify_wa" value="1" {{ ($basecamp['notify_wa'] ?? true) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500 shrink-0">
                            </label>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Pengaturan Notifikasi</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </main>
    </div>

    <!-- SCRIPT TAB NAVIGATION & IMAGE PREVIEWS -->
    <script>
        // Tab Navigation Function
        function switchSettingsTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.settings-tab-content').forEach(el => el.classList.add('hidden'));

            // Show target tab
            const target = document.getElementById('tab-content-' + tabName);
            if (target) {
                target.classList.remove('hidden');
            }

            // Update Tab Buttons Style
            document.querySelectorAll('.settings-tab-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-800', 'text-white', 'shadow-xs', 'font-black');
                btn.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-slate-100', 'font-semibold');
            });

            const activeBtn = document.getElementById('tab-btn-' + tabName);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-slate-100', 'font-semibold');
                activeBtn.classList.add('bg-emerald-800', 'text-white', 'shadow-xs', 'font-black');
            }

            // Save to localStorage & URL hash
            localStorage.setItem('active_settings_tab', tabName);
            if (window.history.replaceState) {
                window.history.replaceState(null, null, '#' + tabName);
            }
        }

        // On Page Load: Restore Active Tab
        document.addEventListener('DOMContentLoaded', function() {
            let initialTab = 'akun';
            const hash = window.location.hash.replace('#', '');
            const validTabs = ['akun', 'usaha', 'beranda', 'operasional', 'pembayaran'];

            if (validTabs.includes(hash)) {
                initialTab = hash;
            } else {
                const savedTab = localStorage.getItem('active_settings_tab');
                if (savedTab && validTabs.includes(savedTab)) {
                    initialTab = savedTab;
                }
            }
            switchSettingsTab(initialTab);
        });

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
            if (fileInput && fileInput.files && fileInput.files.length > 0) return;

            const previewImg = document.getElementById('preview_hero_bg');
            if (previewImg) {
                if (url && url.trim().length > 5) {
                    previewImg.src = url.trim();
                } else {
                    previewImg.src = defaultHeroBgSrc;
                }
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

        // Galeri Beranda Helpers (1 - 5)
        const defaultGallerySrcs = {
            1: "{{ !empty($basecamp['gallery_image_1']) ? (str_starts_with($basecamp['gallery_image_1'], 'http') ? $basecamp['gallery_image_1'] : asset($basecamp['gallery_image_1'])) : asset('img/1.jpeg') }}",
            2: "{{ !empty($basecamp['gallery_image_2']) ? (str_starts_with($basecamp['gallery_image_2'], 'http') ? $basecamp['gallery_image_2'] : asset($basecamp['gallery_image_2'])) : asset('img/2.jpeg') }}",
            3: "{{ !empty($basecamp['gallery_image_3']) ? (str_starts_with($basecamp['gallery_image_3'], 'http') ? $basecamp['gallery_image_3'] : asset($basecamp['gallery_image_3'])) : asset('img/3.jpeg') }}",
            4: "{{ !empty($basecamp['gallery_image_4']) ? (str_starts_with($basecamp['gallery_image_4'], 'http') ? $basecamp['gallery_image_4'] : asset($basecamp['gallery_image_4'])) : asset('img/4.jpeg') }}",
            5: "{{ !empty($basecamp['gallery_image_5']) ? (str_starts_with($basecamp['gallery_image_5'], 'http') ? $basecamp['gallery_image_5'] : asset($basecamp['gallery_image_5'])) : asset('img/5.jpeg') }}",
        };

        function previewGalleryFile(input) {
            const index = input.getAttribute('data-gallery-index');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const previewImg = document.getElementById('preview_gallery_' + index);
                const btnCancel = document.getElementById('btn_cancel_gallery_' + index + '_file');
                const labelName = document.getElementById('label_gallery_' + index + '_filename');

                if (previewImg) previewImg.src = URL.createObjectURL(file);
                if (btnCancel) btnCancel.classList.remove('hidden');
                if (labelName) labelName.textContent = file.name;
            }
        }

        function toggleResetGallery(checkbox) {
            const index = checkbox.getAttribute('data-gallery-index');
            const previewImg = document.getElementById('preview_gallery_' + index);
            if (checkbox.checked) {
                if (previewImg) previewImg.src = "{{ asset('img') }}/" + index + ".jpeg";
            } else {
                if (previewImg) previewImg.src = defaultGallerySrcs[index];
            }
        }
    </script>
</x-admin-layout>
