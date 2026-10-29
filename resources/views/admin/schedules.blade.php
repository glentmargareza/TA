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
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-800">Kelola Jadwal & Sesi Keberangkatan</h2>
                <p class="text-xs text-slate-500">Atur jam operasional trip, kuota armada jeep per sesi, dan status operasional</p>
            </div>
            <div>
                <button type="button" onclick="openCreateScheduleModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Sesi Baru</span>
                </button>
            </div>
        </header>

        <main class="p-6 space-y-6 flex-1 overflow-y-auto">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-2xs space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>Gagal memproses data jadwal sesi:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-4">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ringkasan Statistik Jadwal Sesi -->
            @php
                $totalSlots = $slots->count();
                $activeSlots = $slots->where('is_active', true)->count();
                $inactiveSlots = $slots->where('is_active', false)->count();
                $totalJeepCapacity = $slots->where('is_active', true)->sum('max_jeeps');
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-slate-400 text-xs font-medium uppercase tracking-wider">Total Sesi Keberangkatan</span>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $totalSlots }} <span class="text-xs font-normal text-slate-500">sesi</span></p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-emerald-600 text-xs font-medium uppercase tracking-wider">Sesi Buka (Aktif)</span>
                    <p class="text-2xl font-black text-emerald-700 mt-1">{{ $activeSlots }} <span class="text-xs font-normal text-slate-500">sesi</span></p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-slate-500 text-xs font-medium uppercase tracking-wider">Sesi Tutup / Libur</span>
                    <p class="text-2xl font-black text-slate-700 mt-1">{{ $inactiveSlots }} <span class="text-xs font-normal text-slate-500">sesi</span></p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-slate-400 text-xs font-medium uppercase tracking-wider">Kapasitas Maksimal Harian</span>
                    <p class="text-2xl font-black text-wukirsari-primary mt-1">{{ $totalJeepCapacity }} <span class="text-xs font-normal text-slate-500">jeep / hari</span></p>
                </div>
            </div>

            <!-- Tabel Daftar Sesi Jadwal -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Slot Waktu Keberangkatan Jeep</h3>
                        <p class="text-xs text-slate-500">Wisatawan hanya dapat memilih sesi yang berstatus aktif saat melakukan pemesanan tiket</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">Nama Sesi</th>
                                <th class="py-3 px-4">Rentang Waktu (WIB)</th>
                                <th class="py-3 px-4 text-center">Batas Kuota Jeep</th>
                                <th class="py-3 px-4 text-center">Total Reservasi</th>
                                <th class="py-3 px-4 text-center">Status Operasional</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($slots as $idx => $slot)
                                @php
                                    $startTimeFormatted = date('H:i', strtotime($slot->start_time));
                                    $endTimeFormatted = date('H:i', strtotime($slot->end_time));
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ !$slot->is_active ? 'opacity-70 bg-slate-50/40' : '' }}">
                                    <td class="py-3 px-4 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-xs shrink-0">
                                                ⏱️
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-800 text-sm block">{{ $slot->slot_label }}</span>
                                                <span class="text-[10px] text-slate-400">ID Sesi: #{{ $slot->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 border border-slate-200 font-bold text-slate-700">
                                            <span>{{ $startTimeFormatted }}</span>
                                            <span class="text-slate-400">-</span>
                                            <span>{{ $endTimeFormatted }} WIB</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <span class="font-extrabold text-sm text-slate-800">{{ $slot->max_jeeps }}</span>
                                        <span class="text-[10px] text-slate-400 block">Jeep / Trip</span>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span>{{ $slot->bookings_count ?? 0 }}</span>
                                            <span class="text-[9px] text-blue-500 font-normal">tiket</span>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.schedules.toggle', $slot->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" title="Klik untuk mengubah status aktif/non-aktif" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold transition-all cursor-pointer {{ $slot->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
                                                <span class="w-2 h-2 rounded-full {{ $slot->is_active ? 'bg-emerald-600 animate-pulse' : 'bg-slate-400' }}"></span>
                                                <span>{{ $slot->is_active ? 'Buka (Aktif)' : 'Tutup (Libur)' }}</span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button"
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer"
                                                data-id="{{ $slot->id }}"
                                                data-label="{{ $slot->slot_label }}"
                                                data-start="{{ $startTimeFormatted }}"
                                                data-end="{{ $endTimeFormatted }}"
                                                data-max="{{ $slot->max_jeeps }}"
                                                onclick="handleEditScheduleClick(this)">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.schedules.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi {{ addslashes($slot->slot_label) }}?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <p class="font-medium text-sm">Belum ada sesi keberangkatan yang dibuat.</p>
                                        <p class="text-xs mt-1">Klik tombol "Tambah Sesi Baru" di atas untuk menambahkan slot waktu operasional.</p>
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
    <div id="modalCreateSchedule" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Tambah Sesi Baru</h3>
                        <p class="text-xs text-slate-500">Tentukan jam keberangkatan & kuota armada</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateScheduleModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.schedules.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama / Label Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" name="slot_label" required placeholder="Contoh: Sesi 1: Sunrise Pagi" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" name="start_time" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" name="end_time" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Maksimal Armada Jeep per Sesi <span class="text-rose-500">*</span></label>
                    <input type="number" name="max_jeeps" required min="1" max="50" value="15" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary font-semibold">
                    <p class="text-[10px] text-slate-400 mt-1">Sistem otomatis membatasi pesanan jika kuota jeep pada jam tersebut terpenuhi</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeCreateScheduleModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                        Simpan Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit Sesi Jadwal -->
    <div id="modalEditSchedule" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Edit Sesi Jadwal</h3>
                        <p class="text-xs text-slate-500">Perbarui jam operasional dan kuota armada jeep</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditScheduleModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold">&times;</button>
            </div>

            <form id="editScheduleForm" action="" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama / Label Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_slot_label" name="slot_label" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" id="edit_start_time" name="start_time" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" id="edit_end_time" name="end_time" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Maksimal Armada Jeep per Sesi <span class="text-rose-500">*</span></label>
                    <input type="number" id="edit_max_jeeps" name="max_jeeps" required min="1" max="50" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary font-semibold">
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditScheduleModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateScheduleModal() {
            document.getElementById('modalCreateSchedule').classList.remove('hidden');
        }

        function closeCreateScheduleModal() {
            document.getElementById('modalCreateSchedule').classList.add('hidden');
        }

        function openEditScheduleModal(id, label, startTime, endTime, maxJeeps) {
            const form = document.getElementById('editScheduleForm');
            form.action = '/admin/jadwal/' + id;
            document.getElementById('edit_slot_label').value = label || '';
            document.getElementById('edit_start_time').value = startTime || '';
            document.getElementById('edit_end_time').value = endTime || '';
            document.getElementById('edit_max_jeeps').value = maxJeeps || '';
            document.getElementById('modalEditSchedule').classList.remove('hidden');
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
            document.getElementById('modalEditSchedule').classList.add('hidden');
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateScheduleModal();
                closeEditScheduleModal();
            }
        });
    </script>
</body>
</html>
