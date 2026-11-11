<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard & Monitoring Operasional | Jeep Wukirsari</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- TOP SCENIC HEADER BANNER (Matching Mockup with Bright Scenic Landscape) -->
        <div class="relative bg-[#E8EFEA] border-b border-slate-200/80 shadow-xs z-30">
            <!-- Background Image with Soft Fade -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <img src="{{ $heroBg ?? asset('img/beranda.jpeg') }}" alt="Merapi" class="w-full h-full object-cover object-right opacity-45 mix-blend-multiply">
            </div>
            <!-- Soft White/Cream to Transparent Gradient so left texts are crisp -->
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/85 to-transparent pointer-events-none"></div>

            <div class="relative z-10 px-6 sm:px-8 py-6 sm:py-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Left: Title & Subtitle -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1 font-medium">Selamat datang, Admin! Berikut informasi terbaru hari ini.</p>
                </div>

                <!-- Right: Header Quick Controls (Date, Notifications, Avatar) -->
                <div class="flex items-center gap-3">
                    <!-- Tombol Cepat Kunjungi Beranda -->
                    <a href="{{ route('home') }}" target="_self" class="inline-flex items-center gap-2 bg-emerald-800 hover:bg-emerald-900 active:scale-95 text-white px-3.5 py-1.5 rounded-full text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer" title="Buka website Beranda wisatawan">
                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Lihat Beranda</span>
                    </a>

                    <!-- Live Clock Pill (Jam, Menit, Detik) -->
                    <div class="flex items-center gap-2 bg-white/95 backdrop-blur-md border border-slate-200 text-slate-700 px-3.5 py-1.5 rounded-full text-xs font-bold shadow-xs tracking-wide">
                        <svg class="w-4 h-4 text-emerald-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span id="adminLiveClock">{{ now()->timezone('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                    </div>

                    <!-- Notification Bell with Dropdown -->
                    <div class="relative">
                        <button type="button" onclick="toggleNotificationDropdown()" class="relative p-2 rounded-full bg-white/95 hover:bg-white backdrop-blur-md border border-slate-200 text-slate-700 transition-all cursor-pointer shadow-xs active:scale-95" title="Notifikasi Sistem">
                            <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute -top-1 -right-1 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-500 text-white text-[10px] font-extrabold shadow-xs">
                                {{ $unreadNotificationsCount > 0 ? ($unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount) : '3' }}
                            </span>
                        </button>

                        <!-- Notification Dropdown Popover -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 animate-in fade-in zoom-in-95">
                            <div class="p-4 border-b border-slate-100 bg-slate-50/90 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-800 text-sm">Notifikasi Masuk</h4>
                                    @if($unreadNotificationsCount > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">
                                        {{ $unreadNotificationsCount }} Baru
                                    </span>
                                    @endif
                                </div>
                                <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-semibold text-emerald-800 hover:underline cursor-pointer">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                                @forelse($notifications as $notif)
                                <a href="{{ route('admin.notifications') }}" class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors {{ !$notif->is_read ? 'bg-emerald-50/40' : '' }} block">
                                    <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center text-white text-xs font-bold
                                        {{ $notif->icon_type === 'booking' ? 'bg-emerald-600' : '' }}
                                        {{ $notif->icon_type === 'payment' ? 'bg-blue-600' : '' }}
                                        {{ $notif->icon_type === 'cancel' ? 'bg-rose-500' : '' }}
                                        {{ $notif->icon_type === 'schedule' ? 'bg-amber-500' : '' }}
                                        {{ !in_array($notif->icon_type, ['booking','payment','cancel','schedule']) ? 'bg-teal-600' : '' }}">
                                        @if($notif->icon_type === 'booking') 📋
                                        @elseif($notif->icon_type === 'payment') 💳
                                        @elseif($notif->icon_type === 'cancel') ✕
                                        @elseif($notif->icon_type === 'schedule') ⚠️
                                        @else 🔔
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-xs font-bold text-slate-800 truncate">{{ $notif->title }}</span>
                                            <span class="text-[10px] text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-600 line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                                    </div>
                                </a>
                                @empty
                                <div class="p-6 text-center text-slate-400 text-xs">Belum ada notifikasi baru.</div>
                                @endforelse
                            </div>
                            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                                <a href="{{ route('admin.notifications') }}" onclick="toggleNotificationDropdown()" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 inline-flex items-center gap-1">
                                    <span>Lihat Semua Riwayat Aktivitas</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <main class="p-6 sm:p-8 space-y-7 flex-1">
            
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- ROW 1: 4 METRIC CARDS (Persis Gambar Referensi Atas - Data Dinamis Riil) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- 1. Total Booking Hari Ini -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @if($todayPercentChange >= 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A] border border-emerald-200/60">
                                &uarr; +{{ $todayPercentChange }}%
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEE2E2] text-[#DC2626] border border-rose-200/60">
                                &darr; {{ $todayPercentChange }}%
                            </span>
                            @endif
                        </div>
                        <span class="text-xs font-semibold text-slate-500 block">Total Booking Hari Ini</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight">
                            {{ $todayBookingsCount }}
                        </span>
                    </div>
                    <div class="mt-4 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Dibanding kemarin: {{ $yesterdayBookingsCount }}</span>
                        <!-- Subtle wave decoration -->
                        <svg class="w-16 h-5 {{ $todayPercentChange >= 0 ? 'text-emerald-500/50' : 'text-rose-400/60' }}" viewBox="0 0 80 20" fill="none">
                            <path d="{{ $todayPercentChange >= 0 ? 'M0 15 C 20 18, 40 5, 80 8' : 'M0 6 C 30 10, 50 16, 80 18' }}" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- 2. Booking Mendatang -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A] border border-emerald-200/60">
                                &uarr; +{{ $upcomingPercentage }}%
                            </span>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 block">Booking Mendatang</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight">
                            {{ $upcomingBookingsCount }}
                        </span>
                    </div>
                    <div class="mt-4 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Untuk 7 hari ke depan</span>
                        <svg class="w-16 h-5 text-emerald-500/50" viewBox="0 0 80 20" fill="none">
                            <path d="M0 16 C 30 18, 50 8, 80 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- 3. Booking Selesai -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-[#174825] text-white flex items-center justify-center shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A] border border-emerald-200/60">
                                &uarr; +{{ $completedPercentage }}%
                            </span>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 block">Booking Selesai</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight">
                            {{ $completedBookingsCount }}
                        </span>
                    </div>
                    <div class="mt-4 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Total sejak awal bulan</span>
                        <svg class="w-16 h-5 text-emerald-500/50" viewBox="0 0 80 20" fill="none">
                            <path d="M0 18 C 25 15, 45 10, 80 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- 4. Booking Dibatalkan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-[#F87171] text-white flex items-center justify-center shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEE2E2] text-[#DC2626] border border-rose-200/60">
                                &darr; -{{ $cancelPercentage }}%
                            </span>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 block">Booking Dibatalkan</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight">
                            {{ $cancelledBookingsCount }}
                        </span>
                    </div>
                    <div class="mt-4 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Total bulan ini</span>
                        <svg class="w-16 h-5 text-rose-400/60" viewBox="0 0 80 20" fill="none">
                            <path d="M0 6 C 30 10, 50 16, 80 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

            </div>

            <!-- ROW 2: 3 ANALYTICS CARDS (Persis Gambar Referensi Tengah) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                
                <!-- Card 1: Grafik Omzet & Booking (lg:col-span-6) -->
                <div class="lg:col-span-6 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Header Bar with Title, Metric Switcher, Chart Type & Period -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-100">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Tren Performa Penjualan</h3>
                                    <p class="text-[11px] text-slate-500 font-medium" id="chartSubtitle">Statistik omzet penjualan harian</p>
                                </div>
                            </div>
                            
                            <!-- Controls: Metric Switcher, Chart Type, & Period Filter -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- Metric Tab Switcher -->
                                <div class="inline-flex p-0.5 rounded-xl bg-slate-100 border border-slate-200/70 text-xs">
                                    <button type="button" onclick="setChartMetric('revenue')" id="btnMetricRevenue" class="px-2.5 py-1 rounded-lg bg-white text-emerald-800 shadow-2xs font-bold transition-all cursor-pointer">
                                        Omzet (Rp)
                                    </button>
                                    <button type="button" onclick="setChartMetric('bookings')" id="btnMetricBookings" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-semibold transition-all cursor-pointer">
                                        Booking
                                    </button>
                                </div>

                                <!-- Chart Type Toggle (Area vs Bar) -->
                                <div class="inline-flex p-0.5 rounded-xl bg-slate-100 border border-slate-200/70 text-xs">
                                    <button type="button" onclick="setChartType('line')" id="btnTypeLine" class="p-1.5 rounded-lg bg-white text-emerald-800 shadow-2xs font-bold transition-all cursor-pointer" title="Tampilan Garis Area">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M4 17h16"/></svg>
                                    </button>
                                    <button type="button" onclick="setChartType('bar')" id="btnTypeBar" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 font-semibold transition-all cursor-pointer" title="Tampilan Grafik Batang">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </button>
                                </div>

                                <!-- Period Dropdown -->
                                <select id="chartPeriodSelect" onchange="changeChartPeriod(this.value)" class="text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl px-2.5 py-1 shadow-2xs cursor-pointer focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                    <option value="7">7 Hari</option>
                                    <option value="14">14 Hari</option>
                                    <option value="30">30 Hari</option>
                                </select>
                            </div>
                        </div>

                        <!-- Canvas Chart Container -->
                        <div class="relative w-full h-[225px]">
                            <canvas id="chartOmzetBooking"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Status Pembayaran (lg:col-span-3) - Side-by-side Donut & Legend -->
                <div class="lg:col-span-3 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <h3 class="text-sm font-bold text-slate-800">Status Pembayaran</h3>
                        </div>

                        <!-- Side by Side: Donut on Left, Legend on Right -->
                        <div class="flex items-center justify-between gap-3 my-3">
                            <!-- Donut with Center Text Overlay -->
                            <div class="relative w-[125px] h-[125px] shrink-0 flex items-center justify-center">
                                <canvas id="chartPaymentStatus"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-xl font-black text-slate-900 leading-none">{{ $paidPercentage }}%</span>
                                    <span class="text-[10px] font-semibold text-slate-500 mt-1">Berhasil</span>
                                </div>
                            </div>

                            <!-- Legend List on Right -->
                            <div class="flex-1 space-y-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#10B981] shrink-0"></span>
                                    <span class="text-slate-600 font-medium text-[11px]">Berhasil</span>
                                    <span class="font-bold text-slate-800 ml-auto text-xs">{{ $paidPercentage }}%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B] shrink-0"></span>
                                    <span class="text-slate-600 font-medium text-[11px]">Menunggu</span>
                                    <span class="font-bold text-slate-800 ml-auto text-xs">{{ $pendingPercentage }}%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F87171] shrink-0"></span>
                                    <span class="text-slate-600 font-medium text-[11px]">Gagal</span>
                                    <span class="font-bold text-slate-800 ml-auto text-xs">{{ $cancelPercentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Armada Jeep (lg:col-span-3) - Side-by-side Donut & Legend + Total Box -->
                <div class="lg:col-span-3 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/></svg>
                                <h3 class="text-sm font-bold text-slate-800">Armada Jeep</h3>
                            </div>
                            <a href="{{ route('admin.armada') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-950 inline-flex items-center gap-1">
                                <span>Lihat Detail</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <!-- Side by Side: Donut on Left, Legend on Right -->
                        <div class="flex items-center justify-between gap-3 my-2">
                            <!-- Donut with Center Car Icon -->
                            <div class="relative w-[125px] h-[125px] shrink-0 flex items-center justify-center">
                                <canvas id="chartJeepFleet"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-700">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.04 3H5.81l1.04-3zM19 17H5v-4.66l.12-.34h13.77l.11.34V17z"/><circle cx="7.5" cy="14.5" r="1.5"/><circle cx="16.5" cy="14.5" r="1.5"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Legend List on Right -->
                            <div class="flex-1 space-y-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#10B981] shrink-0"></span>
                                    <span class="text-slate-600 font-medium text-[11px]">Tersedia</span>
                                    <span class="font-bold text-slate-800 ml-auto text-xs">{{ $readyJeepsCount }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B] shrink-0"></span>
                                    <span class="text-slate-600 font-medium text-[11px]">Digunakan</span>
                                    <span class="font-bold text-slate-800 ml-auto text-xs">{{ $onTripJeepsCount }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#94A3B8] shrink-0"></span>
                                    <span class="text-slate-600 font-medium text-[11px]">Tidak Tersedia</span>
                                    <span class="font-bold text-slate-800 ml-auto text-xs">{{ $maintenanceJeepsCount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Total Armada Box (Persis Mockup Hijau Mint Bawah) -->
                        <div class="mt-2.5 p-2.5 rounded-xl bg-[#EBF7EE] border border-[#D1EED7] flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                ✓
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="block text-xs font-bold text-emerald-950 leading-tight">Total Armada: {{ $totalJeepsCount }} Jeep ({{ $readyJeepsCount }} Unit Aktif)</span>
                                <span class="block text-[10px] text-emerald-700/80 leading-tight">Kapasitas Maks: 4 Penumpang/Jeep</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ROW 3: 2 COLUMNS (Daftar Booking Terbaru & Aktivitas Terbaru) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                
                <!-- Left: Daftar Booking Terbaru (lg:col-span-8) -->
                <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Header -->
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                <h3 class="text-sm font-bold text-slate-800">Daftar Booking Terbaru</h3>
                            </div>
                            <a href="{{ route('admin.bookings') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-950 inline-flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                                    <tr>
                                        <th class="py-3 px-4">Kode Booking</th>
                                        <th class="py-3 px-4">Nama Pemesan</th>
                                        <th class="py-3 px-4">Paket Wisata</th>
                                        <th class="py-3 px-4">Tanggal</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentBookings as $rb)
                                    @php
                                        $proofUrl = null;
                                        $senderName = null;
                                        $senderBank = null;
                                        foreach ($rb->payments as $p) {
                                            if (is_array($p->payload_response) && !empty($p->payload_response['proof_url'])) {
                                                $proofUrl = asset($p->payload_response['proof_url']);
                                                $senderName = $p->payload_response['sender_name'] ?? null;
                                                $senderBank = $p->payload_response['sender_bank'] ?? null;
                                                break;
                                            }
                                        }

                                        $rbDetail = [
                                            'id' => $rb->id,
                                            'booking_code' => $rb->booking_code,
                                            'customer_name' => $rb->customer_name,
                                            'customer_phone' => $rb->customer_phone,
                                            'customer_email' => $rb->customer_email ?? '-',
                                            'package_name' => $rb->package?->name ?? 'Paket Jeep',
                                            'trip_date' => $rb->trip_date ? $rb->trip_date->translatedFormat('d F Y') : '-',
                                            'slot_label' => ($rb->slot?->start_time ?? '09:00') . ' - ' . ($rb->slot?->end_time ?? '11:00') . ' WIB',
                                            'jeep_quantity' => $rb->jeep_quantity,
                                            'passenger_count' => $rb->passenger_count,
                                            'arrival_type' => $rb->arrival_type === 'PICKUP' ? 'Jemput di Hotel/Stasiun' : 'Datang Sendiri ke Basecamp',
                                            'pickup_zone' => $rb->pickupZone?->name ?? 'Basecamp',
                                            'pickup_address' => $rb->pickup_address ?? '-',
                                            'payment_mode' => $rb->payment_mode === 'DP' ? 'Uang Muka (DP 30%)' : 'Pembayaran Penuh (100%)',
                                            'package_subtotal' => number_format($rb->package_subtotal, 0, ',', '.'),
                                            'pickup_fee' => number_format($rb->pickup_fee, 0, ',', '.'),
                                            'total_amount' => number_format($rb->total_amount, 0, ',', '.'),
                                            'paid_amount' => number_format($rb->paid_amount, 0, ',', '.'),
                                            'remaining_amount' => number_format($rb->remaining_amount, 0, ',', '.'),
                                            'booking_status' => $rb->booking_status,
                                            'created_at' => $rb->created_at ? $rb->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-',
                                            'jeep_name' => $rb->assignments->first()?->jeep?->model_name ?? 'Belum Ditugaskan',
                                            'driver_name' => $rb->assignments->first()?->driver?->full_name ?? 'Dialokasikan Otomatis',
                                            'customer_notes' => $rb->customer_notes ?? '',
                                            'proof_url' => $proofUrl,
                                            'sender_name' => $senderName,
                                            'sender_bank' => $senderBank,
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                            {{ $rb->booking_code }}
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">
                                            {{ $rb->customer_name }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-600">
                                            {{ $rb->package?->name ?? 'Paket' }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">
                                            {{ $rb->trip_date ? $rb->trip_date->translatedFormat('d M Y') : '-' }} &bull; {{ $rb->slot?->start_time ?? '09.00' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($rb->booking_status === 'FULL_PAID')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">
                                                Dikonfirmasi
                                            </span>
                                            @elseif($rb->booking_status === 'DP_PAID')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">
                                                Dikonfirmasi (DP)
                                            </span>
                                            @elseif($rb->booking_status === 'COMPLETED')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">
                                                Selesai
                                            </span>
                                            @elseif($rb->booking_status === 'CANCELLED')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEE2E2] text-[#DC2626]">
                                                Dibatalkan
                                            </span>
                                            @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#D97706]">
                                                Menunggu Pembayaran
                                            </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" data-booking='@json($rbDetail)' onclick="openBookingDetailModal(this)" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-semibold text-[11px] shadow-2xs transition-all cursor-pointer">
                                                    👁 Detail
                                                </button>
                                                @if(!empty($rbDetail['proof_url']))
                                                <!-- Tombol Akses Bukti Transfer Cepat (1 Tab / Modal Lightbox) -->
                                                <button type="button" 
                                                    data-proof-url="{{ $rbDetail['proof_url'] }}" 
                                                    data-booking-code="{{ $rb->booking_code }}" 
                                                    data-sender-name="{{ $rbDetail['sender_name'] ?? 'Wisatawan' }}" 
                                                    data-sender-bank="{{ $rbDetail['sender_bank'] ?? 'Bank BPD DIY' }}" 
                                                    onclick="openProofFromBtn(this)" 
                                                    class="p-1 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer" 
                                                    title="Lihat Bukti Transfer Pelanggan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <!-- Fallback sample rows matching visual mockup -->
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">JKR-1027</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Andi Pratama</td>
                                        <td class="py-3 px-4 text-slate-600">Short Trip</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">01 Nov 2026 &bull; 09.00</td>
                                        <td class="py-3 px-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#D97706]">Menunggu Pembayaran</span></td>
                                        <td class="py-3 px-4 text-center"><button class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 text-[11px] font-semibold">👁 Detail</button></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">JKR-1026</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Siti Nurhaliza</td>
                                        <td class="py-3 px-4 text-slate-600">Adventure</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">01 Nov 2026 &bull; 10.00</td>
                                        <td class="py-3 px-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">Dikonfirmasi</span></td>
                                        <td class="py-3 px-4 text-center"><button class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 text-[11px] font-semibold">👁 Detail</button></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">JKR-1025</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Budi Santoso</td>
                                        <td class="py-3 px-4 text-slate-600">Lavature</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">31 Okt 2026 &bull; 14.00</td>
                                        <td class="py-3 px-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">Selesai</span></td>
                                        <td class="py-3 px-4 text-center"><button class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 text-[11px] font-semibold">👁 Detail</button></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">JKR-1024</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Rina Amelia</td>
                                        <td class="py-3 px-4 text-slate-600">Sunrise Trip</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">31 Okt 2026 &bull; 05.30</td>
                                        <td class="py-3 px-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">Selesai</span></td>
                                        <td class="py-3 px-4 text-center"><button class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 text-[11px] font-semibold">👁 Detail</button></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">JKR-1023</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Dika Permana</td>
                                        <td class="py-3 px-4 text-slate-600">Medium Trip</td>
                                        <td class="py-3 px-4 text-slate-500 text-[11px]">31 Okt 2026 &bull; 09.00</td>
                                        <td class="py-3 px-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEE2E2] text-[#DC2626]">Dibatalkan</span></td>
                                        <td class="py-3 px-4 text-center"><button class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 text-[11px] font-semibold">👁 Detail</button></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Aktivitas Terbaru Timeline (lg:col-span-4) -->
                <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
                    <div>
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h3 class="text-sm font-bold text-slate-800">Aktivitas Terbaru</h3>
                            </div>
                            <a href="{{ route('admin.notifications') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-950 inline-flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <!-- Connected Vertical Timeline (Matching Reference) -->
                        <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            @forelse($recentActivities->take(4) as $act)
                            @php
                                $dotColor = 'bg-[#10B981]';
                                if ($act->type === 'BOOKING_NEW') $dotColor = 'bg-[#10B981]';
                                elseif ($act->type === 'PAYMENT_SUCCESS') $dotColor = 'bg-[#3B82F6]';
                                elseif ($act->type === 'BOOKING_CANCELLED') $dotColor = 'bg-[#F59E0B]';
                                elseif ($act->type === 'SCHEDULE_FULL') $dotColor = 'bg-[#10B981]';
                            @endphp
                            <div class="relative flex items-start justify-between gap-3">
                                <!-- Timeline Node Dot -->
                                <span class="absolute -left-[1.8rem] top-1 w-2.5 h-2.5 rounded-full {{ $dotColor }}"></span>
                                
                                <div class="min-w-0 flex-1">
                                    <span class="block text-xs font-bold text-slate-800 leading-snug">{{ $act->title }}</span>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $act->message }}</p>
                                </div>
                                <span class="text-[10px] text-slate-400 shrink-0 font-medium">
                                    {{ $act->created_at->diffForHumans(null, true, true) }}
                                </span>
                            </div>
                            @empty
                            <!-- Sample Timeline items matching mockup -->
                            <div class="relative flex items-start justify-between gap-3">
                                <span class="absolute -left-[1.8rem] top-1 w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-xs font-bold text-slate-800">Booking baru</span>
                                    <span class="block text-[11px] text-slate-500 truncate">JKR-1027 oleh Andi Pratama</span>
                                </div>
                                <span class="text-[10px] text-slate-400 shrink-0">2 menit lalu</span>
                            </div>

                            <div class="relative flex items-start justify-between gap-3">
                                <span class="absolute -left-[1.8rem] top-1 w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-xs font-bold text-slate-800">Pembayaran berhasil</span>
                                    <span class="block text-[11px] text-slate-500 truncate">JKR-1026 senilai Rp 750.000</span>
                                </div>
                                <span class="text-[10px] text-slate-400 shrink-0">5 menit lalu</span>
                            </div>

                            <div class="relative flex items-start justify-between gap-3">
                                <span class="absolute -left-[1.8rem] top-1 w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-xs font-bold text-slate-800">Booking dibatalkan</span>
                                    <span class="block text-[11px] text-slate-500 truncate">JKR-1025 oleh Budi Santoso</span>
                                </div>
                                <span class="text-[10px] text-slate-400 shrink-0">10 menit lalu</span>
                            </div>

                            <div class="relative flex items-start justify-between gap-3">
                                <span class="absolute -left-[1.8rem] top-1 w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-xs font-bold text-slate-800">Jadwal Jeep diperbarui</span>
                                    <span class="block text-[11px] text-slate-500 truncate">Sesi 09.00 - 11.00</span>
                                </div>
                                <span class="text-[10px] text-slate-400 shrink-0">1 jam lalu</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <!-- MODAL DETAIL LENGKAP INFORMASI PEMESAN -->
            <div id="modalBookingDetail" onclick="if(event.target === this) closeBookingDetailModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200">
                <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95">
                    <!-- Modal Header -->
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-800 text-base">Detail Informasi Pemesan</h3>
                                    <span id="detail_status_badge"></span>
                                </div>
                                <p class="text-xs text-slate-500">Kode Booking: <span id="detail_booking_code" class="font-mono font-bold text-emerald-800"></span></p>
                            </div>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6 text-xs sm:text-sm">
                        <!-- 1. Identitas Pemesan -->
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Data Wisatawan / Pemesan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <span class="text-slate-400 text-xs block">Nama Lengkap</span>
                                    <span id="detail_customer_name" class="font-bold text-slate-800 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">No. WhatsApp / HP</span>
                                    <span id="detail_customer_phone" class="font-bold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Email</span>
                                    <span id="detail_customer_email" class="font-medium text-slate-700 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Waktu Pemesanan Masuk</span>
                                    <span id="detail_created_at" class="font-medium text-slate-700 block"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan / Permintaan Khusus Wisatawan -->
                        <div id="detail_notes_container" class="bg-amber-50/80 p-4 sm:p-5 rounded-2xl border border-amber-200/90 space-y-1.5 hidden">
                            <h4 class="font-bold text-amber-900 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <span>💬</span>
                                <span>Pesan / Catatan Khusus dari Pemesan</span>
                            </h4>
                            <p id="detail_customer_notes" class="text-xs text-amber-950 font-semibold italic leading-relaxed"></p>
                        </div>

                        <!-- 2. Paket & Jadwal Trip -->
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Paket Wisata & Waktu Pelaksanaan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <span class="text-slate-400 text-xs block">Paket Wisata</span>
                                    <span id="detail_package_name" class="font-bold text-slate-800 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Tanggal Trip</span>
                                    <span id="detail_trip_date" class="font-bold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Sesi Waktu</span>
                                    <span id="detail_slot_label" class="font-semibold text-slate-700 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-xs block">Armada & Penumpang</span>
                                    <span id="detail_armada_pax" class="font-semibold text-slate-700 block"></span>
                                </div>
                                <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-200/60">
                                    <div>
                                        <span class="text-slate-400 text-xs block">Armada Jeep Ditugaskan</span>
                                        <span id="detail_jeep_name" class="font-bold text-slate-800 block mt-0.5"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-xs block">Driver Paguyuban (Otomatis)</span>
                                        <span id="detail_driver_name" class="font-bold text-emerald-800 block mt-0.5"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Kedatangan & Penjemputan -->
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Logistik Kedatangan Wisatawan
                            </h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-slate-400 text-xs block">Tipe Kedatangan</span>
                                    <span id="detail_arrival_type" class="font-bold text-slate-800 block"></span>
                                </div>
                                <div id="detail_pickup_container" class="hidden">
                                    <span class="text-slate-400 text-xs block">Zona & Alamat Lengkap Penjemputan</span>
                                    <div class="bg-white p-3 rounded-xl border border-slate-200 text-slate-700 mt-1 leading-relaxed">
                                        <span id="detail_pickup_zone" class="font-semibold text-emerald-800 block mb-0.5"></span>
                                        <span id="detail_pickup_address"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Rincian Tagihan & Pembayaran -->
                        <div class="bg-emerald-50/60 p-4 sm:p-5 rounded-2xl border border-emerald-200/80 space-y-3">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-xs uppercase tracking-wider text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Rincian Keuangan & Status Loket
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div>
                                    <span class="text-slate-500 text-xs block">Mode Bayar</span>
                                    <span id="detail_payment_mode" class="font-semibold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Biaya Paket</span>
                                    <span id="detail_package_subtotal" class="font-semibold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Biaya Jemput</span>
                                    <span id="detail_pickup_fee" class="font-semibold text-slate-800 block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Total Tagihan</span>
                                    <span id="detail_total_amount" class="font-black text-slate-900 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Telah Dibayar</span>
                                    <span id="detail_paid_amount" class="font-bold text-emerald-800 text-sm block"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-xs block">Sisa di Loket</span>
                                    <span id="detail_remaining_amount" class="font-black text-rose-600 text-sm block"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Bukti Transfer Pelanggan (Jika Ada) -->
                        <div id="detail_proof_box" class="bg-blue-50/70 p-4 sm:p-5 rounded-2xl border border-blue-200/80 space-y-3 hidden">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-blue-900 flex items-center gap-2 text-xs uppercase tracking-wider">
                                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Bukti Transfer Pelanggan (Bank BPD DIY)
                                </h4>
                                <span id="detail_proof_bank_badge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800"></span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <img id="detail_proof_thumb" src="" alt="Thumbnail Bukti" onclick="showCurrentProofLightbox()" class="w-12 h-12 sm:w-14 sm:h-14 object-cover rounded-xl border border-slate-200 cursor-pointer hover:opacity-80 transition-opacity shrink-0 shadow-xs" title="Klik untuk memperbesar bukti transfer" />
                                    <div>
                                        <span class="text-slate-400 text-xs block">Nama Pengirim (Sesuai Rekening / Struk):</span>
                                        <span id="detail_proof_sender" class="font-bold text-slate-800 text-xs sm:text-sm block"></span>
                                        <span class="text-[11px] text-blue-600 block mt-0.5">Transfer Resmi Bank BPD DIY &bull; <button type="button" onclick="showCurrentProofLightbox()" class="underline font-semibold hover:text-blue-800 cursor-pointer">Klik gambar untuk perbesar</button></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="btnDetailViewProof" onclick="showCurrentProofLightbox()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs hover:shadow transition-all cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Bukti Transfer</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a id="btnWhatsappCustomer" href="#" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                <span>Hubungi Pemesan (WhatsApp)</span>
                            </a>
                            <button type="button" id="btnFooterViewProof" onclick="showCurrentProofLightbox()" class="hidden inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Lihat Bukti Transfer</span>
                            </button>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODAL POPUP LIGHTBOX BUKTI TRANSFER (1 TAB / IN-PAGE PREVIEW) -->
            <div id="modalProofLightbox" onclick="if(event.target === this) closeProofLightbox()" class="fixed inset-0 z-[60] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 hidden transition-all duration-200">
                <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in-95">
                    <!-- Lightbox Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Bukti Transfer Pelanggan</h3>
                                    <span id="proofLightboxCode" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800"></span>
                                </div>
                                <p id="proofLightboxSender" class="text-xs text-slate-500 font-medium mt-0.5"></p>
                            </div>
                        </div>
                        <button type="button" onclick="closeProofLightbox()" class="w-8 h-8 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-xl cursor-pointer" title="Tutup Bukti">&times;</button>
                    </div>

                    <!-- Lightbox Body Image -->
                    <div class="p-4 sm:p-6 bg-slate-900/5 flex items-center justify-center overflow-auto flex-1 min-h-[280px]">
                        <img id="proofLightboxImg" src="" alt="Bukti Transfer Pelanggan" class="max-h-[65vh] w-auto max-w-full object-contain rounded-2xl shadow-md border border-slate-200 bg-white" />
                    </div>

                    <!-- Lightbox Footer -->
                    <div class="p-3.5 sm:p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-2">
                        <span id="proofLightboxBank" class="text-xs font-semibold text-slate-600 px-3 py-1 bg-white border border-slate-200 rounded-lg"></span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="closeProofLightbox()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-colors cursor-pointer">
                                Tutup Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SCRIPTS: DETAIL MODAL, DROPDOWN, & CHART.JS INITIALIZATION -->
            <script>
                // State bukti transfer aktif untuk modal detail
                let currentDetailProof = { url: '', code: '', sender: '', bank: '' };

                // Lightbox Modal Bukti Transfer (1 Tab)
                function openProofLightbox(url, code, sender, bank) {
                    if (!url) return;
                    document.getElementById('proofLightboxImg').src = url;
                    document.getElementById('proofLightboxCode').textContent = code ? ('#' + code) : '';
                    document.getElementById('proofLightboxSender').textContent = sender ? ('Pengirim: ' + sender) : 'Pengirim: Wisatawan';
                    document.getElementById('proofLightboxBank').textContent = bank || 'Transfer Bank BPD DIY';
                    document.getElementById('modalProofLightbox').classList.remove('hidden');
                }

                function closeProofLightbox() {
                    const lightbox = document.getElementById('modalProofLightbox');
                    if (lightbox) lightbox.classList.add('hidden');
                    const img = document.getElementById('proofLightboxImg');
                    if (img) img.src = '';
                }

                function showCurrentProofLightbox() {
                    if (currentDetailProof.url) {
                        openProofLightbox(currentDetailProof.url, currentDetailProof.code, currentDetailProof.sender, currentDetailProof.bank);
                    }
                }

                function openProofFromBtn(btn) {
                    if (!btn) return;
                    openProofLightbox(
                        btn.getAttribute('data-proof-url'),
                        btn.getAttribute('data-booking-code'),
                        btn.getAttribute('data-sender-name'),
                        btn.getAttribute('data-sender-bank')
                    );
                }

                // 1. Modal Booking Detail
                function openBookingDetailModal(source) {
                    let data;
                    try {
                        if (source instanceof HTMLElement) {
                            data = JSON.parse(source.getAttribute('data-booking'));
                        } else if (typeof source === 'string') {
                            data = JSON.parse(source);
                        } else {
                            data = source;
                        }
                    } catch (e) {
                        console.error('Error parsing booking detail data:', e);
                        return;
                    }

                    document.getElementById('detail_booking_code').textContent = data.booking_code;
                    document.getElementById('detail_customer_name').textContent = data.customer_name;
                    document.getElementById('detail_customer_phone').textContent = data.customer_phone;
                    document.getElementById('detail_customer_email').textContent = data.customer_email;
                    document.getElementById('detail_created_at').textContent = data.created_at;
                    document.getElementById('detail_package_name').textContent = data.package_name;
                    document.getElementById('detail_trip_date').textContent = data.trip_date;
                    document.getElementById('detail_slot_label').textContent = data.slot_label;
                    document.getElementById('detail_armada_pax').textContent = data.jeep_quantity + ' Jeep (' + data.passenger_count + ' Orang)';
                    document.getElementById('detail_jeep_name').textContent = data.jeep_name || 'Belum Ditugaskan';
                    document.getElementById('detail_driver_name').textContent = data.driver_name;
                    document.getElementById('detail_arrival_type').textContent = data.arrival_type;

                    // Pesan / Catatan Khusus Pelanggan
                    const notesContainer = document.getElementById('detail_notes_container');
                    if (notesContainer) {
                        if (data.customer_notes && data.customer_notes.trim() !== '') {
                            notesContainer.classList.remove('hidden');
                            document.getElementById('detail_customer_notes').textContent = '"' + data.customer_notes + '"';
                        } else {
                            notesContainer.classList.add('hidden');
                        }
                    }

                    const pickupContainer = document.getElementById('detail_pickup_container');
                    if (data.pickup_address && data.pickup_address !== '-') {
                        pickupContainer.classList.remove('hidden');
                        document.getElementById('detail_pickup_zone').textContent = 'Zona Jemput: ' + data.pickup_zone;
                        document.getElementById('detail_pickup_address').textContent = data.pickup_address;
                    } else {
                        pickupContainer.classList.add('hidden');
                    }

                    document.getElementById('detail_payment_mode').textContent = data.payment_mode;
                    document.getElementById('detail_package_subtotal').textContent = 'Rp ' + data.package_subtotal;
                    document.getElementById('detail_pickup_fee').textContent = 'Rp ' + data.pickup_fee;
                    document.getElementById('detail_total_amount').textContent = 'Rp ' + data.total_amount;
                    document.getElementById('detail_paid_amount').textContent = 'Rp ' + data.paid_amount;
                    document.getElementById('detail_remaining_amount').textContent = 'Rp ' + data.remaining_amount;

                    const badgeContainer = document.getElementById('detail_status_badge');
                    if (data.booking_status === 'FULL_PAID') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">Lunas 100%</span>';
                    } else if (data.booking_status === 'DP_PAID') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">DP Terbayar</span>';
                    } else if (data.booking_status === 'COMPLETED') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A]">Selesai</span>';
                    } else if (data.booking_status === 'CANCELLED') {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEE2E2] text-[#DC2626]">Dibatalkan</span>';
                    } else {
                        badgeContainer.innerHTML = '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#D97706]">Menunggu</span>';
                    }

                    const waBtn = document.getElementById('btnWhatsappCustomer');
                    let phone = (data.customer_phone || '').replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '62' + phone.substring(1);
                    }
                    if (phone) {
                        waBtn.href = 'https://wa.me/' + phone + '?text=' + encodeURIComponent('Halo ' + data.customer_name + ', kami dari Paguyuban Jeep Wukirsari Adventure mengenai reservasi Anda dengan kode booking: ' + data.booking_code);
                        waBtn.classList.remove('hidden');
                    } else {
                        waBtn.classList.add('hidden');
                    }

                    // Handle Bukti Transfer Pelanggan
                    currentDetailProof = {
                        url: data.proof_url || '',
                        code: data.booking_code || '',
                        sender: (data.sender_name || 'Wisatawan') + (data.sender_bank ? ' (' + data.sender_bank + ')' : ''),
                        bank: data.sender_bank || 'Transfer Bank BPD DIY'
                    };

                    const proofBox = document.getElementById('detail_proof_box');
                    const btnFooterViewProof = document.getElementById('btnFooterViewProof');
                    const thumbImg = document.getElementById('detail_proof_thumb');

                    if (data.proof_url) {
                        if (proofBox) proofBox.classList.remove('hidden');
                        if (thumbImg) thumbImg.src = data.proof_url;
                        if (document.getElementById('detail_proof_sender')) {
                            document.getElementById('detail_proof_sender').textContent = currentDetailProof.sender;
                        }
                        if (document.getElementById('detail_proof_bank_badge')) {
                            document.getElementById('detail_proof_bank_badge').textContent = data.sender_bank || 'Transfer Bank BPD DIY';
                        }
                        if (btnFooterViewProof) btnFooterViewProof.classList.remove('hidden');
                    } else {
                        if (proofBox) proofBox.classList.add('hidden');
                        if (thumbImg) thumbImg.src = '';
                        if (btnFooterViewProof) btnFooterViewProof.classList.add('hidden');
                    }

                    document.getElementById('modalBookingDetail').classList.remove('hidden');
                }

                function closeBookingDetailModal() {
                    document.getElementById('modalBookingDetail').classList.add('hidden');
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const lightbox = document.getElementById('modalProofLightbox');
                        if (lightbox && !lightbox.classList.contains('hidden')) {
                            closeProofLightbox();
                            return;
                        }
                        closeBookingDetailModal();
                    }
                });

                // 2. Notification Dropdown Toggle
                function toggleNotificationDropdown() {
                    const dd = document.getElementById('notificationDropdown');
                    if (dd) {
                        dd.classList.toggle('hidden');
                    }
                }

                document.addEventListener('click', function(e) {
                    const dd = document.getElementById('notificationDropdown');
                    const btn = e.target.closest('[onclick="toggleNotificationDropdown()"]');
                    if (dd && !dd.classList.contains('hidden') && !dd.contains(e.target) && !btn) {
                        dd.classList.add('hidden');
                    }
                });

                // 2b. Jam Digital Real-time (Jam, Menit, Detik WIB)
                function updateAdminLiveClock() {
                    const clockEl = document.getElementById('adminLiveClock');
                    if (!clockEl) return;
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
                }
                setInterval(updateAdminLiveClock, 1000);

                // 3. Inisialisasi Chart.js
                document.addEventListener('DOMContentLoaded', function() {
                    const dataEl = document.getElementById('adminDashboardData');
                    let dashboardConfig = {
                        chartDays: ['25 Okt', '26 Okt', '27 Okt', '28 Okt', '29 Okt', '30 Okt', '1 Nov'],
                        chartRevenue: [2200000, 1800000, 1600000, 2400000, 2000000, 2700000, 2100000],
                        chartBookings: [4, 3, 3, 5, 4, 6, 5],
                        paidPercentage: 95,
                        pendingPercentage: 3,
                        cancelPercentage: 2,
                        readyJeepsCount: 13,
                        onTripJeepsCount: 0,
                        maintenanceJeepsCount: 7
                    };

                    if (dataEl && dataEl.textContent) {
                        try {
                            dashboardConfig = Object.assign(dashboardConfig, JSON.parse(dataEl.textContent));
                        } catch (e) {
                            console.error('Error parsing dashboard data:', e);
                        }
                    }

                    // Chart 1: Omzet & Booking (Modern Reactive Area / Bar Chart with Metric & Period Switchers)
                    let mainChart = null;
                    let currentMetric = 'revenue';
                    let currentChartType = 'line';
                    let currentPeriod = '7';

                    function buildOmzetChart() {
                        const ctx = document.getElementById('chartOmzetBooking');
                        if (!ctx) return;

                        if (mainChart) {
                            mainChart.destroy();
                        }

                        const dataPeriod = (dashboardConfig.chartData && dashboardConfig.chartData[currentPeriod])
                            ? dashboardConfig.chartData[currentPeriod]
                            : {
                                days: dashboardConfig.chartDays,
                                revenue: dashboardConfig.chartRevenue,
                                bookings: dashboardConfig.chartBookings
                            };

                        const isRevenue = currentMetric === 'revenue';
                        const labels = dataPeriod.days;
                        const values = isRevenue ? dataPeriod.revenue : dataPeriod.bookings;

                        // Create gradient fill for smooth line/area
                        const context2d = ctx.getContext('2d');
                        let bgFill = '#10B981';
                        let borderColor = '#059669';

                        if (currentChartType === 'line') {
                            const gradient = context2d.createLinearGradient(0, 0, 0, 220);
                            if (isRevenue) {
                                gradient.addColorStop(0, 'rgba(5, 150, 105, 0.35)');
                                gradient.addColorStop(0.8, 'rgba(5, 150, 105, 0.05)');
                                gradient.addColorStop(1, 'rgba(5, 150, 105, 0.0)');
                                borderColor = '#059669';
                            } else {
                                gradient.addColorStop(0, 'rgba(2, 132, 199, 0.35)');
                                gradient.addColorStop(0.8, 'rgba(2, 132, 199, 0.05)');
                                gradient.addColorStop(1, 'rgba(2, 132, 199, 0.0)');
                                borderColor = '#0284c7';
                            }
                            bgFill = gradient;
                        } else {
                            bgFill = isRevenue ? '#10B981' : '#0284c7';
                            borderColor = isRevenue ? '#059669' : '#0369a1';
                        }

                        mainChart = new Chart(ctx, {
                            type: currentChartType,
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: isRevenue ? 'Omzet Penjualan' : 'Jumlah Booking',
                                    data: values,
                                    backgroundColor: bgFill,
                                    borderColor: borderColor,
                                    borderWidth: currentChartType === 'line' ? 2.5 : 1,
                                    fill: currentChartType === 'line',
                                    tension: 0.35,
                                    borderRadius: currentChartType === 'bar' ? 8 : 0,
                                    maxBarThickness: currentPeriod === '30' ? 14 : (currentPeriod === '14' ? 24 : 36),
                                    pointRadius: currentChartType === 'line' ? 3.5 : 0,
                                    pointHoverRadius: currentChartType === 'line' ? 6.5 : 0,
                                    pointBackgroundColor: borderColor,
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 30, 18, 0.95)',
                                        titleFont: { size: 12, weight: 'bold' },
                                        bodyFont: { size: 11 },
                                        padding: 10,
                                        cornerRadius: 10,
                                        callbacks: {
                                            label: function(context) {
                                                if (isRevenue) {
                                                    return 'Omzet: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                                }
                                                return 'Reservasi: ' + context.parsed.y + ' Sesi';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            font: { size: 10 },
                                            color: '#64748b',
                                            maxRotation: currentPeriod === '30' ? 45 : 0,
                                            autoSkip: currentPeriod === '30',
                                            maxTicksLimit: currentPeriod === '30' ? 12 : 14
                                        }
                                    },
                                    y: {
                                        type: 'linear',
                                        beginAtZero: true,
                                        grid: { color: '#f1f5f9' },
                                        ticks: {
                                            font: { size: 10 },
                                            color: '#64748b',
                                            callback: function(val) {
                                                if (isRevenue) {
                                                    if (val >= 1000000) return 'Rp ' + (val / 1000000) + 'jt';
                                                    if (val >= 1000) return 'Rp ' + (val / 1000) + 'rb';
                                                    if (val === 0) return 'Rp 0';
                                                    return val;
                                                }
                                                return Number.isInteger(val) ? (val + ' sesi') : '';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    window.setChartMetric = function(metric) {
                        currentMetric = metric;
                        const btnRev = document.getElementById('btnMetricRevenue');
                        const btnBkg = document.getElementById('btnMetricBookings');
                        const subtitle = document.getElementById('chartSubtitle');

                        if (metric === 'revenue') {
                            btnRev.className = "px-2.5 py-1 rounded-lg bg-white text-emerald-800 shadow-2xs font-bold transition-all cursor-pointer";
                            btnBkg.className = "px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-semibold transition-all cursor-pointer";
                            if (subtitle) subtitle.textContent = "Statistik omzet penjualan harian";
                        } else {
                            btnBkg.className = "px-2.5 py-1 rounded-lg bg-white text-blue-700 shadow-2xs font-bold transition-all cursor-pointer";
                            btnRev.className = "px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 font-semibold transition-all cursor-pointer";
                            if (subtitle) subtitle.textContent = "Statistik volume booking harian";
                        }
                        buildOmzetChart();
                    };

                    window.setChartType = function(type) {
                        currentChartType = type;
                        const btnLine = document.getElementById('btnTypeLine');
                        const btnBar = document.getElementById('btnTypeBar');

                        if (type === 'line') {
                            btnLine.className = "p-1.5 rounded-lg bg-white text-emerald-800 shadow-2xs font-bold transition-all cursor-pointer";
                            btnBar.className = "p-1.5 rounded-lg text-slate-500 hover:text-slate-800 font-semibold transition-all cursor-pointer";
                        } else {
                            btnBar.className = "p-1.5 rounded-lg bg-white text-emerald-800 shadow-2xs font-bold transition-all cursor-pointer";
                            btnLine.className = "p-1.5 rounded-lg text-slate-500 hover:text-slate-800 font-semibold transition-all cursor-pointer";
                        }
                        buildOmzetChart();
                    };

                    window.changeChartPeriod = function(period) {
                        currentPeriod = period;
                        buildOmzetChart();
                    };

                    // Initial build
                    buildOmzetChart();

                    // Chart 2: Status Pembayaran Donut
                    const ctxPayment = document.getElementById('chartPaymentStatus');
                    if (ctxPayment) {
                        new Chart(ctxPayment, {
                            type: 'doughnut',
                            data: {
                                labels: ['Berhasil', 'Menunggu', 'Gagal/Batal'],
                                datasets: [{
                                    data: [
                                        dashboardConfig.paidPercentage,
                                        dashboardConfig.pendingPercentage,
                                        dashboardConfig.cancelPercentage
                                    ],
                                    backgroundColor: ['#10B981', '#F59E0B', '#F87171'],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '76%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return ' ' + context.label + ': ' + context.parsed + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Chart 3: Armada Jeep Donut
                    const ctxJeep = document.getElementById('chartJeepFleet');
                    if (ctxJeep) {
                        new Chart(ctxJeep, {
                            type: 'doughnut',
                            data: {
                                labels: ['Tersedia', 'Digunakan', 'Tidak Tersedia'],
                                datasets: [{
                                    data: [
                                        dashboardConfig.readyJeepsCount,
                                        dashboardConfig.onTripJeepsCount,
                                        dashboardConfig.maintenanceJeepsCount
                                    ],
                                    backgroundColor: ['#10B981', '#F59E0B', '#94A3B8'],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '76%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return ' ' + context.label + ': ' + context.parsed + ' Jeep';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>

            <!-- Chart JSON Payload (Zero syntax conflicts with JS language servers) -->
            <script type="application/json" id="adminDashboardData">
                {!! json_encode([
                    'chartDays' => $chartDays ?? ['25 Okt', '26 Okt', '27 Okt', '28 Okt', '29 Okt', '30 Okt', '1 Nov'],
                    'chartRevenue' => $chartRevenue ?? [2200000, 1800000, 1600000, 2400000, 2000000, 2700000, 2100000],
                    'chartBookings' => $chartBookings ?? [4, 3, 3, 5, 4, 6, 5],
                    'chartData' => $chartData ?? null,
                    'paidPercentage' => (int)($paidPercentage ?? 95),
                    'pendingPercentage' => (int)($pendingPercentage ?? 3),
                    'cancelPercentage' => (int)($cancelPercentage ?? 2),
                    'readyJeepsCount' => (int)($readyJeepsCount ?? 13),
                    'onTripJeepsCount' => (int)($onTripJeepsCount ?? 0),
                    'maintenanceJeepsCount' => (int)($maintenanceJeepsCount ?? 7),
                ]) !!}
            </script>

        </main>
    </div>

</body>
</html>
