<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin Loket | Jeep Wukirsari</title>
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
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row font-sans">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white/90 backdrop-blur-md border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-20 shadow-2xs">
            <h2 class="text-base font-bold text-slate-800 tracking-tight">
                Portal Operasional Basecamp Wukirsari
            </h2>
            <div class="flex items-center gap-2.5 text-xs text-emerald-800 bg-emerald-50 border border-emerald-200/90 px-3.5 py-1.5 rounded-full font-bold shadow-2xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Sistem Kasir Siaga</span>
            </div>
        </header>

        <main class="p-6 space-y-8 flex-1 overflow-y-auto">
            
            @if(session('success'))
            <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-300 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center gap-2">
                <span class="text-emerald-600 text-base">✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- 3 Stat Cards with Gradient Accents -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Card 1: Omzet -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between relative overflow-hidden group hover:border-emerald-300 hover:shadow-md transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-700"></div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Omzet Terverifikasi</span>
                        <span class="text-2xl font-black text-slate-900 block mt-1 tracking-tight">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Berdasarkan data tersimpan
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-md shadow-emerald-500/25 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Card 2: Armada -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between relative overflow-hidden group hover:border-emerald-300 hover:shadow-md transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#1E3F20] via-emerald-600 to-teal-600"></div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Armada Jeep Siap</span>
                        <span class="text-2xl font-black text-slate-900 block mt-1 tracking-tight">
                            {{ $readyJeepsCount }} / {{ $totalJeepsCount }} Armada
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Status Ready Pangkalan
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#1E3F20] to-emerald-700 text-white shadow-md shadow-emerald-950/25 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    </div>
                </div>

                <!-- Card 3: Menunggu Pelunasan -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between relative overflow-hidden group hover:border-emerald-300 hover:shadow-md transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800"></div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Menunggu Pelunasan Loket</span>
                        <span class="text-2xl font-black text-emerald-800 block mt-1 tracking-tight">
                            Rp {{ number_format($pendingDpAmount, 0, ',', '.') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-800 font-semibold mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $pendingDpCount }} Booking Berstatus DP
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-600 to-emerald-800 text-white shadow-md shadow-emerald-600/25 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Reservasi Terkini -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Reservasi & Penugasan Driver</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola penugasan mobil paguyuban dan validasi pelunasan uang muka di loket basecamp.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                                <th class="py-3.5 px-4">Kode Booking</th>
                                <th class="py-3.5 px-4">Pemesan</th>
                                <th class="py-3.5 px-4">Paket & Tanggal</th>
                                <th class="py-3.5 px-4">Armada / Pax</th>
                                <th class="py-3.5 px-4">Status Tagihan</th>
                                <th class="py-3.5 px-4">Penugasan Paguyuban</th>
                                <th class="py-3.5 px-4 text-center">Aksi Loket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $b)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                    {{ $b->booking_code }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-800 block">{{ $b->customer_name }}</span>
                                    <span class="text-slate-500 text-[11px]">{{ $b->customer_phone }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800 block">{{ $b->package->name }}</span>
                                    <span class="text-slate-500 text-[11px]">{{ $b->trip_date->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-700">{{ $b->jeep_quantity }} Jeep ({{ $b->passenger_count }} Org)</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($b->booking_status === 'FULL_PAID')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        LUNAS (100%)
                                    </span>
                                    @else
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            DP TERBAYAR
                                        </span>
                                        <span class="block text-[10px] text-rose-600 font-bold mt-1">
                                            Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @php $assign = $b->assignments->first(); @endphp
                                    @if($assign)
                                    <span class="font-semibold text-slate-800 block">{{ $assign->driver->full_name }}</span>
                                    <span class="text-slate-500 font-mono text-[11px]">{{ $assign->jeep->plate_number }}</span>
                                    @else
                                    <form action="{{ route('admin.assign', $b->id) }}" method="POST" class="flex gap-1.5 items-center">
                                        @csrf
                                        <select name="driver_id" class="px-2 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-medium focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                            @foreach($drivers as $d)
                                             <option value="{{ $d->id }}">{{ $d->full_name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="jeep_id" class="px-2 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-mono font-medium focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                            @foreach($jeeps as $j)
                                            <option value="{{ $j->id }}">{{ $j->plate_number }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white rounded-lg text-[10px] font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                                            Tugaskan
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($b->booking_status === 'DP_PAID')
                                    <form action="{{ route('admin.settle', $b->id) }}" method="POST" onsubmit="return confirm('Konfirmasi terima pelunasan sisa tagihan untuk booking ini?')">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white font-bold text-[11px] shadow-sm hover:shadow transition-all cursor-pointer active:scale-95">
                                            Terima Pelunasan
                                        </button>
                                    </form>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Selesai</span>
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Belum ada transaksi pemesanan yang tercatat di database.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
