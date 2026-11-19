<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Scanner E-Tiket & Pelunasan | Admin Jeep Wukirsari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-display, h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }
        
        /* Laser scanner animation */
        @keyframes scanLaser {
            0% { top: 6%; opacity: 0.8; }
            50% { top: 90%; opacity: 1; }
            100% { top: 6%; opacity: 0.8; }
        }
        .laser-line {
            animation: scanLaser 2.2s ease-in-out infinite;
        }

        /* html5-qrcode video styling override */
        #reader {
            width: 100% !important;
            border: none !important;
        }
        #reader video {
            width: 100% !important;
            height: auto !important;
            max-height: 380px !important;
            object-fit: cover !important;
            border-radius: 1rem !important;
        }
        #reader__scan_region {
            background: transparent !important;
        }
        #reader__dashboard {
            display: none !important;
        }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-display">
                        QR Scanner E-Tiket
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase tracking-wide">
                        Check-in & Pelunasan
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Pindai QR code e-tiket pelanggan untuk konfirmasi check-in dan proses pelunasan sisa tagihan DP di loket basecamp.
                </p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Daftar Booking</span>
                </a>
                <a href="{{ route('admin.armada') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/></svg>
                    <span>Monitoring Armada</span>
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="p-4 sm:p-6 lg:p-8 space-y-6">

            <!-- Flash Alert (Status Box) -->
            <div id="statusToast" class="hidden rounded-2xl p-4 transition-all duration-300 shadow-sm border">
                <div class="flex items-start gap-3">
                    <div id="toastIcon" class="shrink-0 mt-0.5"></div>
                    <div class="flex-1">
                        <h4 id="toastTitle" class="text-sm font-bold"></h4>
                        <p id="toastMessage" class="text-xs mt-0.5"></p>
                    </div>
                    <button type="button" onclick="closeToast()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Grid Scanner & Quick Action -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Camera Scanner Viewport (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 gap-3 flex-wrap">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-slate-800 tracking-tight font-display">Kamera Scanner E-Tiket</h2>
                                <p class="text-xs text-slate-500">Arahkan lensa kamera perangkat ke QR Code pelanggan</p>
                            </div>
                        </div>

                        <!-- Live Status Badge -->
                        <div class="flex items-center gap-2">
                            <span id="scannerIndicator" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Kamera Standby</span>
                            </span>
                        </div>
                    </div>

                    <!-- Camera Viewport Box -->
                    <div class="mt-4 relative bg-slate-950 rounded-2xl overflow-hidden min-h-[300px] sm:min-h-[360px] flex items-center justify-center border-2 border-slate-800 shadow-inner">
                        
                        <!-- Video Reader Container -->
                        <div id="reader" class="w-full h-full"></div>

                        <!-- Scanner Viewport Frame & Laser overlay (Visible only while scanning) -->
                        <div id="scannerOverlay" class="absolute inset-0 pointer-events-none hidden flex-col items-center justify-center p-6">
                            <!-- Target Bounding Box -->
                            <div class="relative w-64 h-64 sm:w-72 sm:h-72 border-2 border-emerald-400/80 rounded-2xl shadow-[0_0_20px_rgba(16,185,129,0.35)] flex items-center justify-center overflow-hidden">
                                
                                <!-- Corner Accents -->
                                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-emerald-400 rounded-tl-lg"></div>
                                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-emerald-400 rounded-tr-lg"></div>
                                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-emerald-400 rounded-bl-lg"></div>
                                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-emerald-400 rounded-br-lg"></div>
                                
                                <!-- Moving Laser Line -->
                                <div class="laser-line absolute left-2 right-2 h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-[0_0_12px_#10B981]"></div>
                                
                                <span class="text-[11px] font-bold text-white/70 tracking-wider uppercase mt-40 drop-shadow">Posisikan QR Code di sini</span>
                            </div>
                        </div>

                        <!-- Idle State / Placeholder before camera starts -->
                        <div id="cameraIdlePlaceholder" class="p-8 text-center text-white/80 max-w-sm flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800/80 border border-slate-700/60 flex items-center justify-center mb-3 text-emerald-400 shadow-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-white font-display">Kamera Belum Aktif</h3>
                            <p class="text-xs text-slate-400 mt-1 mb-4 leading-relaxed">
                                Klik tombol <strong>Mulai Scan</strong> di bawah untuk mengaktifkan kamera smartphone atau webcam desktop Anda.
                            </p>
                            <button type="button" onclick="startScanner()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 cursor-pointer active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Mulai Kamera Scanner</span>
                            </button>
                        </div>

                        <!-- Permission Error Placeholder -->
                        <div id="cameraErrorPlaceholder" class="hidden p-6 text-center text-white/90 max-w-md">
                            <div class="w-14 h-14 rounded-2xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center mx-auto mb-3 text-rose-400">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-rose-300">Izin Kamera Tidak Diberikan</h3>
                            <p id="cameraErrorMessage" class="text-xs text-slate-300 mt-1 mb-4 leading-relaxed">
                                Browser tidak dapat mengakses kamera. Pastikan Anda telah memberikan izin akses kamera (allow camera permission) pada browser atau gunakan fitur input kode manual di samping.
                            </p>
                            <button type="button" onclick="startScanner()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold border border-slate-700 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Coba Lagi</span>
                            </button>
                        </div>
                    </div>

                    <!-- Camera Controls Bar -->
                    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100">
                        <div class="w-full sm:w-auto flex items-center gap-2">
                            <label for="cameraSelect" class="text-xs font-semibold text-slate-500 shrink-0">Kamera:</label>
                            <select id="cameraSelect" class="w-full sm:w-56 text-xs bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                                <option value="">Pilih Kamera Perangkat...</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="button" id="btnStartScan" onclick="startScanner()" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                <span>Mulai Scan</span>
                            </button>

                            <button type="button" id="btnStopScan" onclick="stopScanner()" class="hidden flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                <span>Hentikan Scan</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Manual Input & Petunjuk Kasir Loket (5 Cols) -->
                <div class="lg:col-span-5 space-y-5">
                    
                    <!-- Manual Input Card -->
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 tracking-tight font-display">Input Manual Kode E-Tiket</h3>
                                <p class="text-[11px] text-slate-500">Alternatif jika kamera terkendala atau QR code buram</p>
                            </div>
                        </div>

                        <form id="formManualLookup" onsubmit="handleManualLookup(event)" class="mt-4 space-y-3">
                            <div>
                                <label for="manualInputCode" class="block text-xs font-bold text-slate-700 mb-1">
                                    Kode Booking / Token QR:
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="manualInputCode" 
                                        placeholder="Contoh: WKR-2026-0001 / WKR-QR-..." 
                                        class="w-full text-xs font-mono uppercase bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all font-semibold"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        onclick="pasteFromClipboard()" 
                                        title="Paste dari Clipboard" 
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-emerald-700 cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </button>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                id="btnManualSubmit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95"
                            >
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Periksa Data E-Tiket</span>
                            </button>
                        </form>
                    </div>

                    <!-- SOP Loket & Petunjuk Kasir -->
                    <div class="bg-gradient-to-br from-emerald-950 via-[#143B1E] to-[#0D2714] text-white rounded-3xl p-5 sm:p-6 shadow-sm border border-emerald-900/60">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <h4 class="text-xs font-extrabold text-emerald-300 uppercase tracking-wider">SOP Loket Pelunasan</h4>
                        </div>
                        <ul class="text-xs text-white/80 space-y-2.5 leading-relaxed font-normal">
                            <li class="flex items-start gap-2">
                                <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">1</span>
                                <span>Arahkan kamera ke QR Code e-tiket di HP pelanggan saat tiba di basecamp.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">2</span>
                                <span>Periksa nominal <strong>Sisa Pelunasan</strong> pada pop-up konfirmasi.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">3</span>
                                <span>Terima uang tunai atau cek mutasi transfer kasir, lalu klik <strong>"Ya, Pelunasan Diterima"</strong>.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">4</span>
                                <span>Sistem otomatis mengubah status menjadi <strong>LUNAS</strong> dan menugaskan Driver & Jeep.</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>

            <!-- Bottom Section: Riwayat Pelunasan Terakhir (Tabel Sesi Ini) -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-black text-slate-800 tracking-tight font-display">
                            Riwayat Pelunasan E-Tiket Loket
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Daftar transaksi pelunasan sisa tagihan DP yang baru saja dicatat di loket basecamp.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold self-start sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ count($recentSettlements) }} Transaksi Pelunasan Terakhir</span>
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Kode Booking</th>
                                <th class="py-3 px-4">Nama Pelanggan</th>
                                <th class="py-3 px-4">Paket Wisata</th>
                                <th class="py-3 px-4">Nominal Pelunasan</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="settlementsTableBody" class="divide-y divide-slate-100 font-medium">
                            @forelse($recentSettlements as $st)
                                @php
                                    $b = $st->booking;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                        {{ $st->created_at ? $st->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                        {{ $b?->booking_code ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">
                                        {{ $b?->customer_name ?? 'Pelanggan Loket' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-700">
                                        {{ $b?->package?->name ?? 'Paket Trip Jeep' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-700 whitespace-nowrap">
                                        Rp {{ number_format($st->gross_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                            {{ $st->payment_method ?? ($st->payment_type === 'TRANSFER_BASECAMP' ? 'Transfer Bank' : 'Tunai Loket') }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            LUNAS
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($b)
                                            <a href="{{ route('ticket.check', ['code' => $b->qr_token ?? $b->booking_code]) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>E-Tiket</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptySettlementsRow">
                                    <td colspan="8" class="py-8 text-center text-slate-400 text-xs">
                                        Belum ada riwayat pelunasan di loket untuk sesi saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL POP-UP DETAIL BOOKING & KONFIRMASI PELUNASAN -->
    <!-- ======================================================== -->
    <div id="bookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg border border-slate-100">
                
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-emerald-950 to-[#143B1E] text-white flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-300 font-mono tracking-wider uppercase">Verifikasi E-Tiket</span>
                            <span id="modalStatusBadge" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase"></span>
                        </div>
                        <h3 class="text-lg font-black font-display text-white mt-0.5" id="modalBookingCode"></h3>
                    </div>
                    <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                    <!-- Customer Info Section -->
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Informasi Pelanggan</span>
                            <span id="modalTripDate" class="text-xs font-bold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-200"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-slate-400 text-[11px] block">Nama Pelanggan:</span>
                                <strong id="modalCustomerName" class="text-slate-900 text-sm"></strong>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[11px] block">No. WhatsApp:</span>
                                <a id="modalCustomerPhoneLink" href="#" target="_blank" class="text-emerald-700 font-bold hover:underline inline-flex items-center gap-1">
                                    <span id="modalCustomerPhone"></span>
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </a>
                            </div>
                            <div class="col-span-2 pt-1 border-t border-slate-200/60 flex items-center justify-between">
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Paket Wisata:</span>
                                    <strong id="modalPackageName" class="text-slate-900"></strong>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 text-[11px] block">Sesi Jam:</span>
                                    <span id="modalTimeSlot" class="font-bold text-slate-800"></span>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center justify-between text-slate-600 bg-white p-2 rounded-lg border border-slate-200">
                                <span>Peserta: <strong id="modalPax"></strong> orang (<strong id="modalJeepCount"></strong> Jeep)</span>
                                <span>Tipe: <strong id="modalArrivalType"></strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Breakdown Card -->
                    <div class="bg-emerald-50/60 rounded-2xl p-4 border border-emerald-200/80 space-y-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 block">Rincian Pembayaran</span>
                        
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Total Harga Booking:</span>
                                <strong id="modalTotalAmount" class="text-slate-900"></strong>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Uang Muka (DP) Sudah Dibayar:</span>
                                <strong id="modalPaidAmount" class="text-emerald-700"></strong>
                            </div>
                        </div>

                        <!-- Big Remaining Balance Highlight -->
                        <div id="remainingBox" class="pt-3 border-t border-emerald-200/80 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wide block">Sisa Pelunasan:</span>
                                <span class="text-[11px] text-slate-500">Wajib dibayar saat tiba di loket</span>
                            </div>
                            <div class="text-right">
                                <span id="modalRemainingAmount" class="text-2xl font-black text-emerald-800 font-display"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Metode Pembayaran (Hanya tampil jika belum lunas) -->
                    <div id="settlementActionSection" class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700">Pilih Metode Pembayaran Pelunasan:</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex flex-col p-3 rounded-xl border-2 border-emerald-600 bg-emerald-50/40 cursor-pointer transition-all hover:bg-emerald-50/70" id="labelMethodCash">
                                <input type="radio" name="payment_method" value="CASH" checked onchange="togglePaymentMethod(this)" class="sr-only">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-extrabold text-slate-900">Tunai / Cash</span>
                                    <div class="w-4 h-4 rounded-full border-2 border-emerald-600 bg-emerald-600 flex items-center justify-center text-white" id="indicatorCash">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-500 leading-tight">Uang fisik diterima langsung oleh kasir loket basecamp</span>
                            </label>

                            <label class="relative flex flex-col p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all hover:bg-slate-50" id="labelMethodTransfer">
                                <input type="radio" name="payment_method" value="TRANSFER" onchange="togglePaymentMethod(this)" class="sr-only">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-extrabold text-slate-900">Transfer Langsung</span>
                                    <div class="w-4 h-4 rounded-full border-2 border-slate-300 bg-white flex items-center justify-center" id="indicatorTransfer">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-500 leading-tight">Transfer ke rekening kas Paguyuban (BPD DIY) / QRIS</span>
                            </label>
                        </div>

                        <!-- Pertanyaan Konfirmasi -->
                        <div class="bg-amber-50 rounded-xl p-3.5 border border-amber-200 flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-xs text-amber-900 font-medium leading-relaxed">
                                Apakah Anda yakin sudah menerima uang pelunasan dari pelanggan sebesar 
                                <strong id="confirmAmountText" class="text-amber-950 font-black"></strong>?
                            </p>
                        </div>
                    </div>

                    <!-- Already Paid Box (Tampil jika sudah FULL_PAID) -->
                    <div id="alreadyPaidSection" class="hidden bg-emerald-100/70 border border-emerald-300 rounded-2xl p-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="text-sm font-black text-emerald-900">Booking Sudah Berstatus LUNAS</h4>
                        <p class="text-xs text-emerald-800 mt-1">E-Tiket ini valid dan seluruh tagihan telah dilunasi sebelumnya. Pelanggan dapat langsung diarahkan ke antrean keberangkatan Jeep.</p>
                        <div id="settleInfoDetails" class="text-[11px] text-emerald-700 font-semibold mt-2 pt-2 border-t border-emerald-200"></div>
                    </div>

                    <!-- Cancelled or Pending Box -->
                    <div id="warningNoticeSection" class="hidden bg-rose-50 border border-rose-200 rounded-2xl p-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-rose-600 text-white flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h4 id="warningNoticeTitle" class="text-sm font-black text-rose-900"></h4>
                        <p id="warningNoticeMessage" class="text-xs text-rose-800 mt-1"></p>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-all cursor-pointer">
                        Tutup
                    </button>

                    <div class="flex items-center gap-2">
                        <a id="btnViewETicket" href="#" target="_blank" class="hidden inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>Lihat E-Tiket</span>
                        </a>

                        <button 
                            type="button" 
                            id="btnConfirmSettle" 
                            onclick="submitSettlement()" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md shadow-emerald-950/20 cursor-pointer active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span id="btnConfirmText">Ya, Pelunasan Diterima</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let html5QrCode = null;
        let isScanning = false;
        let isProcessingLookup = false;
        let currentBookingData = null;
        let selectedMethod = 'CASH';

        // Suara Beep Sukses via Web Audio API (Tanpa butuh file MP3 eksternal)
        function playBeepSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime); // 880Hz (A5)
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.2);
            } catch(e) {
                // Ignore audio error
            }
        }

        // Inisialisasi daftar kamera saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            initCameraList();
        });

        async function initCameraList() {
            try {
                const cameras = await Html5Qrcode.getCameras();
                const select = document.getElementById('cameraSelect');
                select.innerHTML = '';

                if (cameras && cameras.length > 0) {
                    cameras.forEach((camera, index) => {
                        const opt = document.createElement('option');
                        opt.value = camera.id;
                        opt.text = camera.label || `Kamera ${index + 1}`;
                        // Prioritaskan kamera belakang jika ada kata 'back' atau 'rear'
                        if (camera.label && (camera.label.toLowerCase().includes('back') || camera.label.toLowerCase().includes('rear'))) {
                            opt.selected = true;
                        }
                        select.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.text = 'Tidak ada kamera terdeteksi';
                    select.appendChild(opt);
                }
            } catch (err) {
                console.warn('Gagal memuat daftar kamera:', err);
            }
        }

        async function startScanner() {
            if (isScanning) return;

            document.getElementById('cameraIdlePlaceholder').classList.add('hidden');
            document.getElementById('cameraErrorPlaceholder').classList.add('hidden');
            document.getElementById('scannerOverlay').classList.remove('hidden');

            const cameraSelect = document.getElementById('cameraSelect');
            let cameraId = cameraSelect.value;

            try {
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                // Pengaturan scanner: mode environment (kamera belakang) jika ID tidak dispesifikasikan
                const config = {
                    fps: 10,
                    qrbox: { width: 260, height: 260 },
                    aspectRatio: 1.0
                };

                const cameraConfig = cameraId ? cameraId : { facingMode: "environment" };

                await html5QrCode.start(
                    cameraConfig,
                    config,
                    (decodedText, decodedResult) => {
                        handleQrDetected(decodedText);
                    },
                    (errorMessage) => {
                        // ignore frame scanning errors
                    }
                );

                isScanning = true;
                updateScannerUI(true);
            } catch (err) {
                console.error("Gagal memulai scanner:", err);
                document.getElementById('cameraErrorPlaceholder').classList.remove('hidden');
                document.getElementById('cameraErrorMessage').textContent = 
                    err.message || 'Browser memblokir akses kamera atau kamera sedang digunakan aplikasi lain.';
                document.getElementById('scannerOverlay').classList.add('hidden');
                updateScannerUI(false);
            }
        }

        async function stopScanner() {
            if (!isScanning || !html5QrCode) return;
            try {
                await html5QrCode.stop();
                isScanning = false;
                document.getElementById('scannerOverlay').classList.add('hidden');
                document.getElementById('cameraIdlePlaceholder').classList.remove('hidden');
                updateScannerUI(false);
            } catch (err) {
                console.warn("Gagal menghentikan scanner:", err);
            }
        }

        function updateScannerUI(active) {
            const indicator = document.getElementById('scannerIndicator');
            const btnStart = document.getElementById('btnStartScan');
            const btnStop = document.getElementById('btnStopScan');

            if (active) {
                indicator.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 animate-pulse';
                indicator.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>Kamera Aktif & Memindai...</span>';
                btnStart.classList.add('hidden');
                btnStop.classList.remove('hidden');
            } else {
                indicator.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600';
                indicator.innerHTML = '<span class="w-2 h-2 rounded-full bg-slate-400"></span><span>Kamera Standby</span>';
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
            }
        }

        // Tangani QR Code yang tertangkap oleh scanner
        function handleQrDetected(qrText) {
            if (isProcessingLookup) return;
            isProcessingLookup = true;

            playBeepSound();
            showToast('info', 'QR Code Terdeteksi!', `Memeriksa data tiket: ${qrText.substring(0, 32)}...`);

            // Lookup ke backend
            performLookup(qrText);
        }

        // Tangani Input Manual
        function handleManualLookup(e) {
            e.preventDefault();
            const code = document.getElementById('manualInputCode').value.trim();
            if (!code) return;

            const btn = document.getElementById('btnManualSubmit');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Memeriksa...</span>
            `;

            performLookup(code, () => {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Periksa Data E-Tiket</span>
                `;
            });
        }

        async function pasteFromClipboard() {
            try {
                const text = await navigator.clipboard.readText();
                if (text) {
                    document.getElementById('manualInputCode').value = text.trim();
                }
            } catch (err) {
                console.warn('Gagal membaca clipboard:', err);
            }
        }

        // AJAX Request Lookup ke AdminController@scanQrLookup
        async function performLookup(tokenOrCode, callback) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("{{ route('admin.scanner.lookup') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ token: tokenOrCode })
                });

                const data = await response.json();

                if (response.ok && data.status !== 'error') {
                    // Valid booking found (DP_PAID, FULL_PAID, or WARNING)
                    openBookingModal(data);
                    showToast('success', 'E-Tiket Ditemukan!', data.message);
                } else {
                    // Not found or error
                    showToast('error', 'Pencarian Gagal', data.message || 'E-Tiket tidak ditemukan.');
                }
            } catch (err) {
                console.error('Error saat lookup:', err);
                showToast('error', 'Koneksi Terganggu', 'Gagal menghubungi server untuk memeriksa tiket.');
            } finally {
                if (callback) callback();
                // Beri jeda 2 detik sebelum scanner boleh mendeteksi lagi
                setTimeout(() => {
                    isProcessingLookup = false;
                }, 2000);
            }
        }

        // Buka Pop-Up Modal Konfirmasi Pelunasan
        function openBookingModal(lookupResponse) {
            const b = lookupResponse.booking;
            currentBookingData = b;

            document.getElementById('modalBookingCode').textContent = `#${b.booking_code}`;
            document.getElementById('modalCustomerName').textContent = b.customer_name;
            document.getElementById('modalCustomerPhone').textContent = b.customer_phone;
            document.getElementById('modalCustomerPhoneLink').href = `https://wa.me/${b.customer_phone.replace(/^0/, '62')}`;
            document.getElementById('modalPackageName').textContent = b.package_name;
            document.getElementById('modalTripDate').textContent = b.trip_date;
            document.getElementById('modalTimeSlot').textContent = b.time_slot;
            document.getElementById('modalPax').textContent = b.passenger_count;
            document.getElementById('modalJeepCount').textContent = b.jeep_quantity;
            document.getElementById('modalArrivalType').textContent = b.arrival_type === 'SHUTTLE' ? 'Shuttle / Dijemput' : 'Langsung ke Loket Basecamp';
            
            document.getElementById('modalTotalAmount').textContent = b.formatted_total;
            document.getElementById('modalPaidAmount').textContent = b.formatted_paid;
            document.getElementById('modalRemainingAmount').textContent = b.formatted_remaining;
            document.getElementById('confirmAmountText').textContent = b.formatted_remaining;

            const badge = document.getElementById('modalStatusBadge');
            const settlementSection = document.getElementById('settlementActionSection');
            const alreadyPaidSection = document.getElementById('alreadyPaidSection');
            const warningSection = document.getElementById('warningNoticeSection');
            const btnConfirm = document.getElementById('btnConfirmSettle');
            const btnETicket = document.getElementById('btnViewETicket');

            // Reset Section States
            settlementSection.classList.add('hidden');
            alreadyPaidSection.classList.add('hidden');
            warningSection.classList.add('hidden');
            btnConfirm.classList.add('hidden');
            btnETicket.classList.remove('hidden');
            btnETicket.href = b.ticket_url;

            if (lookupResponse.code === 'DP_PAID') {
                // Siap Dilunasi
                badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-400 text-amber-950';
                badge.textContent = 'Uang Muka (DP 30%)';
                settlementSection.classList.remove('hidden');
                btnConfirm.classList.remove('hidden');
                document.getElementById('btnConfirmText').textContent = `Ya, Pelunasan Diterima (${b.formatted_remaining})`;
            } else if (lookupResponse.code === 'FULL_PAID') {
                // Sudah Lunas
                badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-400 text-emerald-950';
                badge.textContent = 'LUNAS (100%)';
                alreadyPaidSection.classList.remove('hidden');
                if (lookupResponse.settlement_info) {
                    const si = lookupResponse.settlement_info;
                    document.getElementById('settleInfoDetails').textContent = 
                        `Pelunasan tercatat: ${si.amount} via ${si.method} pada ${si.paid_at}`;
                }
            } else {
                // Cancelled atau Pending
                badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-rose-400 text-rose-950';
                badge.textContent = b.booking_status;
                warningSection.classList.remove('hidden');
                document.getElementById('warningNoticeTitle').textContent = `Perhatian: Status ${b.booking_status}`;
                document.getElementById('warningNoticeMessage').textContent = lookupResponse.message;
            }

            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            currentBookingData = null;
        }

        function togglePaymentMethod(radio) {
            selectedMethod = radio.value;
            const labelCash = document.getElementById('labelMethodCash');
            const labelTransfer = document.getElementById('labelMethodTransfer');
            const indCash = document.getElementById('indicatorCash');
            const indTransfer = document.getElementById('indicatorTransfer');

            if (selectedMethod === 'CASH') {
                labelCash.className = 'relative flex flex-col p-3 rounded-xl border-2 border-emerald-600 bg-emerald-50/40 cursor-pointer transition-all hover:bg-emerald-50/70';
                labelTransfer.className = 'relative flex flex-col p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all hover:bg-slate-50';
                indCash.className = 'w-4 h-4 rounded-full border-2 border-emerald-600 bg-emerald-600 flex items-center justify-center text-white';
                indTransfer.className = 'w-4 h-4 rounded-full border-2 border-slate-300 bg-white flex items-center justify-center';
            } else {
                labelCash.className = 'relative flex flex-col p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all hover:bg-slate-50';
                labelTransfer.className = 'relative flex flex-col p-3 rounded-xl border-2 border-emerald-600 bg-emerald-50/40 cursor-pointer transition-all hover:bg-emerald-50/70';
                indCash.className = 'w-4 h-4 rounded-full border-2 border-slate-300 bg-white flex items-center justify-center';
                indTransfer.className = 'w-4 h-4 rounded-full border-2 border-emerald-600 bg-emerald-600 flex items-center justify-center text-white';
            }
        }

        // Konfirmasi Pelunasan Diterima (Submit Settle)
        async function submitSettlement() {
            if (!currentBookingData) return;

            const btnConfirm = document.getElementById('btnConfirmSettle');
            const origHtml = btnConfirm.innerHTML;
            btnConfirm.disabled = true;
            btnConfirm.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Memproses Pelunasan...</span>
            `;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch("{{ route('admin.scanner.settle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: currentBookingData.id,
                        payment_method: selectedMethod
                    })
                });

                const resData = await response.json();

                if (response.ok && resData.status === 'success') {
                    // Sukses!
                    playBeepSound();
                    showToast('success', 'Pelunasan Berhasil!', resData.message);

                    // Perbarui tampilan di modal menjadi LUNAS
                    document.getElementById('modalStatusBadge').className = 'px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-400 text-emerald-950';
                    document.getElementById('modalStatusBadge').textContent = 'LUNAS (100%)';
                    document.getElementById('modalRemainingAmount').textContent = 'Rp 0';
                    document.getElementById('settlementActionSection').classList.add('hidden');
                    document.getElementById('btnConfirmSettle').classList.add('hidden');
                    document.getElementById('alreadyPaidSection').classList.remove('hidden');
                    document.getElementById('settleInfoDetails').textContent = 
                        `Pelunasan tercatat: ${resData.data.formatted_remaining_paid} via ${resData.data.payment_method} pada ${resData.data.paid_at}`;

                    // Tambahkan baris transaksi baru ke tabel riwayat loket di bawah
                    prependSettlementRow(resData.data);
                } else {
                    showToast('error', 'Gagal Memproses Pelunasan', resData.message || 'Terjadi kesalahan sistem.');
                    btnConfirm.disabled = false;
                    btnConfirm.innerHTML = origHtml;
                }
            } catch (err) {
                console.error('Error saat submit pelunasan:', err);
                showToast('error', 'Koneksi Terputus', 'Gagal mengirim konfirmasi pelunasan ke server.');
                btnConfirm.disabled = false;
                btnConfirm.innerHTML = origHtml;
            }
        }

        // Menambahkan baris ke tabel riwayat secara langsung (Real-time DOM update)
        function prependSettlementRow(data) {
            const tbody = document.getElementById('settlementsTableBody');
            const emptyRow = document.getElementById('emptySettlementsRow');
            if (emptyRow) emptyRow.remove();

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/70 transition-colors bg-emerald-50/40 animate-pulse';
            tr.innerHTML = `
                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">${data.paid_at}</td>
                <td class="py-3.5 px-4 font-mono font-bold text-slate-900 whitespace-nowrap">#${data.booking_code}</td>
                <td class="py-3.5 px-4 font-semibold text-slate-900">${data.customer_name}</td>
                <td class="py-3.5 px-4 text-slate-700">${data.package_name}</td>
                <td class="py-3.5 px-4 font-bold text-emerald-700 whitespace-nowrap">${data.formatted_remaining_paid}</td>
                <td class="py-3.5 px-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                        ${data.payment_method}
                    </span>
                </td>
                <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        LUNAS
                    </span>
                </td>
                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                    <a href="${data.ticket_url}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>E-Tiket</span>
                    </a>
                </td>
            `;

            tbody.insertBefore(tr, tbody.firstChild);

            // Hilangkan efek pulse setelah 3 detik
            setTimeout(() => {
                tr.classList.remove('bg-emerald-50/40', 'animate-pulse');
            }, 3000);
        }

        // Toast Notification Helper
        let toastTimeout = null;
        function showToast(type, title, message) {
            const toast = document.getElementById('statusToast');
            const icon = document.getElementById('toastIcon');
            const titleEl = document.getElementById('toastTitle');
            const msgEl = document.getElementById('toastMessage');

            clearTimeout(toastTimeout);

            toast.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-900', 'bg-rose-50', 'border-rose-200', 'text-rose-900', 'bg-blue-50', 'border-blue-200', 'text-blue-900');

            if (type === 'success') {
                toast.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-900');
                icon.innerHTML = '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
            } else if (type === 'error') {
                toast.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-900');
                icon.innerHTML = '<svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
            } else {
                toast.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-900');
                icon.innerHTML = '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            }

            titleEl.textContent = title;
            msgEl.textContent = message;

            toastTimeout = setTimeout(() => {
                closeToast();
            }, 5000);
        }

        function closeToast() {
            document.getElementById('statusToast').classList.add('hidden');
        }
    </script>
</body>
</html>