<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jeep Wukirsari Adventure Tour | Wisata Offroad Lereng Merapi')</title>
    <meta name="description" content="Jelajahi keindahan dan petualangan lereng Gunung Merapi bersama Jeep Wukirsari Adventure Tour. Reservasi online cepat, aman, dan bergaransi pengalaman terbaik.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600;700&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN dengan Konfigurasi Resmi Wukirsari -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wukirsari: {
                            primary: '#1E3F20',
                            dark: '#142B16',
                            hover: '#265029',
                            sage: '#E8EFE9',
                            cream: '#F7F7F2',
                            border: '#E2EAE3',
                        },
                        merapiText: {
                            primary: '#19231A',
                            secondary: '#526054',
                            muted: '#7E8E81',
                        },
                        accent: {
                            emerald: '#10B981',
                            light: '#34D399',
                            dark: '#059669',
                        },
                        sunrise: {
                            gold: '#E8EFE9',
                            amber: '#1E3F20',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                        accent: ['"Playfair Display"', 'serif'],
                        script: ['"Caveat"', 'cursive'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F7F7F2; color: #526054; -webkit-tap-highlight-color: transparent; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-wukirsari-sage selection:text-wukirsari-primary">

    <!-- NAVBAR HEADER -->
    @hasSection('header')
        @yield('header')
    @else
    <header id="mainNavbar" class="{{ request()->routeIs('home') ? 'sticky top-0 z-50 bg-[#0c2314] border-b border-emerald-900/60 text-white shadow-md transition-all' : 'sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-wukirsari-border text-merapiText-secondary transition-all' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 xl:gap-6">
                
                <!-- Logo Brand (Logo Asli Jeep Wukirsari) -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl {{ request()->routeIs('home') ? 'bg-black/40 border border-white/20' : 'bg-wukirsari-primary/10 border border-wukirsari-border' }} p-1 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform shrink-0">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="block font-display font-extrabold text-sm sm:text-base {{ request()->routeIs('home') ? 'text-white' : 'text-merapiText-primary' }} tracking-tight leading-none">
                            JEEP WUKIRSARI
                        </span>
                        <span class="block text-[9px] sm:text-[10px] font-medium {{ request()->routeIs('home') ? 'text-emerald-300' : 'text-merapiText-secondary' }} uppercase tracking-widest mt-0.5">
                            ADVENTURE TOUR
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav Links (Matching Mockup) -->
                <nav class="hidden lg:flex items-center gap-4 xl:gap-7 shrink-0">
                    <a href="{{ route('home') }}" class="text-xs xl:text-sm py-1 relative whitespace-nowrap transition-colors {{ request()->routeIs('home') ? 'font-semibold text-white' : 'font-medium text-merapiText-secondary hover:text-wukirsari-primary' }}">
                        Beranda
                        @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-white rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ route('home') }}#paket" class="text-xs xl:text-sm font-medium {{ request()->routeIs('home') ? 'text-white/85 hover:text-white' : 'text-merapiText-secondary hover:text-wukirsari-primary' }} transition-colors py-1 whitespace-nowrap">
                        Paket Wisata
                    </a>
                    <a href="{{ route('home') }}#tentang" class="text-xs xl:text-sm font-medium {{ request()->routeIs('home') ? 'text-white/85 hover:text-white' : 'text-merapiText-secondary hover:text-wukirsari-primary' }} transition-colors py-1 whitespace-nowrap">
                        Tentang Kami
                    </a>
                    <a href="{{ route('home') }}#galeri" class="text-xs xl:text-sm font-medium {{ request()->routeIs('home') ? 'text-white/85 hover:text-white' : 'text-merapiText-secondary hover:text-wukirsari-primary' }} transition-colors py-1 whitespace-nowrap">
                        Galeri
                    </a>
                    <a href="{{ route('home') }}#kontak" class="text-xs xl:text-sm font-medium {{ request()->routeIs('home') ? 'text-white/85 hover:text-white' : 'text-merapiText-secondary hover:text-wukirsari-primary' }} transition-colors py-1 whitespace-nowrap">
                        Kontak
                    </a>
                    @if(!request()->routeIs('home'))
                    <a href="{{ route('reviews.index') }}" class="text-xs xl:text-sm py-1 relative whitespace-nowrap transition-colors {{ request()->routeIs('reviews.*') ? 'font-semibold text-wukirsari-primary' : 'font-medium text-merapiText-secondary hover:text-wukirsari-primary' }}">
                        Ulasan
                        @if(request()->routeIs('reviews.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full"></span>
                        @endif
                    </a>
                    @endif
                </nav>

                <!-- Right Action -->
                <div class="hidden lg:flex items-center gap-2 xl:gap-3 shrink-0">
                    <a href="{{ route('home') }}#paket" class="p-2 rounded-full {{ request()->routeIs('home') ? 'hover:bg-white/15 text-white/85 hover:text-white' : 'hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary' }} transition-colors shrink-0" title="Cari Paket Wisata">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>

                    <!-- MENU PROFIL / LOGIN (USER & ADMIN - Sesuai Image 2) -->
                    @auth
                        @if(Auth::user()->isAdmin())
                        <!-- Menu Khusus Admin (Image 2) -->
                        <div class="relative shrink-0">
                            <button type="button" id="btnAuthAdmin" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#EAF3EB] border border-[#C5DEC8] text-[#1E3F20] text-xs font-bold hover:bg-[#DDEEDE] transition-all cursor-pointer whitespace-nowrap shrink-0 shadow-xs">
                                <span class="w-5 h-5 rounded-full bg-[#1E3F20] text-white flex items-center justify-center text-[10px] font-extrabold shrink-0">A</span>
                                <span class="max-w-[80px] xl:max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-[#1E3F20] text-white font-extrabold uppercase tracking-wider shrink-0">ADMIN</span>
                                <svg class="w-3 h-3 text-[#1E3F20] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="menuAuthAdmin" class="app-dropdown-menu hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-0 overflow-hidden transition-all z-50">
                                <div class="px-4 py-2.5 border-b border-gray-100">
                                    <p class="text-[10px] font-extrabold text-[#1E3F20] uppercase tracking-wider">PERAN: ADMINISTRATOR</p>
                                    <p class="text-xs font-bold text-gray-900 truncate mt-0.5">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-800 hover:bg-[#EAF3EB]/60 hover:text-[#1E3F20] transition-colors">
                                    Dashboard Loket & Armada
                                </a>
                                <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-800 hover:bg-[#EAF3EB]/60 hover:text-[#1E3F20] transition-colors">
                                    Beranda Wisatawan
                                </a>
                                <div class="border-t border-gray-100">
                                    <form action="{{ route('logout') }}" method="POST"
                                          data-confirm-title="Keluar Sesi Administrator"
                                          data-confirm-message="Apakah Anda yakin ingin keluar dari akun Anda?"
                                          data-confirm-type="danger"
                                          data-confirm-text="Ya, Keluar"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 bg-rose-50/70 hover:bg-rose-100 transition-colors text-left cursor-pointer">
                                            Keluar (Logout)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Menu Khusus Wisatawan / User -->
                        <div class="relative shrink-0">
                            <button type="button" id="btnAuthUser" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#EAF3EB] border border-[#C5DEC8] text-[#1E3F20] text-xs font-bold hover:bg-[#DDEEDE] transition-all cursor-pointer whitespace-nowrap shrink-0 shadow-xs">
                                <span class="w-5 h-5 rounded-full bg-[#1E3F20] text-white flex items-center justify-center text-[10px] font-extrabold shrink-0">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                <span class="max-w-[80px] xl:max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-3 h-3 text-[#1E3F20] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="menuAuthUser" class="app-dropdown-menu hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-0 overflow-hidden transition-all z-50">
                                <div class="px-4 py-2.5 border-b border-gray-100">
                                    <p class="text-[10px] font-extrabold text-[#1E3F20] uppercase tracking-wider">Akun Wisatawan</p>
                                    <p class="text-xs font-bold text-gray-900 truncate mt-0.5">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-800 hover:bg-[#EAF3EB]/60 hover:text-[#1E3F20] transition-colors">
                                    Profil & Tiket Saya
                                </a>
                                <a href="{{ route('booking.create') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-800 hover:bg-[#EAF3EB]/60 hover:text-[#1E3F20] transition-colors">
                                    Booking Jeep Baru
                                </a>
                                <div class="border-t border-gray-100">
                                    <form action="{{ route('logout') }}" method="POST"
                                          data-confirm-title="Keluar Sesi Akun"
                                          data-confirm-message="Apakah Anda yakin ingin keluar dari akun Anda?"
                                          data-confirm-type="danger"
                                          data-confirm-text="Ya, Keluar"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 bg-rose-50/70 hover:bg-rose-100 transition-colors text-left cursor-pointer">
                                            Keluar (Logout)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @else
                    <!-- Tombol Masuk Akun Tunggal -->
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full {{ request()->routeIs('home') ? 'border border-white/35 text-white/90 hover:bg-white/15' : 'border border-wukirsari-border text-merapiText-primary hover:border-wukirsari-primary hover:text-wukirsari-primary hover:bg-wukirsari-sage/40' }} text-xs font-semibold transition-all cursor-pointer whitespace-nowrap shrink-0">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Masuk</span>
                    </a>
                    @endauth

                    <a href="{{ route('booking.create') }}" class="whitespace-nowrap shrink-0 inline-flex items-center gap-1.5 px-5 xl:px-6 py-2.5 rounded-full bg-[#245329] hover:bg-[#2d6633] text-white text-xs font-bold border border-white/20 transition-all shadow-md hover:scale-[1.02]">
                        <span>Booking Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Mobile Header Actions & Hamburger Toggle (lg:hidden) -->
                <div class="flex items-center gap-2 lg:hidden">
                    <a href="https://wa.me/6281228677843" target="_blank" class="p-2 rounded-full border {{ request()->routeIs('home') ? 'border-white/25 text-emerald-300 hover:bg-white/10' : 'border-wukirsari-border text-emerald-600 hover:bg-wukirsari-sage/40' }} transition-colors" title="Chat WhatsApp CS">
                        <svg class="w-4 h-4 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>

                    <a href="{{ route('booking.create') }}" class="px-3 sm:px-4 py-1.5 rounded-full bg-[#245329] hover:bg-[#2d6633] border border-white/20 text-white text-xs font-semibold shadow-xs transition-all">
                        Booking
                    </a>

                    <!-- Hamburger Button -->
                    <button type="button" id="btnMobileMenuToggle" aria-label="Buka Menu Navigasi" class="p-2 rounded-xl border {{ request()->routeIs('home') ? 'border-white/25 text-white hover:bg-white/10' : 'border-wukirsari-border text-merapiText-primary hover:bg-wukirsari-sage/50' }} active:scale-95 transition-all cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </header>
    @endif

    <!-- MOBILE NAVIGATION DRAWER & BACKDROP (lg:hidden) -->
    <div id="mobileMenuBackdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none lg:hidden"></div>

    <aside id="mobileNavDrawer" class="fixed inset-y-0 right-0 w-[85%] max-w-sm bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between overflow-y-auto lg:hidden">
        <!-- Top Drawer Section -->
        <div>
            <!-- Header Drawer with Logo & Close button -->
            <div class="p-5 border-b border-wukirsari-border flex items-center justify-between bg-wukirsari-cream/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-wukirsari-primary/10 p-1 flex items-center justify-center border border-wukirsari-border">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="block font-display font-bold text-sm text-merapiText-primary leading-tight">JEEP WUKIRSARI</span>
                        <span class="block text-[10px] text-merapiText-secondary uppercase tracking-wider">Adventure Tour</span>
                    </div>
                </div>
                <button type="button" id="btnMobileMenuClose" aria-label="Tutup Menu" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-merapiText-secondary flex items-center justify-center text-base font-bold transition-colors cursor-pointer">
                    ✕
                </button>
            </div>

            <!-- Profile / Account Box in Drawer -->
            <div class="p-4 border-b border-wukirsari-border bg-slate-50/70">
                @auth
                    @if(Auth::user()->isAdmin())
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-wukirsari-primary text-white flex items-center justify-center text-sm font-bold shadow-xs">
                                ADM
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <h4 class="text-xs font-bold text-merapiText-primary truncate">{{ Auth::user()->name }}</h4>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-wukirsari-primary text-white font-extrabold uppercase tracking-wider">Admin</span>
                                </div>
                                <p class="text-[11px] text-merapiText-muted truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl bg-wukirsari-primary text-white text-xs font-semibold text-center shadow-xs">
                                Dashboard Loket
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50 text-xs font-semibold transition-all">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-sm font-bold shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <h4 class="text-xs font-bold text-merapiText-primary truncate">{{ Auth::user()->name }}</h4>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-700 text-white font-extrabold uppercase tracking-wider">User</span>
                                </div>
                                <p class="text-[11px] text-merapiText-muted truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('profile') }}" class="px-3 py-2 rounded-xl bg-wukirsari-sage text-wukirsari-primary text-xs font-bold text-center border border-wukirsari-border">
                                Tiket Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50 text-xs font-semibold transition-all">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="space-y-2">
                        <p class="text-[11px] font-bold text-merapiText-muted uppercase tracking-wider">Akses Akun</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="px-3 py-2.5 rounded-xl bg-wukirsari-primary text-white text-xs font-bold text-center shadow-xs transition-colors">
                                Masuk Akun
                            </a>
                            <a href="{{ route('register') }}" class="px-3 py-2.5 rounded-xl border border-wukirsari-border bg-white text-xs font-bold text-merapiText-primary text-center hover:bg-wukirsari-sage/40 transition-colors">
                                Daftar Akun
                            </a>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Navigation Links in Drawer -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link flex items-center px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-wukirsari-sage/70 text-wukirsari-primary font-bold' : 'text-merapiText-primary hover:bg-slate-50' }}">
                    <span>Beranda</span>
                </a>

                <a href="{{ route('home') }}#paket" class="mobile-nav-link flex items-center px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold text-merapiText-primary hover:bg-slate-50 transition-colors">
                    <span>Paket Wisata</span>
                </a>

                <a href="{{ route('home') }}#tentang" class="mobile-nav-link flex items-center px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold text-merapiText-primary hover:bg-slate-50 transition-colors">
                    <span>Tentang Kami</span>
                </a>

                <a href="{{ route('home') }}#galeri" class="mobile-nav-link flex items-center px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold text-merapiText-primary hover:bg-slate-50 transition-colors">
                    <span>Galeri Momen</span>
                </a>

                <a href="{{ route('reviews.index') }}" class="mobile-nav-link flex items-center px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold transition-colors {{ request()->routeIs('reviews.*') ? 'bg-wukirsari-sage/70 text-wukirsari-primary font-bold' : 'text-merapiText-primary hover:bg-slate-50' }}">
                    <span>Ulasan Wisatawan</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Drawer Actions -->
        <div class="p-5 border-t border-wukirsari-border space-y-3 bg-wukirsari-cream/40">
            <a href="{{ route('booking.create') }}" class="w-full py-3.5 px-4 rounded-full bg-wukirsari-primary text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20 hover:bg-wukirsari-hover transition-all">
                <span>Booking Jeep Sekarang →</span>
            </a>

            <a href="https://wa.me/6281228677843" target="_blank" class="w-full py-2.5 px-4 rounded-full border border-wukirsari-border bg-white text-merapiText-primary text-xs font-semibold flex items-center justify-center gap-2 hover:bg-wukirsari-sage/40 transition-colors">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>Bantuan CS WhatsApp</span>
            </a>

            <p class="text-[11px] text-center text-merapiText-muted pt-1">
                Basecamp Jeep Wukirsari • Siaga 24 Jam
            </p>
        </div>
    </aside>

    <!-- FLOATING TOAST NOTIFICATIONS (Floating overlay: 0 layout height/gap) -->
    <div id="flashToastContainer" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 pointer-events-none flex flex-col items-center gap-2 transition-all">
        @if(session('success'))
        <div class="toast-item pointer-events-auto bg-white/95 backdrop-blur-md border border-emerald-300 text-emerald-900 px-4 py-2.5 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xl shadow-black/10 transition-all duration-300">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.closest('.toast-item').remove()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">✕</button>
        </div>
        @endif

        @if(session('info'))
        <div class="toast-item pointer-events-auto bg-white/95 backdrop-blur-md border border-emerald-300 text-emerald-950 px-4 py-2.5 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xl shadow-black/10 transition-all duration-300">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold">{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.closest('.toast-item').remove()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-item pointer-events-auto bg-white/95 backdrop-blur-md border border-rose-300 text-rose-950 px-4 py-2.5 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xl shadow-black/10 transition-all duration-300">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.closest('.toast-item').remove()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">✕</button>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                document.querySelectorAll('.toast-item').forEach(function (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-10px)';
                    setTimeout(function () { el.remove(); }, 300);
                });
            }, 5000);
        });
    </script>

    <!-- CONTENT WRAPPER -->
    <main class="flex-1 pb-20 md:pb-0">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @hasSection('footer')
        @yield('footer')
    @else
    <footer id="kontak" class="bg-gradient-to-b from-[#091F12] via-[#0D2918] to-[#06140B] text-white/80 pt-14 sm:pt-16 pb-24 md:pb-12 border-t border-emerald-900/40 relative overflow-hidden select-none mt-auto">
        
        <!-- Ambient Glowing Lights -->
        <div class="absolute -top-24 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 right-1/4 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Topographic Contour Lines - Left -->
        <svg class="absolute bottom-0 left-0 w-80 sm:w-96 lg:w-[540px] h-auto pointer-events-none opacity-20 text-emerald-400 z-0" viewBox="0 0 500 280" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M-60,280 C40,240 100,270 200,200 C300,130 380,180 500,80" />
            <path d="M-60,255 C50,220 110,250 210,180 C310,110 390,160 500,60" />
            <path d="M-60,230 C60,200 120,230 220,160 C320,90 400,140 500,40" />
            <path d="M-60,205 C70,180 130,210 230,140 C330,70 410,120 500,20" />
            <path d="M-60,180 C80,160 140,190 240,120 C340,50 420,100 500,0" />
            <path d="M-60,155 C90,140 150,170 250,100 C350,30 430,80 500,-20" />
        </svg>

        <!-- Topographic Contour Lines - Right -->
        <svg class="absolute bottom-0 right-0 w-80 sm:w-96 lg:w-[540px] h-auto pointer-events-none opacity-20 text-emerald-400 z-0 transform scale-x-[-1]" viewBox="0 0 500 280" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M-60,280 C40,240 100,270 200,200 C300,130 380,180 500,80" />
            <path d="M-60,255 C50,220 110,250 210,180 C310,110 390,160 500,60" />
            <path d="M-60,230 C60,200 120,230 220,160 C320,90 400,140 500,40" />
            <path d="M-60,205 C70,180 130,210 230,140 C330,70 410,120 500,20" />
            <path d="M-60,180 C80,160 140,190 240,120 C340,50 420,100 500,0" />
        </svg>

        <!-- Botanical Corner Fronds (Echoing the natural botanical aesthetic of the site) -->
        <div class="hidden lg:block absolute bottom-0 left-0 pointer-events-none opacity-25 text-emerald-400/80 z-0">
            <svg class="w-40 h-auto" viewBox="-5 0 175 125" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M -5,108 C 28,105 65,86 102,56 C 126,36 146,20 162,7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <path d="M 26,98 C 14,86 8,74 15,64 C 25,66 31,82 34,92 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1.1"/>
                <path d="M 36,92 C 48,80 60,76 65,84 C 59,93 47,95 40,94 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1.1"/>
                <path d="M 46,80 C 32,67 28,53 36,46 C 44,50 49,64 52,75 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1.1"/>
                <path d="M 56,74 C 70,61 82,58 86,66 C 78,74 68,76 60,75 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1.1"/>
                <path d="M 68,61 C 56,48 54,35 62,30 C 69,34 71,47 74,57 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1.1"/>
                <path d="M 76,56 C 90,44 100,41 104,49 C 96,56 86,57 79,57 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1.1"/>
                <path d="M 88,45 C 78,34 78,23 85,18 C 91,23 92,32 93,41 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1"/>
                <path d="M 96,40 C 108,30 117,28 120,35 C 113,41 105,42 98,41 Z" fill="currentColor" fill-opacity="0.12" stroke="currentColor" stroke-width="1"/>
                <path d="M 130,18 C 141,9 148,7 152,9 C 148,15 142,20 135,20 Z" fill="currentColor" fill-opacity="0.15" stroke="currentColor" stroke-width="1"/>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- MAIN 3-COLUMN FOOTER CONTENT -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 pb-12 items-start">
                
                <!-- Kolom 1: Brand, Logo & Status Operasional (lg:col-span-5) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center p-2 shrink-0 shadow-md shadow-black/20">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain filter drop-shadow">
                        </div>
                        <div>
                            <span class="block font-display font-extrabold text-base sm:text-lg text-white tracking-tight leading-none">
                                JEEP WUKIRSARI
                            </span>
                            <span class="block text-[10px] sm:text-[11px] font-semibold text-emerald-300 uppercase tracking-widest mt-1">
                                ADVENTURE TOUR
                            </span>
                        </div>
                    </div>
                    
                    <p class="text-xs sm:text-sm text-white/75 leading-relaxed font-normal pr-4">
                        Petualangan hari ini, cerita untuk selamanya. Jelajahi pesona lereng Gunung Merapi dengan armada tangguh dan pemandu ramah terpercaya.
                    </p>

                    <!-- Status Operasional Pill -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-medium">Buka Setiap Hari: 06.00 – 18.00 WIB</span>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat (lg:col-span-3) -->
                <div class="lg:col-span-3">
                    <h4 class="text-white font-bold text-xs sm:text-sm tracking-wider mb-4 font-display flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Navigasi</span>
                    </h4>
                    <ul class="space-y-2.5 text-xs sm:text-sm text-white/75">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-emerald-300 hover:translate-x-1 inline-flex items-center gap-1.5 transition-all">
                                <span class="text-emerald-400 text-xs">›</span>
                                <span>Beranda</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#paket" class="hover:text-emerald-300 hover:translate-x-1 inline-flex items-center gap-1.5 transition-all">
                                <span class="text-emerald-400 text-xs">›</span>
                                <span>Paket Wisata</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#tentang" class="hover:text-emerald-300 hover:translate-x-1 inline-flex items-center gap-1.5 transition-all">
                                <span class="text-emerald-400 text-xs">›</span>
                                <span>Tentang Kami</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#galeri" class="hover:text-emerald-300 hover:translate-x-1 inline-flex items-center gap-1.5 transition-all">
                                <span class="text-emerald-400 text-xs">›</span>
                                <span>Galeri Foto</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reviews.index') }}" class="hover:text-emerald-300 hover:translate-x-1 inline-flex items-center gap-1.5 transition-all">
                                <span class="text-emerald-400 text-xs">›</span>
                                <span>Ulasan Wisatawan</span>
                            </a>
                        </li>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Basecamp & Media Sosial (lg:col-span-4) -->
                <div class="lg:col-span-4 space-y-4">
                    <h4 class="text-white font-bold text-xs sm:text-sm tracking-wider mb-4 font-display flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Basecamp & Kontak</span>
                    </h4>
                    
                    <ul class="space-y-2.5 text-xs sm:text-sm text-white/75">
                        <li class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                    <path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.4-5.1-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1z"/>
                                </svg>
                            </div>
                            <a href="https://wa.me/6281228677843" target="_blank" class="hover:text-emerald-300 transition-colors font-medium">+62 81228677843</a>
                        </li>

                        <li class="flex items-start gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30 mt-0.5">
                                <svg class="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                    <circle cx="12" cy="9" r="2.2" fill="currentColor"/>
                                </svg>
                            </div>
                            <a href="https://maps.app.goo.gl/4JDthdZY315Qpf6D6" target="_blank" rel="noopener noreferrer" class="text-white/70 hover:text-emerald-300 transition-colors leading-snug">Wukirsari, Cangkringan, Sleman, D.I. Yogyakarta 55583</a>
                        </li>
                    </ul>

                    <!-- Social Media Pills -->
                    <div class="pt-2">
                        <span class="block text-[11px] font-semibold text-white/50 uppercase tracking-wider mb-2.5">Ikuti Media Sosial</span>
                        <div class="flex items-center gap-2.5">
                            <!-- Instagram -->
                            <a href="https://www.instagram.com/jeep_wukirsari?rpxt=Z3puazN5aDMwNm5s" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-500 hover:text-white border border-white/10 flex items-center justify-center text-white/80 transition-all hover:-translate-y-1 shadow-sm" title="Instagram">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <!-- TikTok -->
                            <a href="https://www.tiktok.com/@antocingoh.26?_r=1&_t=ZS-9BJ4ZkjdNwK" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-500 hover:text-white border border-white/10 flex items-center justify-center text-white/80 transition-all hover:-translate-y-1 shadow-sm" title="TikTok">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 3. BOTTOM COPYRIGHT & LEGAL BAR -->
            <div class="pt-6 border-t border-white/10 flex flex-col md:flex-row items-center justify-between text-xs text-white/60 gap-3">
                <p>© 2025 <span class="text-white font-medium">Jeep Wukirsari Adventure Tour</span>. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4 text-white/60">
                    <a href="{{ route('terms') }}" class="hover:text-emerald-300 transition-colors">Syarat & Ketentuan</a>
                    <span class="text-white/20">•</span>
                    <a href="{{ route('privacy') }}" class="hover:text-emerald-300 transition-colors">Kebijakan Privasi</a>
                    <span class="text-white/20">•</span>
                    <a href="https://wa.me/+6281228677843" target="_blank" class="hover:text-emerald-300 transition-colors">Pusat Bantuan</a>
                </div>
            </div>

        </div>
    </footer>
    @endif

    <!-- STICKY BOTTOM BAR (MOBILE ONLY - DISEMBUNYIKAN DI FORM BOOKING / ADMIN) -->
    @if(!View::hasSection('hide_floating_cta') && !request()->routeIs('admin.*'))
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-wukirsari-border px-4 py-2.5 shadow-lg flex items-center justify-between gap-3">
        <div>
            <span class="block text-[10px] font-medium text-merapiText-muted uppercase tracking-wider">Mulai dari</span>
            <span class="text-sm font-extrabold text-merapiText-primary leading-tight">Rp 450.000<span class="text-[11px] font-normal text-merapiText-secondary">/jeep</span></span>
        </div>
        <a href="{{ route('booking.create') }}" class="flex-1 max-w-[200px] py-2 px-3.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-md shadow-wukirsari-primary/20 active:scale-95 transition-all">
            <span>Booking Sekarang</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>
    @endif

    <!-- MODAL EDIT ULASAN WISATAWAN -->
    <div id="editReviewModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-wukirsari-border relative transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-wukirsari-border">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-merapiText-primary font-display">Edit Ulasan Wisatawan</h3>
                        <p class="text-xs text-merapiText-muted">Perbarui rating bintang dan cerita pengalaman Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditReviewModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-merapiText-secondary flex items-center justify-center text-sm font-bold transition-colors">
                    ✕
                </button>
            </div>

            <form id="editReviewForm" method="POST" action="" class="space-y-4 pt-4">
                @csrf
                @method('PUT')

                <!-- Rating Bintang -->
                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1">Penilaian Bintang *</label>
                    <select name="rating" id="editRatingSelect" required class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary bg-white">
                        <option value="5">⭐⭐⭐⭐⭐ (5/5) Sangat Puas</option>
                        <option value="4">⭐⭐⭐⭐ (4/5) Puas</option>
                        <option value="3">⭐⭐⭐ (3/5) Cukup</option>
                        <option value="2">⭐⭐ (2/5) Kurang</option>
                        <option value="1">⭐ (1/5) Kecewa</option>
                    </select>
                </div>

                <!-- Kota Asal -->
                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1">Kota / Daerah Asal</label>
                    <input type="text" name="city" id="editCityInput" placeholder="Contoh: Jakarta / Sleman" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <!-- Komentar -->
                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1">Komentar / Pengalaman *</label>
                    <textarea name="comment" id="editCommentTextarea" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEditReviewModal()" class="px-4 py-2.5 rounded-full border border-wukirsari-border text-merapiText-secondary hover:bg-slate-50 text-xs font-semibold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dropdown Menu, Mobile Drawer & Modal Controller -->
    <script>
        function handleEditReviewClick(btn) {
            if (!btn) return;
            const id = btn.getAttribute('data-id');
            const rating = btn.getAttribute('data-rating') || '5';
            const city = btn.getAttribute('data-city') || '';
            const comment = btn.getAttribute('data-comment') || '';
            openEditReviewModal(id, rating, city, comment);
        }

        function openEditReviewModal(id, rating, city, comment) {
            const modal = document.getElementById('editReviewModal');
            const form = document.getElementById('editReviewForm');
            const ratingSelect = document.getElementById('editRatingSelect');
            const cityInput = document.getElementById('editCityInput');
            const commentText = document.getElementById('editCommentTextarea');

            if (!modal || !form) return;

            form.action = "{{ url('/reviews') }}/" + id;
            ratingSelect.value = rating;
            cityInput.value = city;
            commentText.value = comment;

            modal.classList.remove('hidden');
        }

        function closeEditReviewModal() {
            const modal = document.getElementById('editReviewModal');
            if (modal) modal.classList.add('hidden');
        }

        // Mobile Menu Drawer Functions
        function openMobileDrawer() {
            const backdrop = document.getElementById('mobileMenuBackdrop');
            const drawer = document.getElementById('mobileNavDrawer');
            if (!backdrop || !drawer) return;
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100', 'pointer-events-auto');
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');
            document.body.classList.add('overflow-hidden');
        }

        function closeMobileDrawer() {
            const backdrop = document.getElementById('mobileMenuBackdrop');
            const drawer = document.getElementById('mobileNavDrawer');
            if (!backdrop || !drawer) return;
            backdrop.classList.remove('opacity-100', 'pointer-events-auto');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            drawer.classList.remove('translate-x-0');
            drawer.classList.add('translate-x-full');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Mobile Menu Listeners
            const btnOpen = document.getElementById('btnMobileMenuToggle');
            const btnClose = document.getElementById('btnMobileMenuClose');
            const backdrop = document.getElementById('mobileMenuBackdrop');
            if (btnOpen) btnOpen.addEventListener('click', openMobileDrawer);
            if (btnClose) btnClose.addEventListener('click', closeMobileDrawer);
            if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.addEventListener('click', closeMobileDrawer);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeMobileDrawer();
            });

            // Desktop Dropdown Setup
            function setupDropdownToggle(btnId, menuId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                if (!btn || !menu) return;

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = menu.classList.contains('hidden');
                    document.querySelectorAll('.app-dropdown-menu').forEach(m => m.classList.add('hidden'));
                    if (isHidden) {
                        menu.classList.remove('hidden');
                    } else {
                        menu.classList.add('hidden');
                    }
                });

                menu.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }

            setupDropdownToggle('btnAuthGuest', 'menuAuthGuest');
            setupDropdownToggle('btnAuthUser', 'menuAuthUser');
            setupDropdownToggle('btnAuthAdmin', 'menuAuthAdmin');

            document.addEventListener('click', function () {
                document.querySelectorAll('.app-dropdown-menu').forEach(m => m.classList.add('hidden'));
            });
        });
    </script>

    @include('admin.partials.confirm-modal')
</body>
</html>