<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Armada Jeep ({{ $totalJeepsCount }} Unit) | Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Monitoring Armada Jeep & Driver Paguyuban
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Admin memantau kesiapan armada Jeep, ketersediaan pangkalan, serta status operasional driver Paguyuban.</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap">
                <button type="button" onclick="openAddJeepModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Kendaraan</span>
                </button>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ $totalJeepsCount }} Kendaraan
                </span>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">
                    {{ $activeDriversCount }} Driver Aktif &bull; {{ $inactiveDriversCount }} Nonaktif
                </span>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-5 sm:space-y-7 flex-1">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-rose-600 text-lg">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- 3 KARTU RINGKASAN: JUMLAH KENDARAAN, DRIVER AKTIF, DRIVER NONAKTIF -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <!-- 1. Jumlah Kendaraan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Jumlah Kendaraan</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight">
                            {{ $totalJeepsCount }} <span class="text-xs font-normal text-slate-400">Unit</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-600 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Total Armada Paguyuban
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/></svg>
                    </div>
                </div>

                <!-- 2. Driver Aktif -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Driver Aktif</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-emerald-800 block mt-1 tracking-tight">
                            {{ $activeDriversCount }} <span class="text-xs font-normal text-slate-400">Driver</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Siap ditugaskan trip
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>

                <!-- 3. Driver Tidak Aktif / Nonaktif -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Driver Nonaktif</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-amber-700 block mt-1 tracking-tight">
                            {{ $inactiveDriversCount }} <span class="text-xs font-normal text-slate-400">Driver</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-amber-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Libur / Izin / Nonaktif
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                </div>
            </div>

            <!-- PANEL: MONITORING DRIVER PAGUYUBAN (AKTIF & TIDAK AKTIF) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Status & Kesiapan Driver Paguyuban ({{ $totalDriversCount }} Driver)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar driver yang siap ditugaskan melayani wisatawan dan driver yang sedang libur/tidak aktif.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800">
                            {{ $activeDriversCount }} Driver Aktif
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $inactiveDriversCount }} Driver Nonaktif
                        </span>
                    </div>
                </div>

                <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- KOLOM 1: DAFTAR SEMENTARA DRIVER YANG AKTIF -->
                    <div class="bg-[#F8FAF9] rounded-2xl border border-emerald-200/80 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-emerald-200/70">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    </span>
                                    <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">Daftar Driver yang Aktif</h4>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    {{ $activeDriversCount }} Personel
                                </span>
                            </div>

                            <div class="space-y-2">
                                @forelse($activeDrivers as $index => $d)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-slate-200/90 hover:border-emerald-300 hover:shadow-xs transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center justify-center shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="min-w-0">
                                            <span class="font-bold text-slate-900 text-xs sm:text-sm block capitalize truncate">
                                                {{ $d->full_name }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 block font-mono">
                                                ID: #DRV-{{ str_pad($d->id, 2, '0', STR_PAD_LEFT) }} &bull; {{ $d->license_number ?? 'SIM-B1' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#16A34A] border border-emerald-200/60 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Siap Jalan
                                        </span>
                                        <form action="{{ route('admin.drivers.status', $d->id) }}" method="POST" class="inline"
                                              data-confirm-title="Nonaktifkan Driver"
                                              data-confirm-message="Ubah status driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> menjadi <strong class='text-amber-700 font-bold'>TIDAK AKTIF</strong>? Driver sementara tidak akan dialokasikan ke booking."
                                              data-confirm-type="warning"
                                              data-confirm-text="Ya, Nonaktifkan"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 rounded-lg border border-slate-200 hover:border-amber-300 bg-white hover:bg-amber-50 text-[10px] font-bold text-slate-600 hover:text-amber-800 transition-all cursor-pointer" title="Nonaktifkan Driver">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.drivers.destroy', $d->id) }}" method="POST" class="inline"
                                              data-confirm-title="Hapus Driver"
                                              data-confirm-message="Apakah Anda yakin ingin menghapus data driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> secara permanen dari sistem?"
                                              data-confirm-type="danger"
                                              data-confirm-text="Ya, Hapus Driver"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-lg border border-slate-200 hover:border-rose-300 bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-all cursor-pointer" title="Hapus Driver">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <div class="p-6 text-center text-slate-400 text-xs italic">
                                    Tidak ada driver aktif saat ini.
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-emerald-100 flex items-center justify-between text-[11px] text-emerald-800 font-medium">
                            <span>Status: Muncul dalam penugasan order booking</span>
                            <span>✓ Terintegrasi Rotasi</span>
                        </div>
                    </div>

                    <!-- KOLOM 2: DAFTAR DRIVER YANG TIDAK AKTIF -->
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-slate-400 flex items-center justify-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    </span>
                                    <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">Daftar Driver yang Tidak Aktif</h4>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                                    {{ $inactiveDriversCount }} Personel
                                </span>
                            </div>

                            <div class="space-y-2">
                                @forelse($inactiveDrivers as $index => $d)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-slate-200/90 hover:border-slate-300 transition-all">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-extrabold text-xs flex items-center justify-center shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="min-w-0">
                                            <span class="font-bold text-slate-700 text-xs sm:text-sm block capitalize truncate">
                                                {{ $d->full_name }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 block font-mono">
                                                ID: #DRV-{{ str_pad($d->id, 2, '0', STR_PAD_LEFT) }} &bull; {{ $d->license_number ?? 'SIM-B1' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Libur / Izin
                                        </span>
                                        <form action="{{ route('admin.drivers.status', $d->id) }}" method="POST" class="inline"
                                              data-confirm-title="Aktifkan Kembali Driver"
                                              data-confirm-message="Aktifkan kembali driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> untuk jadwal rotasi penugasan armada?"
                                              data-confirm-type="success"
                                              data-confirm-text="Ya, Aktifkan"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-[10px] font-bold text-emerald-800 transition-all cursor-pointer" title="Aktifkan Driver">
                                                Aktifkan
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.drivers.destroy', $d->id) }}" method="POST" class="inline"
                                              data-confirm-title="Hapus Driver"
                                              data-confirm-message="Apakah Anda yakin ingin menghapus data driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> secara permanen dari sistem?"
                                              data-confirm-type="danger"
                                              data-confirm-text="Ya, Hapus Driver"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-lg border border-slate-200 hover:border-rose-300 bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-all cursor-pointer" title="Hapus Driver">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <div class="p-6 text-center text-slate-400 text-xs italic">
                                    Semua driver saat ini berstatus aktif.
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                            <span>Status: Tidak dialokasikan untuk order baru</span>
                            <span>⏸ Standby Off</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL: TAMBAH KENDARAAN / ARMADA JEEP BARU -->
    @php
        $nextJeepNo = $totalJeepsCount + 1;
    @endphp
    <div id="modalAddJeep" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalAddJeepCard" class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
            <form action="{{ route('admin.jeeps.store') }}" method="POST">
                @csrf
                <!-- Modal Header -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Tambah Kendaraan Baru</h3>
                            <p class="text-xs text-slate-500">Nomor plat / unit diurutkan otomatis dari 1</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAddJeepModal()" class="w-8 h-8 rounded-full hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
                </div>

                <!-- Modal Body Form -->
                <div class="p-6 space-y-4 text-xs">
                    <!-- 1. Nomor Armada / Plat -->
                    <div>
                        <label for="jeep_plate_number" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Nomor Armada / Plat (Urut dari 1) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="plate_number" id="jeep_plate_number" value="{{ $nextJeepNo }}" oninput="syncJeepName()" placeholder="Contoh: {{ $nextJeepNo }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-mono font-bold tracking-wider focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Otomatis urut mulai dari nomor 1.</span>
                    </div>

                    <!-- 2. Nama Driver Baru (Ketik Manual) -->
                    <div>
                        <label for="jeep_driver_name" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Nama Lengkap Driver Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="driver_name" id="jeep_driver_name" required oninput="syncJeepName()" placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Nama driver yang diketik akan otomatis ditambahkan ke daftar driver paguyuban.</span>
                    </div>

                    <!-- 3. Nomor Telepon / WhatsApp Driver -->
                    <div>
                        <label for="jeep_driver_phone" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Nomor Telepon / WhatsApp Driver <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="driver_phone" id="jeep_driver_phone" placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- 4. Format Nama / Identitas Kendaraan -->
                    <div>
                        <label for="jeep_model_name" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Format Nama / Identitas Armada
                        </label>
                        <input type="text" name="model_name" id="jeep_model_name" value="Jeep #{{ $nextJeepNo }}" required placeholder="Contoh: Jeep #{{ $nextJeepNo }} (Nama Driver)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Otomatis terformat menjadi Jeep #{{ $nextJeepNo }} (Nama Driver).</span>
                    </div>

                    <!-- 5. Status Awal -->
                    <div>
                        <label for="jeep_status" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Status Awal Operasional <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="jeep_status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            <option value="READY" selected>● TERSEDIA (Armada & Driver Langsung Siap Jalan)</option>
                            <option value="MAINTENANCE">● TIDAK TERSEDIA (Sedang Servis / Libur)</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeAddJeepModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Kendaraan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT MODAL ADD JEEP -->
    <script>
        function syncJeepName() {
            const numInput = document.getElementById('jeep_plate_number');
            const driverInput = document.getElementById('jeep_driver_name');
            const modelInput = document.getElementById('jeep_model_name');

            const num = numInput ? (numInput.value.trim() || '{{ $nextJeepNo }}') : '{{ $nextJeepNo }}';
            const driver = driverInput ? driverInput.value.trim() : '';

            if (driver) {
                modelInput.value = 'Jeep #' + num + ' (' + driver + ')';
            } else {
                modelInput.value = 'Jeep #' + num;
            }
        }

        function openAddJeepModal() {
            const modal = document.getElementById('modalAddJeep');
            const card = document.getElementById('modalAddJeepCard');
            if (!modal) return;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
                document.getElementById('jeep_driver_name')?.focus();
            });
        }

        function closeAddJeepModal() {
            const modal = document.getElementById('modalAddJeep');
            const card = document.getElementById('modalAddJeepCard');
            if (!modal) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddJeepModal();
            }
        });

        const modalAddJeep = document.getElementById('modalAddJeep');
        if (modalAddJeep) {
            modalAddJeep.addEventListener('click', function(e) {
                if (e.target === modalAddJeep) {
                    closeAddJeepModal();
                }
            });
        }
    </script>
</body>
</html>

