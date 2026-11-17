<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kandidat Hotel Baru | Jeep Wukirsari</title>
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
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    Kandidat Hotel Baru (Pencatatan Otomatis)
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Hotel atau titik jemput yang diketik tamu saat booking antar-jemput yang berjarak &gt; 100 m dari database hotels.json.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>{{ count($candidates) }} Menunggu Persetujuan</span>
                </span>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1">

            <!-- Flash Notification Success -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-sm font-semibold shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        ✓
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- Flash Notification Error -->
            @if(isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs font-semibold shadow-xs space-y-1">
                <div class="flex items-center gap-2 font-bold text-rose-800">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-5 font-normal">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Informasi Panduan -->
            <div class="bg-emerald-50/70 border border-emerald-200 rounded-2xl p-4 sm:p-5 flex items-start gap-3.5 text-xs text-emerald-900 leading-relaxed">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 font-bold text-sm">
                    💡
                </div>
                <div>
                    <h4 class="font-bold text-emerald-950 text-sm">Alur Kerja Persetujuan Hotel 1-Klik</h4>
                    <p class="mt-0.5 text-emerald-800">
                        Setiap kali ada pemesan yang memilih layanan antar-jemput dan memasukkan lokasi baru (jarak lebih dari 100 meter dari data hotel yang ada), sistem secara otomatis mencatatnya di halaman ini. Klik tombol <strong class="text-emerald-900 font-extrabold">"Setujui"</strong> untuk langsung memasukkan hotel tersebut ke file <code class="bg-white/80 px-1.5 py-0.5 rounded border border-emerald-300 font-mono text-[11px]">hotels.json</code> sehingga langsung muncul dalam saran pencarian tamu berikutnya.
                    </p>
                </div>
            </div>

            <!-- Tabel Daftar Kandidat -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Kandidat Hotel Menunggu Review</h3>
                        <p class="text-xs text-slate-500">Urut berdasarkan frekuensi penggunaan terbanyak oleh wisatawan.</p>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">Total: {{ count($candidates) }} data</span>
                </div>

                @if(count($candidates) === 0)
                <div class="p-12 text-center space-y-3">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                        🏨
                    </div>
                    <div class="font-bold text-slate-700 text-sm">Tidak Ada Kandidat Hotel Baru</div>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Semua lokasi penjemputan dari pemesanan antar-jemput sudah terdaftar di database <code class="font-mono text-slate-600">hotels.json</code>.
                    </p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4">Nama yang Diketik Tamu</th>
                                <th class="py-3 px-4">Koordinat & Peta</th>
                                <th class="py-3 px-4 text-center">Jumlah Dipakai</th>
                                <th class="py-3 px-4">Terakhir Digunakan</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($candidates as $cand)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                        <span>{{ $cand['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-mono text-[11px] text-slate-600">
                                        {{ $cand['lat'] }}, {{ $cand['lng'] }}
                                    </div>
                                    <a href="https://www.google.com/maps?q={{ $cand['lat'] }},{{ $cand['lng'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[11px] text-emerald-700 hover:text-emerald-900 font-medium hover:underline mt-0.5">
                                        <span>Buka di Google Maps</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ ($cand['jumlah_pakai'] ?? 1) > 1 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $cand['jumlah_pakai'] ?? 1 }}x
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                    {{ $cand['updated_at'] ?? ($cand['created_at'] ?? '-') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Setujui 1-Klik -->
                                        <form action="{{ route('admin.hotel_candidates.approve', $cand['id']) }}" method="POST" onsubmit="return confirm('Setujui hotel \'{{ addslashes($cand['name']) }}\' dan masukkan ke hotels.json?')">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-[#143B1E] hover:bg-[#1E5E30] text-white text-xs font-bold transition-all shadow-xs cursor-pointer flex items-center gap-1.5">
                                                <span>✓ Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.hotel_candidates.delete', $cand['id']) }}" method="POST" onsubmit="return confirm('Hapus kandidat hotel ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold transition-all cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </main>
    </div>

</body>
</html>

