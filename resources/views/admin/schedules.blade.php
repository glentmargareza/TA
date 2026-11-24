<x-admin-layout title="Kelola Jadwal Sesi | Admin Jeep Wukirsari">

    <!-- Header Banner -->
    <x-admin.header title="Kelola Jadwal & Sesi Keberangkatan" subtitle="Atur jam operasional trip dan ketersediaan waktu pemesanan tiket wisatawan.">
        <x-slot:actions>
            <button type="button" onclick="openCreateScheduleModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                <x-icon name="plus" class="w-4 h-4" />
                <span>Tambah Sesi Baru</span>
            </button>
        </x-slot:actions>
    </x-admin.header>

    <main class="p-4 sm:p-8 space-y-5 sm:space-y-6 flex-1">

        <!-- 3 Kartu Ringkasan Statistik Jadwal Sesi -->
        @php
            $totalSlots = $slots->count();
            $activeSlots = $slots->where('is_active', true)->count();
            $inactiveSlots = $slots->where('is_active', false)->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 md:gap-5">
            <x-stat-card label="Total Sesi" :value="$totalSlots" unit="Sesi" subtext="Slot Terdaftar" icon="clock" />
            <x-stat-card label="Sesi Aktif" :value="$activeSlots" unit="Sesi" subtext="Buka Pemesanan" icon="check" iconWrapper="bg-emerald-100 text-emerald-800" />
            <x-stat-card label="Sesi Tutup" :value="$inactiveSlots" unit="Sesi" subtext="Nonaktif / Libur" icon="x-mark" iconWrapper="bg-amber-100 text-amber-800" />
        </div>

        <!-- TABEL DAFTAR SESI JADWAL -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h3 class="text-base font-bold text-slate-800 font-display">Daftar Slot Waktu Keberangkatan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Wisatawan hanya dapat memilih sesi yang berstatus aktif saat melakukan pemesanan tiket.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700 min-w-[640px]">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200 select-none">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 text-center whitespace-nowrap">Aksi</th>
                            <th class="py-3.5 px-5 min-w-[220px]">Nama Sesi</th>
                            <th class="py-3.5 px-5 min-w-[180px] whitespace-nowrap">Rentang Waktu (WIB)</th>
                            <th class="py-3.5 px-5 text-center min-w-[130px] whitespace-nowrap">Total Reservasi</th>
                            <th class="py-3.5 px-5 text-center whitespace-nowrap">Status Operasional</th>
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

                                <!-- Aksi (Edit & Hapus) -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <x-action-button 
                                            type="edit"
                                            title="Edit Sesi Jadwal"
                                            data-id="{{ $slot->id }}"
                                            data-label="{{ $slot->slot_label }}"
                                            data-start="{{ $startTimeFormatted }}"
                                            data-end="{{ $endTimeFormatted }}"
                                            data-max="{{ $slot->max_jeeps }}"
                                            onclick="handleEditScheduleClick(this)" />
                                        
                                        <!-- Tombol Hapus dengan Konfirmasi Modal Kustom -->
                                        <form action="{{ route('admin.schedules.destroy', $slot->id) }}" method="POST" class="inline-block"
                                              data-confirm-title="Hapus Sesi Jadwal"
                                              data-confirm-message="Apakah Anda yakin ingin menghapus sesi jadwal <strong class='text-slate-800 font-bold'>{{ $slot->slot_label }}</strong>? Tindakan ini akan menghapus slot waktu keberangkatan."
                                              data-confirm-type="danger"
                                              data-confirm-text="Ya, Hapus Sesi"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            @method('DELETE')
                                            <x-action-button type="delete" as="submit" title="Hapus Sesi" />
                                        </form>
                                    </div>
                                </td>

                                <!-- Nama Sesi -->
                                <td class="py-3.5 px-5">
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-slate-800 text-sm block leading-tight">{{ $slot->slot_label }}</span>
                                        <span class="text-[11px] text-slate-400 font-medium block font-mono">ID Sesi: #{{ $slot->id }}</span>
                                    </div>
                                </td>

                                <!-- Rentang Waktu -->
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-bold text-xs font-mono">
                                        <x-icon name="clock" class="w-3.5 h-3.5 text-slate-400" />
                                        <span>{{ $startTimeFormatted }} &ndash; {{ $endTimeFormatted }} WIB</span>
                                    </span>
                                </td>

                                <!-- Total Reservasi -->
                                <td class="py-3.5 px-5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs">
                                        <span>{{ $slot->bookings_count ?? 0 }} Tiket</span>
                                    </span>
                                </td>

                                <!-- Status Operasional -->
                                <td class="py-3.5 px-5 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.schedules.toggle', $slot->id) }}" method="POST" class="inline-block"
                                          data-confirm-title="{{ $slot->is_active ? 'Tutup Sesi Jadwal' : 'Buka Sesi Jadwal' }}"
                                          data-confirm-message="Ubah status operasional sesi keberangkatan <strong class='text-slate-800 font-bold'>{{ $slot->slot_label }}</strong> menjadi <strong class='{{ $slot->is_active ? 'text-amber-700' : 'text-emerald-700' }} font-bold'>{{ $slot->is_active ? 'TUTUP (LIBUR)' : 'BUKA (AKTIF)' }}</strong>?"
                                          data-confirm-type="{{ $slot->is_active ? 'warning' : 'success' }}"
                                          data-confirm-text="{{ $slot->is_active ? 'Ya, Tutup Sesi' : 'Ya, Buka Sesi' }}"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        <button type="submit" title="Klik untuk mengubah status buka/tutup sesi" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs {{ $slot->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}">
                                            <span>{{ $slot->is_active ? 'Aktif' : 'Tutup' }}</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state 
                                        title="Belum Ada Sesi Keberangkatan Dibuat" 
                                        description="Klik tombol Tambah Sesi Baru di atas untuk menambahkan slot waktu operasional pangkalan." 
                                        icon="clock" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL: Tambah Sesi Baru -->
    <div id="modalCreateSchedule" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalCreateScheduleCard" class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                        <x-icon name="clock" class="w-5 h-5 text-emerald-700" />
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Sesi Baru</h3>
                        <p class="text-xs text-slate-500">Tentukan jam dan batas armada per sesi</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateScheduleModal()" class="w-8 h-8 rounded-full hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form action="{{ route('admin.schedules.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Label Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" name="slot_label" required placeholder="Contoh: Sesi 1 Pagi (Sunrise)" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" name="start_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" name="end_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Maksimal Armada Jeep / Sesi <span class="text-rose-500">*</span></label>
                    <input type="number" name="max_jeeps" required min="1" max="50" value="10" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">Batas kuota armada jeep yang dapat diberangkatkan bersamaan pada sesi ini.</span>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeCreateScheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-xs hover:shadow cursor-pointer">
                        <x-icon name="check" class="w-4 h-4" />
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
                        <x-icon name="pencil" class="w-5 h-5 text-emerald-700" />
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Sesi Jadwal</h3>
                        <p class="text-xs text-slate-500">Perbarui jam keberangkatan atau batas kuota</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditScheduleModal()" class="w-8 h-8 rounded-full hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form id="editScheduleForm" action="" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Label Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_slot_label" name="slot_label" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" id="edit_start_time" name="start_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" id="edit_end_time" name="end_time" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Maksimal Armada Jeep / Sesi <span class="text-rose-500">*</span></label>
                    <input type="number" id="edit_max_jeeps" name="max_jeeps" required min="1" max="50" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeEditScheduleModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-xs hover:shadow cursor-pointer">
                        <x-icon name="check" class="w-4 h-4" />
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
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

            // Auto-refresh jadwal & kuota setiap 25 detik saat tab aktif
            setInterval(() => {
                if (document.visibilityState === 'visible') {
                    const modalCreate = document.getElementById('modalCreateSchedule');
                    const modalEdit = document.getElementById('modalEditSchedule');
                    const isModalOpen = (modalCreate && !modalCreate.classList.contains('hidden')) ||
                                        (modalEdit && !modalEdit.classList.contains('hidden'));
                    if (!isModalOpen) {
                        window.location.reload();
                    }
                }
            }, 25000);

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
    </x-slot:scripts>

</x-admin-layout>
