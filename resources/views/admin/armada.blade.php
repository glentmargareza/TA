<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Armada Jeep (20 Unit) | Jeep Wukirsari</title>
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
                    Monitoring Armada Jeep (20 Unit)
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Admin memantau operasional armada, ketersediaan pangkalan, dan jadwal sesi harian.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Total: {{ $totalJeepsCount }} Unit Armada (Maks 4 Pax/Jeep)
                </span>
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

            <!-- 3 KARTU STATUS UTAMA ARMADA -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- 1. Total Armada -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Total Armada Paguyuban</span>
                        <span class="text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight">
                            {{ $totalJeepsCount }} <span class="text-xs font-normal text-slate-400">Armada</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-600 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Terdaftar di Paguyuban
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/></svg>
                    </div>
                </div>

                <!-- 2. Jeep Aktif / Tersedia -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Jumlah Jeep Aktif (Ready)</span>
                        <span class="text-3xl font-extrabold text-emerald-800 block mt-1 tracking-tight">
                            {{ $readyJeepsCount }} <span class="text-xs font-normal text-slate-400">Armada</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Siap di basecamp / standby
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- 3. Jeep Sedang Digunakan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Jeep Sedang Digunakan</span>
                        <span class="text-3xl font-extrabold text-blue-700 block mt-1 tracking-tight">
                            {{ $onTripJeepsCount }} <span class="text-xs font-normal text-slate-400">Armada</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-blue-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Sedang melayani trip wisatawan
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
            </div>

            <!-- PANEL: 20 UNIT ARMADA JEEP & JADWAL SESI -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Status & Jadwal Penggunaan 20 Armada</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Ubah status armada secara live pada menu dropdown di setiap kartu armada.</p>
                    </div>

                    <!-- Filter Kategori Status Jeep & Tombol Aksi Cepat -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <button type="button" onclick="filterJeeps('ALL', this)" class="jeep-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-emerald-800 text-white shadow-xs cursor-pointer">
                                Semua ({{ $totalJeepsCount }})
                            </button>
                            <button type="button" onclick="filterJeeps('READY', this)" class="jeep-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all bg-white text-slate-600 hover:text-emerald-800 border border-slate-200 cursor-pointer">
                                Tersedia ({{ $readyJeepsCount }})
                            </button>
                            <button type="button" onclick="filterJeeps('ON_TRIP', this)" class="jeep-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all bg-white text-slate-600 hover:text-blue-800 border border-slate-200 cursor-pointer">
                                On Trip ({{ $onTripJeepsCount }})
                            </button>
                            <button type="button" onclick="filterJeeps('MAINTENANCE', this)" class="jeep-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all bg-white text-slate-600 hover:text-amber-800 border border-slate-200 cursor-pointer">
                                Off / Servis ({{ $maintenanceJeepsCount }})
                            </button>
                        </div>

                        @if($readyJeepsCount < $totalJeepsCount)
                        <form action="{{ route('admin.jeeps.activateAll') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengaktifkan seluruh {{ $totalJeepsCount }} unit armada Jeep menjadi status TERSEDIA (Ready)?')" class="inline ml-auto">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-emerald-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                                <span>Aktifkan Semua ({{ $totalJeepsCount }} Unit)</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Grid 20 Unit Jeep -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($jeepsMonitoring as $j)
                    @php
                        $activeAssign = $j->assignments->first();
                        $upcomingAssign = $j->assignments->slice(1, 3);
                        $status = $j->status;
                    @endphp
                    <div class="jeep-row bg-slate-50/70 rounded-2xl border border-slate-200 p-4 flex flex-col justify-between hover:shadow-md hover:border-emerald-300 transition-all" data-status="{{ $status }}">
                        <div>
                            <!-- Header Jeep Card -->
                            <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-200/80">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 text-sm">{{ $j->model_name }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-white text-slate-700 border border-slate-200">
                                            {{ $j->plate_number }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-slate-500 block mt-0.5">Kapasitas Maks: {{ $j->capacity }} Penumpang</span>
                                </div>
                                
                                <!-- Dropdown Update Status Cepat -->
                                <form action="{{ route('admin.jeeps.status', $j->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-[10px] font-extrabold rounded-lg px-2 py-1 border focus:outline-none cursor-pointer
                                        {{ $status === 'READY' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : '' }}
                                        {{ $status === 'ON_TRIP' ? 'bg-blue-100 text-blue-800 border-blue-300' : '' }}
                                        {{ $status === 'MAINTENANCE' ? 'bg-amber-100 text-amber-800 border-amber-300' : '' }}">
                                        <option value="READY" {{ $status === 'READY' ? 'selected' : '' }}>● TERSEDIA</option>
                                        <option value="ON_TRIP" {{ $status === 'ON_TRIP' ? 'selected' : '' }}>● ON TRIP</option>
                                        <option value="MAINTENANCE" {{ $status === 'MAINTENANCE' ? 'selected' : '' }}>● TIDAK TERSEDIA</option>
                                    </select>
                                </form>
                            </div>

                            <!-- Detail Operasional & Jadwal Penggunaan -->
                            <div class="py-3 space-y-2">
                                @if($activeAssign)
                                <div class="p-2.5 rounded-xl bg-blue-50/80 border border-blue-200 text-xs space-y-1">
                                    <div class="flex items-center justify-between text-blue-900 font-bold">
                                        <span class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                                            Trip Berlangsung Sekarang
                                        </span>
                                        <span class="font-mono text-[11px]">#{{ $activeAssign->booking?->booking_code }}</span>
                                    </div>
                                    <div class="text-blue-800 text-[11px]">
                                        <span>Paket: <strong>{{ $activeAssign->booking?->package?->name ?? 'Trip' }}</strong></span>
                                        <span class="mx-1">&bull;</span>
                                        <span>Sesi: <strong>{{ $activeAssign->booking?->slot?->start_time }} WIB</strong></span>
                                    </div>
                                    <div class="text-blue-700 text-[11px] pt-1 border-t border-blue-200/60">
                                        Driver: <strong>{{ $activeAssign->driver?->full_name ?? 'Auto Paguyuban' }}</strong> ({{ $activeAssign->booking?->passenger_count }} Wisatawan)
                                    </div>
                                </div>
                                @elseif($status === 'MAINTENANCE')
                                <div class="p-2.5 rounded-xl bg-amber-50/80 border border-amber-200 text-xs text-amber-900 space-y-0.5">
                                    <span class="font-bold flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Armada Sedang Istirahat / Servis
                                    </span>
                                    <p class="text-[11px] text-amber-700">Tidak dialokasikan untuk pemesanan baru sampai status diubah ke Tersedia.</p>
                                </div>
                                @else
                                <div class="p-2.5 rounded-xl bg-emerald-50/80 border border-emerald-200 text-xs text-emerald-900 flex items-center justify-between">
                                    <span class="font-semibold flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Standby di Basecamp Wukirsari
                                    </span>
                                    <span class="text-[10px] text-emerald-700 font-bold">Siap Ditugaskan</span>
                                </div>
                                @endif

                                <!-- Jadwal Penggunaan Hari Ini -->
                                <div class="pt-2">
                                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1.5">Jadwal Sesi Hari Ini:</span>
                                    @if(count($upcomingAssign) > 0)
                                    <div class="space-y-1">
                                        @foreach($upcomingAssign as $ua)
                                        <div class="flex items-center justify-between text-[11px] p-1.5 rounded-lg bg-white border border-slate-200/80">
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span class="font-bold text-slate-800">{{ $ua->booking?->slot?->start_time ?? '09:00' }} WIB</span>
                                                <span class="text-slate-500">({{ $ua->booking?->package?->name ?? 'Trip' }})</span>
                                            </div>
                                            <span class="font-mono text-slate-600 text-[10px]">#{{ $ua->booking?->booking_code }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-[11px] text-slate-400 italic">Belum ada antrean jadwal berikutnya hari ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer Info Driver -->
                        <div class="pt-2.5 border-t border-slate-200/80 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Sistem Driver:</span>
                            <span class="font-semibold text-emerald-800">
                                🎲 Alokasi Otomatis Paguyuban
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-8 text-slate-400 text-xs">
                        Tidak ada data armada jeep.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- JAVASCRIPT UNTUK FILTER JEEP CARDS -->
            <script>
                function filterJeeps(status, btn) {
                    document.querySelectorAll('.jeep-filter-btn').forEach(b => {
                        b.classList.remove('bg-emerald-800', 'text-white', 'shadow-xs');
                        b.classList.add('bg-white', 'text-slate-600');
                    });
                    btn.classList.add('bg-emerald-800', 'text-white', 'shadow-xs');
                    btn.classList.remove('bg-white', 'text-slate-600');
                    
                    const rows = document.querySelectorAll('.jeep-row');
                    rows.forEach(row => {
                        if (status === 'ALL' || row.getAttribute('data-status') === status) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            </script>

        </main>
    </div>

</body>
</html>

