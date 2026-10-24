@extends('layouts.app')

@section('title', 'Jeep Wukirsari Adventure Tour | Wisata Offroad Lereng Merapi')

@section('content')

    <!-- 1. HERO SECTION -->
    <section class="relative min-h-[580px] sm:min-h-[640px] lg:min-h-[720px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=2000&q=80" alt="Jeep Merapi Adventure" class="w-full h-full object-cover object-center filter brightness-[0.88]">
            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-wukirsari-dark/80 via-transparent to-black/30"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full flex flex-col justify-between min-h-[580px] sm:min-h-[640px] lg:min-h-[720px]">
            <div class="max-w-2xl mt-8 sm:mt-12 space-y-6">
                <!-- Pill Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs sm:text-sm font-accent italic">
                    <svg class="w-3.5 h-3.5 text-sunrise-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.286L13 21l-2.286-6.857L5 12l5.714-2.286L13 3z"/></svg>
                    <span>Petualangan di Lereng Merapi</span>
                </div>

                <!-- Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white font-display leading-[1.12] tracking-tight">
                    Jelajahi Keindahan Alam Merapi Bersama <span class="text-sunrise-gold">Jeep Wukirsari</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl font-normal">
                    Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.
                </p>

                <!-- Dual CTA Buttons -->
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm sm:text-base font-semibold shadow-lg shadow-black/30 hover:scale-[1.02] active:scale-95 transition-all">
                        <span>Booking Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    <a href="#paket" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-black/25 hover:bg-white/20 backdrop-blur-md border border-white/40 text-white text-sm sm:text-base font-medium transition-all">
                        <span>Jelajahi Paket</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Hero Bar -->
            <div class="pt-8 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between text-white/80 gap-4 border-t border-white/15">
                <div class="flex items-center gap-3 text-xs tracking-wider">
                    <span class="font-bold text-white border-b-2 border-sunrise-gold pb-0.5">01</span>
                    <span>02</span>
                    <span>03</span>
                    <span>04</span>
                    <span>05</span>
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/30 backdrop-blur-sm border border-white/20 text-xs text-white">
                    <svg class="w-3.5 h-3.5 text-sunrise-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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

                <a href="#paket" class="inline-flex items-center gap-1.5 text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors">
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
                                <span>{{ $pkg->duration_minutes }} menit</span>
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

    <!-- 6. TESTIMONI PELANGGAN -->
    <section class="py-16 sm:py-20 bg-gradient-to-b from-wukirsari-cream to-white border-t border-wukirsari-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">TESTIMONI</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">Apa Kata Mereka?</h2>
                <p class="text-merapiText-secondary text-sm sm:text-base mt-2">Pengalaman nyata dari pelanggan yang sudah merasakan keseruannya.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3.5">
                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80" alt="Andi" class="w-11 h-11 rounded-full object-cover border-2 border-wukirsari-border">
                            <div>
                                <h4 class="text-sm font-bold text-merapiText-primary font-display">Andi Pratama</h4>
                                <span class="text-xs text-merapiText-muted">Yogyakarta</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "Pengalaman yang luar biasa! Driver-nya ramah dan sangat berpengalaman. Pemandangannya keren banget!"
                        </p>
                    </div>
                    <div class="flex items-center gap-1 text-sunrise-gold pt-2">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3.5">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80" alt="Siti" class="w-11 h-11 rounded-full object-cover border-2 border-wukirsari-border">
                            <div>
                                <h4 class="text-sm font-bold text-merapiText-primary font-display">Siti Nurhaliza</h4>
                                <span class="text-xs text-merapiText-muted">Sleman</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "Sunrise di Merapi benar-benar magical. Timnya profesional dan semua sesuai dengan yang dijanjikan."
                        </p>
                    </div>
                    <div class="flex items-center gap-1 text-sunrise-gold pt-2">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3.5">
                            <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=150&q=80" alt="Rizky" class="w-11 h-11 rounded-full object-cover border-2 border-wukirsari-border">
                            <div>
                                <h4 class="text-sm font-bold text-merapiText-primary font-display">Rizky Maulana</h4>
                                <span class="text-xs text-merapiText-muted">Bandung</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "Booking-nya mudah, adminnya responsif, dan semuanya sesuai dengan yang dijanjikan."
                        </p>
                    </div>
                    <div class="flex items-center gap-1 text-sunrise-gold pt-2">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
