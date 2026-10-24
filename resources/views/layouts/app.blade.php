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
                        sunrise: {
                            gold: '#F59E0B',
                            amber: '#D97706',
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
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-wukirsari-primary text-white flex items-center justify-center shadow-md shadow-wukirsari-primary/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M13 7l-4 4-6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="block font-display font-extrabold text-lg text-merapiText-primary tracking-tight leading-none">
                            JEEP WUKIRSARI
                        </span>
                        <span class="block text-[11px] font-medium text-merapiText-secondary uppercase tracking-widest mt-0.5">
                            Adventure Tour
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-wukirsari-primary py-1 relative">
                        Beranda
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full"></span>
                    </a>
                    <a href="{{ route('home') }}#paket" class="text-sm font-medium text-merapiText-secondary hover:text-wukirsari-primary transition-colors py-1">
                        Paket Wisata
                    </a>
                    <a href="{{ route('home') }}#tentang" class="text-sm font-medium text-merapiText-secondary hover:text-wukirsari-primary transition-colors py-1">
                        Tentang Kami
                    </a>
                    <a href="{{ route('home') }}#galeri" class="text-sm font-medium text-merapiText-secondary hover:text-wukirsari-primary transition-colors py-1">
                        Galeri
                    </a>
                    <a href="{{ route('ticket.check') }}" class="text-sm font-medium text-merapiText-secondary hover:text-wukirsari-primary transition-colors py-1">
                        Cek Tiket
                    </a>
                </nav>

                <!-- Right Action -->
                <div class="hidden lg:flex items-center gap-4">
                    <a href="https://wa.me/6281234567890" target="_blank" class="p-2.5 rounded-full hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary transition-colors" title="Bantuan WhatsApp CS">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </a>

                    <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-wukirsari-primary text-white text-sm font-semibold hover:bg-wukirsari-hover transition-all shadow-sm shadow-wukirsari-primary/20 hover:scale-[1.02]">
                        <span>Booking Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Mobile Header CTA -->
                <div class="flex items-center gap-2 md:hidden">
                    <a href="{{ route('booking.create') }}" class="px-4 py-2 rounded-full bg-wukirsari-primary text-white text-xs font-semibold">
                        Booking
                    </a>
                </div>

            </div>
        </div>
    </header>

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
                        <div class="w-10 h-10 rounded-xl bg-wukirsari-primary text-white flex items-center justify-center border border-white/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M13 7l-4 4-6-6"></path></svg>
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
                        <li><a href="{{ route('home') }}#paket" class="hover:text-white transition-colors">Paket Wisata</a></li>
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
                            <span class="text-sunrise-gold mt-0.5">📞</span>
                            <a href="tel:+6281234567890" class="hover:text-white transition-colors">+62 812-3456-7890</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-sunrise-gold mt-0.5">✉️</span>
                            <a href="mailto:jeepwukirsari@gmail.com" class="hover:text-white transition-colors">jeepwukirsari@gmail.com</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-sunrise-gold mt-0.5">📍</span>
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

</body>
</html>
