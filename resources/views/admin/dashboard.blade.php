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

    <!-- Sidebar Admin -->
    <aside class="w-full md:w-64 bg-wukirsari-dark text-white p-6 flex flex-col justify-between shrink-0">
        <div>
            <div class="flex items-center gap-3 pb-6 border-b border-white/10 mb-6">
                <div class="w-10 h-10 rounded-xl bg-wukirsari-primary text-white flex items-center justify-center font-bold text-lg">
                    🚙
                </div>
                <div>
                    <span class="block font-extrabold text-base text-white tracking-tight">ADMIN LOKET</span>
                    <span class="block text-[10px] text-amber-400 uppercase tracking-widest">Jeep Wukirsari</span>
                </div>
            </div>

            <nav class="space-y-1.5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-wukirsari-primary text-white shadow-sm">
                    📊 <span>Dashboard & Omzet</span>
                </a>
            </nav>
        </div>

        <div class="pt-6 border-t border-white/10 mt-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-white/60 hover:text-white transition-colors">
                ← <span>Kembali ke Website Publik</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">
                Portal Operasional Basecamp Wukirsari
            </h2>
            <div class="flex items-center gap-2 text-xs text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Sistem Kasir Siaga</span>
            </div>
        </header>

        <main class="p-6 space-y-8 flex-1 overflow-y-auto">
            
            @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
            @endif

            <!-- 3 Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Omzet Terverifikasi</span>
                        <span class="text-2xl font-extrabold text-slate-800 block mt-1">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-emerald-600 font-medium">Berdasarkan data tersimpan</span>
                    </div>
                    <span class="text-3xl">💰</span>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Armada Jeep Siap</span>
                        <span class="text-2xl font-extrabold text-slate-800 block mt-1">
                            {{ $readyJeepsCount }} / {{ $totalJeepsCount }} Armada
                        </span>
                        <span class="text-xs text-slate-500">Status Ready Pangkalan</span>
                    </div>
                    <span class="text-3xl">🚙</span>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Menunggu Pelunasan Loket</span>
                        <span class="text-2xl font-extrabold text-amber-600 block mt-1">
                            Rp {{ number_format($pendingDpAmount, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-amber-700 font-medium">{{ $pendingDpCount }} Booking Berstatus DP</span>
                    </div>
                    <span class="text-3xl">⏳</span>
                </div>
            </div>

            <!-- Tabel Reservasi Terkini -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-base font-bold text-slate-800">Daftar Reservasi & Penugasan Driver</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola penugasan mobil paguyuban dan validasi pelunasan uang muka di loket basecamp.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                                <th class="py-3 px-4">Kode Booking</th>
                                <th class="py-3 px-4">Pemesan</th>
                                <th class="py-3 px-4">Paket & Tanggal</th>
                                <th class="py-3 px-4">Armada / Pax</th>
                                <th class="py-3 px-4">Status Tagihan</th>
                                <th class="py-3 px-4">Penugasan Paguyuban</th>
                                <th class="py-3 px-4 text-center">Aksi Loket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $b)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                    {{ $b->booking_code }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-800 block">{{ $b->customer_name }}</span>
                                    <span class="text-slate-500">{{ $b->customer_phone }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800 block">{{ $b->package->name }}</span>
                                    <span class="text-slate-500">{{ $b->trip_date->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span>{{ $b->jeep_quantity }} Jeep ({{ $b->passenger_count }} Org)</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($b->booking_status === 'FULL_PAID')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        LUNAS (100%)
                                    </span>
                                    @else
                                    <div>
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            DP TERBAYAR
                                        </span>
                                        <span class="block text-[10px] text-red-600 font-semibold mt-0.5">
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
                                    <form action="{{ route('admin.assign', $b->id) }}" method="POST" class="flex gap-1 items-center">
                                        @csrf
                                        <select name="driver_id" class="px-2 py-1 rounded border border-slate-200 text-[10px]">
                                            @foreach($drivers as $d)
                                            <option value="{{ $d->id }}">{{ $d->full_name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="jeep_id" class="px-2 py-1 rounded border border-slate-200 text-[10px]">
                                            @foreach($jeeps as $j)
                                            <option value="{{ $j->id }}">{{ $j->plate_number }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-2 py-1 bg-wukirsari-primary text-white rounded text-[10px] font-bold">
                                            Tugaskan
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($b->booking_status === 'DP_PAID')
                                    <form action="{{ route('admin.settle', $b->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Konfirmasi terima pelunasan tunai/QRIS loket sebesar Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}?')" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] shadow-xs transition-colors">
                                            Terima Pelunasan
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-emerald-700 font-semibold text-[11px]">✓ Selesai</span>
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
