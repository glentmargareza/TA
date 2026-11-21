<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi & Aktivitas Operasional | Admin Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-display { font-family: 'Poppins', 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Bersih & Ringkas -->
        <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight font-display">
                    Notifikasi & Aktivitas
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Pemberitahuan transaksi pembayaran, alokasi armada, dan mutasi driver secara real-time.</p>
            </div>
            
            @if($unreadNotificationsCount > 0)
            <div>
                <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-2xs transition-all cursor-pointer active:scale-95">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Tandai Semua Dibaca ({{ $unreadNotificationsCount }})</span>
                    </button>
                </form>
            </div>
            @endif
        </header>

        <main class="p-4 sm:p-8 space-y-4 sm:space-y-5 flex-1 max-w-5xl w-full mx-auto overflow-y-auto">

            @if(session('success'))
            <div class="p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-medium flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold cursor-pointer text-base leading-none">&times;</button>
            </div>
            @endif

            @php
                \Carbon\Carbon::setLocale('id');
                $totalAll = $totalActivities ?? ($activities->total() ?? 0);
                $counts = $typeCounts ?? [];
                $currentType = $type ?? 'ALL';
            @endphp

            <!-- FILTER KATEGORI (SIMPEL & BISA DIGESER DI HP) -->
            <div class="bg-slate-200/70 p-1 rounded-2xl flex items-center gap-1 overflow-x-auto no-scrollbar scroll-smooth">
                <!-- Semua -->
                <a href="{{ route('admin.notifications', ['type' => 'ALL']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'ALL' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Semua</span>
                    <span class="text-[11px] {{ $currentType === 'ALL' ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">{{ $totalAll }}</span>
                </a>

                <!-- Pembayaran Masuk -->
                <a href="{{ route('admin.notifications', ['type' => 'PAYMENT_SUCCESS']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'PAYMENT_SUCCESS' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Pembayaran</span>
                    <span class="text-[11px] {{ $currentType === 'PAYMENT_SUCCESS' ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">{{ $counts['PAYMENT_SUCCESS'] ?? 0 }}</span>
                </a>

                <!-- Penugasan Armada -->
                <a href="{{ route('admin.notifications', ['type' => 'AUTO_ASSIGN']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'AUTO_ASSIGN' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Penugasan Armada</span>
                    <span class="text-[11px] {{ $currentType === 'AUTO_ASSIGN' ? 'text-blue-700 font-bold' : 'text-slate-400' }}">{{ $counts['AUTO_ASSIGN'] ?? 0 }}</span>
                </a>

                <!-- WhatsApp E-Tiket -->
                <a href="{{ route('admin.notifications', ['type' => 'NOTIFICATION_WA']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'NOTIFICATION_WA' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>WhatsApp Tiket</span>
                    <span class="text-[11px] {{ $currentType === 'NOTIFICATION_WA' ? 'text-teal-700 font-bold' : 'text-slate-400' }}">{{ $counts['NOTIFICATION_WA'] ?? 0 }}</span>
                </a>

                <!-- Aktivitas Admin -->
                <a href="{{ route('admin.notifications', ['type' => 'ADMIN_ACTION']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'ADMIN_ACTION' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Aktivitas Admin</span>
                    <span class="text-[11px] {{ $currentType === 'ADMIN_ACTION' ? 'text-amber-700 font-bold' : 'text-slate-400' }}">{{ $counts['ADMIN_ACTION'] ?? 0 }}</span>
                </a>

                <!-- Pesanan Baru -->
                <a href="{{ route('admin.notifications', ['type' => 'BOOKING_NEW']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'BOOKING_NEW' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Pesanan Baru</span>
                    <span class="text-[11px] {{ $currentType === 'BOOKING_NEW' ? 'text-indigo-700 font-bold' : 'text-slate-400' }}">{{ $counts['BOOKING_NEW'] ?? 0 }}</span>
                </a>

                <!-- Pembatalan -->
                <a href="{{ route('admin.notifications', ['type' => 'BOOKING_CANCELLED']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'BOOKING_CANCELLED' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Pembatalan</span>
                    <span class="text-[11px] {{ $currentType === 'BOOKING_CANCELLED' ? 'text-rose-700 font-bold' : 'text-slate-400' }}">{{ $counts['BOOKING_CANCELLED'] ?? 0 }}</span>
                </a>

                <!-- Jadwal Penuh -->
                <a href="{{ route('admin.notifications', ['type' => 'SCHEDULE_FULL']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $currentType === 'SCHEDULE_FULL' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Jadwal Penuh</span>
                    <span class="text-[11px] {{ $currentType === 'SCHEDULE_FULL' ? 'text-orange-700 font-bold' : 'text-slate-400' }}">{{ $counts['SCHEDULE_FULL'] ?? 0 }}</span>
                </a>
            </div>

            <!-- DAFTAR NOTIFIKASI GAYA PESAN LUGAS (SANGAT MUDAH DIBACA) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">

                @php
                    $groupedActivities = $activities->getCollection()->groupBy(function($item) {
                        return $item->created_at->format('Y-m-d');
                    });
                @endphp

                @forelse($groupedActivities as $dateString => $itemsOnDate)
                    @php
                        $carbonDate = \Carbon\Carbon::parse($dateString);
                        if ($carbonDate->isToday()) {
                            $dateTitle = 'Hari Ini — ' . $carbonDate->translatedFormat('d F Y');
                        } elseif ($carbonDate->isYesterday()) {
                            $dateTitle = 'Kemarin — ' . $carbonDate->translatedFormat('d F Y');
                        } else {
                            $dateTitle = $carbonDate->translatedFormat('l, d F Y');
                        }
                    @endphp

                    <!-- Pemisah Tanggal Sederhana -->
                    <div class="px-5 py-2.5 bg-slate-50 border-y border-slate-100 flex items-center justify-between text-xs text-slate-500 font-semibold">
                        <span>{{ $dateTitle }}</span>
                        <span class="text-slate-400 text-[11px] font-normal">{{ $itemsOnDate->count() }} aktivitas</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($itemsOnDate as $act)
                            @php
                                $isUnread = !$act->is_read;
                                
                                // Clean Title (buang emoji mentah)
                                $rawTitle = $act->title ?? '';
                                $cleanTitle = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1F1E0}-\x{1F1FF}]/u', '', $rawTitle));

                                // Booking Code
                                $code = $act->metadata['booking_code'] ?? null;
                                if (!$code && preg_match('/#(WKR-[A-Z0-9]+)/', $act->message, $m)) {
                                    $code = $m[1];
                                }
                                $codeLink = $act->link ?: ($code ? route('admin.bookings', ['search' => $code]) : null);

                                // Customer Name
                                $cust = $act->metadata['customer_name'] ?? null;
                                if (!$cust && preg_match('/atas nama\s+([^.]+)/i', $act->message, $cm)) {
                                    $cust = trim($cm[1]);
                                }
                                if (!$cust && preg_match('/\(([^)]+)\)\.?$/', $act->message, $cm2)) {
                                    $cust = trim($cm2[1]);
                                }

                                // Status mutasi driver
                                $isAktif = str_contains($act->message, 'menjadi AKTIF');
                                $isNonaktif = str_contains($act->message, 'menjadi NONAKTIF');

                                // Kanal pembayaran ringkas
                                $channelRaw = $act->metadata['channel'] ?? '';
                                if (str_contains($channelRaw, 'BPD DIY')) {
                                    if (preg_match('/Bank ([A-Za-z0-9]+)/', $channelRaw, $bm) && $bm[1] !== 'BPD') {
                                        $cleanChannel = "Transfer Bank BPD DIY (" . $bm[1] . ")";
                                    } else {
                                        $cleanChannel = "Transfer Bank BPD DIY";
                                    }
                                } elseif (str_contains($channelRaw, 'Midtrans')) {
                                    $cleanChannel = "Midtrans Payment Gateway";
                                } else {
                                    $cleanChannel = $channelRaw ?: "Transfer Kasir";
                                }
                            @endphp

                            <div class="px-4 sm:px-6 py-3.5 sm:py-4 flex items-start gap-3.5 sm:gap-4 transition-colors {{ $isUnread ? 'bg-emerald-50/20 hover:bg-emerald-50/35' : 'hover:bg-slate-50/80' }}">
                                
                                <!-- Lingkaran Ikon Sederhana -->
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full shrink-0 flex items-center justify-center mt-0.5
                                    @if($act->type === 'PAYMENT_SUCCESS')
                                        bg-emerald-100/70 text-emerald-700
                                    @elseif($act->type === 'AUTO_ASSIGN')
                                        bg-blue-100/70 text-blue-700
                                    @elseif($act->type === 'NOTIFICATION_WA')
                                        bg-teal-100/70 text-teal-700
                                    @elseif($act->type === 'ADMIN_ACTION')
                                        bg-amber-100/70 text-amber-700
                                    @elseif($act->type === 'BOOKING_NEW')
                                        bg-indigo-100/70 text-indigo-700
                                    @elseif($act->type === 'BOOKING_CANCELLED')
                                        bg-rose-100/70 text-rose-700
                                    @else
                                        bg-slate-100 text-slate-600
                                    @endif
                                ">
                                    @if($act->type === 'PAYMENT_SUCCESS')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @elseif($act->type === 'AUTO_ASSIGN')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    @elseif($act->type === 'NOTIFICATION_WA')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    @elseif($act->type === 'ADMIN_ACTION')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @elseif($act->type === 'BOOKING_NEW')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                    @elseif($act->type === 'BOOKING_CANCELLED')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>

                                <!-- Teks Notifikasi yang Mengalir dan Langsung Dipahami -->
                                <div class="flex-1 min-w-0">
                                    
                                    <!-- Baris 1: Judul Inti + Waktu -->
                                    <div class="flex items-baseline justify-between gap-3">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($act->type === 'PAYMENT_SUCCESS')
                                                @php
                                                    $amount = $act->metadata['amount'] ?? 0;
                                                    $isFull = ($act->metadata['booking_status'] ?? 'FULL_PAID') === 'FULL_PAID';
                                                @endphp
                                                <h4 class="text-sm sm:text-base font-bold text-slate-900">
                                                    Pembayaran Rp {{ number_format($amount, 0, ',', '.') }} Diterima
                                                </h4>
                                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                                    {{ $isFull ? 'Lunas' : 'DP' }}
                                                </span>

                                            @elseif($act->type === 'AUTO_ASSIGN')
                                                <h4 class="text-sm sm:text-base font-bold text-slate-900">
                                                    Armada & Driver Ditugaskan
                                                </h4>

                                            @elseif($act->type === 'NOTIFICATION_WA')
                                                <h4 class="text-sm sm:text-base font-bold text-slate-900">
                                                    E-Tiket Terkirim ke WhatsApp
                                                </h4>

                                            @elseif($act->type === 'ADMIN_ACTION')
                                                @php
                                                    preg_match('/driver\s+([^beserta]+)/i', $act->message, $adm);
                                                    $driverName = isset($adm[1]) ? trim($adm[1]) : 'Driver';
                                                @endphp
                                                <h4 class="text-sm sm:text-base font-bold text-slate-900">
                                                    Status Driver {{ $driverName }} Diubah
                                                </h4>

                                            @elseif($act->type === 'BOOKING_NEW')
                                                @php $pkgName = $act->metadata['package_name'] ?? 'Paket Trip'; @endphp
                                                <h4 class="text-sm sm:text-base font-bold text-slate-900">
                                                    Pesanan Baru: {{ $pkgName }}
                                                </h4>

                                            @else
                                                <h4 class="text-sm sm:text-base font-bold text-slate-900">
                                                    {{ $cleanTitle ?: 'Pemberitahuan Sistem' }}
                                                </h4>
                                            @endif

                                            @if($isUnread)
                                                <span class="w-2 h-2 rounded-full bg-emerald-600" title="Belum dibaca"></span>
                                            @endif
                                        </div>

                                        <!-- Jam -->
                                        <span class="text-xs text-slate-400 font-normal shrink-0">
                                            {{ $act->created_at->format('H:i') }} WIB
                                        </span>
                                    </div>

                                    <!-- Baris 2: Kalimat Ringkas dan Lengkap (Semua Data Kunci Langsung Terbaca) -->
                                    <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">
                                        @if($act->type === 'PAYMENT_SUCCESS')
                                            Uang diterima dari <strong class="text-slate-800 font-semibold">{{ $cust ?: 'Wisatawan' }}</strong> via {{ $cleanChannel }} 
                                            @if($code)
                                                untuk booking <a href="{{ $codeLink }}" class="font-mono font-bold text-slate-900 hover:text-emerald-700 hover:underline">#{{ $code }}</a>.
                                            @else
                                                .
                                            @endif

                                        @elseif($act->type === 'AUTO_ASSIGN')
                                            @php
                                                $jeepName = $act->metadata['jeep_name'] ?? 'Jeep Wisata';
                                                $driverName = $act->metadata['driver_name'] ?? 'Driver';
                                            @endphp
                                            Unit <strong class="text-slate-900 font-semibold">{{ $jeepName }}</strong> dan Driver <strong class="text-slate-900 font-semibold">{{ $driverName }}</strong> ditugaskan untuk pesanan <strong class="text-slate-800 font-semibold">{{ $cust ?: '-' }}</strong>
                                            @if($code)
                                                (Booking <a href="{{ $codeLink }}" class="font-mono font-bold text-slate-900 hover:text-blue-700 hover:underline">#{{ $code }}</a>).
                                            @else
                                                .
                                            @endif

                                        @elseif($act->type === 'NOTIFICATION_WA')
                                            @php $phone = $act->metadata['phone'] ?? '-'; @endphp
                                            Tiket QR Code resmi berhasil dikirimkan ke nomor WhatsApp pemesan <strong class="font-mono text-slate-800 font-semibold">{{ $phone }}</strong>
                                            @if($code)
                                                untuk booking <a href="{{ $codeLink }}" class="font-mono font-bold text-slate-900 hover:text-teal-700 hover:underline">#{{ $code }}</a>.
                                            @else
                                                .
                                            @endif

                                        @elseif($act->type === 'ADMIN_ACTION')
                                            @if($isAktif)
                                                Status driver diubah menjadi <strong class="text-emerald-700 font-semibold">AKTIF (Siap Jalan & Unit Ready)</strong> oleh {{ $act->user?->name ?? 'Admin Jeep Wukirsari' }}.
                                            @elseif($isNonaktif)
                                                Status driver diubah menjadi <strong class="text-amber-700 font-semibold">NONAKTIF (Libur / Izin & Servis)</strong> oleh {{ $act->user?->name ?? 'Admin Jeep Wukirsari' }}.
                                            @else
                                                {{ $act->message }}
                                            @endif

                                        @elseif($act->type === 'BOOKING_NEW')
                                            Dipesan oleh <strong class="text-slate-800 font-semibold">{{ $cust ?: 'Wisatawan' }}</strong> sebanyak <strong class="text-slate-800 font-semibold">{{ $act->metadata['jeep_quantity'] ?? 1 }} Unit Jeep</strong>
                                            @if($code)
                                                (Booking <a href="{{ $codeLink }}" class="font-mono font-bold text-slate-900 hover:text-indigo-700 hover:underline">#{{ $code }}</a>).
                                            @else
                                                .
                                            @endif

                                        @else
                                            {{ $act->message }}
                                        @endif
                                    </p>

                                </div>
                            </div>
                        @endforeach
                    </div>

                @empty
                    <!-- Kosong -->
                    <div class="py-12 px-4 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <p class="font-bold text-slate-700 text-xs">Tidak ada notifikasi pada kategori ini</p>
                        @if($currentType !== 'ALL')
                        <div class="mt-3">
                            <a href="{{ route('admin.notifications', ['type' => 'ALL']) }}" class="text-xs font-semibold text-emerald-700 hover:underline">
                                Kembali ke Semua Aktivitas
                            </a>
                        </div>
                        @endif
                    </div>
                @endforelse

                @if($activities->hasPages())
                <div class="p-4 border-t border-slate-100 flex justify-center bg-slate-50/50">
                    {{ $activities->links() }}
                </div>
                @endif
            </div>

        </main>
    </div>

</body>
</html>
