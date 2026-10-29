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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F7F7F2; color: #526054; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-wukirsari-sage selection:text-wukirsari-primary">

    <!-- NAVBAR HEADER -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-wukirsari-border transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-3 xl:gap-6">
                
                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-wukirsari-primary/10 p-1 flex items-center justify-center border border-wukirsari-border shadow-xs group-hover:scale-105 transition-transform shrink-0">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="block font-display font-extrabold text-base sm:text-lg text-merapiText-primary tracking-tight leading-none">
                            JEEP WUKIRSARI
                        </span>
                        <span class="block text-[10px] sm:text-[11px] font-medium text-merapiText-secondary uppercase tracking-widest mt-0.5">
                            Adventure Tour
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center gap-3.5 xl:gap-6 shrink-0">
                    <a href="{{ route('home') }}" class="text-xs xl:text-sm py-1 relative whitespace-nowrap transition-colors {{ request()->routeIs('home') ? 'font-semibold text-wukirsari-primary' : 'font-medium text-merapiText-secondary hover:text-wukirsari-primary' }}">
                        Beranda
                        @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ route('packages.index') }}" class="text-xs xl:text-sm py-1 relative whitespace-nowrap transition-colors {{ request()->routeIs('packages.*') ? 'font-semibold text-wukirsari-primary' : 'font-medium text-merapiText-secondary hover:text-wukirsari-primary' }}">
                        Paket Wisata
                        @if(request()->routeIs('packages.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ route('home') }}#tentang" class="text-xs xl:text-sm font-medium text-merapiText-secondary hover:text-wukirsari-primary transition-colors py-1 whitespace-nowrap">
                        Tentang Kami
                    </a>
                    <a href="{{ route('home') }}#galeri" class="text-xs xl:text-sm font-medium text-merapiText-secondary hover:text-wukirsari-primary transition-colors py-1 whitespace-nowrap">
                        Galeri
                    </a>
                    <a href="{{ route('reviews.index') }}" class="text-xs xl:text-sm py-1 relative whitespace-nowrap transition-colors {{ request()->routeIs('reviews.*') ? 'font-semibold text-wukirsari-primary' : 'font-medium text-merapiText-secondary hover:text-wukirsari-primary' }}">
                        Ulasan
                        @if(request()->routeIs('reviews.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ route('ticket.check') }}" class="text-xs xl:text-sm py-1 relative whitespace-nowrap transition-colors {{ request()->routeIs('ticket.*') ? 'font-semibold text-wukirsari-primary' : 'font-medium text-merapiText-secondary hover:text-wukirsari-primary' }}">
                        Cek Tiket
                        @if(request()->routeIs('ticket.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full"></span>
                        @endif
                    </a>
                </nav>

                <!-- Right Action -->
                <div class="hidden lg:flex items-center gap-2 xl:gap-3 shrink-0">
                    <a href="{{ route('packages.index') }}" class="p-2 rounded-full hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary transition-colors shrink-0" title="Cari Paket Wisata">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>

                    <a href="https://wa.me/6281234567890" target="_blank" class="p-2 rounded-full hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary transition-colors shrink-0" title="Bantuan WhatsApp CS">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </a>

                    <!-- MENU PROFIL / LOGIN (USER & ADMIN) -->
                    @auth
                        @if(Auth::user()->isAdmin())
                        <!-- Menu Khusus Admin -->
                        <div class="relative shrink-0">
                            <button type="button" id="btnAuthAdmin" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-wukirsari-sage/70 border border-wukirsari-border text-wukirsari-primary text-xs font-bold hover:bg-wukirsari-sage transition-all cursor-pointer whitespace-nowrap shrink-0">
                                <span class="w-5 h-5 rounded-full bg-wukirsari-primary text-white flex items-center justify-center text-[10px] shrink-0">🛡️</span>
                                <span class="max-w-[80px] xl:max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-wukirsari-primary text-white font-extrabold uppercase tracking-wider shrink-0">Admin</span>
                                <svg class="w-3 h-3 text-wukirsari-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="menuAuthAdmin" class="app-dropdown-menu hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-wukirsari-border py-2 transition-all z-50">
                                <div class="px-4 py-2 border-b border-wukirsari-border">
                                    <p class="text-[10px] font-bold text-wukirsari-primary uppercase tracking-wider">Peran: Administrator</p>
                                    <p class="text-xs font-bold text-merapiText-primary truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-merapiText-muted truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-merapiText-primary hover:bg-wukirsari-sage/40 hover:text-wukirsari-primary transition-colors">
                                    <span>📊</span> Dashboard Loket & Armada
                                </a>
                                <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-merapiText-primary hover:bg-wukirsari-sage/40 hover:text-wukirsari-primary transition-colors">
                                    <span>🌐</span> Beranda Wisatawan
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-wukirsari-border mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50 transition-colors text-left cursor-pointer">
                                        <span>🚪</span> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <!-- Menu Khusus Wisatawan / User -->
                        <div class="relative shrink-0">
                            <button type="button" id="btnAuthUser" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-wukirsari-sage/60 border border-wukirsari-border text-merapiText-primary text-xs font-bold hover:bg-wukirsari-sage transition-all cursor-pointer whitespace-nowrap shrink-0">
                                <span class="w-5 h-5 rounded-full bg-wukirsari-primary text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="max-w-[80px] xl:max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-700 text-white font-extrabold uppercase tracking-wider shrink-0">User</span>
                                <svg class="w-3 h-3 text-merapiText-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="menuAuthUser" class="app-dropdown-menu hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-wukirsari-border py-2 transition-all z-50">
                                <div class="px-4 py-2 border-b border-wukirsari-border">
                                    <p class="text-[10px] font-bold text-wukirsari-primary uppercase tracking-wider">Akun Wisatawan</p>
                                    <p class="text-xs font-bold text-merapiText-primary truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-merapiText-muted truncate">{{ Auth::user()->email }}</p>
                                </div>
                                    <p class="text-xs font-bold text-merapiText-primary truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-merapiText-muted truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-merapiText-primary hover:bg-wukirsari-sage/40 hover:text-wukirsari-primary transition-colors">
                                    <span>👤</span> Profil & Tiket Saya
                                </a>
                                <a href="{{ route('booking.create') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-merapiText-primary hover:bg-wukirsari-sage/40 hover:text-wukirsari-primary transition-colors">
                                    <span>🚙</span> Booking Jeep Baru
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-wukirsari-border mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50 transition-colors text-left cursor-pointer">
                                        <span>🚪</span> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @else
                    <!-- Dropdown Pilihan Login (Belum Masuk) -->
                    <div class="relative shrink-0">
                        <button type="button" id="btnAuthGuest" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full border border-wukirsari-border text-xs font-semibold text-merapiText-primary hover:border-wukirsari-primary hover:bg-wukirsari-sage/40 transition-all cursor-pointer whitespace-nowrap shrink-0">
                            <svg class="w-4 h-4 text-wukirsari-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Masuk / Akun</span>
                            <svg class="w-3 h-3 text-merapiText-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="menuAuthGuest" class="app-dropdown-menu hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-wukirsari-border py-2 transition-all z-50">
                            <div class="px-4 py-1.5 text-[10px] font-bold text-merapiText-muted uppercase tracking-wider border-b border-wukirsari-border">
                                Pilih Jenis Akun
                            </div>
                            <a href="{{ route('login', ['role' => 'user']) }}" class="flex items-start gap-2.5 px-4 py-2.5 text-xs hover:bg-wukirsari-sage/40 transition-colors">
                                <span class="text-base">🎒</span>
                                <div>
                                    <span class="font-bold text-merapiText-primary block">Login Wisatawan</span>
                                    <span class="text-[10px] text-merapiText-muted block">Akses tiket & riwayat booking</span>
                                </div>
                            </a>
                            <a href="{{ route('login', ['role' => 'admin']) }}" class="flex items-start gap-2.5 px-4 py-2.5 text-xs hover:bg-wukirsari-sage/40 transition-colors">
                                <span class="text-base">🛡️</span>
                                <div>
                                    <span class="font-bold text-merapiText-primary block">Login Admin Loket</span>
                                    <span class="text-[10px] text-merapiText-muted block">Khusus staf basecamp & armada</span>
                                </div>
                            </a>
                            <div class="border-t border-wukirsari-border mt-1 pt-1.5 px-4 pb-1">
                                <a href="{{ route('register') }}" class="text-[11px] font-bold text-wukirsari-primary hover:underline block text-center">
                                    Belum punya akun? Daftar Wisatawan →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <a href="{{ route('booking.create') }}" class="whitespace-nowrap shrink-0 inline-flex items-center gap-1.5 px-4 xl:px-5 py-2.5 rounded-full bg-wukirsari-primary text-white text-xs font-semibold hover:bg-wukirsari-hover transition-all shadow-sm shadow-wukirsari-primary/20 hover:scale-[1.02]">
                        <span>Booking Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Mobile Header CTA -->
                <div class="flex items-center gap-2 md:hidden">
                    @auth
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold">
                            Admin
                        </a>
                        @else
                        <a href="{{ route('profile') }}" class="px-3 py-1.5 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs font-bold">
                            Profil
                        </a>
                        @endif
                    @else
                    <a href="{{ route('login') }}" class="p-2 rounded-full border border-wukirsari-border text-merapiText-secondary hover:text-wukirsari-primary text-xs" title="Masuk">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                    @endauth
                    <a href="{{ route('booking.create') }}" class="px-3.5 py-1.5 rounded-full bg-wukirsari-primary text-white text-xs font-semibold">
                        Booking
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- FLOATING TOAST NOTIFICATIONS (Floating overlay: 0 layout height/gap) -->
    <div id="flashToastContainer" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 pointer-events-none flex flex-col items-center gap-2 transition-all">
        @if(session('success'))
        <div class="toast-item pointer-events-auto bg-white/95 backdrop-blur-md border border-emerald-300 text-emerald-900 px-4 py-2.5 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xl shadow-black/10 transition-all duration-300">
            <div class="flex items-center gap-2">
                <span class="text-sm shrink-0">✅</span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.closest('.toast-item').remove()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">✕</button>
        </div>
        @endif

        @if(session('info'))
        <div class="toast-item pointer-events-auto bg-white/95 backdrop-blur-md border border-emerald-300 text-emerald-950 px-4 py-2.5 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xl shadow-black/10 transition-all duration-300">
            <div class="flex items-center gap-2">
                <span class="text-sm shrink-0">ℹ️</span>
                <span class="font-semibold">{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.closest('.toast-item').remove()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-item pointer-events-auto bg-white/95 backdrop-blur-md border border-rose-300 text-rose-950 px-4 py-2.5 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xl shadow-black/10 transition-all duration-300">
            <div class="flex items-center gap-2">
                <span class="text-sm shrink-0">⚠️</span>
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
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-wukirsari-dark text-white/80 pt-16 pb-12 border-t border-wukirsari-primary/30 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                
                <!-- Kolom 1 -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-white/10 p-1 flex items-center justify-center border border-white/20">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="block font-display font-extrabold text-lg text-white tracking-tight leading-none">JEEP WUKIRSARI</span>
                            <span class="block text-[10px] font-medium text-wukirsari-sage uppercase tracking-widest mt-0.5">Adventure Tour</span>
                        </div>
                    </div>
                    <p class="text-sm text-white/70 leading-relaxed font-accent italic">"Petualangan hari ini, cerita untuk selamanya."</p>
                    <p class="text-xs text-white/60 leading-relaxed">Jelajahi jalur lahar dingin, bebatuan vulkanik, dan keindahan alam Gunung Merapi bersama pemandu profesional dan armada 4x4 tangguh.</p>
                </div>

                <!-- Kolom 2 -->
                <div>
                    <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('packages.index') }}" class="hover:text-white transition-colors">Paket Wisata</a></li>
                        <li><a href="{{ route('home') }}#tentang" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('home') }}#galeri" class="hover:text-white transition-colors">Galeri Dokumentasi</a></li>
                        <li><a href="{{ route('ticket.check') }}" class="hover:text-white transition-colors">Cek Status Tiket</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div>
                    <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-400 mt-0.5 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <a href="tel:+6281234567890" class="hover:text-white transition-colors">+62 812-3456-7890</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-400 mt-0.5 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <a href="mailto:jeepwukirsari@gmail.com" class="hover:text-white transition-colors">jeepwukirsari@gmail.com</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-400 mt-0.5 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <span>Basecamp Jeep Wukirsari, Kapanewon Cangkringan, Sleman, D.I. Yogyakarta</span>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 4 -->
                <div>
                    <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Pangkalan Operasional</h4>
                    <div class="p-3 bg-white/5 rounded-2xl border border-white/10 text-xs text-white/60 mb-4">
                        <span class="block font-medium text-white mb-1">Siaga 24 Jam</span>
                        <span>Layanan booking online instan dengan konfirmasi e-tiket WhatsApp langsung.</span>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-block px-4 py-2 rounded-xl bg-white/10 hover:bg-wukirsari-primary text-white text-xs font-semibold transition-all">
                        ⚙️ Portal Admin Loket
                    </a>
                </div>

            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-white/50 gap-4">
                <p>© 2026 Jeep Wukirsari Adventure Tour. Tugas Akhir (TA) Sistem Informasi.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- STICKY BOTTOM BAR (MOBILE ONLY) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-wukirsari-border px-4 py-3 shadow-lg flex items-center justify-between gap-3">
        <div>
            <span class="block text-[11px] font-medium text-merapiText-muted">Mulai dari</span>
            <span class="text-base font-bold text-merapiText-primary leading-tight">Rp 450.000<span class="text-xs font-normal text-merapiText-secondary">/jeep</span></span>
        </div>
        <a href="{{ route('booking.create') }}" class="flex-1 max-w-[220px] py-2.5 px-4 rounded-full bg-wukirsari-primary text-white text-xs font-semibold flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20 active:scale-95 transition-all">
            <span>Booking Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <!-- MODAL EDIT ULASAN WISATAWAN -->
    <div id="editReviewModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-wukirsari-border relative transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-wukirsari-border">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-lg font-bold">
                        ✏️
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

    <!-- Dropdown Menu & Edit Modal Controller -->
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

        document.addEventListener('DOMContentLoaded', function () {
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

</body>
</html>
