<!-- Sidebar Admin -->
<aside class="w-full md:w-64 bg-gradient-to-b from-[#183c21] via-[#102b17] to-[#0a1b0e] text-white p-6 flex flex-col justify-between shrink-0 shadow-2xl border-r border-[#1e4626] relative overflow-hidden">
    <!-- Ambient subtle lighting effect -->
    <div class="absolute -top-24 -left-24 w-52 h-52 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -right-20 w-48 h-48 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10">
        <!-- Logo & Brand Header -->
        <div class="flex items-center gap-3 pb-6 border-b border-white/10 mb-6">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-white/20 via-white/10 to-transparent p-1.5 flex items-center justify-center shadow-inner border border-white/15 shrink-0 backdrop-blur-xs">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Jeep Wukirsari" class="w-full h-full object-contain filter drop-shadow">
            </div>
            <div>
                <span class="block font-black text-base text-white tracking-tight drop-shadow-xs">ADMIN LOKET</span>
                <span class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Jeep Wukirsari</span>
            </div>
        </div>

        <!-- Menu Navigasi Admin -->
        <nav class="space-y-1.5">
            <!-- 1. Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-emerald-600 via-[#1f592d] to-[#164320] text-white shadow-lg shadow-emerald-950/50 border border-emerald-400/40 font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-0.5' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-200' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Dashboard & Omzet</span>
                </div>
                @if(request()->routeIs('admin.dashboard'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]"></span>
                @endif
            </a>

            <!-- 2. Laporan Penjualan -->
            <a href="{{ route('admin.reports') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.reports*') ? 'bg-gradient-to-r from-emerald-600 via-[#1f592d] to-[#164320] text-white shadow-lg shadow-emerald-950/50 border border-emerald-400/40 font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-0.5' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.reports*') ? 'text-emerald-200' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan Penjualan</span>
                </div>
                @if(request()->routeIs('admin.reports*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]"></span>
                @endif
            </a>

            <!-- 3. Kelola Paket Wisata -->
            <a href="{{ route('admin.packages') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.packages*') ? 'bg-gradient-to-r from-emerald-600 via-[#1f592d] to-[#164320] text-white shadow-lg shadow-emerald-950/50 border border-emerald-400/40 font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-0.5' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.packages*') ? 'text-emerald-200' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Kelola Paket Wisata</span>
                </div>
                @if(request()->routeIs('admin.packages*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]"></span>
                @endif
            </a>

            <!-- 4. Kelola Jadwal / Sesi -->
            <a href="{{ route('admin.schedules') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.schedules*') ? 'bg-gradient-to-r from-emerald-600 via-[#1f592d] to-[#164320] text-white shadow-lg shadow-emerald-950/50 border border-emerald-400/40 font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-0.5' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.schedules*') ? 'text-emerald-200' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Kelola Jadwal Sesi</span>
                </div>
                @if(request()->routeIs('admin.schedules*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]"></span>
                @endif
            </a>

            <!-- 5. Manajemen Ulasan -->
            <a href="{{ route('admin.reviews') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('admin.reviews*') ? 'bg-gradient-to-r from-emerald-600 via-[#1f592d] to-[#164320] text-white shadow-lg shadow-emerald-950/50 border border-emerald-400/40 font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-0.5' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>Manajemen Ulasan</span>
                </div>
                @if(request()->routeIs('admin.reviews*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]"></span>
                @endif
            </a>
        </nav>
    </div>

    <!-- Profil & Logout -->
    <div class="relative z-10 pt-5 border-t border-white/10 mt-6 space-y-3">
        <div class="p-3.5 rounded-2xl bg-gradient-to-br from-white/[0.08] to-white/[0.02] border border-white/10 backdrop-blur-xs shadow-inner">
            <span class="block text-emerald-200/60 text-[10px] uppercase font-bold tracking-wider">Login Sebagai:</span>
            <span class="block text-white font-bold text-xs truncate mt-0.5">{{ Auth::user()->name ?? 'Admin Wukirsari' }}</span>
            <div class="flex items-center gap-1.5 mt-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[10px] text-emerald-300 font-medium">Administrator</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold bg-rose-500/15 hover:bg-rose-500/25 text-rose-200 border border-rose-500/20 transition-all cursor-pointer shadow-xs active:scale-98">
                <span>🚪</span> <span>Keluar (Logout)</span>
            </button>
        </form>
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-1.5 text-xs text-white/60 hover:text-white transition-colors pt-1">
            ← <span>Kembali ke Beranda</span>
        </a>
    </div>
</aside>
