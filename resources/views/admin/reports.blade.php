<x-admin-layout :title="($activeTab === 'laba_rugi' ? 'Laporan Laba Rugi' : 'Laporan Penjualan & Kasir')">
    <x-slot:head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            @media print {
                aside,
                header,
                #filter-box,
                #tab-navigation,
                .no-print,
                button,
                nav {
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

                .print-table th,
                .print-table td {
                    border: 1px solid #cbd5e1 !important;
                    padding: 6px 8px !important;
                }

                .print-header {
                    display: block !important;
                }
            }
        </style>
    </x-slot:head>

    <!-- Header Banner (no-print) -->
    <x-admin.header
        :title="$activeTab === 'laba_rugi' ? 'Laporan Laba Rugi Operasional' : 'Laporan Penjualan & Kasir'"
        :subtitle="$activeTab === 'laba_rugi' ? 'Analisis pendapatan kotor, beban pokok trip armada, operasional basecamp, dan laba bersih paguyuban.' : 'Rekapitulasi transaksi pemesanan tiket tour, arus kas masuk, dan piutang pelunasan sewa Jeep.'"
        class="no-print"
    >
        <x-slot:actions>
            <a href="{{ route('admin.reports.csv', request()->all()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Unduh CSV</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                <x-icon name="printer" class="w-4 h-4" />
                <span>Cetak Laporan</span>
            </button>
        </x-slot:actions>
    </x-admin.header>

    <main class="p-4 sm:p-8 space-y-5 sm:space-y-6 flex-1 overflow-y-auto">

            <!-- TAB SWITCHER NAVIGATION (NO PRINT) -->
            <div id="tab-navigation" class="flex items-center gap-2 border-b border-slate-200 pb-2 no-print">
                <a href="{{ route('admin.reports', array_merge(request()->query(), ['tab' => 'penjualan'])) }}"
                    class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-bold transition-all {{ $activeTab !== 'laba_rugi' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 shadow-2xs' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Rekapitulasi Penjualan & Kas</span>
                </a>
                <a href="{{ route('admin.reports', array_merge(request()->query(), ['tab' => 'laba_rugi'])) }}"
                    class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'laba_rugi' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 shadow-2xs' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Laporan Laba Rugi (P&L)</span>
                </a>
            </div>

            <!-- KOP SURAT RESMI (Hanya Tampil Saat Cetak / Print) -->
            <div class="hidden print-header pb-4 mb-4 border-b-2 border-slate-800 text-center space-y-1">
                <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900">PAGUYUBAN JEEP WUKIRSARI ADVENTURE TOUR</h1>
                <p class="text-xs text-slate-600">Basecamp Cangkringan, Sleman, Daerah Istimewa Yogyakarta &bull; Telp: +62 812-3456-7890</p>
                <h2 class="text-sm font-bold underline uppercase pt-2 text-slate-800">
                    {{ $activeTab === 'laba_rugi' ? 'LAPORAN LABA RUGI KOMPREHENSIF OPERASIONAL JEEP' : 'LAPORAN REKAPITULASI PENJUALAN TIKET TOUR JEEP' }}
                </h2>
                <p class="text-[11px] text-slate-500">
                    Periode: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Semua Periode' }} s/d {{ $endDate ? date('d/m/Y', strtotime($endDate)) : date('d/m/Y') }}
                </p>
            </div>

            @if($activeTab === 'laba_rugi')
            <!-- FILTER CARD: TAB LABA RUGI -->
            <x-admin.filter-card 
                id="filter-box"
                :action="route('admin.reports')" 
                :resetUrl="route('admin.reports', ['tab' => 'laba_rugi'])"
                :liveSearch="false"
                submitLabel="Terapkan Filter"
                gridCols="grid-cols-1 sm:grid-cols-2 lg:grid-cols-4"
                class="no-print">
                
                <x-slot:header>
                    <!-- Mode Filter Periode: Harian, Bulanan, Tahunan, Rentang Tanggal -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold text-slate-700">Pilihan Periode:</span>
                            <div class="inline-flex p-1 bg-slate-100 rounded-xl gap-1">
                                <button type="button" onclick="setPeriodMode('daily')" id="btn-period-daily"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer {{ $period === 'daily' ? 'bg-emerald-700 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                                    Harian
                                </button>
                                <button type="button" onclick="setPeriodMode('monthly')" id="btn-period-monthly"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer {{ $period === 'monthly' ? 'bg-emerald-700 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                                    Bulanan
                                </button>
                                <button type="button" onclick="setPeriodMode('yearly')" id="btn-period-yearly"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer {{ $period === 'yearly' ? 'bg-emerald-700 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                                    Tahunan
                                </button>
                                <button type="button" onclick="setPeriodMode('custom')" id="btn-period-custom"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer {{ $period === 'custom' ? 'bg-emerald-700 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                                    Rentang Tanggal
                                </button>
                            </div>
                        </div>
                        <div class="text-xs text-slate-500 font-semibold">
                            Laporan: <span class="text-emerald-800 font-bold">{{ $periodLabel }}</span>
                        </div>
                    </div>
                </x-slot:header>

                <input type="hidden" name="tab" value="laba_rugi">
                <input type="hidden" name="period" id="periodInput" value="{{ $period }}">

                <!-- Input Periode Harian -->
                <div id="period-sec-daily" class="{{ $period === 'daily' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Pilih Tanggal Operasional
                    </label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                </div>

                <!-- Input Periode Bulanan -->
                <div id="period-sec-monthly" class="{{ $period === 'monthly' ? '' : 'hidden' }} grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Bulan</label>
                        <select name="month" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                            @php
                            $allMonths = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            @endphp
                            @foreach($allMonths as $mNum => $mName)
                            <option value="{{ $mNum }}" {{ (int)$selectedMonth === (int)$mNum ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun</label>
                        <select name="year" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                            @for($y = 2024; $y <= 2030; $y++)
                                <option value="{{ $y }}" {{ (int)$selectedYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>

                <!-- Input Periode Tahunan -->
                <div id="period-sec-yearly" class="{{ $period === 'yearly' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Tahun</label>
                    <select name="year_annual" onchange="document.querySelector('#period-sec-monthly select[name=year]').value = this.value" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                        @for($y = 2024; $y <= 2030; $y++)
                            <option value="{{ $y }}" {{ (int)$selectedYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                    </select>
                </div>

                <!-- Input Periode Rentang Tanggal -->
                <div id="period-sec-custom" class="{{ $period === 'custom' ? '' : 'hidden' }} grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                    </div>
                </div>

                <!-- Filter Paket Wisata -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Paket Wisata</label>
                    <select name="package_id" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                        <option value="">Semua Paket Wisata</option>
                        @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ (string)$packageId === (string)$pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </x-admin.filter-card>

            @else
            <!-- FILTER CARD: TAB PENJUALAN & KAS -->
            <x-admin.filter-card 
                id="filter-box"
                :action="route('admin.reports')" 
                :resetUrl="route('admin.reports', ['tab' => 'penjualan'])"
                :liveSearch="false"
                submitLabel="Terapkan Filter"
                gridCols="grid-cols-1 sm:grid-cols-2 lg:grid-cols-5"
                class="no-print">

                <input type="hidden" name="tab" value="penjualan">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Dari Tanggal Trip</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Tagihan</label>
                    <select name="status" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                        <option value="ALL" {{ $status === 'ALL' ? 'selected' : '' }}>Semua Status</option>
                        <option value="FULL_PAID" {{ $status === 'FULL_PAID' ? 'selected' : '' }}>LUNAS (100%)</option>
                        <option value="DP_PAID" {{ $status === 'DP_PAID' ? 'selected' : '' }}>DP Terbayar (Sisa Loket)</option>
                        <option value="PENDING" {{ $status === 'PENDING' ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="CANCELLED" {{ $status === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Paket Wisata</label>
                    <select name="package_id" class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white shadow-2xs">
                        <option value="">Semua Paket Wisata</option>
                        @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ (string)$packageId === (string)$pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                        @endforeach
                    </select>
                </div>

            </x-admin.filter-card>
            @endif

            @if($activeTab === 'laba_rugi')
            <!-- ========================================== -->
            <!-- TAB 2: LAPORAN LABA RUGI (P&L STATEMENT)   -->
            <!-- ========================================== -->

            <!-- 4 KARTU METRIK LABA RUGI (SESUAI REQUEST USER) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-5">
                <!-- 1. Card Total Pendapatan -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pendapatan</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight block">
                            Rp {{ number_format($grossIncome, 0, ',', '.') }}
                        </span>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1 flex items-center gap-1.5 flex-wrap">
                            <span class="text-emerald-700 font-bold">Paket: Rp {{ number_format($incomePackage, 0, ',', '.') }}</span>
                            @if($incomePickup > 0)
                            <span>&bull; Shuttle: Rp {{ number_format($incomePickup, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- 2. Card Total Pengeluaran -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pengeluaran</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-rose-700 tracking-tight block">
                            Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                        </span>
                        <p class="text-[11px] font-semibold text-slate-500 mt-1 flex items-center gap-1.5 flex-wrap">
                            <span class="text-rose-600 font-bold">Operasional: Rp {{ number_format($totalOperationalExpenses ?? $totalExpenses, 0, ',', '.') }}</span>
                            @if(!empty($paymentGatewayFee) && $paymentGatewayFee > 0)
                            <span>&bull; Gateway: Rp {{ number_format($paymentGatewayFee, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- 3. Card Laba Bersih -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Laba Bersih</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-emerald-800 tracking-tight block">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </span>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Margin {{ $netMargin }}%
                            </span>
                            <span class="text-[11px] font-semibold text-slate-500">
                                Rp {{ number_format($avgProfitPerJeep, 0, ',', '.') }}/Jeep
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 4. Card Jumlah Booking Berhasil -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Booking Berhasil</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight block">
                            {{ $pnlOrdersCount }} <span class="text-xs font-normal text-slate-400">Order</span>
                        </span>
                        <p class="text-[11px] font-semibold text-blue-700 mt-1 flex items-center gap-1">
                            <span>{{ $pnlJeepsCount }} Unit Jeep Operasional</span>
                        </p>
                    </div>
                </div>
            </div>

    <!-- GRAFIK PENDAPATAN DAN PENGELUARAN (SESUAI REQUEST USER) -->
    <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs no-print space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <span>Grafik Pendapatan vs Pengeluaran</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Visualisasi tren perbandingan arus omzet dan beban pengeluaran ({{ $periodLabel }})
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 mr-2">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Pendapatan</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Pengeluaran</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Laba Bersih</span>
                </div>
                <div class="inline-flex p-1 bg-slate-100 rounded-xl gap-1">
                    <button type="button" id="btnChartBar" onclick="setChartType('bar')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-2xs transition-all cursor-pointer">
                        Bar
                    </button>
                    <button type="button" id="btnChartLine" onclick="setChartType('line')" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition-all cursor-pointer">
                        Line
                    </button>
                </div>
            </div>
        </div>

        <!-- Canvas Container -->
        <div class="relative h-64 sm:h-80 md:h-96 w-full">
            <canvas id="chartPnlCanvas"></canvas>
        </div>
    </div>

    <!-- TABEL RINCIAN PENDAPATAN DAN PENGELUARAN (SESUAI REQUEST USER) -->
    <!-- 1. LAPORAN LABA RUGI KOMPREHENSIF (STANDAR SAK EMKM) -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-gradient-to-r from-slate-50 to-white">
            <div>
                <h3 class="text-base font-bold text-slate-800">Tabel Rincian Laba Rugi Komprehensif</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Periode: {{ $periodLabel }} &bull; Standar Akuntansi Entitas Mikro, Kecil, dan Menengah (SAK EMKM)
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0 self-start sm:self-auto">
                Net Profit Margin: {{ $netMargin }}%
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs print-table min-w-[550px]">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 sm:px-6">Uraian / Akun Laba Rugi</th>
                        <th class="py-3 px-4 sm:px-6 text-right whitespace-nowrap w-48">Jumlah (Rp)</th>
                        <th class="py-3 px-4 sm:px-6 text-right whitespace-nowrap w-28">Porsi (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <!-- I. PENDAPATAN USAHA -->
                    <tr class="bg-slate-50/70 font-bold text-slate-900">
                        <td class="py-3 px-4 sm:px-6 uppercase tracking-wide text-xs" colspan="3">
                            I. Pendapatan Usaha (Gross Revenue)
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Pendapatan Sewa Paket Tour Jeep Wisata
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            Rp {{ number_format($incomePackage, 0, ',', '.') }}
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            {{ $grossIncome > 0 ? round(($incomePackage / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Pendapatan Layanan Antar-Jemput (Shuttle)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            Rp {{ number_format($incomePickup, 0, ',', '.') }}
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            {{ $grossIncome > 0 ? round(($incomePickup / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    <tr class="bg-emerald-50/60 font-bold text-emerald-900 border-t border-b border-emerald-100">
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10 font-bold">
                            Total Pendapatan Usaha (Omzet)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-emerald-800 font-extrabold">
                            Rp {{ number_format($grossIncome, 0, ',', '.') }}
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-emerald-800 font-bold">
                            100,0%
                        </td>
                    </tr>

                    <!-- II. BEBAN POKOK OPERASIONAL (HPP) -->
                    <tr class="bg-slate-50/70 font-bold text-slate-900">
                        <td class="py-3 px-4 sm:px-6 uppercase tracking-wide text-xs" colspan="3">
                            II. Beban Pokok Operasional Trip & Armada (HPP)
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Bagi Hasil & Honor Driver Paguyuban (45% Paket)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            (Rp {{ number_format($driverCost, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            {{ $grossIncome > 0 ? round(($driverCost / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Biaya Bahan Bakar (BBM) & Perawatan Mesin Jeep (20% Paket)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            (Rp {{ number_format($fuelMaintenanceCost, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            {{ $grossIncome > 0 ? round(($fuelMaintenanceCost / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Retribusi Objek Wisata & Tiket TPR Destinasi Merapi (10% Paket)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            (Rp {{ number_format($retributionCost, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            {{ $grossIncome > 0 ? round(($retributionCost / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @if($incomePickup > 0)
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Biaya Operasional Bahan Bakar Shuttle Antar-Jemput (60%)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            (Rp {{ number_format($pickupCost, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            {{ $grossIncome > 0 ? round(($pickupCost / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endif
                    <tr class="bg-rose-50/50 font-bold text-rose-900 border-t border-b border-rose-100">
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Subtotal Beban Pokok Operasional (HPP)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-rose-800 font-extrabold">
                            (Rp {{ number_format($totalCogs, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-rose-800 font-bold">
                            {{ $grossIncome > 0 ? round(($totalCogs / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- III. LABA KOTOR -->
                    <tr class="bg-slate-100/80 font-black text-slate-900 border-t border-b border-slate-300">
                        <td class="py-3 px-4 sm:px-6 uppercase tracking-wide text-xs">
                            III. Laba Kotor Operasional (Gross Profit)
                        </td>
                        <td class="py-3 px-4 sm:px-6 text-right text-slate-900 font-extrabold text-sm">
                            Rp {{ number_format($grossProfit, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 sm:px-6 text-right text-slate-800 font-bold">
                            {{ $grossMargin }}%
                        </td>
                    </tr>

                    <!-- IV. BEBAN OPERASIONAL BASECAMP & ADMINISTRASI -->
                    <tr class="bg-slate-50/70 font-bold text-slate-900">
                        <td class="py-3 px-4 sm:px-6 uppercase tracking-wide text-xs" colspan="3">
                            IV. Beban Umum & Operasional Basecamp (OPEX)
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Operasional Basecamp, Fasilitas Tamu, Listrik & Air Mineral (5%)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            (Rp {{ number_format($basecampCost, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            5,0%
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Administrasi Kasir, Sistem & Payment Gateway Fee (2%)
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right font-semibold text-slate-800">
                            (Rp {{ number_format($adminSystemCost, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-slate-500">
                            2,0%
                        </td>
                    </tr>
                    <tr class="bg-amber-50/50 font-bold text-amber-900 border-t border-b border-amber-100">
                        <td class="py-2.5 px-4 sm:px-6 pl-8 sm:pl-10">
                            Subtotal Beban Umum & Operasional Basecamp
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-amber-800 font-extrabold">
                            (Rp {{ number_format($totalOpex, 0, ',', '.') }})
                        </td>
                        <td class="py-2.5 px-4 sm:px-6 text-right text-amber-800 font-bold">
                            7,0%
                        </td>
                    </tr>

                    <!-- V. TOTAL SELURUH BEBAN PENGELUARAN -->
                    <tr class="bg-rose-100/70 font-black text-rose-950 border-t border-b border-rose-300">
                        <td class="py-3 px-4 sm:px-6 uppercase tracking-wide text-xs">
                            V. Total Pengeluaran (HPP + OPEX)
                        </td>
                        <td class="py-3 px-4 sm:px-6 text-right text-rose-900 font-black text-sm">
                            (Rp {{ number_format($totalExpenses, 0, ',', '.') }})
                        </td>
                        <td class="py-3 px-4 sm:px-6 text-right text-rose-900 font-bold">
                            {{ $grossIncome > 0 ? round(($totalExpenses / $grossIncome) * 100, 1) : 0 }}%
                        </td>
                    </tr>

                    <!-- VI. LABA BERSIH PAGUYUBAN -->
                    <tr class="bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-950 font-black border-t-2 border-b-2 border-emerald-500">
                        <td class="py-3.5 px-4 sm:px-6 uppercase tracking-wide text-xs sm:text-sm">
                            VI. Laba Bersih Paguyuban (Net Profit)
                        </td>
                        <td class="py-3.5 px-4 sm:px-6 text-right text-emerald-900 font-black text-sm sm:text-base">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 sm:px-6 text-right text-emerald-900 font-black text-xs sm:text-sm">
                            {{ $netMargin }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- RINGKASAN STRUKTUR BIAYA & ALOKASI PENDAPATAN (NO PRINT) -->
        <div class="p-4 sm:p-6 bg-slate-50 border-t border-slate-200 no-print space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                <span>Visualisasi Alokasi Biaya & Margin Laba</span>
                <span>Total Omzet: Rp {{ number_format($grossIncome, 0, ',', '.') }}</span>
            </div>
            <div class="w-full h-3 rounded-full overflow-hidden flex bg-slate-200">
                <div style="width: 45%" class="bg-blue-600" title="Bagi Hasil Driver: 45%"></div>
                <div style="width: 20%" class="bg-amber-500" title="BBM & Armada: 20%"></div>
                <div style="width: 10%" class="bg-purple-600" title="Retribusi Wisata: 10%"></div>
                <div style="width: 7%" class="bg-slate-400" title="Operasional Basecamp & Sistem: 7%"></div>
                <div style="width: 18%" class="bg-emerald-600" title="Laba Bersih Paguyuban: 18%"></div>
            </div>
            <div class="flex items-center gap-3 sm:gap-6 flex-wrap text-[11px] font-semibold text-slate-600 pt-1">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Driver (45%)</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> BBM & Mesin (20%)</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span> Retribusi (10%)</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Basecamp & Sistem (7%)</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600 font-bold text-emerald-800"></span> Laba Bersih (18%)</div>
            </div>
        </div>
    </div>

    <!-- 2. TABEL RINCIAN KINERJA PER PAKET WISATA -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden no-print">
        <div class="p-4 sm:p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
            <h3 class="text-base font-bold text-slate-800">Rincian Kontribusi per Paket Wisata</h3>
            <p class="text-xs text-slate-500 mt-0.5">Analisis pendapatan sewa paket, beban operasional, dan kontribusi laba bersih.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 sm:px-6">Paket Wisata</th>
                        <th class="py-3 px-4 text-center">Pesanan</th>
                        <th class="py-3 px-4 text-center">Unit Jeep</th>
                        <th class="py-3 px-4 text-right">Pendapatan</th>
                        <th class="py-3 px-4 text-right">Beban Operasional</th>
                        <th class="py-3 px-4 text-right">Laba Bersih</th>
                        <th class="py-3 px-4 sm:px-6 text-right">Margin (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($packageBreakdown as $pb)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 sm:px-6 font-bold text-slate-900">
                            {{ $pb['name'] }}
                        </td>
                        <td class="py-3 px-4 text-center font-semibold">
                            {{ $pb['orders'] }}
                        </td>
                        <td class="py-3 px-4 text-center font-semibold text-slate-600">
                            {{ $pb['jeeps'] }} Unit
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-slate-900">
                            Rp {{ number_format($pb['revenue'], 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-rose-700">
                            Rp {{ number_format($pb['total_cost'], 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-800">
                            Rp {{ number_format($pb['net_profit'], 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 sm:px-6 text-right font-bold text-emerald-700">
                            {{ $pb['margin'] }}%
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Tidak ada data paket pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. TABEL DAFTAR TRANSAKSI TRIP PERIODE INI (MOBILE & DESKTOP FRIENDLY) -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden no-print">
        <div class="p-4 sm:p-6 border-b border-slate-200 flex items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Transaksi Trip Sukses Periode Ini</h3>
                <p class="text-xs text-slate-500 mt-0.5">Rincian booking terverifikasi yang membentuk omzet dan laba periode ini.</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">
                {{ $pnlBookings->count() }} Transaksi
            </span>
        </div>

        <!-- Tampilan Mobile (Kartu) -->
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($pnlBookings as $idx => $pb)
            @php
            $bRev = (float)($pb->package_subtotal + $pb->pickup_fee);
            $bCogs = round($pb->package_subtotal * 0.75 + $pb->pickup_fee * 0.60);
            $bOpex = round($bRev * 0.07);
            $bExp = $bCogs + $bOpex;
            $bNet = $bRev - $bExp;
            @endphp
            <div class="p-4 space-y-2 hover:bg-slate-50/80 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="font-mono font-bold text-xs text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                        {{ $pb->booking_code }}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-500">
                        {{ date('d/m/Y', strtotime($pb->trip_date)) }}
                    </span>
                </div>
                <div class="text-xs">
                    <span class="font-bold text-slate-900">{{ $pb->customer_name }}</span>
                    <span class="text-slate-500 block">{{ $pb->package->name ?? 'Paket Jeep' }} &bull; {{ $pb->jeep_quantity }} Jeep</span>
                </div>
                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                    <div class="bg-slate-50 p-1.5 rounded-lg">
                        <span class="text-[10px] text-slate-500 block">Pendapatan</span>
                        <span class="text-xs font-bold text-slate-900">Rp {{ number_format($bRev, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-rose-50/60 p-1.5 rounded-lg">
                        <span class="text-[10px] text-rose-600 block">Pengeluaran</span>
                        <span class="text-xs font-bold text-rose-700">Rp {{ number_format($bExp, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-emerald-50/60 p-1.5 rounded-lg">
                        <span class="text-[10px] text-emerald-600 block">Laba Bersih</span>
                        <span class="text-xs font-bold text-emerald-800">Rp {{ number_format($bNet, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 text-xs">
                Tidak ada transaksi trip pada periode ini.
            </div>
            @endforelse
        </div>

        <!-- Tampilan Desktop (Tabel) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Tanggal Trip</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Paket Wisata</th>
                        <th class="py-3 px-4 text-right">Pendapatan</th>
                        <th class="py-3 px-4 text-right">Pengeluaran</th>
                        <th class="py-3 px-4 text-right">Laba Bersih</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($pnlBookings as $idx => $pb)
                    @php
                    $bRev = (float)($pb->package_subtotal + $pb->pickup_fee);
                    $bCogs = round($pb->package_subtotal * 0.75 + $pb->pickup_fee * 0.60);
                    $bOpex = round($bRev * 0.07);
                    $bExp = $bCogs + $bOpex;
                    $bNet = $bRev - $bExp;
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4">
                            <span class="font-mono font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                {{ $pb->booking_code }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">
                            {{ date('d/m/Y', strtotime($pb->trip_date)) }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-slate-900 block">{{ $pb->customer_name }}</span>
                            <span class="text-[11px] text-slate-500">{{ $pb->customer_phone }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-800">{{ $pb->package->name ?? 'Paket Jeep' }}</span>
                            <span class="text-[11px] text-slate-500 block">{{ $pb->jeep_quantity }} Jeep</span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-slate-900">
                            Rp {{ number_format($bRev, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-rose-700">
                            Rp {{ number_format($bExp, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-800">
                            Rp {{ number_format($bNet, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pb->booking_status === 'FULL_PAID' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $pb->booking_status === 'FULL_PAID' ? 'LUNAS' : 'DP' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            Tidak ada data transaksi pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else
    <!-- ============================================== -->
    <!-- TAB 1: REKAPITULASI PENJUALAN & KASIR TRANSAKSI -->
    <!-- ============================================== -->

    <!-- 4 KARTU STATISTIK PENJUALAN (KPIs) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 md:gap-5">
        <!-- 1. Total Kas Masuk (Terbayar) -->
        <div class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between min-w-0">
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs font-semibold text-slate-500 block truncate">Kas Masuk (Terbayar)</span>
                <span class="text-base sm:text-xl md:text-2xl font-black text-emerald-700 block mt-0.5 sm:mt-1 tracking-tight truncate">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-emerald-700 font-semibold mt-1 truncate">
                    Kas Real Diterima
                </span>
            </div>
        </div>

        <!-- 2. Total Nilai Penjualan -->
        <div class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between min-w-0">
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs font-semibold text-slate-500 block truncate">Total Nilai Penjualan</span>
                <span class="text-base sm:text-xl md:text-2xl font-black text-slate-900 block mt-0.5 sm:mt-1 tracking-tight truncate">
                    Rp {{ number_format($totalBilled, 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-slate-600 font-semibold mt-1 truncate">
                    Akumulasi Tagihan
                </span>
            </div>
        </div>

        <!-- 3. Sisa Belum Lunas (Piutang) -->
        <div class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between min-w-0">
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs font-semibold text-slate-500 block truncate">Sisa Piutang Loket</span>
                <span class="text-base sm:text-xl md:text-2xl font-black text-amber-700 block mt-0.5 sm:mt-1 tracking-tight truncate">
                    Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-amber-700 font-semibold mt-1 truncate">
                    Pelunasan di Loket
                </span>
            </div>
        </div>

        <!-- 4. Transaksi & Armada -->
        <div class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between min-w-0">
            <div class="min-w-0">
                <span class="text-[11px] sm:text-xs font-semibold text-slate-500 block truncate">Transaksi & Armada</span>
                <span class="text-base sm:text-xl md:text-2xl font-black text-slate-900 block mt-0.5 sm:mt-1 tracking-tight truncate">
                    {{ $totalOrders }} <span class="text-xs font-normal text-slate-400">Order</span> / {{ $totalJeepsBooked }} <span class="text-xs font-normal text-slate-400">Jeep</span>
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-slate-600 font-semibold mt-1 truncate">
                    Volume Pemesanan
                </span>
            </div>
        </div>
    </div>

    <!-- DAFTAR TRANSAKSI PENJUALAN -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white no-print">
            <div>
                <h3 class="text-base font-bold text-slate-800">Rincian Transaksi Penjualan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi pesanan tiket Jeep, nominal kas terbayar, dan sisa pelunasan.</p>
            </div>
        </div>

        <!-- 1. TAMPILAN MOBILE: KARTU TRANSAKSI (SANGAT MUDAH DIBACA DI HP, NO HORIZONTAL SCROLL) -->
        <div class="block md:hidden divide-y divide-slate-100 no-print">
            @forelse($bookings as $idx => $b)
            <div class="p-3.5 sm:p-4 space-y-2.5 hover:bg-slate-50/80 transition-colors">
                <!-- Header Kartu: Kode & Status -->
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold inline-flex items-center justify-center">
                            {{ $bookings->firstItem() + $idx }}
                        </span>
                        <span class="font-mono font-bold text-xs text-emerald-800 px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-100">
                            {{ $b->booking_code }}
                        </span>
                    </div>
                    <div>
                        @if($b->booking_status === 'FULL_PAID')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            LUNAS (100%)
                        </span>
                        @elseif($b->booking_status === 'DP_PAID')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            DP LOKET
                        </span>
                        @elseif($b->booking_status === 'CANCELLED')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                            BATAL
                        </span>
                        @elseif($b->booking_status === 'COMPLETED')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 border border-teal-200">
                            SELESAI
                        </span>
                        @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $b->booking_status }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Baris Info Pemesan & Trip -->
                <div class="space-y-1 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900">{{ $b->customer_name }}</span>
                        <span class="text-slate-500 font-mono text-[11px]">{{ $b->customer_phone }}</span>
                    </div>
                    <div class="text-[11px] text-slate-600 flex items-center justify-between flex-wrap gap-1">
                        <span>{{ $b->package->name ?? '-' }} ({{ $b->jeep_quantity }} Unit)</span>
                        <span class="font-medium text-slate-500">{{ $b->trip_date ? $b->trip_date->format('d/m/Y') : '-' }} &bull; {{ $b->slot->slot_label ?? 'Sesi Trip' }}</span>
                    </div>
                </div>

                <!-- Strip Keuangan (Ringkas 3 Kolom) -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 grid grid-cols-3 gap-2 text-center text-xs">
                    <div>
                        <span class="text-[10px] text-slate-500 block">Total</span>
                        <span class="font-bold text-slate-800 block text-[11px]">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-emerald-600 block">Terbayar</span>
                        <span class="font-bold text-emerald-700 block text-[11px]">Rp {{ number_format($b->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-amber-600 block">Sisa</span>
                        <span class="font-bold {{ $b->remaining_amount > 0 ? 'text-amber-700' : 'text-slate-400' }} block text-[11px]">Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-10 text-center text-slate-400 px-4">
                <p class="font-bold text-slate-700 text-sm">Tidak ada data transaksi</p>
                <p class="text-xs text-slate-500 mt-1">Tidak ditemukan transaksi penjualan yang sesuai dengan filter.</p>
            </div>
            @endforelse
        </div>

        <!-- 2. TAMPILAN DESKTOP & CETAK (TABEL LENGKAP 10 KOLOM) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 print-table min-w-[760px]">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3.5 w-12 text-center">No</th>
                        <th class="py-3 px-4 whitespace-nowrap min-w-[130px]">Kode Booking</th>
                        <th class="py-3 px-4 whitespace-nowrap min-w-[110px]">Tanggal Trip</th>
                        <th class="py-3 px-4 min-w-[170px]">Pelanggan</th>
                        <th class="py-3 px-4 min-w-[170px]">Paket & Sesi</th>
                        <th class="py-3 px-3 text-center whitespace-nowrap">Jeep</th>
                        <th class="py-3 px-4 text-right whitespace-nowrap">Tagihan Total</th>
                        <th class="py-3 px-4 text-right whitespace-nowrap">Sudah Dibayar</th>
                        <th class="py-3 px-4 text-right whitespace-nowrap">Sisa Tagihan</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $idx => $b)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- No -->
                        <td class="py-3.5 px-3.5 text-center">
                            <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs inline-flex items-center justify-center">
                                {{ $bookings->firstItem() + $idx }}
                            </span>
                        </td>

                        <!-- Kode Booking -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-bold font-mono text-xs text-emerald-800 px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-100">
                                {{ $b->booking_code }}
                            </span>
                        </td>

                        <!-- Tanggal Trip -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-semibold text-slate-700 text-xs">
                                {{ $b->trip_date ? $b->trip_date->format('d/m/Y') : '-' }}
                            </span>
                        </td>

                        <!-- Pelanggan -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-0.5">
                                <span class="font-bold text-slate-900 text-xs block leading-tight">{{ $b->customer_name }}</span>
                                <span class="text-[11px] text-slate-500 font-mono block">{{ $b->customer_phone }}</span>
                            </div>
                        </td>

                        <!-- Paket & Sesi -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-0.5">
                                <span class="font-semibold text-slate-800 text-xs block leading-tight">{{ $b->package->name ?? '-' }}</span>
                                <span class="text-[11px] text-slate-500 block">
                                    {{ $b->slot->slot_label ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Jeep -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 font-bold text-slate-800 text-xs">
                                {{ $b->jeep_quantity }} <span class="font-normal text-[10px] text-slate-500 uppercase">Unit</span>
                            </span>
                        </td>

                        <!-- Tagihan Total -->
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <span class="font-bold text-slate-800 text-xs">
                                Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- Sudah Dibayar -->
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <span class="font-bold text-emerald-700 text-xs">
                                Rp {{ number_format($b->paid_amount, 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- Sisa Tagihan -->
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            @if($b->remaining_amount > 0)
                            <span class="font-bold text-amber-700 text-xs px-2 py-0.5 rounded-md bg-amber-50 border border-amber-100">
                                Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                            </span>
                            @else
                            <span class="font-semibold text-slate-400 text-xs">
                                Rp 0
                            </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($b->booking_status === 'FULL_PAID')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                LUNAS
                            </span>
                            @elseif($b->booking_status === 'DP_PAID')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                DP LOKET
                            </span>
                            @elseif($b->booking_status === 'CANCELLED')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                BATAL
                            </span>
                            @elseif($b->booking_status === 'COMPLETED')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                SELESAI
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $b->booking_status }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-slate-400">
                            <div class="max-w-sm mx-auto space-y-2">
                                <p class="font-bold text-slate-700 text-sm">Tidak ada data transaksi</p>
                                <p class="text-xs text-slate-500">Tidak ditemukan transaksi penjualan yang sesuai dengan filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/90 font-bold text-slate-800 border-t-2 border-slate-200">
                    <tr>
                        <td colspan="6" class="py-3.5 px-4 text-right uppercase tracking-wider text-[11px] font-bold text-slate-600">
                            Subtotal Halaman Ini:
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-slate-900 text-xs">
                            Rp {{ number_format($bookings->sum('total_amount'), 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-emerald-800 text-xs">
                            Rp {{ number_format($bookings->sum('paid_amount'), 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-amber-800 text-xs">
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
    @endif

    <!-- TANDA TANGAN REKAP RESMI (Hanya Tampil Saat Print) -->
    <div class="hidden print-header pt-12 mt-6 border-t border-slate-300">
        <div class="flex justify-between text-xs text-slate-800">
            <div class="text-center w-64 space-y-16">
                <span>Mengetahui,<br><strong>Ketua Paguyuban Jeep Wukirsari</strong></span>
                <div class="border-b border-slate-400 w-48 mx-auto"></div>
                <span>( .................................................. )</span>
            </div>

            <div class="text-center w-64 space-y-16">
                <span>Sleman, {{ date('d F Y') }}<br><strong>Petugas Kasir / Bendahara</strong></span>
                <div class="border-b border-slate-400 w-48 mx-auto"></div>
                <span><strong>{{ Auth::user()->name ?? 'Administrator' }}</strong></span>
            </div>
        </div>
    </div>

    </main>
    </div>

    @if($activeTab === 'laba_rugi')
    <!-- Data JSON Chart -->
    <script type="application/json" id="pnlChartData">
        {
            !!json_encode([
                'labels' => $chartLabels,
                'revenue' => $chartRevenueData,
                'expense' => $chartExpenseData,
                'netProfit' => $chartNetProfitData,
            ]) !!
        }
    </script>
    <script>
        let pnlChart = null;
        let currentChartType = 'bar';

        function initPnlChart() {
            const raw = document.getElementById('pnlChartData');
            if (!raw) return;
            const data = JSON.parse(raw.textContent);
            const canvas = document.getElementById('chartPnlCanvas');
            if (!canvas) return;

            if (pnlChart) {
                pnlChart.destroy();
            }

            const isBar = currentChartType === 'bar';

            pnlChart = new Chart(canvas, {
                type: currentChartType,
                data: {
                    labels: data.labels,
                    datasets: [{
                            label: 'Pendapatan',
                            data: data.revenue,
                            backgroundColor: isBar ? 'rgba(16, 185, 129, 0.85)' : 'rgba(16, 185, 129, 0.15)',
                            borderColor: '#10B981',
                            borderWidth: isBar ? 0 : 2.5,
                            borderRadius: isBar ? 6 : 0,
                            fill: !isBar,
                            tension: 0.35,
                        },
                        {
                            label: 'Pengeluaran',
                            data: data.expense,
                            backgroundColor: isBar ? 'rgba(244, 63, 94, 0.85)' : 'rgba(244, 63, 94, 0.15)',
                            borderColor: '#F43F5E',
                            borderWidth: isBar ? 0 : 2.5,
                            borderRadius: isBar ? 6 : 0,
                            fill: !isBar,
                            tension: 0.35,
                        },
                        {
                            label: 'Laba Bersih',
                            data: data.netProfit,
                            backgroundColor: isBar ? 'rgba(59, 130, 246, 0.85)' : 'rgba(59, 130, 246, 0.10)',
                            borderColor: '#3B82F6',
                            borderWidth: isBar ? 0 : 2.5,
                            borderRadius: isBar ? 6 : 0,
                            fill: false,
                            tension: 0.35,
                            type: isBar ? 'bar' : 'line',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12,
                                usePointStyle: true,
                                font: {
                                    family: 'Inter',
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: {
                                family: 'Inter',
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                family: 'Inter',
                                size: 12
                            },
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed.y || 0;
                                    return ' ' + context.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 10,
                                    weight: '500'
                                },
                                color: '#64748B',
                                maxRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 15
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F1F5F9'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 10
                                },
                                color: '#64748B',
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        function setChartType(type) {
            currentChartType = type;
            const btnBar = document.getElementById('btnChartBar');
            const btnLine = document.getElementById('btnChartLine');
            if (type === 'bar') {
                if (btnBar) btnBar.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-2xs transition-all cursor-pointer';
                if (btnLine) btnLine.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
            } else {
                if (btnLine) btnLine.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-2xs transition-all cursor-pointer';
                if (btnBar) btnBar.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
            }
            initPnlChart();
        }

        function setPeriodMode(mode) {
            const input = document.getElementById('periodInput');
            if (input) input.value = mode;

            ['daily', 'monthly', 'yearly', 'custom'].forEach(p => {
                const btn = document.getElementById('btn-period-' + p);
                const sec = document.getElementById('period-sec-' + p);
                if (p === mode) {
                    if (btn) btn.className = 'px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer bg-emerald-700 text-white font-bold shadow-xs';
                    if (sec) sec.classList.remove('hidden');
                } else {
                    if (btn) btn.className = 'px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer text-slate-600 hover:text-slate-900 font-semibold';
                    if (sec) sec.classList.add('hidden');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initPnlChart();
        });
    </script>
    @endif
</x-admin-layout>