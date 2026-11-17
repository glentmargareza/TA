<!-- Sidebar Admin -->
<aside class="w-full md:w-64 bg-gradient-to-b from-[#143B1E] via-[#0E2C15] to-[#07180B] text-white p-5 flex flex-col justify-between shrink-0 shadow-2xl border-r border-[#1e4e2a]/80 relative overflow-hidden select-none">
    <!-- Ambient subtle background lighting -->
    <div class="absolute -top-24 -left-24 w-56 h-56 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -right-24 w-56 h-56 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 left-10 w-48 h-48 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10">
        <!-- Logo & Brand Header -->
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center text-center pb-5 border-b border-white/10 mb-5 group">
            <div class="relative w-16 h-16 rounded-2xl bg-white/10 p-2.5 border border-white/20 shadow-md backdrop-blur-md flex items-center justify-center mb-2.5 group-hover:scale-105 transition-all duration-200">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain filter drop-shadow">
            </div>
            <span class="block font-black text-sm text-white tracking-widest leading-tight font-display group-hover:text-emerald-200 transition-colors">
                JEEP WUKIRSARI
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-widest mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                ADMIN LOKET
            </span>
        </a>

        <!-- Menu Navigasi Admin -->
        <nav class="space-y-1.5">
            <!-- 1. Dashboard & Omzet -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard & Omzet</span>
                </div>
            </a>

            <!-- 2. Manajemen Booking -->
            <a href="{{ route('admin.bookings') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.bookings*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.bookings*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Manajemen Booking</span>
                </div>
            </a>

            <!-- 3. Monitoring Armada -->
            <a href="{{ route('admin.armada') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.armada*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.armada*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/></svg>
                    <span>Monitoring Armada</span>
                </div>
            </a>

            <!-- 4. Kelola Paket Wisata -->
            <a href="{{ route('admin.packages') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.packages*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.packages*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Kelola Paket Wisata</span>
                </div>
            </a>

            <!-- 5. Kelola Jadwal Sesi -->
            <a href="{{ route('admin.schedules') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.schedules*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.schedules*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Kelola Jadwal Sesi</span>
                </div>
            </a>

            <!-- 6. Laporan Penjualan -->
            <a href="{{ route('admin.reports') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.reports*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.reports*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Laporan Penjualan</span>
                </div>
            </a>

            <!-- 7. Manajemen Ulasan -->
            <a href="{{ route('admin.reviews') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.reviews*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.reviews*') ? 'text-yellow-300' : 'text-yellow-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>Manajemen Ulasan</span>
                </div>
            </a>

            <!-- 8. Notifikasi -->
            @php
                $sidebarUnreadCount = $unreadNotificationsCount ?? \App\Models\ActivityLog::unread()->count();
            @endphp
            <a href="{{ route('admin.notifications') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.notifications*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.notifications*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>Notifikasi</span>
                </div>
                @if($sidebarUnreadCount > 0)
                <span class="px-2 py-0.5 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black shadow-xs animate-pulse">
                    {{ $sidebarUnreadCount }}
                </span>
                @endif
            </a>

            <!-- 9. Pengaturan -->
            <a href="{{ route('admin.settings') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600/90 to-emerald-700/80 text-white font-extrabold shadow-md shadow-emerald-950/60 border border-emerald-400/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.settings*') ? 'text-white' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan</span>
                </div>
            </a>

            @php
                $sidebarCandFile = database_path('hotel_kandidat.json');
                $sidebarCandCount = file_exists($sidebarCandFile) ? count(json_decode(file_get_contents($sidebarCandFile), true) ?: []) : 0;
            @endphp

            <!-- 10. Kandidat Hotel Baru -->
            <a href="{{ route('admin.hotel_candidates') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-150 {{ request()->routeIs('admin.hotel_candidates*') ? 'bg-[#1D5E30] text-white font-bold shadow-md shadow-emerald-950/40' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Kandidat Hotel</span>
                </div>
                @if($sidebarCandCount > 0)
                <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-bold shadow-xs">
                    {{ $sidebarCandCount }}
                </span>
                @endif
            </a>
        </nav>
    </div>

    <!-- Profil & Logout (Footer Sidebar) -->
    <div class="relative z-10 pt-4 border-t border-white/10 mt-6 space-y-2.5">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
            <div class="w-8 h-8 rounded-lg bg-emerald-600/60 border border-emerald-400/40 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-xs">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <span class="block text-white font-bold text-xs truncate">{{ Auth::user()->name ?? 'Admin Loket' }}</span>
                <span class="block text-emerald-300/80 text-[10px] truncate">{{ Auth::user()->email ?? 'admin@jeepwukirsari.id' }}</span>
            </div>
        </div>
        
        <form action="{{ route('logout') }}" method="POST"
              data-confirm-title="Keluar Sesi Administrator"
              data-confirm-message="Apakah Anda yakin ingin keluar dari portal admin loket Jeep Wukirsari?"
              data-confirm-type="danger"
              data-confirm-text="Ya, Keluar"
              data-confirm-cancel="Batal">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-200/90 hover:bg-rose-500/20 hover:text-white transition-all cursor-pointer">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Keluar / Logout</span>
            </button>
        </form>

        <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-emerald-200 hover:bg-white/10 hover:text-white transition-all cursor-pointer border border-emerald-500/20">
            <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Lihat Beranda Web</span>
        </a>
    </div>
</aside>

@include('admin.partials.confirm-modal')
