<x-admin-layout title="Dashboard & Monitoring Operasional | Jeep Wukirsari">
    <x-slot:head>
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot:head>
        
        <!-- TOP SCENIC HEADER BANNER (Matching Mockup with Bright Scenic Landscape) -->
        <div class="relative bg-[#E8EFEA] border-b border-slate-200/80 shadow-xs z-30">
            <!-- Background Image with Soft Fade -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <img src="{{ $heroBg ?? asset('img/beranda.jpeg') }}" alt="Merapi" class="w-full h-full object-cover object-right opacity-45 mix-blend-multiply">
            </div>
            <!-- Soft White/Cream to Transparent Gradient so left texts are crisp -->
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/85 to-transparent pointer-events-none"></div>

            <div class="relative z-10 px-4 sm:px-8 py-5 sm:py-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Left: Title & Subtitle -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1 font-medium">Selamat datang, Admin! Berikut informasi terbaru hari ini.</p>
                </div>

                <!-- Right: Header Quick Controls (Date, Notifications, Avatar) -->
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
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
                                        @if($notif->icon_type === 'booking')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        @elseif($notif->icon_type === 'payment')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @elseif($notif->icon_type === 'cancel')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @elseif($notif->icon_type === 'schedule')
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
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
            <x-alert type="success" :message="session('success')" />
            @endif

            <!-- ROW 1: 4 METRIC CARDS (Persis Gambar Referensi Atas - Data Dinamis Riil) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- 1. Total Booking Hari Ini -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            
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
                            
                            <div class="min-w-0 flex-1">
                                <span class="block text-xs font-bold text-emerald-950 leading-tight">Total Armada: {{ $totalJeepsCount }} Jeep ({{ $readyJeepsCount }} Unit Aktif)</span>
                                
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
                                
                                <h3 class="text-sm font-bold text-slate-800">Daftar Booking Terbaru</h3>
                            </div>
                            <a href="{{ route('admin.bookings') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-950 inline-flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs min-w-[640px]">
                                <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                                    <tr>
                                        <th class="py-3 px-4 text-center whitespace-nowrap">Aksi</th>
                                        <th class="py-3 px-4">Kode Booking</th>
                                        <th class="py-3 px-4">Nama Pemesan</th>
                                        <th class="py-3 px-4">Paket Wisata</th>
                                        <th class="py-3 px-4">Tanggal</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentBookings as $rb)
                                    @php
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
                                            'is_pickup' => strtolower((string)$rb->arrival_type) === 'pickup',
                                            'arrival_type' => strtolower((string)$rb->arrival_type) === 'pickup' ? 'Jemput di Hotel / Alamat' : 'Datang Sendiri ke Basecamp',
                                            'pickup_zone' => $rb->pickupZone?->name ?? ($rb->pickup_distance_km ? 'Antar-Jemput (' . number_format($rb->pickup_distance_km, 1) . ' km)' : 'Layanan Antar-Jemput'),
                                            'pickup_address' => $rb->pickup_address ?? '-',
                                            'payment_mode' => $rb->payment_mode === 'DP' ? 'Uang Muka (DP 30%)' : 'Pembayaran Penuh (100%)',
                                            'payment_channel' => $rb->payment_channel,
                                            'package_subtotal' => number_format($rb->package_subtotal, 0, ',', '.'),
                                            'pickup_fee' => number_format($rb->pickup_fee, 0, ',', '.'),
                                            'maps_link' => ($rb->pickup_address && $rb->pickup_address !== '-') ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($rb->pickup_address) : null,
                                            'total_amount' => number_format($rb->total_amount, 0, ',', '.'),
                                            'paid_amount' => number_format($rb->paid_amount, 0, ',', '.'),
                                            'remaining_amount' => number_format($rb->remaining_amount, 0, ',', '.'),
                                            'booking_status' => $rb->booking_status,
                                            'created_at' => $rb->created_at ? $rb->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-',
                                            'jeep_name' => $rb->assignments->first()?->jeep?->model_name ?? 'Belum Ditugaskan',
                                            'driver_name' => $rb->assignments->first()?->driver?->full_name ?? 'Dialokasikan Otomatis',
                                            'customer_notes' => $rb->customer_notes ?? '',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" data-booking='@json($rbDetail)' onclick="openBookingDetailModal(this)" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 shadow-2xs transition-all cursor-pointer" title="Lihat Detail Pesanan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                            {{ $rb->booking_code }}
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span>{{ $rb->customer_name }}</span>
                                                @if(strtolower((string)$rb->arrival_type) === 'pickup')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="{{ $rb->pickup_address }}">
                                                    <svg class="w-2.5 h-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Pickup
                                                </span>
                                                @endif
                                            </div>
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
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <p class="text-xs font-semibold text-slate-500">Belum ada booking terbaru</p>
                                                <p class="text-[11px] text-slate-400 mt-0.5">Semua reservasi booking yang masuk akan ditampilkan di sini.</p>
                                            </div>
                                        </td>
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
                                
                                <h3 class="text-sm font-bold text-slate-800">Aktivitas Terbaru</h3>
                            </div>
                            <a href="{{ route('admin.notifications') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-950 inline-flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <!-- Connected Vertical Timeline (Matching Reference) -->
                        <div class="relative {{ $recentActivities->isNotEmpty() ? 'pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200' : '' }}">
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
                            <div class="py-8 text-center text-slate-400">
                                <svg class="w-7 h-7 mx-auto mb-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs font-semibold text-slate-500">Belum ada aktivitas terbaru</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Aktivitas reservasi dan sistem akan muncul di sini.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <!-- MODAL DETAIL LENGKAP INFORMASI PEMESAN (CLEAN MINIMALIST SHEET) -->
            <div id="modalBookingDetail" onclick="if(event.target === this) closeBookingDetailModal()" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6 hidden transition-opacity duration-200">
                <div class="bg-white rounded-2xl max-w-xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden text-slate-800">
                    
                    <!-- Header: Bersih & Tenang -->
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs text-slate-400 font-medium">Reservasi</span>
                                <span id="detail_booking_code" class="font-mono font-bold text-slate-900 text-base"></span>
                                <button type="button" onclick="copyDetailText('detail_booking_code', this)" class="text-slate-400 hover:text-emerald-600 transition p-1" title="Salin Kode">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <span id="detail_status_badge"></span>
                            </div>
                            <p id="detail_created_at" class="text-[11px] text-slate-400 mt-0.5"></p>
                        </div>
                        <button type="button" onclick="closeBookingDetailModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center text-xl transition cursor-pointer">&times;</button>
                    </div>

                    <!-- Modal Body (Bersih, Berjarak Nyaman, Mudah Dipindai) -->
                    <div class="p-6 space-y-5 overflow-y-auto">

                        <!-- 1. Kontak & Pemesan -->
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Pemesan</h4>
                            <div class="bg-slate-50/70 rounded-xl p-3.5 border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div>
                                        <span id="detail_customer_name" class="font-bold text-slate-900 text-sm sm:text-base block"></span>
                                        <span id="detail_customer_email" class="text-xs text-slate-500 block"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span id="detail_customer_phone" class="font-mono text-xs font-semibold text-slate-700 bg-white px-2.5 py-1 rounded-lg border border-slate-200"></span>
                                        <button type="button" onclick="copyDetailText('detail_customer_phone', this)" class="text-slate-400 hover:text-emerald-600 text-xs p-1" title="Salin HP">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                        <a id="btnQuickWa" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs transition">
                                            <span>WhatsApp</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Catatan Wisatawan (Hanya tampil jika ada) -->
                                <div id="detail_notes_container" class="pt-2 border-t border-slate-200/60 hidden">
                                    <span class="text-[10px] text-amber-800 font-bold block uppercase tracking-wider">Catatan Pemesan:</span>
                                    <p id="detail_customer_notes" class="text-xs text-slate-700 italic mt-0.5"></p>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Jadwal & Penugasan Armada -->
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Trip & Armada</h4>
                            <div class="border border-slate-100 rounded-xl overflow-hidden divide-y divide-slate-100 text-xs">
                                <div class="flex items-center justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Paket Wisata</span>
                                    <span id="detail_package_name" class="font-bold text-slate-900 text-right"></span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-slate-50/50">
                                    <span class="text-slate-400 font-medium">Jadwal & Sesi</span>
                                    <div class="text-right">
                                        <span id="detail_trip_date" class="font-semibold text-slate-800 block"></span>
                                        <span id="detail_slot_label" class="text-[11px] text-slate-500 block"></span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Armada & Kapasitas</span>
                                    <span id="detail_armada_pax" class="font-medium text-slate-800 text-right"></span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-slate-50/50">
                                    <span class="text-slate-400 font-medium">Armada Jeep Ditugaskan</span>
                                    <span id="detail_jeep_name" class="font-semibold text-slate-900 text-right"></span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Driver Paguyuban</span>
                                    <span id="detail_driver_name" class="font-bold text-emerald-800 text-right"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Titik Kumpul / Kedatangan -->
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Lokasi Kedatangan</h4>
                            <span id="detail_arrival_type" class="hidden"></span>

                            <!-- Kondisi A: Antar-Jemput -->
                            <div id="detail_pickup_container" class="hidden p-3.5 bg-amber-50/70 rounded-xl border border-amber-200/80 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-amber-950">Layanan Antar-Jemput (Pickup Hotel / Alamat)</span>
                                    <a id="detail_pickup_maps_btn" href="#" target="_blank" rel="noopener noreferrer" class="hidden font-semibold text-emerald-700 hover:text-emerald-900 text-xs hover:underline">
                                        Buka Google Maps &rarr;
                                    </a>
                                </div>
                                <p class="text-slate-700">
                                    <strong id="detail_pickup_zone" class="text-slate-900 font-semibold"></strong> &bull; <span id="detail_pickup_address"></span>
                                </p>
                            </div>

                            <!-- Kondisi B: Datang Sendiri ke Basecamp -->
                            <div id="detail_basecamp_container" class="hidden p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">Datang Sendiri ke Basecamp Paguyuban</span>
                                    <a href="https://maps.google.com/?q=Basecamp+Jeep+Wukirsari+Adventure+Cangkringan+Sleman" target="_blank" rel="noopener noreferrer" class="font-semibold text-emerald-700 hover:text-emerald-900 text-xs hover:underline">
                                        Buka Rute Basecamp &rarr;
                                    </a>
                                </div>
                                <p class="text-slate-500">
                                    Basecamp Jeep Wukirsari: Dusun Pusung, Wukirsari, Cangkringan, Sleman. Wisatawan berkumpul langsung di meeting point.
                                </p>
                            </div>
                        </div>

                        <!-- 4. Rincian Tagihan & Kasir Loket -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pembayaran & Status Kasir</h4>
                                <span id="detail_payment_mode_badge" class="text-[11px] font-medium text-slate-500"></span>
                            </div>
                            
                            <div class="border border-slate-100 rounded-xl overflow-hidden divide-y divide-slate-100 text-xs">
                                <div class="flex justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Biaya Paket</span>
                                    <span id="detail_package_subtotal" class="font-medium text-slate-700"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-white">
                                    <span class="text-slate-400 font-medium">Biaya Antar-Jemput</span>
                                    <span id="detail_pickup_fee" class="font-medium text-slate-700"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-slate-50/70 font-semibold">
                                    <span class="text-slate-700">Total Tagihan</span>
                                    <span id="detail_total_amount" class="text-slate-900 text-sm font-bold"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-white items-center">
                                    <span class="text-slate-500 font-medium">Metode / Saluran Bayar</span>
                                    <span id="detail_payment_channel" class="font-bold text-slate-800 text-xs bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200"></span>
                                </div>
                                <div class="flex justify-between p-3 bg-emerald-50/40">
                                    <span class="text-emerald-800 font-medium">Telah Dibayar (Transfer)</span>
                                    <span id="detail_paid_amount" class="text-emerald-700 font-bold text-sm"></span>
                                </div>
                                <div id="detail_remaining_box" class="flex justify-between p-3.5 items-center">
                                    <div>
                                        <span id="detail_remaining_label" class="block font-bold"></span>
                                        <span id="detail_remaining_sublabel" class="text-[10px] block mt-0.5"></span>
                                    </div>
                                    <span id="detail_remaining_amount" class="text-base font-extrabold"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer: Bersih & Fungsional -->
                    <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
                        <a id="btnWhatsappCustomer" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs transition">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Hubungi via WhatsApp</span>
                        </a>
                        <button type="button" onclick="closeBookingDetailModal()" class="px-4 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs transition cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- SCRIPTS: DETAIL MODAL, DROPDOWN, & CHART.JS INITIALIZATION -->
            <script>
                // Salin teks ke clipboard helper
                function copyDetailText(elementId, btn) {
                    const el = document.getElementById(elementId);
                    if (!el) return;
                    const text = el.textContent || el.innerText;
                    if (!navigator.clipboard) {
                        return;
                    }
                    navigator.clipboard.writeText(text.trim()).then(() => {
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                        btn.classList.add('text-emerald-700');
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('text-emerald-700');
                        }, 1400);
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                    });
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

                    // Identitas Pemesan
                    document.getElementById('detail_booking_code').textContent = data.booking_code;
                    document.getElementById('detail_customer_name').textContent = data.customer_name;
                    document.getElementById('detail_customer_phone').textContent = data.customer_phone;
                    document.getElementById('detail_customer_email').textContent = (data.customer_email && data.customer_email !== '-') ? data.customer_email : 'Tidak ada email';
                    document.getElementById('detail_created_at').textContent = 'Dipesan pada ' + (data.created_at || '-');

                    const initialEl = document.getElementById('detail_customer_initial');
                    if (initialEl) {
                        initialEl.textContent = (data.customer_name || 'W').trim().charAt(0).toUpperCase();
                    }

                    // Catatan Khusus Wisatawan
                    const notesContainer = document.getElementById('detail_notes_container');
                    if (notesContainer) {
                        if (data.customer_notes && data.customer_notes.trim() !== '' && data.customer_notes !== '-') {
                            notesContainer.classList.remove('hidden');
                            document.getElementById('detail_customer_notes').textContent = '"' + data.customer_notes + '"';
                        } else {
                            notesContainer.classList.add('hidden');
                        }
                    }

                    // Paket & Jadwal Pelaksanaan
                    document.getElementById('detail_package_name').textContent = data.package_name;
                    document.getElementById('detail_trip_date').textContent = data.trip_date;
                    document.getElementById('detail_slot_label').textContent = data.slot_label;
                    document.getElementById('detail_armada_pax').textContent = (data.jeep_quantity || 1) + ' Unit Jeep • ' + (data.passenger_count || 4) + ' Orang Penumpang';
                    document.getElementById('detail_jeep_name').textContent = data.jeep_name || 'Belum Ditugaskan';
                    document.getElementById('detail_driver_name').textContent = data.driver_name || 'Dialokasikan Otomatis';

                    // Logistik Kedatangan (Basecamp vs Pickup)
                    const isPickup = Boolean(data.is_pickup);
                    const pickupContainer = document.getElementById('detail_pickup_container');
                    const basecampContainer = document.getElementById('detail_basecamp_container');
                    const mapsBtn = document.getElementById('detail_pickup_maps_btn');

                    if (isPickup) {
                        if (pickupContainer) pickupContainer.classList.remove('hidden');
                        if (basecampContainer) basecampContainer.classList.add('hidden');

                        const zoneEl = document.getElementById('detail_pickup_zone');
                        if (zoneEl) zoneEl.textContent = data.pickup_zone || 'Layanan Antar-Jemput';

                        const addrEl = document.getElementById('detail_pickup_address');
                        if (addrEl) addrEl.textContent = (data.pickup_address && data.pickup_address !== '-') ? data.pickup_address : 'Alamat hotel / villa belum diisi';

                        if (mapsBtn) {
                            if (data.maps_link) {
                                mapsBtn.href = data.maps_link;
                                mapsBtn.classList.remove('hidden');
                            } else {
                                mapsBtn.classList.add('hidden');
                            }
                        }
                    } else {
                        if (pickupContainer) pickupContainer.classList.add('hidden');
                        if (basecampContainer) basecampContainer.classList.remove('hidden');
                    }

                    // Rincian Pembayaran & Status Kasir
                    const modeBadge = document.getElementById('detail_payment_mode_badge');
                    if (modeBadge) {
                        modeBadge.textContent = data.payment_mode || 'Pembayaran Penuh';
                    }
                    document.getElementById('detail_package_subtotal').textContent = 'Rp ' + (data.package_subtotal || '0');
                    document.getElementById('detail_pickup_fee').textContent = 'Rp ' + (data.pickup_fee || '0');
                    document.getElementById('detail_total_amount').textContent = 'Rp ' + (data.total_amount || '0');
                    document.getElementById('detail_paid_amount').textContent = 'Rp ' + (data.paid_amount || '0');
                    const channelEl = document.getElementById('detail_payment_channel');
                    if (channelEl) {
                        channelEl.textContent = data.payment_channel || 'Transfer Bank / Midtrans';
                    }

                    // Card Sisa Pelunasan di Loket
                    const remainingBox = document.getElementById('detail_remaining_box');
                    const remainingLabel = document.getElementById('detail_remaining_label');
                    const remainingAmount = document.getElementById('detail_remaining_amount');
                    const remainingSublabel = document.getElementById('detail_remaining_sublabel');

                    const cleanRemaining = String(data.remaining_amount || '').replace(/[^0-9]/g, '');
                    const isFullyPaid = cleanRemaining === '0' || cleanRemaining === '' || data.booking_status === 'FULL_PAID';

                    if (isFullyPaid) {
                        if (remainingBox) {
                            remainingBox.className = 'flex justify-between p-3.5 items-center bg-emerald-50 text-emerald-950 border-t border-emerald-100';
                        }
                        if (remainingLabel) {
                            remainingLabel.className = 'block font-bold text-xs text-emerald-900';
                            remainingLabel.textContent = 'Sisa Pelunasan di Loket';
                        }
                        if (remainingAmount) {
                            remainingAmount.className = 'text-base font-black text-emerald-700';
                            remainingAmount.textContent = 'Rp 0';
                        }
                        if (remainingSublabel) {
                            remainingSublabel.className = 'text-[11px] text-emerald-700 block mt-0.5';
                            remainingSublabel.textContent = 'Lunas 100% (Tidak ada tagihan loket)';
                        }
                    } else {
                        if (remainingBox) {
                            remainingBox.className = 'flex justify-between p-3.5 items-center bg-rose-50 text-rose-950 border-t border-rose-200';
                        }
                        if (remainingLabel) {
                            remainingLabel.className = 'block font-bold text-xs text-rose-900';
                            remainingLabel.textContent = 'Sisa Wajib Ditagih di Loket';
                        }
                        if (remainingAmount) {
                            remainingAmount.className = 'text-base font-black text-rose-700';
                            remainingAmount.textContent = 'Rp ' + data.remaining_amount;
                        }
                        if (remainingSublabel) {
                            remainingSublabel.className = 'text-[11px] text-rose-600 block mt-0.5 font-medium';
                            remainingSublabel.textContent = 'Wajib ditagih sebelum tour berangkat';
                        }
                    }

                    // Status Badge
                    const badgeContainer = document.getElementById('detail_status_badge');
                    if (badgeContainer) {
                        if (data.booking_status === 'FULL_PAID') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Lunas 100%</span>';
                        } else if (data.booking_status === 'DP_PAID') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">DP Terbayar</span>';
                        } else if (data.booking_status === 'COMPLETED') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Trip Selesai</span>';
                        } else if (data.booking_status === 'CANCELLED') {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Dibatalkan</span>';
                        } else {
                            badgeContainer.innerHTML = '<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">Menunggu</span>';
                        }
                    }

                    // Tombol Hubungi WhatsApp
                    const waBtn = document.getElementById('btnWhatsappCustomer');
                    const quickWaBtn = document.getElementById('btnQuickWa');
                    let phone = (data.customer_phone || '').replace(/[^0-9]/g, '');
                    if (phone.startsWith('0')) {
                        phone = '62' + phone.substring(1);
                    }
                    if (phone) {
                        const waUrl = 'https://wa.me/' + phone + '?text=' + encodeURIComponent('Halo Kak ' + data.customer_name + ', kami dari Paguyuban Jeep Wukirsari Adventure mengenai reservasi Anda dengan kode booking: ' + data.booking_code);
                        if (waBtn) {
                            waBtn.href = waUrl;
                            waBtn.classList.remove('hidden');
                        }
                        if (quickWaBtn) {
                            quickWaBtn.href = waUrl;
                            quickWaBtn.classList.remove('hidden');
                        }
                    } else {
                        if (waBtn) waBtn.classList.add('hidden');
                        if (quickWaBtn) quickWaBtn.classList.add('hidden');
                    }

                    document.getElementById('modalBookingDetail').classList.remove('hidden');
                }

                function closeBookingDetailModal() {
                    document.getElementById('modalBookingDetail').classList.add('hidden');
                }

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
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
</x-admin-layout>
