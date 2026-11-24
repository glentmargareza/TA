<x-admin-layout :title="'Monitoring Armada Jeep (' . $totalJeepsCount . ' Unit) | Jeep Wukirsari'">

    <!-- Header Banner -->
    <x-admin.header title="Monitoring Armada Jeep & Driver" subtitle="Admin memantau kesiapan armada Jeep, ketersediaan pangkalan, serta status operasional driver Paguyuban.">
        <x-slot:actions>
            <button type="button" onclick="openAddJeepModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer active:scale-95">
                <x-icon name="plus" class="w-4 h-4" />
                <span>Tambah Kendaraan</span>
            </button>
        </x-slot:actions>
    </x-admin.header>

    <main class="p-4 sm:p-8 space-y-5 sm:space-y-7 flex-1">

        @if(session('success'))
        <x-alert type="success" :message="session('success')" />
        @endif

        @if($errors->any())
        <x-alert type="danger" :message="$errors->first()" />
        @endif

        <!-- 3 KARTU RINGKASAN: JUMLAH KENDARAAN, DRIVER AKTIF, DRIVER NONAKTIF -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 md:gap-5">
            <x-stat-card label="Jumlah Kendaraan" :value="$totalJeepsCount" unit="Unit" subtext="Total Armada Paguyuban" icon="car" />
            <x-stat-card label="Driver Aktif" :value="$activeDriversCount" unit="Driver" subtext="Siap ditugaskan" icon="user" iconWrapper="bg-emerald-100 text-emerald-800" />
            <x-stat-card label="Driver Nonaktif" :value="$inactiveDriversCount" unit="Driver" subtext="Nonaktif / Libur" icon="users" iconWrapper="bg-amber-100 text-amber-800" />
        </div>

        <!-- PANEL: MONITORING DRIVER PAGUYUBAN (AKTIF & TIDAK AKTIF) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h3 class="text-base font-bold text-slate-800 font-display">Status & Kesiapan Driver</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar driver yang siap ditugaskan melayani wisatawan dan driver yang sedang libur/tidak aktif.</p>
                </div>
            </div>

<<<<<<< HEAD
            <!-- PANEL: PEMAKAIAN ARMADA PER SESI (SINKRON DENGAN BOOKING) -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
        <div>
            <h3 class="text-base font-bold text-slate-800">Pemakaian Armada per Sesi</h3>
            <p class="text-xs text-slate-500 mt-0.5" id="quotaSub">Data langsung dari sistem booking. Berkurang otomatis saat ada pesanan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="quotaPrev" class="w-8 h-8 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 flex items-center justify-center cursor-pointer">‹</button>
            <input type="date" id="quotaDate" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <button type="button" id="quotaNext" class="w-8 h-8 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 flex items-center justify-center cursor-pointer">›</button>
            <button type="button" id="quotaToday" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-[11px] font-bold text-slate-700 cursor-pointer">Hari Ini</button>
        </div>
    </div>
    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" id="quotaGrid">
        <div class="col-span-full text-xs text-slate-400">Memuat data kuota...</div>
    </div>
</div>

            <!-- PANEL: MONITORING DRIVER PAGUYUBAN (AKTIF & TIDAK AKTIF) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
=======
            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- KOLOM 1: DAFTAR SEMENTARA DRIVER YANG AKTIF -->
                <div class="bg-[#F8FAF9] rounded-2xl border border-emerald-200/80 p-5 flex flex-col justify-between">
>>>>>>> origin/optimasi
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-emerald-200/70">
                            <h4 class="text-sm font-extrabold text-slate-800 tracking-tight font-display">Daftar Driver yang Aktif</h4>
                        </div>

                        <div class="space-y-2">
                            @forelse($activeDrivers as $index => $d)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-slate-200/90 hover:border-emerald-300 hover:shadow-xs transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center justify-center shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 text-xs sm:text-sm block capitalize truncate">
                                            {{ $d->full_name }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 block font-mono">
                                            ID: #DRV-{{ str_pad($d->id, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <form action="{{ route('admin.drivers.status', $d->id) }}" method="POST" class="inline"
                                          data-confirm-title="Nonaktifkan Driver"
                                          data-confirm-message="Ubah status driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> menjadi <strong class='text-amber-700 font-bold'>TIDAK AKTIF</strong>? Driver sementara tidak akan dialokasikan ke booking."
                                          data-confirm-type="warning"
                                          data-confirm-text="Ya, Nonaktifkan"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        <x-action-button type="edit" icon="x-mark" as="submit" title="Nonaktifkan Driver" />
                                    </form>
                                    <form action="{{ route('admin.drivers.destroy', $d->id) }}" method="POST" class="inline"
                                          data-confirm-title="Hapus Driver"
                                          data-confirm-message="Apakah Anda yakin ingin menghapus data driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> secara permanen dari sistem?"
                                          data-confirm-type="danger"
                                          data-confirm-text="Ya, Hapus Driver"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        @method('DELETE')
                                        <x-action-button type="delete" as="submit" title="Hapus Driver" />
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center text-slate-400 text-xs italic">
                                Tidak ada driver aktif saat ini.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- KOLOM 2: DAFTAR DRIVER YANG TIDAK AKTIF -->
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                            <h4 class="text-sm font-extrabold text-slate-800 tracking-tight font-display">Daftar Driver yang Tidak Aktif</h4>
                        </div>

                        <div class="space-y-2">
                            @forelse($inactiveDrivers as $index => $d)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-slate-200/90 hover:border-slate-300 transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-extrabold text-xs flex items-center justify-center shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-700 text-xs sm:text-sm block capitalize truncate">
                                            {{ $d->full_name }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 block font-mono">
                                            ID: #DRV-{{ str_pad($d->id, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <form action="{{ route('admin.drivers.status', $d->id) }}" method="POST" class="inline"
                                          data-confirm-title="Aktifkan Kembali Driver"
                                          data-confirm-message="Aktifkan kembali driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> untuk jadwal rotasi penugasan armada?"
                                          data-confirm-type="success"
                                          data-confirm-text="Ya, Aktifkan"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        <x-action-button type="toggle" icon="check" as="submit" title="Aktifkan Driver" />
                                    </form>
                                    <form action="{{ route('admin.drivers.destroy', $d->id) }}" method="POST" class="inline"
                                          data-confirm-title="Hapus Driver"
                                          data-confirm-message="Apakah Anda yakin ingin menghapus data driver <strong class='text-slate-800 font-bold'>{{ $d->full_name }}</strong> secara permanen dari sistem?"
                                          data-confirm-type="danger"
                                          data-confirm-text="Ya, Hapus Driver"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        @method('DELETE')
                                        <x-action-button type="delete" as="submit" title="Hapus Driver" />
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center text-slate-400 text-xs italic">
                                Semua driver saat ini berstatus aktif.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL: TAMBAH KENDARAAN / ARMADA JEEP BARU -->
    @php
        $nextJeepNo = $totalJeepsCount + 1;
    @endphp
    <div id="modalAddJeep" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalAddJeepCard" class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
            <form action="{{ route('admin.jeeps.store') }}" method="POST">
                @csrf
                <!-- Modal Header -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                            <x-icon name="car" class="w-5 h-5 text-emerald-700" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Kendaraan Baru</h3>
                            <p class="text-xs text-slate-500">Nomor plat / unit diurutkan otomatis dari 1</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAddJeepModal()" class="w-8 h-8 rounded-full hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
                </div>

                <!-- Modal Body Form -->
                <div class="p-6 space-y-4 text-xs">
                    <!-- 1. Nomor Armada / Plat -->
                    <div>
                        <label for="jeep_plate_number" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Nomor Armada / Plat (Urut dari 1) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="plate_number" id="jeep_plate_number" value="{{ $nextJeepNo }}" oninput="syncJeepName()" placeholder="Contoh: {{ $nextJeepNo }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-mono font-bold tracking-wider focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Otomatis urut mulai dari nomor 1.</span>
                    </div>

                    <!-- 2. Nama Driver Baru (Ketik Manual) -->
                    <div>
                        <label for="jeep_driver_name" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Nama Lengkap Driver Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="driver_name" id="jeep_driver_name" required oninput="syncJeepName()" placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Nama driver yang diketik akan otomatis ditambahkan ke daftar driver paguyuban.</span>
                    </div>

                    <!-- 3. Nomor Telepon / WhatsApp Driver -->
                    <div>
                        <label for="jeep_driver_phone" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Nomor Telepon / WhatsApp Driver <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="driver_phone" id="jeep_driver_phone" placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- 4. Format Nama / Identitas Kendaraan -->
                    <div>
                        <label for="jeep_model_name" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Format Nama / Identitas Armada
                        </label>
                        <input type="text" name="model_name" id="jeep_model_name" value="Jeep #{{ $nextJeepNo }}" required placeholder="Contoh: Jeep #{{ $nextJeepNo }} (Nama Driver)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Otomatis terformat menjadi Jeep #{{ $nextJeepNo }} (Nama Driver).</span>
                    </div>

                    <!-- 5. Status Awal -->
                    <div>
                        <label for="jeep_status" class="block font-bold text-slate-700 mb-1.5 text-xs">
                            Status Awal Operasional <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="jeep_status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            <option value="READY" selected>● TERSEDIA (Armada & Driver Langsung Siap Jalan)</option>
                            <option value="MAINTENANCE">● TIDAK TERSEDIA (Sedang Servis / Libur)</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeAddJeepModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow transition-all cursor-pointer">
                        <x-icon name="check" class="w-4 h-4" />
                        <span>Simpan Kendaraan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT MODAL ADD JEEP -->
    <x-slot:scripts>
        <script>
            function syncJeepName() {
                const numInput = document.getElementById('jeep_plate_number');
                const driverInput = document.getElementById('jeep_driver_name');
                const modelInput = document.getElementById('jeep_model_name');

                const num = numInput ? (numInput.value.trim() || '{{ $nextJeepNo }}') : '{{ $nextJeepNo }}';
                const driver = driverInput ? driverInput.value.trim() : '';

                if (driver) {
                    modelInput.value = 'Jeep #' + num + ' (' + driver + ')';
                } else {
                    modelInput.value = 'Jeep #' + num;
                }
            }

            function openAddJeepModal() {
                const modal = document.getElementById('modalAddJeep');
                const card = document.getElementById('modalAddJeepCard');
                if (!modal) return;
                modal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                    document.getElementById('jeep_driver_name')?.focus();
                });
            }

            function closeAddJeepModal() {
                const modal = document.getElementById('modalAddJeep');
                const card = document.getElementById('modalAddJeepCard');
                if (!modal) return;
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150);
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddJeepModal();
                }
            });
<<<<<<< HEAD
        }
    </script>

    <script>
(function () {
    const URL_AVAIL = "{{ route('booking.slot_availability') }}";
    const dateEl = document.getElementById('quotaDate');
    const grid   = document.getElementById('quotaGrid');
    const sub    = document.getElementById('quotaSub');

    const iso = d => new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
    const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const fmt = s => {
        const [y, m, d] = s.split('-').map(Number);
        return new Date(y, m - 1, d).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    };

    dateEl.value = iso(new Date());

    async function load() {
        const date = dateEl.value;
        if (!date) return;
        try {
            const res = await fetch(`${URL_AVAIL}?trip_date=${encodeURIComponent(date)}&jeep_quantity=1`, { cache: 'no-store' });
            if (!res.ok) return;
            const data = await res.json();
            if (date !== dateEl.value || !Array.isArray(data.slots)) return;

            sub.textContent = 'Jadwal ' + fmt(date) + ' • diperbarui ' + new Date().toLocaleTimeString('id-ID');

            grid.innerHTML = data.slots.map(s => {
                const max    = Number(s.max_jeeps ?? 0);
                const booked = Number(s.booked_jeeps ?? 0);
                const sisa   = Math.max(typeof s.remaining === 'number' ? s.remaining : max - booked, 0);
                const pct    = max > 0 ? Math.min(Math.round(booked / max * 100), 100) : 0;
                const full   = sisa <= 0;
                const bar    = full ? 'bg-rose-500' : (booked > 0 ? 'bg-amber-500' : 'bg-emerald-500');
                const box    = full ? 'border-rose-200 bg-rose-50/50'
                             : booked > 0 ? 'border-amber-300 bg-amber-50/40'
                             : 'border-slate-200 bg-white';
                return `
                <div class="p-4 rounded-2xl border ${box} space-y-3 ${s.passed ? 'opacity-60' : ''}">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-bold text-sm text-slate-900">${esc(s.label)}</span>
                        ${s.passed ? '<span class="text-[10px] font-bold text-slate-500">Sudah lewat</span>'
                          : full ? '<span class="text-[10px] font-bold text-rose-600">PENUH</span>' : ''}
                    </div>
                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full ${bar} transition-all" style="width:${pct}%"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div><span class="block text-lg font-extrabold text-slate-900">${max}</span><span class="text-[10px] text-slate-500">Kuota</span></div>
                        <div><span class="block text-lg font-extrabold text-amber-700">${booked}</span><span class="text-[10px] text-slate-500">Dipesan</span></div>
                        <div><span class="block text-lg font-extrabold ${full ? 'text-rose-600' : 'text-emerald-700'}">${sisa}</span><span class="text-[10px] text-slate-500">Sisa</span></div>
                    </div>
                </div>`;
            }).join('') || '<div class="col-span-full text-xs text-slate-400">Tidak ada sesi.</div>';
        } catch (e) { /* abaikan error jaringan */ }
    }

    const shift = n => {
        const [y, m, d] = dateEl.value.split('-').map(Number);
        dateEl.value = iso(new Date(y, m - 1, d + n));
        load();
    };
    document.getElementById('quotaPrev').onclick  = () => shift(-1);
    document.getElementById('quotaNext').onclick  = () => shift(1);
    document.getElementById('quotaToday').onclick = () => { dateEl.value = iso(new Date()); load(); };
    dateEl.addEventListener('change', load);

    load();
    setInterval(() => { if (document.visibilityState === 'visible') load(); }, 15000);
})();
</script>
</body>
</html>
=======
>>>>>>> origin/optimasi

            const modalAddJeep = document.getElementById('modalAddJeep');
            if (modalAddJeep) {
                modalAddJeep.addEventListener('click', function(e) {
                    if (e.target === modalAddJeep) {
                        closeAddJeepModal();
                    }
                });
            }
        </script>
    </x-slot:scripts>

</x-admin-layout>
