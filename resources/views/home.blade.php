@extends('layouts.app')

@section('title', 'Jeep Wukirsari Adventure Tour | Wisata Offroad Lereng Merapi')

@section('content')

    <!-- 1. HERO SECTION -->
    <section class="relative min-h-[580px] sm:min-h-[640px] lg:min-h-[700px] lg:max-h-[800px] flex items-center justify-center overflow-hidden">
        <!-- Hero Slider Background Images -->
        <div id="heroSliderContainer" class="absolute inset-0 z-0 overflow-hidden">
            @php
                $bg0 = $homeSettings['hero_background'] ?? null;
                $src0 = !empty($bg0) ? (str_starts_with($bg0, 'http') ? $bg0 : asset($bg0)) : asset('img/beranda.jpeg');

                $bg1 = $homeSettings['hero_slide_1'] ?? null;
                $src1 = !empty($bg1) ? (str_starts_with($bg1, 'http') ? $bg1 : asset($bg1)) : asset('img/sunrise_trip.jpeg');

                $bg2 = $homeSettings['hero_slide_2'] ?? null;
                $src2 = !empty($bg2) ? (str_starts_with($bg2, 'http') ? $bg2 : asset($bg2)) : asset('img/long_trip.jpeg');

                $bg3 = $homeSettings['hero_slide_3'] ?? null;
                $src3 = !empty($bg3) ? (str_starts_with($bg3, 'http') ? $bg3 : asset($bg3)) : asset('img/medium_trip_a.jpeg');

                $bg4 = $homeSettings['hero_slide_4'] ?? null;
                $src4 = !empty($bg4) ? (str_starts_with($bg4, 'http') ? $bg4 : asset($bg4)) : asset('img/manuver_air.png');
            @endphp
            <img id="heroSlide-0" src="{{ $src0 }}" alt="Jeep Merapi Adventure - Basecamp" class="hero-slide absolute inset-0 w-full h-full object-cover object-center filter brightness-[0.92] transition-opacity duration-700 ease-in-out opacity-100 z-10">
            <img id="heroSlide-1" src="{{ $src1 }}" alt="Sunrise Trip Merapi" class="hero-slide absolute inset-0 w-full h-full object-cover object-center filter brightness-[0.92] transition-opacity duration-700 ease-in-out opacity-0 z-0">
            <img id="heroSlide-2" src="{{ $src2 }}" alt="Long Trip Merapi Offroad" class="hero-slide absolute inset-0 w-full h-full object-cover object-center filter brightness-[0.92] transition-opacity duration-700 ease-in-out opacity-0 z-0">
            <img id="heroSlide-3" src="{{ $src3 }}" alt="Medium Trip Merapi" class="hero-slide absolute inset-0 w-full h-full object-cover object-center filter brightness-[0.92] transition-opacity duration-700 ease-in-out opacity-0 z-0">
            <img id="heroSlide-4" src="{{ $src4 }}" alt="Short Trip Merapi" class="hero-slide absolute inset-0 w-full h-full object-cover object-center filter brightness-[0.92] transition-opacity duration-700 ease-in-out opacity-0 z-0">
            
            <!-- Cinematic Layered Gradients Overlay for Razor-Sharp Text Contrast & Rich Photo Depth -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/92 via-black/60 via-40% to-transparent z-20 pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent z-20 pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-transparent to-transparent z-20 pointer-events-none"></div>
        </div>

        <!-- MAIN HERO CONTENT CONTAINER (Perfect Vertical Centering & No Cutoff) -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 lg:pt-14 pb-6 sm:pb-8 w-full flex flex-col justify-between min-h-[580px] sm:min-h-[640px] lg:min-h-[700px] lg:max-h-[800px]">
            
            <!-- Headline & CTA Area -->
            <div class="max-w-2xl mt-2 sm:mt-6 space-y-4 sm:space-y-6">
                
                <!-- Tagline Badge: Official Adventure Status + Script Quote -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/40 backdrop-blur-md border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Merapi Adventure Tour</span>
                    </span>

                    <span class="font-script text-2xl sm:text-3xl lg:text-4xl text-emerald-100 tracking-wide drop-shadow-md">
                        "Petualangan di Lereng Merapi"
                    </span>
                </div>

                <!-- Main Headline: 3 Clean Lines with Glowing Emerald Nature Gradient -->
                <h1 class="text-3xl sm:text-5xl lg:text-[54px] font-extrabold text-white font-display leading-[1.12] tracking-tight drop-shadow-[0_4px_18px_rgba(0,0,0,0.8)]">
                    Jelajahi Keindahan<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-200 to-white">Alam Merapi</span> Bersama<br>
                    <span>Jeep Wukirsari</span>
                </h1>

                <!-- Subtitle Paragraph with Enhanced Contrast & Readability -->
                <p class="text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl font-normal drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)]">
                    {{ $homeSettings['hero_subtitle'] ?? 'Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.' }}
                </p>

                <!-- Status Login Akun di Beranda (Tetap Terjaga) -->
                @auth
                    @if(Auth::user()->isAdmin())
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/40 backdrop-blur-md border border-emerald-400/40 text-emerald-200 text-xs shadow-lg">
                        <span>Admin: <strong class="text-white">{{ Auth::user()->name }}</strong></span>
                        <a href="{{ route('admin.dashboard') }}" class="ml-1 font-bold text-emerald-300 hover:underline">Dashboard →</a>
                    </div>
                    @else
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/40 backdrop-blur-md border border-white/30 text-white text-xs shadow-lg">
                        <span>Halo, <strong>{{ Auth::user()->name }}</strong></span>
                        <span class="text-white/40">•</span>
                        <a href="{{ route('profile') }}" class="font-bold text-emerald-300 hover:underline">Tiket Saya →</a>
                    </div>
                    @endif
                @endauth

                <!-- 2 Modern Action Buttons Side-by-Side -->
                <div class="pt-2 sm:pt-4 flex flex-wrap items-center gap-3 sm:gap-4">
                    <a href="{{ route('booking.create') }}" class="group inline-flex items-center gap-2.5 px-7 sm:px-8 py-3.5 sm:py-4 rounded-full bg-gradient-to-r from-emerald-600 via-emerald-700 to-[#194821] hover:from-emerald-500 hover:to-[#225d2b] text-white text-xs sm:text-sm font-bold border border-emerald-400/40 shadow-xl shadow-black/40 hover:shadow-emerald-600/30 hover:scale-[1.03] active:scale-95 transition-all">
                        <span>Booking Sekarang</span>
                        <svg class="w-4 h-4 text-emerald-200 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <a href="#paket" class="inline-flex items-center gap-2 px-6 sm:px-7 py-3.5 sm:py-4 rounded-full bg-black/35 hover:bg-white/20 backdrop-blur-md border border-white/30 hover:border-white/60 text-white text-xs sm:text-sm font-semibold shadow-lg transition-all hover:scale-[1.02] active:scale-95">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <span>Jelajahi Paket</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Floating Slider Dock (Clean, Elegant & Always in Viewport) -->
            <div class="pt-6 sm:pt-8 pb-1 flex flex-row items-center justify-between text-white/90 gap-3 border-t border-white/20">
                <div class="flex items-center gap-3 sm:gap-4 text-xs tracking-wider select-none z-10" id="heroSlideButtons">
                    <!-- Slide Numbers Pill Dock -->
                    <div class="relative flex items-center gap-3 sm:gap-5 px-3.5 py-1.5 rounded-full bg-black/35 backdrop-blur-md border border-white/20 shadow-md">
                        <button type="button" onclick="goToHeroSlide(0)" class="hero-indicator font-bold text-white border-b-2 border-emerald-400 pb-0.5 text-xs sm:text-sm transition-all cursor-pointer">01</button>
                        <button type="button" onclick="goToHeroSlide(1)" class="hero-indicator text-white/50 hover:text-white border-b-2 border-transparent pb-0.5 text-xs sm:text-sm transition-all cursor-pointer">02</button>
                        <button type="button" onclick="goToHeroSlide(2)" class="hero-indicator text-white/50 hover:text-white border-b-2 border-transparent pb-0.5 text-xs sm:text-sm transition-all cursor-pointer">03</button>
                        <button type="button" onclick="goToHeroSlide(3)" class="hero-indicator text-white/50 hover:text-white border-b-2 border-transparent pb-0.5 text-xs sm:text-sm transition-all cursor-pointer">04</button>
                        <button type="button" onclick="goToHeroSlide(4)" class="hero-indicator text-white/50 hover:text-white border-b-2 border-transparent pb-0.5 text-xs sm:text-sm transition-all cursor-pointer">05</button>
                    </div>
                    
                    <!-- Chevron Arrows Navigation -->
                    <div class="flex items-center gap-1.5 text-sm text-white/80">
                        <button type="button" onclick="prevHeroSlide()" aria-label="Slide Sebelumnya" class="w-8 h-8 rounded-full bg-black/35 hover:bg-emerald-600 hover:text-white border border-white/20 flex items-center justify-center transition-all cursor-pointer shadow-sm active:scale-90">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" onclick="nextHeroSlide()" aria-label="Slide Selanjutnya" class="w-8 h-8 rounded-full bg-black/35 hover:bg-emerald-600 hover:text-white border border-white/20 flex items-center justify-center transition-all cursor-pointer shadow-sm active:scale-90">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Destination & Location Beacon Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full bg-black/40 backdrop-blur-md border border-white/20 text-[11px] sm:text-xs text-white max-w-[240px] sm:max-w-none truncate shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span id="heroSlideLocationText" class="truncate font-semibold text-emerald-200">{{ $homeSettings['hero_location_0'] ?? 'Susur Kali Gendol' }}</span>
                    <span class="text-white/40 hidden sm:inline">•</span>
                    <span class="text-white/80 hidden sm:inline font-normal">Wukirsari, Sleman, Yogyakarta</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Script Hero Slider dengan Data Lokasi Dinamis -->
    <script>
        window.customHeroLocations = [
            @json($homeSettings['hero_location_0'] ?? 'Susur Kali Gendol'),
            @json($homeSettings['hero_location_1'] ?? 'Spot Sunrise'),
            @json($homeSettings['hero_location_2'] ?? 'Jalur Offroad'),
            @json($homeSettings['hero_location_3'] ?? 'Museum Kampung Hilang'),
            @json($homeSettings['hero_location_4'] ?? 'Manuver Air')
        ];
    </script>
    <script src="{{ asset('js/hero-slider.js') }}"></script>

    <!-- 2. VALUE BAR (4 FITUR KEUNGGULAN) -->
    <section class="bg-[#F8F9F5] py-8 sm:py-10 border-b border-[#E3EBE4] relative overflow-hidden select-none">
        
        <!-- Botanical Mountain Fern Illustration - Left Edge (Bentuk Khas Pakis Merapi Rimbun, Rapi & Elegan) -->
        <div class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 lg:translate-x-0 pointer-events-none opacity-55 lg:opacity-65 z-0">
            <svg class="w-32 sm:w-40 lg:w-48 h-auto text-[#3D6B49]" viewBox="-5 0 175 125" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="vbFernFillLeft" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#4E785B" stop-opacity="0.14" />
                        <stop offset="100%" stop-color="#2D5237" stop-opacity="0.04" />
                    </linearGradient>
                    <linearGradient id="vbFernStemLeft" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#2D5237" stop-opacity="0.85" />
                        <stop offset="100%" stop-color="#4E785B" stop-opacity="0.5" />
                    </linearGradient>
                </defs>

                <!-- MAIN FERN RACHIS (Smooth Arching Curve) -->
                <path d="M -5,108 C 28,105 65,86 102,56 C 126,36 146,20 162,7" stroke="url(#vbFernStemLeft)" stroke-width="1.8" stroke-linecap="round"/>

                <!-- Leaflet Pair 1 (Base - Broad & Gentle) -->
                <path d="M 26,98 C 14,86 8,74 15,64 C 25,66 31,82 34,92 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 26,98 Q 18,82 15,64" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <path d="M 36,92 C 48,80 60,76 65,84 C 59,93 47,95 40,94 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 36,92 Q 52,87 65,84" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <!-- Leaflet Pair 2 -->
                <path d="M 46,80 C 32,67 28,53 36,46 C 44,50 49,64 52,75 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 46,80 Q 39,64 36,46" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <path d="M 56,74 C 70,61 82,58 86,66 C 78,74 68,76 60,75 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 56,74 Q 72,69 86,66" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <!-- Leaflet Pair 3 -->
                <path d="M 68,61 C 56,48 54,35 62,30 C 69,34 71,47 74,57 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 68,61 Q 62,46 62,30" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <path d="M 76,56 C 90,44 100,41 104,49 C 96,56 86,57 79,57 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 76,56 Q 91,52 104,49" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <!-- Leaflet Pair 4 -->
                <path d="M 88,45 C 78,34 78,23 85,18 C 91,23 92,32 93,41 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
                <path d="M 88,45 Q 84,32 85,18" stroke="currentColor" stroke-width="0.6" opacity="0.6"/>

                <path d="M 96,40 C 108,30 117,28 120,35 C 113,41 105,42 98,41 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
                <path d="M 96,40 Q 109,37 120,35" stroke="currentColor" stroke-width="0.6" opacity="0.6"/>

                <!-- Leaflet Pair 5 (Delicate & Tapered) -->
                <path d="M 108,31 C 100,21 102,13 108,9 C 113,14 114,21 114,28 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                <path d="M 108,31 Q 107,20 108,9" stroke="currentColor" stroke-width="0.5" opacity="0.6"/>

                <path d="M 116,27 C 126,17 133,16 135,22 C 129,27 122,28 117,27 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                <path d="M 116,27 Q 126,24 135,22" stroke="currentColor" stroke-width="0.5" opacity="0.6"/>

                <!-- Terminal Pinna (Tip) -->
                <path d="M 130,18 C 141,9 148,7 152,9 C 148,15 142,20 135,20 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                <path d="M 130,18 Q 142,14 152,9" stroke="currentColor" stroke-width="0.5" opacity="0.6"/>

                <!-- SECONDARY COMPANION FROND (Menambah Rimbun & Rame) -->
                <path d="M -5,124 C 20,123 45,118 68,104 C 84,94 98,82 110,70" stroke="url(#vbFernStemLeft)" stroke-width="1.4" stroke-linecap="round" opacity="0.85"/>
                <path d="M 16,118 C 8,110 6,100 12,94 C 20,96 24,106 24,115 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1"/>
                <path d="M 25,114 C 34,106 42,104 46,110 C 42,116 34,118 28,116 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1"/>
                <path d="M 36,108 C 28,98 26,88 32,83 C 38,86 42,96 42,105 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1"/>
                <path d="M 45,102 C 55,94 64,92 68,98 C 63,104 54,106 48,104 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1"/>
                <path d="M 58,94 C 50,84 48,74 54,70 C 60,73 64,82 64,91 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1"/>
                <path d="M 68,88 C 78,80 86,78 90,84 C 85,90 76,92 70,90 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="1"/>
                <path d="M 82,78 C 76,70 76,62 81,58 C 86,61 89,68 88,76 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="0.95"/>
                <path d="M 90,74 C 98,68 104,66 107,71 C 103,76 96,77 92,76 Z" fill="url(#vbFernFillLeft)" stroke="currentColor" stroke-width="0.95"/>
            </svg>
        </div>

        <!-- Botanical Mountain Fern Illustration - Right Edge (Mirrored, Rimbun & Elegan) -->
        <div class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 lg:translate-x-0 pointer-events-none opacity-55 lg:opacity-65 z-0 transform scale-x-[-1]">
            <svg class="w-32 sm:w-40 lg:w-48 h-auto text-[#3D6B49]" viewBox="-5 0 175 125" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="vbFernFillRight" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#4E785B" stop-opacity="0.14" />
                        <stop offset="100%" stop-color="#2D5237" stop-opacity="0.04" />
                    </linearGradient>
                    <linearGradient id="vbFernStemRight" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#2D5237" stop-opacity="0.85" />
                        <stop offset="100%" stop-color="#4E785B" stop-opacity="0.5" />
                    </linearGradient>
                </defs>

                <!-- MAIN FERN RACHIS (Smooth Arching Curve) -->
                <path d="M -5,108 C 28,105 65,86 102,56 C 126,36 146,20 162,7" stroke="url(#vbFernStemRight)" stroke-width="1.8" stroke-linecap="round"/>

                <!-- Leaflet Pair 1 (Base - Broad & Gentle) -->
                <path d="M 26,98 C 14,86 8,74 15,64 C 25,66 31,82 34,92 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 26,98 Q 18,82 15,64" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <path d="M 36,92 C 48,80 60,76 65,84 C 59,93 47,95 40,94 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 36,92 Q 52,87 65,84" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <!-- Leaflet Pair 2 -->
                <path d="M 46,80 C 32,67 28,53 36,46 C 44,50 49,64 52,75 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 46,80 Q 39,64 36,46" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <path d="M 56,74 C 70,61 82,58 86,66 C 78,74 68,76 60,75 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 56,74 Q 72,69 86,66" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <!-- Leaflet Pair 3 -->
                <path d="M 68,61 C 56,48 54,35 62,30 C 69,34 71,47 74,57 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 68,61 Q 62,46 62,30" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <path d="M 76,56 C 90,44 100,41 104,49 C 96,56 86,57 79,57 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M 76,56 Q 91,52 104,49" stroke="currentColor" stroke-width="0.7" opacity="0.65"/>

                <!-- Leaflet Pair 4 -->
                <path d="M 88,45 C 78,34 78,23 85,18 C 91,23 92,32 93,41 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
                <path d="M 88,45 Q 84,32 85,18" stroke="currentColor" stroke-width="0.6" opacity="0.6"/>

                <path d="M 96,40 C 108,30 117,28 120,35 C 113,41 105,42 98,41 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
                <path d="M 96,40 Q 109,37 120,35" stroke="currentColor" stroke-width="0.6" opacity="0.6"/>

                <!-- Leaflet Pair 5 (Delicate & Tapered) -->
                <path d="M 108,31 C 100,21 102,13 108,9 C 113,14 114,21 114,28 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                <path d="M 108,31 Q 107,20 108,9" stroke="currentColor" stroke-width="0.5" opacity="0.6"/>

                <path d="M 116,27 C 126,17 133,16 135,22 C 129,27 122,28 117,27 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                <path d="M 116,27 Q 126,24 135,22" stroke="currentColor" stroke-width="0.5" opacity="0.6"/>

                <!-- Terminal Pinna (Tip) -->
                <path d="M 130,18 C 141,9 148,7 152,9 C 148,15 142,20 135,20 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
                <path d="M 130,18 Q 142,14 152,9" stroke="currentColor" stroke-width="0.5" opacity="0.6"/>

                <!-- SECONDARY COMPANION FROND (Menambah Rimbun & Rame) -->
                <path d="M -5,124 C 20,123 45,118 68,104 C 84,94 98,82 110,70" stroke="url(#vbFernStemRight)" stroke-width="1.4" stroke-linecap="round" opacity="0.85"/>
                <path d="M 16,118 C 8,110 6,100 12,94 C 20,96 24,106 24,115 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1"/>
                <path d="M 25,114 C 34,106 42,104 46,110 C 42,116 34,118 28,116 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1"/>
                <path d="M 36,108 C 28,98 26,88 32,83 C 38,86 42,96 42,105 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1"/>
                <path d="M 45,102 C 55,94 64,92 68,98 C 63,104 54,106 48,104 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1"/>
                <path d="M 58,94 C 50,84 48,74 54,70 C 60,73 64,82 64,91 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1"/>
                <path d="M 68,88 C 78,80 86,78 90,84 C 85,90 76,92 70,90 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="1"/>
                <path d="M 82,78 C 76,70 76,62 81,58 C 86,61 89,68 88,76 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="0.95"/>
                <path d="M 90,74 C 98,68 104,66 107,71 C 103,76 96,77 92,76 Z" fill="url(#vbFernFillRight)" stroke="currentColor" stroke-width="0.95"/>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-6 lg:gap-0 lg:divide-x divide-[#DCE6DE] items-center">
                
                <!-- Fitur 1: Jeep 4x4 -->
                <div class="flex items-center gap-4 lg:px-5 xl:px-6 first:lg:pl-0">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-[22px] bg-[#E1EDE4] flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#1E3F20]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 10 L6.8 4.5 C7 4 7.5 3.5 8.2 3.5 L15.8 3.5 C16.5 3.5 17 4 17.2 4.5 L19 10" />
                            <path d="M12 4 L12 10" />
                            <rect x="3" y="10" width="18" height="7" rx="2" />
                            <circle cx="6.5" cy="13.5" r="1.5" fill="currentColor" />
                            <circle cx="17.5" cy="13.5" r="1.5" fill="currentColor" />
                            <line x1="10" y1="12.5" x2="10" y2="14.5" />
                            <line x1="12" y1="12.5" x2="12" y2="14.5" />
                            <line x1="14" y1="12.5" x2="14" y2="14.5" />
                            <line x1="6" y1="17" x2="18" y2="17" stroke-width="2.2" />
                            <path d="M4 17 L4 20.5 C4 21 4.5 21.5 5 21.5 L6.5 21.5 C7 21.5 7.5 21 7.5 20.5 L7.5 17" />
                            <path d="M16.5 17 L16.5 20.5 C16.5 21 17 21.5 17.5 21.5 L19 21.5 C19.5 21.5 20 21 20 20.5 L20 17" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm sm:text-base font-extrabold text-[#142B16] font-display truncate leading-tight">Jeep 4x4</h4>
                        <p class="text-xs text-[#5E7264] truncate mt-1">Siap di segala medan</p>
                    </div>
                </div>

                <!-- Fitur 2: Driver Berpengalaman -->
                <div class="flex items-center gap-4 lg:px-5 xl:px-6">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-[22px] bg-[#E1EDE4] flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#1E3F20]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            <circle cx="12" cy="11" r="2.2" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm sm:text-base font-extrabold text-[#142B16] font-display truncate leading-tight">Driver Berpengalaman</h4>
                        <p class="text-xs text-[#5E7264] truncate mt-1">Pemandu profesional</p>
                    </div>
                </div>

                <!-- Fitur 3: Fasilitas Lengkap -->
                <div class="flex items-center gap-4 lg:px-5 xl:px-6">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-[22px] bg-[#E1EDE4] flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#1E3F20]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            <path d="M12 8.5v5M9.5 11h5" stroke-width="2.5" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm sm:text-base font-extrabold text-[#142B16] font-display truncate leading-tight">Fasilitas Lengkap</h4>
                        <p class="text-xs text-[#5E7264] truncate mt-1">Aman dan nyaman</p>
                    </div>
                </div>

                <!-- Fitur 4: Lokasi Strategis -->
                <div class="flex items-center gap-4 lg:px-5 xl:px-6 last:lg:pr-0">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-[22px] bg-[#E1EDE4] flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#1E3F20]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm sm:text-base font-extrabold text-[#142B16] font-display truncate leading-tight">Lokasi Strategis</h4>
                        <p class="text-xs text-[#5E7264] truncate mt-1">Dekat Gunung Merapi</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. PAKET WISATA POPULER (DARI DATABASE MYSQL) -->
    <section id="paket" class="py-12 sm:py-16 lg:py-20 bg-[#F8F9F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-12 gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-[#7A8C80] font-display block mb-1.5">PAKET WISATA</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#142B16] font-display leading-tight">Paket Wisata Populer</h2>
                    <p class="text-[#5E7264] text-xs sm:text-base mt-1.5 max-w-xl">Pilihan terbaik untuk pengalaman tak terlupakan di setiap perjalanan.</p>
                </div>
                <a href="{{ route('home') }}#paket" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-[#142B16] hover:text-[#265029] transition-colors self-start md:self-end">
                    <span>Lihat Semua</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Loop Paket dari Model Package Database -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($packages as $pkg)
                <div class="bg-white rounded-3xl overflow-hidden border border-wukirsari-border shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="{{ $pkg->cover_image }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($loop->first || $pkg->category_badge)
                        <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full bg-[#1E3F20] text-white text-[11px] font-semibold shadow-md">
                            {{ $pkg->category_badge ?? 'Paling Populer' }}
                        </span>
                        @endif
                    </div>

                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-merapiText-primary font-display group-hover:text-wukirsari-primary transition-colors">
                                {{ $pkg->name }}
                            </h3>
                            <p class="text-merapiText-secondary text-xs sm:text-sm mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $pkg->description }}
                            </p>
                        </div>

                        <div class="pt-3 flex items-center gap-4 text-xs text-merapiText-secondary border-t border-wukirsari-border/70">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $pkg->effective_duration_text }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Max 4 orang</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-wukirsari-border space-y-3.5">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl sm:text-2xl font-extrabold text-merapiText-primary font-display">
                                    Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-merapiText-secondary font-medium">/ Jeep</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <a href="{{ route('packages.show', $pkg->slug) }}" class="py-2.5 px-3 rounded-full border border-wukirsari-border text-merapiText-primary hover:border-wukirsari-primary hover:text-wukirsari-primary text-xs font-semibold text-center transition-all">
                                    Lihat Detail →
                                </a>
                                <a href="{{ route('booking.create', ['package' => $pkg->slug]) }}" class="py-2.5 px-3 rounded-full bg-[#1E3F20] hover:bg-[#285736] text-white text-xs font-semibold text-center transition-all shadow-sm">
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

    <!-- 4. MENGAPA MEMILIH JEEP WUKIRSARI (PANORAMIC HERO BANNER) -->
    <section id="tentang" class="py-12 sm:py-16 lg:py-24 bg-[#F7F7F2] relative overflow-hidden select-none">
        
                <!-- Botanical Silver Dollar Eucalyptus Illustration - Left Edge (Rangkaian Rimbun & Rame, 3 Cabang Berdimensi) -->
        <div class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 lg:translate-x-0 pointer-events-none opacity-55 lg:opacity-65 z-0">
<svg class="w-44 sm:w-56 lg:w-72 xl:w-80 h-auto text-[#436E4F]" viewBox="-40 -10 245 440" fill="none" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="mmLushFillLeft" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#4E785B" stop-opacity="0.16" />
      <stop offset="100%" stop-color="#2D5237" stop-opacity="0.04" />
    </linearGradient>
    <linearGradient id="mmLushStemLeft" x1="0%" y1="100%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#1E3E26" stop-opacity="0.88" />
      <stop offset="100%" stop-color="#4E785B" stop-opacity="0.55" />
    </linearGradient>
  </defs>

  <!-- STEM 1: Primary Arching Eucalyptus Branch -->
  <path d="M 35,420 C 45,340 58,260 90,175 C 112,115 138,65 165,20" stroke="url(#mmLushStemLeft)" stroke-width="2.2" stroke-linecap="round"/>

  <!-- STEM 2: Secondary Arching Branch -->
  <path d="M 32,425 C 24,370 6,310 -8,255 C -18,210 -22,165 -16,120" stroke="url(#mmLushStemLeft)" stroke-width="1.8" stroke-linecap="round" opacity="0.85"/>

  <!-- STEM 3: Delicate Mid-Sprig Accent -->
  <path d="M 38,415 C 55,360 85,310 115,265 C 138,230 162,198 185,170" stroke="url(#mmLushStemLeft)" stroke-width="1.3" stroke-linecap="round" opacity="0.75"/>

  <!-- BASE GROUNDING LEAVES -->
  <path d="M 30,410 C 12,398 0,378 6,360 C 16,364 24,382 32,398 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 38,405 C 56,392 76,394 82,404 C 74,414 58,416 42,412 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 26,422 C 8,415 -4,398 2,382 C 10,388 18,404 26,416 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 44,418 C 65,408 86,412 92,422 C 82,428 64,428 46,423 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>

  <!-- SECONDARY BRANCH LEAVES -->
  <path d="M 22,380 C 4,370 -12,348 -8,326 C -4,306 14,308 28,322 C 39,336 36,365 22,380 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 22,380 C 10,350 0,334 -8,326" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 25,355 C 42,342 62,340 70,352 C 78,364 72,378 58,382 C 44,386 31,372 25,355 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 25,355 C 42,357 58,360 70,352" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 10,320 C -6,308 -20,288 -16,268 C -12,250 4,252 16,264 C 27,276 24,304 10,320 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 10,320 C 1,292 -8,276 -16,268" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 12,298 C 28,286 46,285 54,296 C 61,306 56,320 44,324 C 32,327 20,314 12,298 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 12,298 C 28,300 42,302 54,296" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -2,260 C -16,248 -28,230 -24,212 C -20,196 -6,198 4,208 C 13,219 10,244 -2,260 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -2,260 C -10,234 -18,220 -24,212" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 0,240 C 14,230 30,230 36,240 C 42,250 37,262 26,265 C 16,268 6,255 0,240 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 0,240 C 14,242 26,244 36,240" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -12,205 C -24,194 -34,178 -30,162 C -26,148 -14,150 -5,158 C 3,168 0,190 -12,205 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -12,205 C -18,182 -24,170 -30,162" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -10,188 C 2,178 16,180 21,189 C 26,198 22,208 12,211 C 3,213 -5,202 -10,188 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -10,188 C 1,190 12,192 21,189" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -18,150 C -28,138 -36,122 -32,108 C -28,96 -18,98 -10,105 C -2,114 -6,134 -18,150 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -18,150 C -23,128 -28,116 -32,108" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -16,135 C -6,126 6,128 11,136 C 15,144 12,154 3,156 C -5,158 -12,148 -16,135 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -16,135 C -6,137 3,138 11,136" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>

  <!-- SPRIG ACCENT LEAFLETS -->
  <path d="M 62,350 C 72,338 84,335 88,342 C 82,350 72,352 64,351 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 55,356 C 46,346 44,335 50,330 C 56,334 58,345 60,353 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 82,310 C 94,298 106,295 110,302 C 103,310 93,312 84,311 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 74,316 C 64,306 62,294 68,288 C 75,293 77,305 79,313 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 104,272 C 118,260 130,257 134,264 C 126,272 115,274 106,273 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 94,278 C 83,268 80,255 87,249 C 94,254 96,266 99,275 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 126,236 C 140,224 150,222 154,228 C 147,235 137,237 128,237 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 116,242 C 106,232 104,220 110,214 C 117,219 119,230 121,239 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 148,202 C 160,192 170,190 174,196 C 167,202 158,204 150,203 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 139,207 C 130,198 128,187 134,182 C 140,186 142,196 144,204 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 172,176 C 182,166 190,165 193,170 C 187,175 179,177 173,177 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>

  <!-- PRIMARY BRANCH LEAVES & DETAILED VEINS -->
  <path d="M 42,370 C 20,356 -2,326 2,300 C 6,274 28,276 45,294 C 60,312 58,350 42,370 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 42,370 C 28,332 16,312 2,300" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 28,332 C 18,326 12,323 7,323" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 28,332 C 38,322 46,317 50,314" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 16,312 C 8,304 6,300 4,296" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 16,312 C 24,302 32,298 36,296" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 48,350 C 74,332 106,328 120,346 C 134,364 124,386 100,392 C 76,398 56,376 48,350 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 48,350 C 76,354 102,358 120,346" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 74,354 C 80,344 89,340 98,338" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 74,354 C 78,366 85,375 92,380" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 100,357 C 104,349 110,346 115,344" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 100,357 C 103,365 108,371 112,374" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 56,295 C 34,278 16,252 24,226 C 31,202 53,205 68,222 C 82,240 76,276 56,295 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 56,295 C 42,260 30,238 24,226" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 42,260 C 31,253 24,250 19,250" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 42,260 C 51,249 59,245 64,242" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 30,238 C 22,230 20,225 18,222" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 30,238 C 39,228 47,223 52,221" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 64,275 C 92,258 124,255 140,272 C 153,290 144,312 120,318 C 96,324 74,302 64,275 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 64,275 C 94,279 120,284 140,272" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 90,279 C 97,268 106,264 116,262" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 90,279 C 95,290 102,299 109,304" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 117,283 C 122,275 128,272 133,270" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 117,283 C 120,291 125,297 129,300" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 72,220 C 51,204 36,180 43,156 C 50,134 70,137 84,152 C 97,168 92,202 72,220 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 72,220 C 59,188 49,168 43,156" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 59,188 C 49,182 43,180 38,180" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 59,188 C 68,178 74,174 79,171" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 49,168 C 42,161 40,157 39,154" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 49,168 C 57,159 63,155 67,153" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 80,200 C 106,184 136,182 150,198 C 162,214 154,234 132,239 C 110,244 90,224 80,200 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 80,200 C 108,203 132,207 150,198" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 104,203 C 111,193 119,190 128,188" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 104,203 C 109,213 115,221 121,226" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 129,206 C 134,199 139,196 144,194" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 129,206 C 131,213 136,218 139,221" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 90,148 C 72,134 59,112 66,92 C 72,72 90,75 101,88 C 113,102 107,132 90,148 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 90,148 C 79,120 71,102 66,92" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 79,120 C 70,115 65,114 61,114" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 79,120 C 86,112 91,108 95,106" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 99,130 C 123,116 150,114 162,128 C 173,143 166,160 146,164 C 126,168 108,151 99,130 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 99,130 C 124,132 145,136 162,128" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 122,132 C 128,123 135,120 142,119" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 122,132 C 126,141 131,148 136,152" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 114,88 C 99,76 88,58 93,42 C 98,26 112,28 121,39 C 130,51 126,74 114,88 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 114,88 C 106,64 99,50 93,42" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 106,64 C 99,60 95,59 92,59" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 106,64 C 112,58 116,55 119,53" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 124,72 C 144,60 166,58 175,70 C 184,81 178,94 162,98 C 146,102 132,88 124,72 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 124,72 C 144,73 162,76 175,70" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 143,73 C 148,66 154,64 159,63" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 143,73 C 146,80 150,85 154,88" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 144,48 C 133,36 128,22 135,14 C 142,6 153,9 160,18 C 166,28 160,40 144,48 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 144,48 C 140,32 137,22 135,14" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 153,40 C 169,28 185,26 192,36 C 199,45 192,57 178,60 C 164,62 155,52 153,40 Z" fill="url(#mmLushFillLeft)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 153,40 C 169,41 183,43 192,36" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
</svg>
        </div>

        <!-- Botanical Silver Dollar Eucalyptus Illustration - Right Edge (Mirrored, Rimbun & Rame) -->
        <div class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 lg:translate-x-0 pointer-events-none opacity-55 lg:opacity-65 z-0 transform scale-x-[-1]">
<svg class="w-44 sm:w-56 lg:w-72 xl:w-80 h-auto text-[#436E4F]" viewBox="-40 -10 245 440" fill="none" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="mmLushFillRight" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#4E785B" stop-opacity="0.16" />
      <stop offset="100%" stop-color="#2D5237" stop-opacity="0.04" />
    </linearGradient>
    <linearGradient id="mmLushStemRight" x1="0%" y1="100%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#1E3E26" stop-opacity="0.88" />
      <stop offset="100%" stop-color="#4E785B" stop-opacity="0.55" />
    </linearGradient>
  </defs>

  <!-- STEM 1: Primary Arching Eucalyptus Branch -->
  <path d="M 35,420 C 45,340 58,260 90,175 C 112,115 138,65 165,20" stroke="url(#mmLushStemRight)" stroke-width="2.2" stroke-linecap="round"/>

  <!-- STEM 2: Secondary Arching Branch -->
  <path d="M 32,425 C 24,370 6,310 -8,255 C -18,210 -22,165 -16,120" stroke="url(#mmLushStemRight)" stroke-width="1.8" stroke-linecap="round" opacity="0.85"/>

  <!-- STEM 3: Delicate Mid-Sprig Accent -->
  <path d="M 38,415 C 55,360 85,310 115,265 C 138,230 162,198 185,170" stroke="url(#mmLushStemRight)" stroke-width="1.3" stroke-linecap="round" opacity="0.75"/>

  <!-- BASE GROUNDING LEAVES -->
  <path d="M 30,410 C 12,398 0,378 6,360 C 16,364 24,382 32,398 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 38,405 C 56,392 76,394 82,404 C 74,414 58,416 42,412 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 26,422 C 8,415 -4,398 2,382 C 10,388 18,404 26,416 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 44,418 C 65,408 86,412 92,422 C 82,428 64,428 46,423 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>

  <!-- SECONDARY BRANCH LEAVES -->
  <path d="M 22,380 C 4,370 -12,348 -8,326 C -4,306 14,308 28,322 C 39,336 36,365 22,380 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 22,380 C 10,350 0,334 -8,326" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 25,355 C 42,342 62,340 70,352 C 78,364 72,378 58,382 C 44,386 31,372 25,355 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 25,355 C 42,357 58,360 70,352" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 10,320 C -6,308 -20,288 -16,268 C -12,250 4,252 16,264 C 27,276 24,304 10,320 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 10,320 C 1,292 -8,276 -16,268" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 12,298 C 28,286 46,285 54,296 C 61,306 56,320 44,324 C 32,327 20,314 12,298 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 12,298 C 28,300 42,302 54,296" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -2,260 C -16,248 -28,230 -24,212 C -20,196 -6,198 4,208 C 13,219 10,244 -2,260 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -2,260 C -10,234 -18,220 -24,212" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M 0,240 C 14,230 30,230 36,240 C 42,250 37,262 26,265 C 16,268 6,255 0,240 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M 0,240 C 14,242 26,244 36,240" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -12,205 C -24,194 -34,178 -30,162 C -26,148 -14,150 -5,158 C 3,168 0,190 -12,205 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -12,205 C -18,182 -24,170 -30,162" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -10,188 C 2,178 16,180 21,189 C 26,198 22,208 12,211 C 3,213 -5,202 -10,188 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -10,188 C 1,190 12,192 21,189" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -18,150 C -28,138 -36,122 -32,108 C -28,96 -18,98 -10,105 C -2,114 -6,134 -18,150 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -18,150 C -23,128 -28,116 -32,108" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>
  <path d="M -16,135 C -6,126 6,128 11,136 C 15,144 12,154 3,156 C -5,158 -12,148 -16,135 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/>
  <path d="M -16,135 C -6,137 3,138 11,136" stroke="currentColor" stroke-width="0.7" stroke-linecap="round" opacity="0.65"/>

  <!-- SPRIG ACCENT LEAFLETS -->
  <path d="M 62,350 C 72,338 84,335 88,342 C 82,350 72,352 64,351 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 55,356 C 46,346 44,335 50,330 C 56,334 58,345 60,353 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 82,310 C 94,298 106,295 110,302 C 103,310 93,312 84,311 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 74,316 C 64,306 62,294 68,288 C 75,293 77,305 79,313 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 104,272 C 118,260 130,257 134,264 C 126,272 115,274 106,273 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 94,278 C 83,268 80,255 87,249 C 94,254 96,266 99,275 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 126,236 C 140,224 150,222 154,228 C 147,235 137,237 128,237 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 116,242 C 106,232 104,220 110,214 C 117,219 119,230 121,239 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 148,202 C 160,192 170,190 174,196 C 167,202 158,204 150,203 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 139,207 C 130,198 128,187 134,182 C 140,186 142,196 144,204 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>
  <path d="M 172,176 C 182,166 190,165 193,170 C 187,175 179,177 173,177 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="0.95" stroke-linejoin="round"/>

  <!-- PRIMARY BRANCH LEAVES & DETAILED VEINS -->
  <path d="M 42,370 C 20,356 -2,326 2,300 C 6,274 28,276 45,294 C 60,312 58,350 42,370 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 42,370 C 28,332 16,312 2,300" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 28,332 C 18,326 12,323 7,323" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 28,332 C 38,322 46,317 50,314" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 16,312 C 8,304 6,300 4,296" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 16,312 C 24,302 32,298 36,296" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 48,350 C 74,332 106,328 120,346 C 134,364 124,386 100,392 C 76,398 56,376 48,350 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 48,350 C 76,354 102,358 120,346" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 74,354 C 80,344 89,340 98,338" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 74,354 C 78,366 85,375 92,380" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 100,357 C 104,349 110,346 115,344" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 100,357 C 103,365 108,371 112,374" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 56,295 C 34,278 16,252 24,226 C 31,202 53,205 68,222 C 82,240 76,276 56,295 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 56,295 C 42,260 30,238 24,226" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 42,260 C 31,253 24,250 19,250" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 42,260 C 51,249 59,245 64,242" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 30,238 C 22,230 20,225 18,222" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 30,238 C 39,228 47,223 52,221" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 64,275 C 92,258 124,255 140,272 C 153,290 144,312 120,318 C 96,324 74,302 64,275 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 64,275 C 94,279 120,284 140,272" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 90,279 C 97,268 106,264 116,262" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 90,279 C 95,290 102,299 109,304" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 117,283 C 122,275 128,272 133,270" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 117,283 C 120,291 125,297 129,300" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 72,220 C 51,204 36,180 43,156 C 50,134 70,137 84,152 C 97,168 92,202 72,220 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 72,220 C 59,188 49,168 43,156" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 59,188 C 49,182 43,180 38,180" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 59,188 C 68,178 74,174 79,171" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 49,168 C 42,161 40,157 39,154" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 49,168 C 57,159 63,155 67,153" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 80,200 C 106,184 136,182 150,198 C 162,214 154,234 132,239 C 110,244 90,224 80,200 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 80,200 C 108,203 132,207 150,198" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 104,203 C 111,193 119,190 128,188" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 104,203 C 109,213 115,221 121,226" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 129,206 C 134,199 139,196 144,194" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 129,206 C 131,213 136,218 139,221" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 90,148 C 72,134 59,112 66,92 C 72,72 90,75 101,88 C 113,102 107,132 90,148 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 90,148 C 79,120 71,102 66,92" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 79,120 C 70,115 65,114 61,114" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 79,120 C 86,112 91,108 95,106" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 99,130 C 123,116 150,114 162,128 C 173,143 166,160 146,164 C 126,168 108,151 99,130 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 99,130 C 124,132 145,136 162,128" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 122,132 C 128,123 135,120 142,119" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 122,132 C 126,141 131,148 136,152" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 114,88 C 99,76 88,58 93,42 C 98,26 112,28 121,39 C 130,51 126,74 114,88 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 114,88 C 106,64 99,50 93,42" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 106,64 C 99,60 95,59 92,59" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 106,64 C 112,58 116,55 119,53" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 124,72 C 144,60 166,58 175,70 C 184,81 178,94 162,98 C 146,102 132,88 124,72 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 124,72 C 144,73 162,76 175,70" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 143,73 C 148,66 154,64 159,63" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 143,73 C 146,80 150,85 154,88" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" opacity="0.55"/>
  <path d="M 144,48 C 133,36 128,22 135,14 C 142,6 153,9 160,18 C 166,28 160,40 144,48 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 144,48 C 140,32 137,22 135,14" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
  <path d="M 153,40 C 169,28 185,26 192,36 C 199,45 192,57 178,60 C 164,62 155,52 153,40 Z" fill="url(#mmLushFillRight)" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/>
  <path d="M 153,40 C 169,41 183,43 192,36" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" opacity="0.75"/>
</svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Unified Wide Banner Card -->
            <div class="relative rounded-[28px] sm:rounded-[36px] overflow-hidden shadow-2xl shadow-emerald-950/20 border border-emerald-900/10">
                
                <!-- Background Image & Atmospheric Fog Mountain Layers -->
                <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                    <!-- Base Deep Green to Mountain Sage Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-r from-[#0F291B] via-[#18422A] to-[#4F7860]"></div>
                    
                    <!-- Real Mountain Landscape Image blended on the right -->
                    <img src="{{ asset('img/sunrise_trip.jpeg') }}" alt="Merapi Mountain Scenic View" class="absolute right-0 top-0 w-full lg:w-3/4 h-full object-cover object-right opacity-45 mix-blend-luminosity filter brightness-115 contrast-110" style="mask-image: linear-gradient(to right, transparent 0%, transparent 15%, black 65%); -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 15%, black 65%);">
                    
                    <!-- Atmospheric Fog & Misty Gradient Overlays -->
                    <div class="absolute inset-0 bg-gradient-to-r from-[#0F291B] via-[#18422A]/90 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-white/15"></div>
                    
                    <!-- SVG Mountain Ridge Silhouettes along bottom -->
                    <svg class="absolute bottom-0 right-0 w-full lg:w-4/5 h-24 sm:h-32 text-[#0D2417]/35 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 1200 180" fill="currentColor">
                        <path d="M0,180 L0,150 Q120,130 220,145 T420,110 T620,135 T820,95 T1020,120 T1200,90 L1200,180 Z" opacity="0.6"/>
                        <path d="M0,180 L0,165 Q160,145 300,155 T600,135 T900,140 T1200,120 L1200,180 Z" opacity="0.8"/>
                    </svg>
                </div>

                <!-- Banner Content -->
                <div class="relative z-10 p-6 sm:p-10 lg:p-12 xl:p-14">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-8 xl:gap-10 items-center">
                        
                        <!-- Left Column: Title, Script & Button -->
                        <div class="lg:col-span-5 xl:col-span-4 space-y-4 sm:space-y-5 text-white">
                            <div>
                                <span class="font-script text-2xl sm:text-3xl text-emerald-100/95 tracking-wide block leading-none mb-1">
                                    Mengapa Memilih
                                </span>
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white font-display tracking-tight leading-tight">
                                    Jeep Wukirsari?
                                </h2>
                            </div>

                            <p class="text-white/85 text-xs sm:text-sm leading-relaxed max-w-sm font-normal">
                                Kami berkomitmen memberikan pengalaman terbaik untuk setiap perjalanan Anda. Dengan pelayanan profesional, fasilitas lengkap dan rute yang aman, liburan Anda akan menjadi lebih berkesan.
                            </p>

                            <div class="pt-1">
                                <button type="button" onclick="openAboutModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-emerald-50 text-[#142B16] font-bold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                                    <span>Tentang Kami</span>
                                    <svg class="w-4 h-4 text-[#142B16]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Right Column: 4 Frosted Glass Feature Cards -->
                        <div class="lg:col-span-7 xl:col-span-8">
                            <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-3.5 xl:gap-4 items-stretch">
                                
                                <!-- Card 1: Harga Transparan -->
                                <div class="bg-white/10 hover:bg-white/15 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20 shadow-lg shadow-black/20 transition-all duration-300 flex flex-col items-center text-center justify-center min-h-[185px] sm:min-h-[195px] group">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#142B16] shadow-md group-hover:scale-105 transition-transform shrink-0 mb-3.5">
                                        <svg class="w-5 h-5 text-[#142B16]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <div class="h-8 sm:h-9 flex items-center justify-center mb-1">
                                            <h4 class="text-xs sm:text-sm font-extrabold text-white font-display leading-tight">
                                                Harga Transparan
                                            </h4>
                                        </div>
                                        <p class="text-[11px] sm:text-xs text-white/75 font-normal leading-tight">
                                            Tanpa biaya tersembunyi
                                        </p>
                                    </div>
                                </div>

                                <!-- Card 2: Pelayanan Ramah -->
                                <div class="bg-white/10 hover:bg-white/15 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20 shadow-lg shadow-black/20 transition-all duration-300 flex flex-col items-center text-center justify-center min-h-[185px] sm:min-h-[195px] group">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#142B16] shadow-md group-hover:scale-105 transition-transform shrink-0 mb-3.5">
                                        <svg class="w-5 h-5 text-[#142B16]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14a4 4 0 00-8 0v2h8v-2z" />
                                            <circle cx="12" cy="8" r="3" stroke-width="2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 00-14 0" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11v2a2 2 0 01-2 2h-1" />
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <div class="h-8 sm:h-9 flex items-center justify-center mb-1">
                                            <h4 class="text-xs sm:text-sm font-extrabold text-white font-display leading-tight">
                                                Pelayanan Ramah
                                            </h4>
                                        </div>
                                        <p class="text-[11px] sm:text-xs text-white/75 font-normal leading-tight">
                                            Siap membantu 24 jam
                                        </p>
                                    </div>
                                </div>

                                <!-- Card 3: Jalur Aman -->
                                <div class="bg-white/10 hover:bg-white/15 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20 shadow-lg shadow-black/20 transition-all duration-300 flex flex-col items-center text-center justify-center min-h-[185px] sm:min-h-[195px] group">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#142B16] shadow-md group-hover:scale-105 transition-transform shrink-0 mb-3.5">
                                        <svg class="w-5 h-5 text-[#142B16]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <div class="h-8 sm:h-9 flex items-center justify-center mb-1">
                                            <h4 class="text-xs sm:text-sm font-extrabold text-white font-display leading-tight">
                                                Jalur Aman
                                            </h4>
                                        </div>
                                        <p class="text-[11px] sm:text-xs text-white/75 font-normal leading-tight">
                                            Dipilih sesuai kondisi
                                        </p>
                                    </div>
                                </div>

                                <!-- Card 4: Pengalaman Terbaik -->
                                <div class="bg-white/10 hover:bg-white/15 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20 shadow-lg shadow-black/20 transition-all duration-300 flex flex-col items-center text-center justify-center min-h-[185px] sm:min-h-[195px] group">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#142B16] shadow-md group-hover:scale-105 transition-transform shrink-0 mb-3.5">
                                        <svg class="w-5 h-5 text-[#142B16]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <div class="h-8 sm:h-9 flex items-center justify-center mb-1">
                                            <h4 class="text-xs sm:text-sm font-extrabold text-white font-display leading-tight">
                                                Pengalaman Terbaik
                                            </h4>
                                        </div>
                                        <p class="text-[11px] sm:text-xs text-white/75 font-normal leading-tight">
                                            Banyak review positif
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Informasi Tentang Kami (Basecamp Jeep Wukirsari) -->
    <div id="aboutBasecampModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200">
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeAboutModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors cursor-pointer">
                ✕
            </button>
            
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-[#143B1E]/10 p-1.5 flex items-center justify-center border border-[#143B1E]/20">
                    <img src="{{ asset('img/logo.png') }}" alt="Jeep Wukirsari Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h3 class="font-display font-extrabold text-base sm:text-lg text-slate-900 leading-tight">Jeep Wukirsari Adventure</h3>
                    <p class="text-xs text-emerald-700 font-semibold">Basecamp Resmi Offroad Lereng Merapi</p>
                </div>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                <strong>Jeep Wukirsari</strong> merupakan paguyuban penyedia wisata petualangan offroad resmi di kawasan lereng Gunung Merapi, Yogyakarta. Dikelola langsung oleh pemuda dan komunitas driver lokal desa wisata Wukirsari yang ramah, berlisensi, dan berpengalaman puluhan tahun menaklukkan rute lava tour.
            </p>

            <div class="space-y-3 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div class="flex items-start gap-2.5 text-xs text-slate-700">
                    <span class="text-emerald-700 font-bold">📍</span>
                    <span><strong>Lokasi Basecamp:</strong> Dusun Pusung, Wukirsari, Cangkringan, Sleman, D.I. Yogyakarta 55583</span>
                </div>
                <div class="flex items-start gap-2.5 text-xs text-slate-700">
                    <span class="text-emerald-700 font-bold">⏰</span>
                    <span><strong>Jam Operasional:</strong> Setiap Hari, 04.30 - 17.00 WIB (Termasuk Sunrise Trip)</span>
                </div>
                <div class="flex items-start gap-2.5 text-xs text-slate-700">
                    <span class="text-emerald-700 font-bold">🛡️</span>
                    <span><strong>Jaminan Keamanan:</strong> Armada terawat berkala, driver bersertifikat, dan asuransi Jasa Raharja</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2.5">
                <a href="#paket" onclick="closeAboutModal()" class="flex-1 py-3 px-4 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs text-center transition-all shadow-md">
                    Lihat Paket Wisata →
                </a>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Jeep%20Wukirsari,%20saya%20ingin%20tanya%20seputar%20tour%20merapi" target="_blank" class="py-3 px-5 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition-all">
                    Chat WhatsApp CS
                </a>
            </div>
        </div>
    </div>

    <script>
        function openAboutModal() {
            const modal = document.getElementById('aboutBasecampModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }
        function closeAboutModal() {
            const modal = document.getElementById('aboutBasecampModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAboutModal();
        });
    </script>

    <!-- 5. GALERI MOMEN INDAH -->
    <section id="galeri" class="py-12 sm:py-16 lg:py-20 bg-wukirsari-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 sm:mb-12">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#1E3F20] font-display block mb-1">GALERI</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">Jelajahi Momen Indah Bersama Kami</h2>
                <p class="text-merapiText-secondary text-xs sm:text-base mt-1.5 max-w-xl">Kumpulan foto perjalanan seru di berbagai rute wisata.</p>
            </div>

            <!-- 5 Photos in 1 Row on Desktop Matching Mockup -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[16/11]">
                    <img src="{{ asset('img/1.jpeg') }}" alt="Petualangan Jeep Merapi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[16/11]">
                    <img src="{{ asset('img/2.jpeg') }}" alt="Sunrise Tour Merapi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[16/11]">
                    <img src="{{ asset('img/3.jpeg') }}" alt="Bunker Kaliadem Merapi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[16/11]">
                    <img src="{{ asset('img/4.jpeg') }}" alt="Rute Kali Kuning Offroad" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[16/11]">
                    <img src="{{ asset('img/5.jpeg') }}" alt="Lembah Panorama Merapi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- 6. TESTIMONI (APA KATA MEREKA?) -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-wukirsari-cream via-white to-wukirsari-cream border-t border-wukirsari-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 sm:mb-12">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#1E3F20] font-display block mb-1">TESTIMONI</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">Apa Kata Mereka?</h2>
                <p class="text-merapiText-secondary text-xs sm:text-base mt-1.5 max-w-xl">
                    Pengalaman nyata dari pelanggan yang sudah merasakan keseruannya.
                </p>
            </div>

            <!-- 3 Testimonial Cards Matching Mockup -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Andi Pratama -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm font-display shrink-0 border border-emerald-200">
                                AP
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 font-display">Andi Pratama</h4>
                                <span class="text-xs text-slate-500 block">Yogyakarta</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                            "Pengalaman yang luar biasa! Driver-nya ramah dan sangat berpengalaman. Pemandangannya keren banget!"
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1 text-amber-400 text-base">
                        ★★★★★
                    </div>
                </div>

                <!-- Card 2: Siti Nurhaliza -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm font-display shrink-0 border border-emerald-200">
                                SN
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 font-display">Siti Nurhaliza</h4>
                                <span class="text-xs text-slate-500 block">Sleman</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                            "Sunrise di Merapi benar-benar magical. Timnya profesional dan semua sesuai dengan yang dijanjikan."
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1 text-amber-400 text-base">
                        ★★★★★
                    </div>
                </div>

                <!-- Card 3: Rizky Maulana -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm font-display shrink-0 border border-emerald-200">
                                RM
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 font-display">Rizky Maulana</h4>
                                <span class="text-xs text-slate-500 block">Bandung</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                            "Booking-nya mudah, adminnya responsif, dan semuanya sesuai dengan yang dijanjikan."
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1 text-amber-400 text-base">
                        ★★★★★
                    </div>
                </div>
            </div>

            <!-- Link to Full Reviews / Form -->
            <div class="text-center pt-2">
                <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-emerald-800 transition-colors">
                    <span>Lihat Semua Ulasan & Kirim Testimoni Wisatawan →</span>
                </a>
            </div>
        </div>
    </section>

@endsection
