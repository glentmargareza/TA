<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Sesi | Admin Jeep Wukirsari</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        
        <!-- Header Banner -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2 font-display">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    Kelola Jadwal & Sesi Keberangkatan
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Atur jam operasional trip dan ketersediaan waktu pemesanan tiket wisatawan.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                    {{ $slots->count() }} Sesi Terdaftar ({{ $slots->where('is_active', true)->count() }} Aktif)
                </span>
                <button type="button" onclick="openCreateScheduleModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Sesi Baru</span>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                    &larr; Dashboard
                </a>
            </div>
        </header>

        <main class="p-6 sm:p-8 space-y-6 flex-1 overflow-y-auto">

            @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-sm cursor-pointer">&times;</button>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm shrink-0">⚠</span>
                    <span>Gagal memproses data jadwal sesi:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-800 pl-8 text-xs font-medium">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- 3 Kartu Ringkasan Statistik Jadwal Sesi -->
            @php
                $totalSlots = $slots->count();
                $activeSlots = $slots->where('is_active', true)->count();
                $inactiveSlots = $slots->where('is_active', false)->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                <!-- 1. Total Sesi Keberangkatan -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Total Sesi Keberangkatan</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block mt-1 tracking-tight font-display">
                            {{ $totalSlots }} <span class="text-xs font-normal text-slate-400">Sesi</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-600 font-medium mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Slot operasional pangkalan
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- 2. Sesi Buka (Aktif) -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Sesi Buka (Aktif)</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-emerald-800 block mt-1 tracking-tight font-display">
                            {{ $activeSlots }} <span class="text-xs font-normal text-slate-400">Sesi</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Dapat dipilih wisatawan
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs border border-emerald-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- 3. Sesi Tutup / Libur -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 block">Sesi Tutup / Libur</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-amber-700 block mt-1 tracking-tight font-display">
                            {{ $inactiveSlots }} <span class="text-xs font-normal text-slate-400">Sesi</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-amber-700 font-semibold mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Ditutup sementara
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs border border-amber-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- TABEL DAFTAR SESI JADWAL -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 font-display tracking-tight">Daftar Slot Waktu Keberangkatan Jeep</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Wisatawan hanya dapat memilih sesi yang berstatus aktif saat melakukan pemesanan tiket.</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            Total: {{ $slots->count() }} Sesi Terjadwal
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider select-none">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center">No</th>
                                <th class="py-4 px-5 min-w-[220px]">Nama Sesi</th>
                                <th class="py-4 px-5 min-w-[180px] whitespace-nowrap">Rentang Waktu (WIB)</th>
                                <th class="py-4 px-5 text-center min-w-[130px] whitespace-nowrap">Total Reservasi</th>
                                <th class="py-4 px-5 text-center whitespace-nowrap">Status Operasional</th>
                                <th class="py-4 px-5 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($slots as $idx => $slot)
                                @php
                                    $startTimeFormatted = date('H:i', strtotime($slot->start_time));
                                    $endTimeFormatted = date('H:i', strtotime($slot->end_time));
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ !$slot->is_active ? 'bg-slate-50/50 opacity-75' : '' }}">
                                    <!-- No -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs inline-flex items-center justify-center">
                                             {{ $idx + 1 }}
                                        </span>
                                    </td>

                                    <!-- Nama Sesi -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0 border border-emerald-100">
                                                ⏱️
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-slate-900 text-sm font-display block leading-tight">{{ $slot->slot_label }}</span>
                                                <span class="text-[11px] text-slate-400 font-mono block mt-0.5">ID Sesi: #{{ $slot->id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Rentang Waktu -->
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100/80 border border-slate-200/80 font-bold text-slate-800 text-xs">
                                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $startTimeFormatted }}</span>
                                            <span class="text-slate-400">&ndash;</span>
                                            <span>{{ $endTimeFormatted }} WIB</span>
                                        </div>
                                    </td>

                                    <!-- Total Reservasi -->
                                    <td class="py-4 px-5 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                            <span>{{ $slot->bookings_count ?? 0 }} Tiket</span>
                                        </span>
                                    </td>

                                    <!-- Status Operasional -->
                                    <td class="py-4 px-5 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.schedules.toggle', $slot->id) }}" method="POST" class="inline-block"
                                              data-confirm-title="{{ $slot->is_active ? 'Tutup Sesi Jadwal' : 'Buka Sesi Jadwal' }}"
                                              data-confirm-message="Ubah status operasional sesi keberangkatan <strong class='text-slate-800 font-bold'>{{ $slot->slot_label }}</strong> menjadi <strong class='{{ $slot->is_active ? 'text-amber-700' : 'text-emerald-700' }} font-bold'>{{ $slot->is_active ? 'TUTUP (LIBUR)' : 'BUKA (AKTIF)' }}</strong>?"
                                              data-confirm-type="{{ $slot->is_active ? 'warning' : 'success' }}"
                                              data-confirm-text="{{ $slot->is_active ? 'Ya, Tutup Sesi' : 'Ya, Buka Sesi' }}"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            <button type="submit" title="Klik untuk mengubah status buka/tutup sesi" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs {{ $slot->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}">
                                                <span class="w-2 h-2 rounded-full {{ $slot->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                                <span>{{ $slot->is_active ? 'Buka (Aktif)' : 'Tutup (Libur)' }}</span>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Aksi (Edit & Hapus) -->
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-700 hover:text-emerald-800 text-xs font-bold shadow-2xs transition-all cursor-pointer"
                                                data-id="{{ $slot->id }}"
                                                data-label="{{ $slot->slot_label }}"
                                                data-start="{{ $startTimeFormatted }}"
                                                data-end="{{ $endTimeFormatted }}"
                                                data-max="{{ $slot->max_jeeps }}"
                                                onclick="handleEditScheduleClick(this)">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                <span>Edit</span>
                                            </button>
                                            
                                            <!-- Tombol Hapus dengan Konfirmasi Modal Kustom -->
                                            <form action="{{ route('admin.schedules.destroy', $slot->id) }}" method="POST" class="inline-block"
                                                  data-confirm-title="Hapus Sesi Jadwal"
                                                  data-confirm-message="Apakah Anda yakin ingin menghapus sesi jadwal <strong class='text-slate-800 font-bold'>{{ $slot->slot_label }}</strong>? Tindakan ini akan menghapus slot waktu keberangkatan."
                                                  data-confirm-type="danger"
                                                  data-confirm-text="Ya, Hapus Sesi"
                                                  data-confirm-cancel="Batal">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-300 text-slate-400 hover:text-rose-600 text-xs shadow-2xs transition-all cursor-pointer" title="Hapus Sesi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">
                                        <div class="max-w-sm mx-auto space-y-2">
                                            <p class="font-bold text-slate-700 text-sm">Belum ada sesi keberangkatan dibuat</p>
                                            <p class="text-xs text-slate-500">Klik tombol "Tambah Sesi Baru" di atas untuk menambahkan slot waktu operasional pangkalan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: Tambah Sesi Baru -->
    <div id="modalCreateSchedule" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalCreateScheduleCard" class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Sesi Baru</h3>
                        <p class="text-xs text-slate-500">Tentukan jam operasional keberangkatan</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateScheduleModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form action="{{ route('admin.schedules.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="max_jeeps" value="20">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama / Label Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" name="slot_label" required placeholder="Contoh: Sesi Pagi 1 (08:00 WIB)" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" name="start_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" name="end_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeCreateScheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-xs hover:shadow cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Sesi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit Sesi Jadwal -->
    <div id="modalEditSchedule" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalEditScheduleCard" class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Sesi Jadwal</h3>
                        <p class="text-xs text-slate-500">Perbarui jam operasional keberangkatan</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditScheduleModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form id="editScheduleForm" action="" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_max_jeeps" name="max_jeeps" value="20">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama / Label Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_slot_label" name="slot_label" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" id="edit_start_time" name="start_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" id="edit_end_time" name="end_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditScheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-xs hover:shadow cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateScheduleModal() {
            const modal = document.getElementById('modalCreateSchedule');
            const card = document.getElementById('modalCreateScheduleCard');
            if (!modal) return;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCreateScheduleModal() {
            const modal = document.getElementById('modalCreateSchedule');
            const card = document.getElementById('modalCreateScheduleCard');
            if (!modal) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        function openEditScheduleModal(id, label, startTime, endTime, maxJeeps) {
            const form = document.getElementById('editScheduleForm');
            const modal = document.getElementById('modalEditSchedule');
            const card = document.getElementById('modalEditScheduleCard');
            if (!modal || !form) return;

            form.action = '/admin/jadwal/' + id;
            document.getElementById('edit_slot_label').value = label || '';
            document.getElementById('edit_start_time').value = startTime || '';
            document.getElementById('edit_end_time').value = endTime || '';
            document.getElementById('edit_max_jeeps').value = maxJeeps || '';

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function handleEditScheduleClick(btn) {
            const id = btn.getAttribute('data-id');
            const label = btn.getAttribute('data-label');
            const startTime = btn.getAttribute('data-start');
            const endTime = btn.getAttribute('data-end');
            const maxJeeps = btn.getAttribute('data-max');

            openEditScheduleModal(id, label, startTime, endTime, maxJeeps);
        }

        function closeEditScheduleModal() {
            const modal = document.getElementById('modalEditSchedule');
            const card = document.getElementById('modalEditScheduleCard');
            if (!modal) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateScheduleModal();
                closeEditScheduleModal();
            }
        });

        // Close on backdrop click
        ['modalCreateSchedule', 'modalEditSchedule'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === el) {
                        if (id === 'modalCreateSchedule') closeCreateScheduleModal();
                        if (id === 'modalEditSchedule') closeEditScheduleModal();
                    }
                });
            }
        });
    </script>
</body>
</html>
