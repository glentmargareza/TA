<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengeluaran Operasional | Admin Jeep Wukirsari</title>
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
                        Manajemen Pengeluaran
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase tracking-wide">
                        Operasional
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Catat dan pantau seluruh beban biaya operasional, BBM, armada jeep, dan utilitas basecamp secara akurat.
                </p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('admin.reports', ['tab' => 'laba_rugi']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Laporan Laba Rugi</span>
                </a>
                <button type="button" onclick="openCreateExpenseModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pengeluaran</span>
                </button>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-6 flex-1 overflow-y-auto">

            <!-- Flash Alert Notifikasi -->
            @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer font-bold px-2 py-0.5 rounded-lg hover:bg-emerald-100">✕</button>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 cursor-pointer font-bold px-2 py-0.5 rounded-lg hover:bg-rose-100">✕</button>
            </div>
            @endif

            @if(!empty($errors) && $errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-semibold space-y-1 shadow-xs">
                <div class="flex items-center gap-2 font-bold text-rose-950 mb-1">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Perhatian: Terjadi kesalahan validasi</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 pl-5">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- A. RINGKASAN PENGELUARAN (4 KARTU STATISTIK) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Total Pengeluaran Bulan Ini -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bulan Ini ({{ Carbon\Carbon::now()->translatedFormat('F Y') }})</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 font-display block">
                            Rp {{ number_format($totalThisMonth, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-emerald-700 font-semibold mt-1 flex items-center gap-1">
                            <span>Biaya operasional bulan berjalan</span>
                        </span>
                    </div>
                </div>

                <!-- 2. Total Pengeluaran Hari Ini -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Hari Ini ({{ Carbon\Carbon::now()->translatedFormat('d M') }})</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 font-display block">
                            Rp {{ number_format($totalToday, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-sky-700 font-semibold mt-1 flex items-center gap-1">
                            <span>Pengeluaran hari ini</span>
                        </span>
                    </div>
                </div>

                <!-- 3. Jumlah Transaksi Pengeluaran -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Transaksi</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 font-display block">
                            {{ number_format($totalTransactions) }} <span class="text-sm font-semibold text-slate-500">Nota</span>
                        </span>
                        <span class="text-[11px] text-indigo-700 font-semibold mt-1 flex items-center gap-1">
                            <span>Audit & bukti tersimpan</span>
                        </span>
                    </div>
                </div>

                <!-- 4. Pengeluaran Terbesar -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pengeluaran Terbesar</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 font-display block truncate">
                            {{ $largestExpense ? $largestExpense->formatted_amount : 'Rp 0' }}
                        </span>
                        <span class="text-[11px] text-amber-800 font-semibold mt-1 truncate block" title="{{ $largestExpense ? $largestExpense->name : '-' }}">
                            {{ $largestExpense ? Str::limit($largestExpense->name, 24) : 'Belum ada data' }}
                        </span>
                    </div>
                </div>

            </div>

            <!-- B. FILTER & PENCARIAN -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-4">
                <form action="{{ route('admin.expenses') }}" method="GET" class="space-y-4">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        
                        <!-- Pencarian -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Cari Pengeluaran</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama pengeluaran, no. bukti, keterangan..." class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        <!-- Filter Kategori -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="category" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="ALL">Semua Kategori (8)</option>
                                @foreach($categories as $catKey => $catLabel)
                                <option value="{{ $catKey }}" {{ $category === $catKey ? 'selected' : '' }}>{{ $catLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Periode Cepat -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Rentang Periode</label>
                            <select name="period" id="periodSelect" onchange="toggleCustomDates(this.value)" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Kustom Tanggal</option>
                            </select>
                        </div>

                    </div>

                    <!-- Input Tanggal Kustom (Jika dipilih) -->
                    <div id="customDateRow" class="{{ $period === 'custom' ? 'grid' : 'hidden' }} grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mulai Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Berdasarkan</label>
                            <select name="sort_by" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="date" {{ $sortBy === 'date' ? 'selected' : '' }}>Tanggal Pengeluaran</option>
                                <option value="amount" {{ $sortBy === 'amount' ? 'selected' : '' }}>Nominal Pengeluaran</option>
                                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Waktu Input</option>
                                <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Nama Pengeluaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Arah Urutan</label>
                            <select name="sort_order" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Terbaru / Terbesar</option>
                                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Terlama / Terkecil</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi Filter -->
                    <div class="flex items-center justify-between pt-2">
                        <div class="text-xs text-slate-500">
                            Menampilkan hasil filter: <strong class="text-slate-800">{{ $expenses->total() }}</strong> transaksi (Total: <strong class="text-emerald-800">Rp {{ number_format($filteredTotal, 0, ',', '.') }}</strong>)
                        </div>
                        <div class="flex items-center gap-2">
                            @if($search || ($category && $category !== 'ALL') || $period !== 'all' || $startDate || $endDate)
                            <a href="{{ route('admin.expenses') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-semibold transition-all">
                                Reset Filter
                            </a>
                            @endif
                            <button type="submit" class="px-4 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-2xs cursor-pointer">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- C. TABEL DATA PENGELUARAN -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200/90">
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4 whitespace-nowrap">Tanggal</th>
                                <th class="py-3.5 px-4">Nama Pengeluaran</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4 text-right whitespace-nowrap">Nominal</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                                <th class="py-3.5 px-4 text-center">Bukti</th>
                                <th class="py-3.5 px-4 text-center whitespace-nowrap w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($expenses as $index => $expense)
                            @php
                                $badgeStyle = 'bg-slate-100 text-slate-800 border-slate-200';
                                switch($expense->category) {
                                    case 'BBM':
                                        $badgeStyle = 'bg-amber-50 text-amber-800 border-amber-200';
                                        break;
                                    case 'Perawatan dan Servis Jeep':
                                        $badgeStyle = 'bg-rose-50 text-rose-800 border-rose-200';
                                        break;
                                    case 'Perlengkapan Wisata':
                                        $badgeStyle = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                        break;
                                    case 'Operasional Pos':
                                        $badgeStyle = 'bg-blue-50 text-blue-800 border-blue-200';
                                        break;
                                    case 'Kebersihan':
                                        $badgeStyle = 'bg-teal-50 text-teal-800 border-teal-200';
                                        break;
                                    case 'Listrik dan Air':
                                        $badgeStyle = 'bg-cyan-50 text-cyan-800 border-cyan-200';
                                        break;
                                    case 'Konsumsi':
                                        $badgeStyle = 'bg-orange-50 text-orange-800 border-orange-200';
                                        break;
                                    default:
                                        $badgeStyle = 'bg-purple-50 text-purple-800 border-purple-200';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- No -->
                                <td class="py-3.5 px-4 text-center text-slate-400 font-semibold">
                                    {{ $expenses->firstItem() + $index }}
                                </td>

                                <!-- Tanggal -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $expense->date->translatedFormat('d M Y') }}</div>
                                    <span class="text-[10px] text-slate-400">{{ $expense->date->translatedFormat('l') }}</span>
                                </td>

                                <!-- Nama & Nomor Bukti -->
                                <td class="py-3.5 px-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="font-extrabold text-slate-900 text-xs sm:text-sm font-display">{{ $expense->name }}</span>
                                            <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-[10px] font-mono border border-slate-200">
                                                {{ $expense->expense_number }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            Dicatat: <span class="text-slate-600 font-medium">{{ $expense->user ? $expense->user->name : 'Admin Loket' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $badgeStyle }}">
                                        {{ $expense->category }}
                                    </span>
                                </td>

                                <!-- Nominal -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <span class="font-black text-sm text-slate-900 font-display block">
                                        {{ $expense->formatted_amount }}
                                    </span>
                                </td>

                                <!-- Keterangan -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <span class="text-slate-600 line-clamp-2" title="{{ $expense->description }}">
                                        {{ $expense->description ?: '-' }}
                                    </span>
                                </td>

                                <!-- Bukti Pengeluaran -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if($expense->receipt_path)
                                        @if($expense->is_receipt_image)
                                        <button type="button" 
                                            data-url="{{ $expense->receipt_url }}" 
                                            data-caption="{{ $expense->name }}"
                                            onclick="openLightbox(this.dataset.url, this.dataset.caption)" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-[11px] font-semibold border border-emerald-200 transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Foto</span>
                                        </button>
                                        @elseif($expense->is_receipt_pdf)
                                        <a href="{{ $expense->receipt_url }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-800 text-[11px] font-semibold border border-rose-200 transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span>PDF</span>
                                        </a>
                                        @else
                                        <a href="{{ $expense->receipt_url }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-[11px] font-semibold transition cursor-pointer">
                                            <span>File</span>
                                        </a>
                                        @endif
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">Tanpa Bukti</span>
                                    @endif
                                </td>

                                <!-- Aksi (Detail, Edit, Hapus) -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Detail -->
                                        <button type="button" 
                                            data-id="{{ $expense->id }}"
                                            onclick="openDetailExpenseModal(this.dataset.id)" 
                                            class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-500 hover:text-emerald-700 text-xs shadow-2xs transition-all cursor-pointer" 
                                            title="Lihat Rincian Pengeluaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit -->
                                        @php
                                            $editData = [
                                                'id' => $expense->id,
                                                'expense_number' => $expense->expense_number,
                                                'name' => $expense->name,
                                                'category' => $expense->category,
                                                'amount' => $expense->amount,
                                                'date' => $expense->date ? $expense->date->format('Y-m-d') : '',
                                                'description' => $expense->description,
                                                'receipt_url' => $expense->receipt_url,
                                                'receipt_original_name' => $expense->receipt_original_name,
                                            ];
                                        @endphp
                                        <button type="button" 
                                            class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-300 text-slate-500 hover:text-blue-700 text-xs shadow-2xs transition-all cursor-pointer" 
                                            title="Edit Data Pengeluaran"
                                            data-expense='@json($editData)'
                                            onclick="openEditExpenseModal(this)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" class="inline"
                                              data-confirm-title="Hapus Data Pengeluaran"
                                              data-confirm-message="Apakah Anda yakin ingin menghapus catatan pengeluaran <strong class='text-slate-800 font-bold'>{{ $expense->name }}</strong> sebesar <strong class='text-emerald-700 font-bold'>{{ $expense->formatted_amount }}</strong>?"
                                              data-confirm-type="danger"
                                              data-confirm-text="Ya, Hapus Pengeluaran"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-300 text-slate-400 hover:text-rose-600 text-xs shadow-2xs transition-all cursor-pointer" title="Hapus Pengeluaran">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="max-w-sm mx-auto space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="font-bold text-slate-700 text-sm">Belum Ada Data Pengeluaran</p>
                                        <p class="text-xs text-slate-500">
                                            @if($search || $category !== 'ALL' || $period !== 'all')
                                                Tidak ditemukan catatan pengeluaran dengan filter saat ini. Coba ubah atau reset filter.
                                            @else
                                                Belum ada pengeluaran operasional yang dicatat. Klik tombol di bawah untuk mencatat pengeluaran pertama.
                                            @endif
                                        </p>
                                        <button type="button" onclick="openCreateExpenseModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Tambah Pengeluaran Sekarang</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if($expenses->hasPages())
                <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                    {{ $expenses->links() }}
                </div>
                @endif
            </div>

        </main>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 1: FORM TAMBAH PENGELUARAN                         -->
    <!-- ======================================================== -->
    <div id="modalCreateExpense" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs hidden flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
        <div id="modalCreateExpenseCard" class="bg-white rounded-3xl shadow-2xl max-w-xl w-full border border-slate-200 overflow-hidden my-auto transform transition-all duration-200 scale-95 opacity-0 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white flex items-center justify-between gap-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-emerald-300 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-display leading-tight">Catat Pengeluaran Baru</h3>
                        <p class="text-xs text-emerald-200/90 mt-0.5">Input biaya operasional, BBM, armada jeep, pos, atau utilitas.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateExpenseModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer shrink-0">
                    ✕
                </button>
            </div>

            <!-- Modal Form Body -->
            <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1 p-5 sm:p-6 space-y-4">
                @csrf

                <!-- Nama Pengeluaran -->
                <div>
                    <label for="create_name" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Nama Pengeluaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="create_name" name="name" required placeholder="Contoh: Pengisian BBM Pertalite Jeep 04" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori Pengeluaran -->
                    <div>
                        <label for="create_category" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Kategori Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <select id="create_category" name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}">{{ $catLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Pengeluaran -->
                    <div>
                        <label for="create_date" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Tanggal Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="create_date" name="date" required value="{{ Carbon\Carbon::today()->toDateString() }}" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                    </div>
                </div>

                <!-- Nominal Pengeluaran (Formatted) -->
                <div>
                    <label for="create_amount_display" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Nominal Pengeluaran (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                        <input type="text" id="create_amount_display" required placeholder="0" oninput="handleCurrencyInput(this, 'create_amount')" class="w-full pl-10 pr-3.5 py-2.5 text-xs font-bold text-slate-900 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-mono">
                        <input type="hidden" id="create_amount" name="amount" required value="">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Ketikkan angka murni, format rupiah akan terisi otomatis.</p>
                </div>

                <!-- Keterangan (Opsional) -->
                <div>
                    <label for="create_description" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Keterangan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea id="create_description" name="description" rows="2.5" placeholder="Tuliskan catatan teknis, plat armada jeep, nama toko, atau rincian pembelian..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"></textarea>
                </div>

                <!-- Upload Bukti Pengeluaran -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <label class="block text-xs font-bold text-slate-800">
                        Upload Bukti Pengeluaran <span class="text-slate-400 font-normal">(Nota / Kuitansi / Struk)</span>
                    </label>
                    <input type="file" id="create_receipt_file" name="receipt_file" accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 file:cursor-pointer cursor-pointer">
                    <p class="text-[11px] text-slate-500">Mendukung format JPG, PNG, WEBP, atau PDF (Ukuran maksimal 5 MB).</p>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateExpenseModal()" class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Pengeluaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 2: FORM EDIT PENGELUARAN                           -->
    <!-- ======================================================== -->
    <div id="modalEditExpense" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs hidden flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
        <div id="modalEditExpenseCard" class="bg-white rounded-3xl shadow-2xl max-w-xl w-full border border-slate-200 overflow-hidden my-auto transform transition-all duration-200 scale-95 opacity-0 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white flex items-center justify-between gap-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-emerald-300 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base sm:text-lg font-bold font-display leading-tight">Edit Data Pengeluaran</h3>
                            <span id="edit_badge_number" class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-mono border border-emerald-400/30">EXP-...</span>
                        </div>
                        <p class="text-xs text-emerald-200/90 mt-0.5">Perbarui rincian atau bukti kuitansi pengeluaran.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditExpenseModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer shrink-0">
                    ✕
                </button>
            </div>

            <!-- Modal Form Body -->
            <form id="formEditExpense" action="" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1 p-5 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama Pengeluaran -->
                <div>
                    <label for="edit_name" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Nama Pengeluaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori Pengeluaran -->
                    <div>
                        <label for="edit_category" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Kategori Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <select id="edit_category" name="category" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}">{{ $catLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Pengeluaran -->
                    <div>
                        <label for="edit_date" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Tanggal Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="edit_date" name="date" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                    </div>
                </div>

                <!-- Nominal Pengeluaran -->
                <div>
                    <label for="edit_amount_display" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Nominal Pengeluaran (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                        <input type="text" id="edit_amount_display" required placeholder="0" oninput="handleCurrencyInput(this, 'edit_amount')" class="w-full pl-10 pr-3.5 py-2.5 text-xs font-bold text-slate-900 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-mono">
                        <input type="hidden" id="edit_amount" name="amount" required value="">
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="edit_description" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Keterangan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea id="edit_description" name="description" rows="2.5" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"></textarea>
                </div>

                <!-- Existing Bukti Info & Ganti Bukti -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-800">
                            Bukti Pengeluaran
                        </label>
                        <div id="edit_existing_receipt_box" class="hidden">
                            <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-rose-600 hover:text-rose-700 cursor-pointer">
                                <input type="checkbox" name="remove_receipt" value="1" id="edit_remove_receipt_cb" class="rounded text-rose-600 focus:ring-rose-500">
                                <span>Hapus Bukti Lama</span>
                            </label>
                        </div>
                    </div>

                    <!-- Link File Eksisting -->
                    <div id="edit_receipt_preview_link" class="hidden p-2 rounded-xl bg-white border border-slate-200 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <span id="edit_receipt_filename" class="font-medium text-slate-800 truncate">bukti.jpg</span>
                        </div>
                        <a id="edit_receipt_view_btn" href="#" target="_blank" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 shrink-0 ml-2">Lihat File</a>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Unggah File Pengganti (Opsional):</label>
                        <input type="file" id="edit_receipt_file" name="receipt_file" accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 file:cursor-pointer cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah file bukti pengeluaran.</p>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditExpenseModal()" class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 3: DETAIL RINCIAN PENGELUARAN                      -->
    <!-- ======================================================== -->
    <div id="modalDetailExpense" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs hidden flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
        <div id="modalDetailExpenseCard" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden my-auto transform transition-all duration-200 scale-95 opacity-0 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white flex items-center justify-between gap-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-emerald-300 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base sm:text-lg font-bold font-display leading-tight">Detail Pengeluaran</h3>
                            <span id="detail_expense_number" class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-mono border border-emerald-400/30">EXP-...</span>
                        </div>
                        <p class="text-xs text-emerald-200/90 mt-0.5">Rincian audit dan lampiran nota pembayaran.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailExpenseModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer shrink-0">
                    ✕
                </button>
            </div>

            <!-- Detail Content -->
            <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1">
                
                <!-- Nominal Header Card -->
                <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/90 text-center">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-1">Total Biaya Dikeluarkan</span>
                    <span id="detail_amount" class="text-2xl sm:text-3xl font-black text-emerald-950 font-display block">Rp 0</span>
                    <span id="detail_category" class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold bg-white text-emerald-800 border border-emerald-300 shadow-2xs">Kategori</span>
                </div>

                <div class="space-y-3 text-xs divide-y divide-slate-100">
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-slate-500">Nama Pengeluaran</span>
                        <span id="detail_name" class="font-bold text-slate-800 text-right">Nama</span>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">Tanggal Pengeluaran</span>
                        <span id="detail_date" class="font-bold text-slate-800 text-right">Tanggal</span>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">Dicatat Oleh</span>
                        <span id="detail_recorded_by" class="font-bold text-slate-800 text-right">Admin</span>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">Waktu Pencatatan</span>
                        <span id="detail_created_at" class="font-medium text-slate-600 text-right">-</span>
                    </div>

                    <div class="pt-2 space-y-1">
                        <span class="text-slate-500 block">Keterangan Tambahan:</span>
                        <p id="detail_description" class="text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-200/80 italic whitespace-pre-line">-</p>
                    </div>

                    <!-- Bukti Section -->
                    <div class="pt-3 space-y-2">
                        <span class="text-slate-500 block font-bold">Bukti / Lampiran:</span>
                        <div id="detail_receipt_container" class="space-y-2">
                            <!-- Populated by JS -->
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="button" onclick="closeDetailExpenseModal()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 4: LIGHTBOX GAMBAR BUKTI                           -->
    <!-- ======================================================== -->
    <div id="modalReceiptLightbox" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center">
            <button type="button" onclick="closeLightbox()" class="absolute -top-12 right-0 text-white/80 hover:text-white p-2 rounded-full bg-white/10 hover:bg-white/20 transition cursor-pointer">
                ✕ Tutup
            </button>
            <img id="lightboxImage" src="" alt="Bukti Pengeluaran" class="max-w-full max-h-[82vh] rounded-2xl object-contain border border-white/20 shadow-2xl">
            <span id="lightboxCaption" class="mt-3 text-xs text-slate-300 font-semibold tracking-wide"></span>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Currency input formatting helper (e.g. 150000 -> 150.000)
        function formatRupiah(val) {
            if (!val) return '';
            let numberString = val.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function handleCurrencyInput(inputEl, hiddenId) {
            let cleanNumber = inputEl.value.replace(/\D/g, '');
            document.getElementById(hiddenId).value = cleanNumber;
            inputEl.value = formatRupiah(cleanNumber);
        }

        function toggleCustomDates(val) {
            const row = document.getElementById('customDateRow');
            if (!row) return;
            if (val === 'custom') {
                row.classList.remove('hidden');
                row.classList.add('grid');
            } else {
                row.classList.add('hidden');
                row.classList.remove('grid');
            }
        }

        // 1. Create Modal Handlers
        function openCreateExpenseModal() {
            const modal = document.getElementById('modalCreateExpense');
            const card = document.getElementById('modalCreateExpenseCard');
            if (!modal || !card) return;

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCreateExpenseModal() {
            const modal = document.getElementById('modalCreateExpense');
            const card = document.getElementById('modalCreateExpenseCard');
            if (!modal || !card) return;

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 150);
        }

        // 2. Edit Modal Handlers
        function openEditExpenseModal(source) {
            let data = source;
            if (source instanceof HTMLElement) {
                try {
                    data = JSON.parse(source.getAttribute('data-expense'));
                } catch (e) {
                    data = {};
                }
            }
            if (!data) return;

            const modal = document.getElementById('modalEditExpense');
            const card = document.getElementById('modalEditExpenseCard');
            const form = document.getElementById('formEditExpense');
            if (!modal || !card || !form) return;

            form.action = '/admin/pengeluaran/' + data.id;

            document.getElementById('edit_badge_number').textContent = data.expense_number || ('EXP-' + data.id);
            document.getElementById('edit_name').value = data.name || '';
            document.getElementById('edit_category').value = data.category || '';
            document.getElementById('edit_date').value = data.date || '';
            
            const rawAmount = data.amount ? Math.round(data.amount).toString() : '0';
            document.getElementById('edit_amount').value = rawAmount;
            document.getElementById('edit_amount_display').value = formatRupiah(rawAmount);

            document.getElementById('edit_description').value = data.description || '';

            // Receipt preview link
            const previewBox = document.getElementById('edit_receipt_preview_link');
            const existBox = document.getElementById('edit_existing_receipt_box');
            const filenameEl = document.getElementById('edit_receipt_filename');
            const viewBtn = document.getElementById('edit_receipt_view_btn');
            const removeCb = document.getElementById('edit_remove_receipt_cb');

            if (removeCb) removeCb.checked = false;

            if (data.receipt_url) {
                if (previewBox) previewBox.classList.remove('hidden');
                if (existBox) existBox.classList.remove('hidden');
                if (filenameEl) filenameEl.textContent = data.receipt_original_name || 'File Bukti Terlampir';
                if (viewBtn) viewBtn.href = data.receipt_url;
            } else {
                if (previewBox) previewBox.classList.add('hidden');
                if (existBox) existBox.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeEditExpenseModal() {
            const modal = document.getElementById('modalEditExpense');
            const card = document.getElementById('modalEditExpenseCard');
            if (!modal || !card) return;

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 150);
        }

        // 3. Detail Modal Handlers (AJAX)
        function openDetailExpenseModal(id) {
            const modal = document.getElementById('modalDetailExpense');
            const card = document.getElementById('modalDetailExpenseCard');
            if (!modal || !card) return;

            // Fetch detail from server
            fetch('/admin/pengeluaran/' + id)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        document.getElementById('detail_expense_number').textContent = d.expense_number || ('EXP-' + d.id);
                        document.getElementById('detail_amount').textContent = d.formatted_amount;
                        document.getElementById('detail_category').textContent = d.category;
                        document.getElementById('detail_name').textContent = d.name;
                        document.getElementById('detail_date').textContent = d.formatted_date;
                        document.getElementById('detail_recorded_by').textContent = d.recorded_by;
                        document.getElementById('detail_created_at').textContent = d.created_at || '-';
                        document.getElementById('detail_description').textContent = d.description || 'Tidak ada catatan tambahan.';

                        // Receipt container
                        const receiptContainer = document.getElementById('detail_receipt_container');
                        if (receiptContainer) {
                            if (d.receipt_url) {
                                if (d.is_receipt_image) {
                                    receiptContainer.innerHTML = `
                                        <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 group aspect-[16/10]">
                                            <img src="${d.receipt_url}" alt="Bukti ${d.name}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                <button type="button" onclick="openLightbox('${d.receipt_url}', '${d.name}')" class="px-3 py-1.5 rounded-lg bg-white/90 text-slate-800 text-xs font-bold shadow hover:bg-white transition cursor-pointer">
                                                    Perbesar Gambar
                                                </button>
                                                <a href="${d.receipt_url}" download class="px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-bold shadow hover:bg-emerald-800 transition">
                                                    Unduh
                                                </a>
                                            </div>
                                        </div>
                                    `;
                                } else if (d.is_receipt_pdf) {
                                    receiptContainer.innerHTML = `
                                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between">
                                            <div class="flex items-center gap-2.5">
                                                <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                <div>
                                                    <span class="font-bold text-slate-800 block text-xs truncate max-w-xs">${d.receipt_original_name || 'Dokumen_Bukti.pdf'}</span>
                                                    <span class="text-[11px] text-slate-500">File Dokumen PDF</span>
                                                </div>
                                            </div>
                                            <a href="${d.receipt_url}" target="_blank" class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition">
                                                Buka PDF
                                            </a>
                                        </div>
                                    `;
                                } else {
                                    receiptContainer.innerHTML = `
                                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                            <span class="font-medium text-slate-700 text-xs">${d.receipt_original_name || 'Lampiran Bukti'}</span>
                                            <a href="${d.receipt_url}" target="_blank" class="text-xs font-bold text-emerald-700 hover:underline">Buka File</a>
                                        </div>
                                    `;
                                }
                            } else {
                                receiptContainer.innerHTML = `<span class="text-slate-400 italic">Tidak ada lampiran bukti nota yang diunggah.</span>`;
                            }
                        }

                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');

                        requestAnimationFrame(() => {
                            card.classList.remove('scale-95', 'opacity-0');
                            card.classList.add('scale-100', 'opacity-100');
                        });
                    }
                })
                .catch(err => {
                    alert('Gagal memuat detail pengeluaran.');
                });
        }

        function closeDetailExpenseModal() {
            const modal = document.getElementById('modalDetailExpense');
            const card = document.getElementById('modalDetailExpenseCard');
            if (!modal || !card) return;

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 150);
        }

        // 4. Lightbox Handlers
        function openLightbox(url, caption) {
            const modal = document.getElementById('modalReceiptLightbox');
            const img = document.getElementById('lightboxImage');
            const cap = document.getElementById('lightboxCaption');
            if (!modal || !img) return;

            img.src = url;
            if (cap) cap.textContent = caption || 'Bukti Pengeluaran';

            modal.classList.remove('hidden');
        }

        function closeLightbox() {
            const modal = document.getElementById('modalReceiptLightbox');
            if (!modal) return;
            modal.classList.add('hidden');
        }

        // Global Backdrop & ESC listeners
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateExpenseModal();
                closeEditExpenseModal();
                closeDetailExpenseModal();
                closeLightbox();
            }
        });

        ['modalCreateExpense', 'modalEditExpense', 'modalDetailExpense', 'modalReceiptLightbox'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === el) {
                        if (id === 'modalCreateExpense') closeCreateExpenseModal();
                        if (id === 'modalEditExpense') closeEditExpenseModal();
                        if (id === 'modalDetailExpense') closeDetailExpenseModal();
                        if (id === 'modalReceiptLightbox') closeLightbox();
                    }
                });
            }
        });
    </script>
</body>
</html>

