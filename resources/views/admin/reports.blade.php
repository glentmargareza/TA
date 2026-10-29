<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan | Admin Jeep Wukirsari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wukirsari: {
                            primary: '#1E3F20',
                            dark: '#142B16',
                            hover: '#265029',
                            sage: '#E8EFE9',
                            cream: '#F7F7F2',
                            border: '#E2EAE3',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            aside, header, #filter-box, .no-print, button {
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
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row font-sans">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0" id="print-area">
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between no-print">
            <h2 class="text-base font-bold text-slate-800">
                Laporan Penjualan & Rekapitulasi Kasir
            </h2>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.reports.csv', request()->all()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh CSV</span>
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl bg-gradient-to-r from-emerald-700 via-[#1E4D2B] to-[#14351C] hover:from-emerald-600 hover:to-[#1a4424] text-white text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </header>

        <main class="p-6 space-y-6 flex-1 overflow-y-auto">

            <!-- KOP SURAT RESMI (Hanya Tampil Saat Cetak / Print) -->
            <div class="hidden print-header pb-4 mb-4 border-b-2 border-slate-800 text-center space-y-1">
                <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900">PAGUYUBAN JEEP WUKIRSARI ADVENTURE TOUR</h1>
                <p class="text-xs text-slate-600">Basecamp Cangkringan, Sleman, Daerah Istimewa Yogyakarta - Telp: +62 812-3456-7890</p>
                <h2 class="text-sm font-bold underline uppercase pt-2 text-slate-800">LAPORAN REKAPITULASI PENJUALAN TIKET TOUR JEEP</h2>
                <p class="text-[11px] text-slate-500">
                    Periode: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Awal' }} s/d {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Hari Ini' }}
                </p>
            </div>

            <!-- FILTER BOX (NO PRINT) -->
            <div id="filter-box" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs no-print">
                <form method="GET" action="{{ route('admin.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal Trip</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Tagihan</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary bg-slate-50">
                            <option value="ALL" {{ $status === 'ALL' ? 'selected' : '' }}>Semua Status</option>
                            <option value="FULL_PAID" {{ $status === 'FULL_PAID' ? 'selected' : '' }}>LUNAS (100%)</option>
                            <option value="DP_PAID" {{ $status === 'DP_PAID' ? 'selected' : '' }}>DP Terbayar (Sisa di Loket)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Paket Wisata</label>
                        <select name="package_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary bg-slate-50">
                            <option value="">Semua Paket Wisata</option>
                            @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ (string)$packageId === (string)$pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 py-2 rounded-xl bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-2xs cursor-pointer">
                            Terapkan Filter
                        </button>
                        @if($startDate || $endDate || ($status && $status !== 'ALL') || $packageId)
                        <a href="{{ route('admin.reports') }}" class="px-3 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold transition-colors" title="Reset filter">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 4 KARTU STATISTIK PENJUALAN (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Total Kas Masuk (Terbayar)</span>
                    <div class="text-xl font-extrabold text-emerald-700 mt-1">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                    <span class="text-[10px] text-emerald-600 font-medium block mt-0.5">Sudah terverifikasi kasir</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Total Nilai Penjualan</span>
                    <div class="text-xl font-extrabold text-slate-800 mt-1">
                        Rp {{ number_format($totalBilled, 0, ',', '.') }}
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Nilai kotor seluruh reservasi</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Sisa Belum Lunas (Piutang)</span>
                    <div class="text-xl font-extrabold text-wukirsari-primary mt-1">
                        Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                    </div>
                    <span class="text-[10px] text-wukirsari-primary font-medium block mt-0.5">Diterima saat tiba di basecamp</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Transaksi & Armada</span>
                    <div class="text-xl font-extrabold text-slate-800 mt-1">
                        {{ $totalOrders }} Order / {{ $totalJeepsBooked }} Jeep
                    </div>
                    <span class="text-[10px] text-slate-500 font-medium block mt-0.5">Rata-rata: Rp {{ number_format($avgOrderValue, 0, ',', '.') }} / order</span>
                </div>
            </div>

            <!-- TABEL DAFTAR TRANSAKSI PENJUALAN -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between no-print">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Rincian Transaksi Penjualan</h3>
                        <p class="text-xs text-slate-400">Total ditemukan: {{ $bookings->total() }} data transaksi</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs print-table">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-3.5">No</th>
                                <th class="py-3 px-3.5">Kode Booking</th>
                                <th class="py-3 px-3.5">Tanggal Trip</th>
                                <th class="py-3 px-3.5">Pelanggan</th>
                                <th class="py-3 px-3.5">Paket & Sesi</th>
                                <th class="py-3 px-3.5 text-center">Jeep</th>
                                <th class="py-3 px-3.5 text-right">Tagihan Total</th>
                                <th class="py-3 px-3.5 text-right">Sudah Dibayar</th>
                                <th class="py-3 px-3.5 text-right">Sisa Tagihan</th>
                                <th class="py-3 px-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($bookings as $idx => $b)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3.5 text-slate-400">{{ $bookings->firstItem() + $idx }}</td>
                                <td class="py-3 px-3.5 font-bold font-mono text-wukirsari-primary">{{ $b->booking_code }}</td>
                                <td class="py-3 px-3.5 text-slate-600 whitespace-nowrap">
                                    {{ $b->trip_date ? $b->trip_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-3.5">
                                    <span class="font-bold text-slate-800 block">{{ $b->customer_name }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $b->customer_phone }}</span>
                                </td>
                                <td class="py-3 px-3.5">
                                    <span class="font-medium text-slate-700 block">{{ $b->package->name ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 block">⏰ {{ $b->slot->slot_label ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-3.5 text-center font-bold">{{ $b->jeep_quantity }}</td>
                                <td class="py-3 px-3.5 text-right font-medium">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3.5 text-right font-bold text-emerald-700">Rp {{ number_format($b->paid_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3.5 text-right font-bold text-slate-700">Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3.5 text-center">
                                    @if($b->booking_status === 'FULL_PAID')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white">
                                        LUNAS
                                    </span>
                                    @elseif($b->booking_status === 'DP_PAID')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-wukirsari-sage text-wukirsari-primary">
                                        DP LOKET
                                    </span>
                                    @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                        {{ $b->booking_status }}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400">
                                    Tidak ada data transaksi penjualan pada kriteria filter ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50 font-bold text-slate-800 border-t-2 border-slate-200">
                            <tr>
                                <td colspan="6" class="py-3 px-3.5 text-right uppercase tracking-wider text-[11px]">Subtotal Halaman Ini:</td>
                                <td class="py-3 px-3.5 text-right">Rp {{ number_format($bookings->sum('total_amount'), 0, ',', '.') }}</td>
                                <td class="py-3 px-3.5 text-right text-emerald-700">Rp {{ number_format($bookings->sum('paid_amount'), 0, ',', '.') }}</td>
                                <td class="py-3 px-3.5 text-right text-slate-700">Rp {{ number_format($bookings->sum('remaining_amount'), 0, ',', '.') }}</td>
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
