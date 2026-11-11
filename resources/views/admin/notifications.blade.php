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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2 font-display">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    Notifikasi & Riwayat Aktivitas Operasional
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Audit log pemesanan baru, konfirmasi pembayaran kasir, pembatalan tiket, dan mutasi driver/armada.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                @if($unreadNotificationsCount > 0)
                <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Tandai Semua Dibaca ({{ $unreadNotificationsCount }})</span>
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1 overflow-y-auto">

            @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            @php
                \Carbon\Carbon::setLocale('id');
                $totalAll = $totalActivities ?? ($activities->total() ?? 0);
                $counts = $typeCounts ?? [];
            @endphp

            <!-- FILTER KATEGORI NOTIFIKASI (TAB PILLS) -->
            <div class="bg-white p-3.5 sm:p-4 rounded-3xl border border-slate-200/80 shadow-xs flex flex-wrap items-center gap-2">
                @php $currentType = $type ?? 'ALL'; @endphp
                
                <!-- Semua -->
                <a href="{{ route('admin.notifications', ['type' => 'ALL']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $currentType === 'ALL' ? 'bg-gradient-to-r from-emerald-700 to-[#183c21] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/70' }}">
                    <span>Semua Aktivitas</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $currentType === 'ALL' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $totalAll }}
                    </span>
                </a>

                <!-- Pesanan Baru -->
                <a href="{{ route('admin.notifications', ['type' => 'BOOKING_NEW']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $currentType === 'BOOKING_NEW' ? 'bg-gradient-to-r from-emerald-700 to-[#183c21] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/70' }}">
                    <span>🎉 Pesanan Baru</span>
                    @if(isset($counts['BOOKING_NEW']) && $counts['BOOKING_NEW'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $currentType === 'BOOKING_NEW' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['BOOKING_NEW'] }}
                    </span>
                    @endif
                </a>

                <!-- Pembayaran Masuk -->
                <a href="{{ route('admin.notifications', ['type' => 'PAYMENT_SUCCESS']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $currentType === 'PAYMENT_SUCCESS' ? 'bg-gradient-to-r from-emerald-700 to-[#183c21] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/70' }}">
                    <span>💳 Pembayaran Masuk</span>
                    @if(isset($counts['PAYMENT_SUCCESS']) && $counts['PAYMENT_SUCCESS'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $currentType === 'PAYMENT_SUCCESS' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['PAYMENT_SUCCESS'] }}
                    </span>
                    @endif
                </a>

                <!-- Pembatalan -->
                <a href="{{ route('admin.notifications', ['type' => 'BOOKING_CANCELLED']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $currentType === 'BOOKING_CANCELLED' ? 'bg-gradient-to-r from-emerald-700 to-[#183c21] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/70' }}">
                    <span>✕ Pembatalan</span>
                    @if(isset($counts['BOOKING_CANCELLED']) && $counts['BOOKING_CANCELLED'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $currentType === 'BOOKING_CANCELLED' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['BOOKING_CANCELLED'] }}
                    </span>
                    @endif
                </a>

                <!-- Jadwal Penuh -->
                <a href="{{ route('admin.notifications', ['type' => 'SCHEDULE_FULL']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $currentType === 'SCHEDULE_FULL' ? 'bg-gradient-to-r from-emerald-700 to-[#183c21] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/70' }}">
                    <span>⚠️ Jadwal Penuh</span>
                    @if(isset($counts['SCHEDULE_FULL']) && $counts['SCHEDULE_FULL'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $currentType === 'SCHEDULE_FULL' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['SCHEDULE_FULL'] }}
                    </span>
                    @endif
                </a>

                <!-- Aktivitas Admin -->
                <a href="{{ route('admin.notifications', ['type' => 'ADMIN_ACTION']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ $currentType === 'ADMIN_ACTION' ? 'bg-gradient-to-r from-emerald-700 to-[#183c21] text-white shadow-xs' : 'bg-slate-100/80 text-slate-600 hover:bg-slate-200/70' }}">
                    <span>👤 Aktivitas Admin</span>
                    @if(isset($counts['ADMIN_ACTION']) && $counts['ADMIN_ACTION'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $currentType === 'ADMIN_ACTION' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['ADMIN_ACTION'] }}
                    </span>
                    @endif
                </a>
            </div>

            <!-- DAFTAR LOG AKTIVITAS OPERASIONAL -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 font-display tracking-tight">Daftar Riwayat & Audit Log</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Menampilkan log sistem operasional, mutasi pangkalan, dan riwayat pesanan.</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $activities->total() }} Log Terdaftar
                        </span>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($activities as $act)
                        @php
                            $titleLower = strtolower($act->title);
                            $msgLower = strtolower($act->message);
                            $isDelete = str_contains($titleLower, 'hapus') || str_contains($msgLower, 'menghapus');
                            $isStatus = str_contains($titleLower, 'status') || str_contains($msgLower, 'mengubah status');
                            $isAdd = str_contains($titleLower, 'tambah') || str_contains($titleLower, 'penambahan');
                            $isSettle = str_contains($titleLower, 'pelunasan');
                        @endphp
                        <div class="p-5 sm:p-6 flex items-start gap-4 transition-all {{ !$act->is_read ? 'bg-emerald-50/40 hover:bg-emerald-50/60' : 'hover:bg-slate-50/80' }}">
                            
                            <!-- Smart Icon Box -->
                            <div class="w-12 h-12 rounded-2xl shrink-0 flex items-center justify-center shadow-xs border
                                @if($act->type === 'BOOKING_NEW')
                                    bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($act->type === 'PAYMENT_SUCCESS')
                                    bg-blue-50 text-blue-700 border-blue-200
                                @elseif($act->type === 'BOOKING_CANCELLED')
                                    bg-rose-50 text-rose-600 border-rose-200
                                @elseif($act->type === 'SCHEDULE_FULL')
                                    bg-amber-50 text-amber-700 border-amber-200
                                @elseif($isDelete)
                                    bg-rose-50 text-rose-600 border-rose-200
                                @elseif($isStatus)
                                    bg-amber-50 text-amber-700 border-amber-200
                                @elseif($isAdd)
                                    bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($isSettle)
                                    bg-teal-50 text-teal-700 border-teal-200
                                @else
                                    bg-slate-100 text-slate-700 border-slate-200
                                @endif
                            ">
                                @if($act->type === 'BOOKING_NEW')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                @elseif($act->type === 'PAYMENT_SUCCESS')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                @elseif($act->type === 'BOOKING_CANCELLED')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @elseif($act->type === 'SCHEDULE_FULL')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                @elseif($isDelete)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                @elseif($isStatus)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                @elseif($isAdd)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                @elseif($isSettle)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>

                            <!-- Notification Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-extrabold text-slate-900 text-sm font-display tracking-tight">{{ $act->title }}</h4>
                                        @if(!$act->is_read)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white shadow-2xs animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                            <span>BARU</span>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="inline-flex items-center gap-1.5 text-[11px] text-slate-500 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $act->created_at->locale('id')->diffForHumans() }}</span>
                                        <span class="text-slate-300">&bull;</span>
                                        <span>{{ $act->created_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed font-normal">{{ $act->message }}</p>

                                <!-- Metadata & Actions -->
                                @if(!empty($act->metadata) && is_array($act->metadata))
                                <div class="mt-3 flex items-center justify-between gap-2 flex-wrap pt-2.5 border-t border-slate-100">
                                    <div class="flex items-center gap-2 flex-wrap text-xs">
                                        @if(isset($act->metadata['booking_code']))
                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-mono font-bold border border-emerald-100 text-xs">
                                            #{{ $act->metadata['booking_code'] }}
                                        </span>
                                        @endif
                                        @if(isset($act->metadata['customer_name']))
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium text-xs">
                                            Pemesan: <strong class="text-slate-900 font-bold">{{ $act->metadata['customer_name'] }}</strong>
                                        </span>
                                        @endif
                                        @if(isset($act->metadata['amount']))
                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-mono font-extrabold border border-emerald-100 text-xs">
                                            Rp {{ number_format($act->metadata['amount'], 0, ',', '.') }}
                                        </span>
                                        @endif
                                        @if(isset($act->metadata['channel']))
                                        <span class="px-2 py-1 rounded-lg bg-slate-50 text-slate-500 text-[11px] font-medium border border-slate-200">
                                            {{ $act->metadata['channel'] }}
                                        </span>
                                        @endif
                                    </div>

                                    @if(!empty($act->link))
                                    <a href="{{ $act->link }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition-colors">
                                        <span>Lihat Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                    <div class="p-16 text-center text-slate-400">
                        <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <p class="font-bold text-slate-700 text-sm">Tidak ada notifikasi atau aktivitas</p>
                        <p class="text-xs text-slate-500 mt-1">Belum ada riwayat aktivitas yang tercatat pada filter kategori ini.</p>
                    </div>
                    @endforelse
                </div>

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
