@extends('layouts.app')

@section('title', 'Jeep Wukirsari Adventure Tour | Wisata Offroad Lereng Merapi')

@section('content')

    <!-- 1. HERO SECTION -->
    <section class="relative min-h-[580px] sm:min-h-[640px] lg:min-h-[720px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/beranda.jpeg') }}" alt="Jeep Merapi Adventure" class="w-full h-full object-cover object-center filter brightness-[0.88]">
            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-wukirsari-dark/80 via-transparent to-black/30"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full flex flex-col justify-between min-h-[580px] sm:min-h-[640px] lg:min-h-[720px]">
            <div class="max-w-2xl mt-8 sm:mt-12 space-y-6">
                <!-- Premium Hero Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-black/45 backdrop-blur-md border border-white/25 shadow-xl shadow-black/30 hover:border-emerald-400/40 transition-all">
                    <span class="flex h-2 w-2 relative shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                    </span>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide text-white flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <span class="text-white font-extrabold uppercase tracking-wider text-[10px] sm:text-[11px] px-2.5 py-0.5 rounded-full bg-white/20 border border-white/30 shrink-0">
                            OFFICIAL BASECAMP
                        </span>
                        <span class="text-white/95 font-medium">Petualangan Offroad Lereng Merapi</span>
                    </div>
                </div>

                <!-- Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white font-display leading-[1.12] tracking-tight">
                    Jelajahi Keindahan Alam Merapi Bersama <span class="text-white font-black drop-shadow-[0_4px_16px_rgba(0,0,0,0.8)]">Jeep Wukirsari</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl font-normal">
                    Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.
                </p>

                <!-- Status Login Akun di Beranda -->
                @auth
                    @if(Auth::user()->isAdmin())
                    <div class="inline-flex flex-wrap items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-900/80 backdrop-blur-md border border-emerald-500/40 text-emerald-100 text-xs shadow-lg">
                        <span class="text-base">🛡️</span>
                        <span>Login sebagai: <strong class="text-white">{{ Auth::user()->name }}</strong> (Admin Loket & Armada)</span>
                        <a href="{{ route('admin.dashboard') }}" class="ml-1 font-bold text-emerald-300 hover:underline">Buka Dashboard Admin →</a>
                    </div>
                    @else
                    <div class="inline-flex flex-wrap items-center gap-2 px-4 py-2.5 rounded-2xl bg-black/40 backdrop-blur-md border border-white/30 text-white text-xs shadow-lg">
                        <span class="text-base">👋</span>
                        <span>Selamat datang, <strong>{{ Auth::user()->name }}</strong></span>
                        <span class="text-white/40">•</span>
                        <a href="{{ route('profile') }}" class="font-bold text-emerald-300 hover:underline">Lihat Profil & Tiket Saya →</a>
                    </div>
                    @endif
                @endauth

                <!-- CTA Button -->
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="#paket" class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm sm:text-base font-semibold shadow-lg shadow-black/30 hover:scale-[1.02] active:scale-95 transition-all">
                        <span>Jelajahi Paket Wisata</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Bottom Hero Bar -->
            <div class="pt-8 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between text-white/80 gap-4 border-t border-white/15">
                <div class="flex items-center gap-3 text-xs tracking-wider">
                    <span class="font-bold text-white border-b-2 border-emerald-400 pb-0.5">01</span>
                    <span>02</span>
                    <span>03</span>
                    <span>04</span>
                    <span>05</span>
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/30 backdrop-blur-sm border border-white/20 text-xs text-white">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Wukirsari, Sleman, Yogyakarta</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. VALUE BAR (4 FITUR KEUNGGULAN) -->
    <section class="bg-wukirsari-sage/60 py-6 border-b border-wukirsari-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white/70 backdrop-blur-sm border border-wukirsari-border/70 hover:bg-white transition-all shadow-xs">
                    <div class="w-11 h-11 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-merapiText-primary truncate">Jeep 4x4</h4>
                        <p class="text-[11px] sm:text-xs text-merapiText-secondary truncate">Siap di segala medan</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white/70 backdrop-blur-sm border border-wukirsari-border/70 hover:bg-white transition-all shadow-xs">
                    <div class="w-11 h-11 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-merapiText-primary truncate">Driver Berpengalaman</h4>
                        <p class="text-[11px] sm:text-xs text-merapiText-secondary truncate">Pemandu profesional</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white/70 backdrop-blur-sm border border-wukirsari-border/70 hover:bg-white transition-all shadow-xs">
                    <div class="w-11 h-11 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm3.5 6.5l-2 5.5-5.5 2 2-5.5 5.5-2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-merapiText-primary truncate">Fasilitas Lengkap</h4>
                        <p class="text-[11px] sm:text-xs text-merapiText-secondary truncate">Aman dan nyaman</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-white/70 backdrop-blur-sm border border-wukirsari-border/70 hover:bg-white transition-all shadow-xs">
                    <div class="w-11 h-11 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-merapiText-primary truncate">Lokasi Strategis</h4>
                        <p class="text-[11px] sm:text-xs text-merapiText-secondary truncate">Dekat Gunung Merapi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. PAKET WISATA POPULER (DARI DATABASE MYSQL) -->
    <section id="paket" class="py-16 sm:py-20 bg-wukirsari-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">PAKET WISATA</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">Paket Wisata Populer</h2>
                    <p class="text-merapiText-secondary text-sm sm:text-base mt-2 max-w-xl">Pilihan terbaik untuk pengalaman tak terlupakan di setiap perjalanan lereng Merapi.</p>
                </div>

                <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors">
                    <span>Lihat Semua</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- Loop Paket dari Model Package Database -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages as $pkg)
                <div class="bg-white rounded-3xl overflow-hidden border border-wukirsari-border shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="{{ $pkg->cover_image }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($pkg->category_badge)
                        <span class="absolute top-4 left-4 px-3.5 py-1 rounded-full bg-wukirsari-primary text-white text-xs font-semibold shadow-md">
                            {{ $pkg->category_badge }}
                        </span>
                        @endif
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-merapiText-primary font-display group-hover:text-wukirsari-primary transition-colors">
                                {{ $pkg->name }}
                            </h3>
                            <p class="text-merapiText-secondary text-xs sm:text-sm mt-2 line-clamp-2 leading-relaxed">
                                {{ $pkg->description }}
                            </p>
                        </div>

                        <div class="pt-2 flex items-center gap-5 text-xs text-merapiText-secondary border-t border-wukirsari-border/70">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $pkg->effective_duration_text }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Max 4 orang</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-wukirsari-border space-y-4">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl sm:text-2xl font-extrabold text-merapiText-primary font-display">
                                    Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-merapiText-secondary font-medium">/ Jeep</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('packages.show', $pkg->slug) }}" class="py-2.5 px-3 rounded-full border border-wukirsari-border text-merapiText-primary hover:border-wukirsari-primary hover:text-wukirsari-primary text-xs font-semibold text-center transition-all">
                                    Lihat Detail →
                                </a>
                                <a href="{{ route('booking.create', ['package' => $pkg->slug]) }}" class="py-2.5 px-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold text-center transition-all shadow-sm shadow-wukirsari-primary/20">
                                    Booking Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. MENGAPA MEMILIH JEEP WUKIRSARI -->
    <section id="tentang" class="py-16 sm:py-20 bg-gradient-to-b from-wukirsari-cream to-wukirsari-sage/40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Kotak Hijau Sisi Kiri -->
                <div class="lg:col-span-5 bg-wukirsari-dark text-white p-8 sm:p-10 rounded-3xl shadow-xl space-y-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-wukirsari-primary/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div>
                        <span class="text-sm font-accent italic text-wukirsari-sage block mb-1">Mengapa Memilih</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-display">Jeep Wukirsari?</h2>
                    </div>
                    <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                        Kami berkomitmen memberikan pengalaman terbaik untuk setiap perjalanan Anda. Dengan pelayanan profesional, fasilitas lengkap dan rute yang aman, liburan Anda akan menjadi lebih berkesan.
                    </p>
                    <div class="pt-2">
                        <a href="#kontak" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-wukirsari-dark text-sm font-semibold hover:bg-wukirsari-sage transition-all shadow-md active:scale-95">
                            <span>Tentang Kami</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- 4 Kartu Fitur Sisi Kanan -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl border border-wukirsari-border shadow-xs hover:shadow-md hover:bg-white transition-all space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-merapiText-primary font-display">Harga Transparan</h4>
                            <p class="text-xs sm:text-sm text-merapiText-secondary mt-1">Tanpa biaya tersembunyi, sudah termasuk asuransi dan BBM.</p>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl border border-wukirsari-border shadow-xs hover:shadow-md hover:bg-white transition-all space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-merapiText-primary font-display">Pelayanan Ramah</h4>
                            <p class="text-xs sm:text-sm text-merapiText-secondary mt-1">Driver paguyuban lokal yang siap membantu dokumentasi & 24 jam siaga.</p>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl border border-wukirsari-border shadow-xs hover:shadow-md hover:bg-white transition-all space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm3.5 6.5l-2 5.5-5.5 2 2-5.5 5.5-2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-merapiText-primary font-display">Jalur Aman</h4>
                            <p class="text-xs sm:text-sm text-merapiText-secondary mt-1">Rute offroad dipilih dan dimonitor sesuai kondisi status aktivitas Gunung Merapi.</p>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl border border-wukirsari-border shadow-xs hover:shadow-md hover:bg-white transition-all space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-merapiText-primary font-display">Pengalaman Terbaik</h4>
                            <p class="text-xs sm:text-sm text-merapiText-secondary mt-1">Ribuan review positif dari wisatawan domestik maupun mancanegara.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. GALERI MOMEN INDAH -->
    <section id="galeri" class="py-16 sm:py-20 bg-wukirsari-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">GALERI</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">Jelajahi Momen Indah Bersama Kami</h2>
                    <p class="text-merapiText-secondary text-sm sm:text-base mt-2 max-w-xl">Kumpulan foto perjalanan seru wisatawan di berbagai rute eksotis lereng Gunung Merapi.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[4/3] col-span-2 md:col-span-1">
                    <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=600&q=80" alt="Trek Pasir" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80" alt="Sunrise" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=600&q=80" alt="Bunker Kaliadem" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80" alt="Kalikuning" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=600&q=80" alt="Lembah Merapi" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- 6. TESTIMONI & REVIEW WISATAWAN -->
    <section class="py-16 sm:py-20 bg-gradient-to-b from-wukirsari-cream to-white border-t border-wukirsari-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">TESTIMONI & ULASAN ASLI</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">Apa Kata Wisatawan Lain?</h2>
                    <p class="text-merapiText-secondary text-sm sm:text-base mt-2">
                        Simak pengalaman seru dan penilaian nyata dari wisatawan yang sudah menikmati offroad lereng Gunung Merapi.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-sm shadow-wukirsari-primary/20">
                        <span>⭐ Lihat Semua Ulasan ({{ $totalReviewsCount }} Review) →</span>
                    </a>

                    @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-wukirsari-primary text-wukirsari-primary hover:bg-wukirsari-sage text-xs font-bold transition-all">
                        <span>🔒 Masuk untuk Tulis Ulasan</span>
                    </a>
                    @endguest
                </div>
            </div>

            <!-- Grid Kartu Ulasan Wisatawan Lain -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @forelse($reviews as $rev)
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-sm shrink-0 border border-wukirsari-border font-display">
                                    {{ strtoupper(substr($rev->user->name ?? 'W', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-merapiText-primary font-display truncate">
                                        {{ $rev->user->name ?? 'Wisatawan' }}
                                    </h4>
                                    <span class="text-xs text-merapiText-muted truncate block">
                                        📍 {{ $rev->city ?? 'Indonesia' }} • {{ $rev->package->name ?? 'Tour Jeep Merapi' }}
                                    </span>
                                </div>
                            </div>

                            @if(Auth::check() && (Auth::id() === $rev->user_id || Auth::user()->isAdmin()))
                            <div class="flex items-center gap-1.5 shrink-0">
                                <!-- Tombol Edit -->
                                <button type="button" 
                                    onclick="handleEditReviewClick(this)"
                                    data-id="{{ $rev->id }}"
                                    data-rating="{{ $rev->rating }}"
                                    data-city="{{ $rev->city }}"
                                    data-comment="{{ $rev->comment }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-wukirsari-primary text-[11px] font-bold border border-wukirsari-border transition-all cursor-pointer shadow-xs" 
                                    title="Edit Ulasan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span>Edit</span>
                                </button>

                                <!-- Tombol Delete -->
                                <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold border border-rose-200 transition-all cursor-pointer shadow-xs" 
                                        title="Hapus Ulasan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "{{ $rev->comment }}"
                        </p>
                    </div>

                    <div class="pt-3 border-t border-wukirsari-border/70 flex items-center justify-between">
                        <div class="flex items-center gap-1 text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rev->rating)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else
                                <svg class="w-4 h-4 fill-slate-200" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-[10px] text-merapiText-muted">
                            {{ $rev->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-merapiText-muted text-sm bg-white rounded-3xl border border-wukirsari-border">
                    Belum ada ulasan yang dipublikasikan. Jadilah wisatawan pertama yang memberikan ulasan!
                </div>
                @endforelse
            </div>

            <!-- Tombol Navigasi Lihat Semua Review Orang Lain -->
            <div class="text-center mb-12">
                <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white border border-wukirsari-border hover:border-wukirsari-primary text-merapiText-primary hover:text-wukirsari-primary text-xs sm:text-sm font-semibold shadow-xs hover:shadow-md transition-all">
                    <span>Lihat Halaman Lengkap Semua Review Wisatawan</span>
                    <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <!-- FORM TULIS ULASAN (HANYA UNTUK USER YANG SUDAH LOGIN) -->
            <div id="tulis-ulasan" class="max-w-2xl mx-auto">
                @auth
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-wukirsari-border shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-b border-wukirsari-border pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-lg">
                            ⭐
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">
                                Berikan Ulasan Pengalaman Anda
                            </h3>
                            <p class="text-xs text-merapiText-secondary">
                                Masuk sebagai: <strong class="text-wukirsari-primary">{{ Auth::user()->name }}</strong> (Wisatawan)
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Rating Bintang Interaktif -->
                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Penilaian Anda (Bintang) *</label>
                            <div class="flex items-center gap-2" id="starRatingContainer">
                                @for($s = 1; $s <= 5; $s++)
                                <button type="button" class="star-btn text-2xl text-yellow-400 hover:scale-125 transition-transform cursor-pointer" data-val="{{ $s }}" title="{{ $s }} Bintang">
                                    ★
                                </button>
                                @endfor
                                <input type="hidden" name="rating" id="ratingInput" value="5">
                                <span class="text-xs font-bold text-merapiText-secondary ml-2" id="ratingLabel">5 dari 5 Bintang (Sangat Puas)</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Paket yang Dinikmati</label>
                                <select name="package_id" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border bg-white text-xs focus:outline-none focus:border-wukirsari-primary">
                                    <option value="">Pilih Paket Wisata (Opsional)</option>
                                    @foreach($packages as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Kota / Daerah Asal</label>
                                <input type="text" name="city" placeholder="Contoh: Jakarta / Surabaya / Sleman" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Ulasan / Cerita Pengalaman Anda *</label>
                            <textarea name="comment" rows="3" required placeholder="Ceritakan bagaimana keseruan rute, keramahan driver, atau keindahan pemandangan lereng Merapi..." class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all flex items-center justify-center gap-2">
                            <span>Kirim Ulasan Saya Sekarang →</span>
                        </button>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const starBtns = document.querySelectorAll('.star-btn');
                        const ratingInput = document.getElementById('ratingInput');
                        const ratingLabel = document.getElementById('ratingLabel');
                        const labels = {
                            1: '1 dari 5 Bintang (Kurang)',
                            2: '2 dari 5 Bintang (Cukup)',
                            3: '3 dari 5 Bintang (Baik)',
                            4: '4 dari 5 Bintang (Sangat Baik)',
                            5: '5 dari 5 Bintang (Sangat Puas)'
                        };

                        starBtns.forEach(btn => {
                            btn.addEventListener('click', function () {
                                const val = parseInt(this.dataset.val);
                                ratingInput.value = val;
                                ratingLabel.innerText = labels[val] || (val + ' Bintang');

                                starBtns.forEach(s => {
                                    const sVal = parseInt(s.dataset.val);
                                    if (sVal <= val) {
                                        s.classList.remove('text-slate-200');
                                        s.classList.add('text-yellow-400');
                                    } else {
                                        s.classList.remove('text-yellow-400');
                                        s.classList.add('text-slate-200');
                                    }
                                });
                            });
                        });
                    });
                </script>
                @else
                <!-- Banner Wajib Login untuk Menulis Ulasan -->
                <div class="bg-gradient-to-r from-wukirsari-dark via-[#142B16] to-wukirsari-primary text-white p-6 sm:p-8 rounded-3xl shadow-lg border border-emerald-800/30 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center sm:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-semibold">
                            <span>🔒</span>
                            <span>Khusus Wisatawan Terverifikasi</span>
                        </div>
                        <h3 class="text-lg font-bold font-display">Ingin Memberikan Ulasan Pengalaman Tour?</h3>
                        <p class="text-xs text-white/80 max-w-md">
                            Untuk menjaga keaslian ulasan, Anda harus masuk ke akun wisatawan terlebih dahulu sebelum mengirimkan testimoni.
                        </p>
                    </div>
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-950/30 transition-all whitespace-nowrap active:scale-95">
                        Masuk untuk Tulis Ulasan →
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </section>

@endsection
