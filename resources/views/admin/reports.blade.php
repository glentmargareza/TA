<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan & Kasir | Admin Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-display, h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }

        @media print {
            aside, header, #filter-box, .no-print, button, nav {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            #print-area {
                display: block !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-table {
                font-size: 11px !important;
                border-collapse: collapse !important;
                width: 100% !important;
            }
            .print-table th, .print-table td {
                border: 1px solid #cbd5e1 !important;
                padding: 6px 8px !important;
            }
            .print-header {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden" id="print-area">
        
        <!-- Header Banner (no-print) -->
        <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs no-print">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-display">
                    Laporan Penjualan & Rekapitulasi Kasir
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi pendapatan tiket tour, arus kas masuk, dan piutang pelunasan sewa Jeep.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                    {{ $bookings->total() }} Transaksi Ditemukan
                </span>
                <a href="{{ route('admin.reports.csv', request()->all()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh CSV</span>
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Laporan</span>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-5 sm:space-y-6 flex-1 overflow-y-auto">

            <!-- KOP SURAT RESMI (Hanya Tampil Saat Cetak / Print) -->
            <div class="hidden print-header pb-4 mb-4 border-b-2 border-slate-800 text-center space-y-1">
                <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900 font-display">PAGUYUBAN JEEP WUKIRSARI ADVENTURE TOUR</h1>
                <p class="text-xs text-slate-600">Basecamp Cangkringan, Sleman, Daerah Istimewa Yogyakarta &bull; Telp: +62 812-3456-7890</p>
                <h2 class="text-sm font-bold underline uppercase pt-2 text-slate-800">LAPORAN REKAPITULASI PENJUALAN TIKET TOUR JEEP</h2>
                <p class="text-[11px] text-slate-500">
                    Periode: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Semua Periode' }} s/d {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Hari Ini' }}
                </p>
            </div>

            <!-- FILTER BOX (NO PRINT) -->
            <div id="filter-box" class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs no-print">
                <form method="GET" action="{{ route('admin.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Dari Tanggal Trip</span>
                        </label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Sampai Tanggal</span>
                        </label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Status Tagihan</span>
                        </label>
                        <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                            <option value="ALL" {{ $status === 'ALL' ? 'selected' : '' }}>Semua Status</option>
                            <option value="FULL_PAID" {{ $status === 'FULL_PAID' ? 'selected' : '' }}>LUNAS (100%)</option>
                            <option value="DP_PAID" {{ $status === 'DP_PAID' ? 'selected' : '' }}>DP Terbayar (Sisa di Loket)</option>
                            <option value="PENDING" {{ $status === 'PENDING' ? 'selected' : '' }}>Menunggu Bayar</option>
                            <option value="CANCELLED" {{ $status === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Paket Wisata</span>
                        </label>
                        <select name="package_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                            <option value="">Semua Paket Wisata</option>
                            @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ (string)$packageId === (string)$pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-emerald-700 to-[#183c21] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold transition-all shadow-xs hover:shadow cursor-pointer flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span>Terapkan Filter</span>
                        </button>
                        @if($startDate || $endDate || ($status && $status !== 'ALL') || $packageId)
                        <a href="{{ route('admin.reports') }}" class="px-3.5 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors shadow-2xs" title="Reset filter">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 4 KARTU STATISTIK PENJUALAN (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- 1. Total Kas Masuk (Terbayar) -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Total Kas Masuk (Terbayar)</span>
                        <span class="text-xl sm:text-2xl font-black text-emerald-700 block mt-1 tracking-tight font-display">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Sudah terverifikasi kasir
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs border border-emerald-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- 2. Total Nilai Penjualan -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Total Nilai Penjualan</span>
                        <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-1 tracking-tight font-display">
                            Rp {{ number_format($totalBilled, 0, ',', '.') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-blue-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Nilai kotor seluruh reservasi
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 shadow-2xs border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m0 0l-6-6m6 6H3m18 12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10z"/></svg>
                    </div>
                </div>

                <!-- 3. Sisa Belum Lunas (Piutang) -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Sisa Belum Lunas (Piutang)</span>
                        <span class="text-xl sm:text-2xl font-black text-amber-700 block mt-1 tracking-tight font-display">
                            Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-amber-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Diterima saat tiba di basecamp
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs border border-amber-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- 4. Transaksi & Armada -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Transaksi & Armada</span>
                        <span class="text-xl sm:text-2xl font-black text-slate-900 block mt-1 tracking-tight font-display">
                            {{ $totalOrders }} <span class="text-xs font-normal text-slate-400">Order</span> / {{ $totalJeepsBooked }} <span class="text-xs font-normal text-slate-400">Jeep</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-purple-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                            Rata-rata: Rp {{ number_format($avgOrderValue, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 shadow-2xs border border-purple-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM4 11h16M4 11l2-6h12l2 6M4 11v6a1 1 0 001 1h1m10 0h1a1 1 0 001-1v-6"/></svg>
                    </div>
                </div>
            </div>

            <!-- TABEL DAFTAR TRANSAKSI PENJUALAN -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white no-print">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 font-display tracking-tight">Rincian Transaksi Penjualan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi pesanan tiket Jeep, nominal kas terbayar, dan sisa pelunasan.</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            Total: {{ $bookings->total() }} Data Transaksi
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700 print-table min-w-[700px]">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider select-none">
                            <tr>
                                <th class="py-4 px-3.5 w-12 text-center">No</th>
                                <th class="py-4 px-4 whitespace-nowrap min-w-[130px]">Kode Booking</th>
                                <th class="py-4 px-4 whitespace-nowrap min-w-[120px]">Tanggal Trip</th>
                                <th class="py-4 px-5 min-w-[180px]">Pelanggan</th>
                                <th class="py-4 px-5 min-w-[180px]">Paket & Sesi</th>
                                <th class="py-4 px-4 text-center whitespace-nowrap">Jeep</th>
                                <th class="py-4 px-5 text-right whitespace-nowrap">Tagihan Total</th>
                                <th class="py-4 px-5 text-right whitespace-nowrap">Sudah Dibayar</th>
                                <th class="py-4 px-5 text-right whitespace-nowrap">Sisa Tagihan</th>
                                <th class="py-4 px-4 text-center whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $idx => $b)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- No -->
                                <td class="py-4 px-3.5 text-center">
                                    <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs inline-flex items-center justify-center">
                                        {{ $bookings->firstItem() + $idx }}
                                    </span>
                                </td>

                                <!-- Kode Booking -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="font-extrabold font-mono text-xs text-emerald-800 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-100">
                                        {{ $b->booking_code }}
                                    </span>
                                </td>

                                <!-- Tanggal Trip -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 font-semibold text-slate-700 text-xs">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>{{ $b->trip_date ? $b->trip_date->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </td>

                                <!-- Pelanggan -->
                                <td class="py-4 px-5">
                                    <div class="space-y-0.5">
                                        <span class="font-extrabold text-slate-900 text-xs block leading-tight">{{ $b->customer_name }}</span>
                                        <span class="text-[11px] text-slate-500 font-mono block">{{ $b->customer_phone }}</span>
                                    </div>
                                </td>

                                <!-- Paket & Sesi -->
                                <td class="py-4 px-5">
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-slate-800 text-xs block leading-tight">{{ $b->package->name ?? '-' }}</span>
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $b->slot->slot_label ?? '-' }}</span>
                                        </span>
                                    </div>
                                </td>

                                <!-- Jeep -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-100 font-black text-slate-800 text-xs font-display">
                                        {{ $b->jeep_quantity }} <span class="font-medium text-[10px] text-slate-500 uppercase">Unit</span>
                                    </span>
                                </td>

                                <!-- Tagihan Total -->
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <span class="font-bold text-slate-800 text-xs font-mono">
                                        Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Sudah Dibayar -->
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <span class="font-extrabold text-emerald-700 text-xs font-mono">
                                        Rp {{ number_format($b->paid_amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Sisa Tagihan -->
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    @if($b->remaining_amount > 0)
                                    <span class="font-extrabold text-amber-700 text-xs font-mono px-2 py-0.5 rounded-md bg-amber-50 border border-amber-100">
                                        Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                    </span>
                                    @else
                                    <span class="font-bold text-slate-400 text-xs font-mono">
                                        Rp 0
                                    </span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if($b->booking_status === 'FULL_PAID')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        <span>LUNAS</span>
                                    </span>
                                    @elseif($b->booking_status === 'DP_PAID')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>DP LOKET</span>
                                    </span>
                                    @elseif($b->booking_status === 'CANCELLED')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>BATAL</span>
                                    </span>
                                    @elseif($b->booking_status === 'COMPLETED')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                        <span>SELESAI</span>
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>{{ $b->booking_status }}</span>
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-slate-400">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <p class="font-bold text-slate-700 text-sm">Tidak ada data transaksi</p>
                                        <p class="text-xs text-slate-500">Tidak ditemukan transaksi penjualan yang sesuai dengan kriteria filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50/90 font-bold text-slate-800 border-t-2 border-slate-200">
                            <tr>
                                <td colspan="6" class="py-4 px-5 text-right uppercase tracking-wider text-[11px] font-bold text-slate-600">
                                    Subtotal Halaman Ini:
                                </td>
                                <td class="py-4 px-5 text-right font-mono font-bold text-slate-900 text-xs">
                                    Rp {{ number_format($bookings->sum('total_amount'), 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-5 text-right font-mono font-extrabold text-emerald-800 text-xs">
                                    Rp {{ number_format($bookings->sum('paid_amount'), 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-5 text-right font-mono font-extrabold text-amber-800 text-xs">
                                    Rp {{ number_format($bookings->sum('remaining_amount'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-100 flex justify-center no-print">
                    {{ $bookings->links() }}
                </div>
                @endif
            </div>

            <!-- TANDA TANGAN REKAP (Hanya Tampil Saat Print) -->
            <div class="hidden print-header pt-12 mt-6 border-t border-slate-300">
                <div class="flex justify-between text-xs text-slate-800">
                    <div class="text-center w-64 space-y-16">
                        <span>Mengetahui,<br><strong>Ketua Paguyuban Jeep Wukirsari</strong></span>
                        <div class="border-b border-slate-400 w-48 mx-auto"></div>
                        <span>( .................................................. )</span>
                    </div>

                    <div class="text-center w-64 space-y-16">
                        <span>Sleman, {{ date('d F Y') }}<br><strong>Petugas Kasir Loket</strong></span>
                        <div class="border-b border-slate-400 w-48 mx-auto"></div>
                        <span><strong>{{ Auth::user()->name ?? 'Administrator' }}</strong></span>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
