<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi & Aktivitas Operasional | Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    Notifikasi & Riwayat Aktivitas Operasional
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Audit log pemesanan baru, konfirmasi pembayaran, pembatalan, kuota jadwal, dan aktivitas admin.</p>
            </div>
            <div class="flex items-center gap-3">
                @if($unreadNotificationsCount > 0)
                <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs shadow-xs transition-all cursor-pointer">
                        Tandai Semua Dibaca ({{ $unreadNotificationsCount }})
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- FILTER KATEGORI NOTIFIKASI -->
            <div class="bg-white p-3 sm:px-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.notifications', ['type' => 'ALL']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($type ?? 'ALL') === 'ALL' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    Semua Aktivitas
                </a>
                <a href="{{ route('admin.notifications', ['type' => 'BOOKING_NEW']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($type ?? '') === 'BOOKING_NEW' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    🎉 Pesanan Baru
                </a>
                <a href="{{ route('admin.notifications', ['type' => 'PAYMENT_SUCCESS']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($type ?? '') === 'PAYMENT_SUCCESS' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    💰 Pembayaran Masuk
                </a>
                <a href="{{ route('admin.notifications', ['type' => 'BOOKING_CANCELLED']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($type ?? '') === 'BOOKING_CANCELLED' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    ❌ Pembatalan
                </a>
                <a href="{{ route('admin.notifications', ['type' => 'SCHEDULE_FULL']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($type ?? '') === 'SCHEDULE_FULL' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    ⚠️ Jadwal Penuh
                </a>
                <a href="{{ route('admin.notifications', ['type' => 'ADMIN_ACTION']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($type ?? '') === 'ADMIN_ACTION' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    👤 Aktivitas Admin
                </a>
            </div>

            <!-- DAFTAR LOG AKTIVITAS OPERASIONAL -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="divide-y divide-slate-100">
                    @forelse($activities as $act)
                    <div class="p-4 sm:p-5 flex items-start gap-3.5 hover:bg-slate-50/70 transition-colors {{ !$act->is_read ? 'bg-emerald-50/25' : '' }}">
                        <div class="w-10 h-10 rounded-2xl shrink-0 flex items-center justify-center text-white text-sm font-bold shadow-xs
                            {{ $act->type === 'BOOKING_NEW' ? 'bg-emerald-600' : '' }}
                            {{ $act->type === 'PAYMENT_SUCCESS' ? 'bg-blue-600' : '' }}
                            {{ $act->type === 'BOOKING_CANCELLED' ? 'bg-rose-500' : '' }}
                            {{ $act->type === 'SCHEDULE_FULL' ? 'bg-amber-500' : '' }}
                            {{ !in_array($act->type, ['BOOKING_NEW','PAYMENT_SUCCESS','BOOKING_CANCELLED','SCHEDULE_FULL']) ? 'bg-teal-600' : '' }}">
                            @if($act->type === 'BOOKING_NEW') 📋
                            @elseif($act->type === 'PAYMENT_SUCCESS') 💳
                            @elseif($act->type === 'BOOKING_CANCELLED') ✕
                            @elseif($act->type === 'SCHEDULE_FULL') ⚠️
                            @else 🔔
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $act->title }}</h4>
                                    @if(!$act->is_read)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-500 text-white animate-pulse">
                                        BARU
                                    </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium">
                                    {{ $act->created_at->diffForHumans() }} &bull; {{ $act->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">{{ $act->message }}</p>

                            @if(!empty($act->metadata) && is_array($act->metadata))
                            <div class="mt-2.5 flex items-center gap-2 flex-wrap text-xs pt-2 border-t border-slate-100">
                                @if(isset($act->metadata['booking_code']))
                                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono font-bold">
                                    #{{ $act->metadata['booking_code'] }}
                                </span>
                                @endif
                                @if(isset($act->metadata['customer_name']))
                                <span class="text-slate-500">
                                    Pemesan: <strong class="text-slate-700">{{ $act->metadata['customer_name'] }}</strong>
                                </span>
                                @endif
                                @if(isset($act->metadata['amount']))
                                <span class="text-emerald-700 font-bold">
                                    Rp {{ number_format($act->metadata['amount'], 0, ',', '.') }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-400 text-xs">
                        Belum ada data notifikasi atau aktivitas pada kategori ini.
                    </div>
                    @endforelse
                </div>

                @if($activities->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $activities->links() }}
                </div>
                @endif
            </div>

        </main>
    </div>

</body>
</html>

