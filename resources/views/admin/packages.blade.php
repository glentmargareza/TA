<x-admin-layout title="Kelola Paket Wisata | Admin Jeep Wukirsari">

    <!-- Header Banner -->
    <x-admin.header title="Kelola Paket Wisata Jeep" subtitle="Atur paket tour Merapi, tarif per armada jeep, rute destinasi, dan ketersediaan paket pemesanan.">
        <x-slot:actions>
            <button type="button" onclick="openCreatePackageModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer active:scale-95">
                <x-icon name="plus" class="w-4 h-4" />
                <span>Tambah Paket Baru</span>
            </button>
        </x-slot:actions>
    </x-admin.header>

    <main class="p-4 sm:p-8 space-y-5 sm:space-y-6 flex-1 overflow-y-auto">

        @if(session('success'))
        <x-alert type="success" :message="session('success')" />
        @endif

        @if(isset($errors) && $errors->any())
        <x-alert type="danger">
            <span class="block font-bold mb-1">Gagal memproses data paket wisata:</span>
            <ul class="list-disc list-inside space-y-0.5 text-xs font-normal">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </x-alert>
        @endif

        <!-- Ringkasan Statistik Paket -->
        @php
            $totalPackages = $packages->count();
            $activePackages = $packages->where('is_active', true)->count();
            $inactivePackages = $packages->where('is_active', false)->count();
            $avgPrice = $packages->avg('price_per_jeep') ?: 0;
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 md:gap-5">
            <x-stat-card label="Total Paket Wisata" :value="$totalPackages" unit="Rute" subtext="Paket Terdaftar" />
            <x-stat-card label="Paket Aktif (Tampil)" :value="$activePackages" unit="Paket" subtext="Tampil di Beranda" iconWrapper="bg-emerald-100 text-emerald-800" />
            <x-stat-card label="Non-Aktif / Draft" :value="$inactivePackages" unit="Paket" subtext="Draft / Disembunyikan" iconWrapper="bg-amber-100 text-amber-800" />
            <x-stat-card label="Rata-rata Tarif / Jeep" :value="'Rp ' . number_format($avgPrice, 0, ',', '.')" subtext="Tarif Rata-rata" />
        </div>

            <!-- TABEL DAFTAR PAKET WISATA -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center gap-3">
                        
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 font-display tracking-tight">Daftar Paket Tour</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Paket berstatus aktif otomatis dapat dipilih oleh wisatawan pada form reservasi online.</p>
                        </div>
                    </div>
                    
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700 min-w-[680px]">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider select-none">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center">No</th>
                                <th class="py-4 px-4 text-center whitespace-nowrap">Aksi</th>
                                <th class="py-4 px-4 min-w-[260px]">Paket Wisata</th>
                                <th class="py-4 px-4 min-w-[140px] whitespace-nowrap">Tarif / Jeep</th>
                                <th class="py-4 px-4 min-w-[150px] whitespace-nowrap">Durasi & Destinasi</th>
                                <th class="py-4 px-5 min-w-[280px]">Rute Kunjungan</th>
                                <th class="py-4 px-4 text-center whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($packages as $idx => $pkg)
                                @php
                                    $routes = is_array($pkg->routes_list) ? $pkg->routes_list : [];
                                    $routesRawText = implode(', ', $routes);
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ !$pkg->is_active ? 'bg-slate-50/50 opacity-75' : '' }}">
                                    <!-- No -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs inline-flex items-center justify-center">
                                            {{ $idx + 1 }}
                                        </span>
                                    </td>

                                    <!-- Aksi (Edit & Hapus) -->
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tombol Buka Detail & Kelola Foto -->
                                            <a href="{{ route('packages.show', $pkg->slug) }}"
                                               target="_blank"
                                               class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-500 hover:text-emerald-700 text-xs shadow-2xs transition-all cursor-pointer inline-flex items-center justify-center"
                                               title="Buka Detail & Kelola Foto Paket">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </a>

                                            <button type="button"
                                                class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-500 hover:text-emerald-700 text-xs shadow-2xs transition-all cursor-pointer"
                                                title="Edit Paket Wisata"
                                                data-id="{{ $pkg->id }}"
                                                data-name="{{ $pkg->name }}"
                                                data-slug="{{ $pkg->slug }}"
                                                data-price="{{ $pkg->price_per_jeep }}"
                                                data-duration="{{ $pkg->duration_minutes }}"
                                                data-duration-text="{{ $pkg->duration_text }}"
                                                data-category-badge="{{ $pkg->category_badge }}"
                                                data-cover-image="{{ $pkg->cover_image }}"
                                                data-description="{{ $pkg->description }}"
                                                data-routes="{{ $routesRawText }}"
                                                onclick="handleEditPackageClick(this)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>

                                            <!-- Tombol Hapus dengan Konfirmasi Modal Kustom -->
                                            <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="inline"
                                                  data-confirm-title="Hapus Paket Wisata"
                                                  data-confirm-message="Apakah Anda yakin ingin menghapus paket <strong class='text-slate-800 font-bold'>{{ $pkg->name }}</strong>? Paket tidak akan dapat dipesan lagi oleh wisatawan."
                                                  data-confirm-type="danger"
                                                  data-confirm-text="Ya, Hapus Paket"
                                                  data-confirm-cancel="Batal">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-300 text-slate-400 hover:text-rose-600 text-xs shadow-2xs transition-all cursor-pointer" title="Hapus Paket">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <!-- Paket Wisata & Gambar -->
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="relative shrink-0">
                                                <img src="{{ $pkg->cover_image ?: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $pkg->name }}" class="w-16 h-14 rounded-2xl object-cover border border-slate-200/90 shadow-2xs">
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="font-extrabold text-slate-900 text-sm font-display leading-tight">{{ $pkg->name }}</span>
                                                    @if($pkg->category_badge)
                                                        @php
                                                            $badgeLower = strtolower($pkg->category_badge);
                                                            $badgeStyle = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                                            if (str_contains($badgeLower, 'populer')) $badgeStyle = 'bg-amber-50 text-amber-800 border-amber-200';
                                                            elseif (str_contains($badgeLower, 'medium') || str_contains($badgeLower, 'rute')) $badgeStyle = 'bg-blue-50 text-blue-800 border-blue-200';
                                                            elseif (str_contains($badgeLower, 'lengkap') || str_contains($badgeLower, 'long')) $badgeStyle = 'bg-purple-50 text-purple-800 border-purple-200';
                                                            elseif (str_contains($badgeLower, 'sunrise')) $badgeStyle = 'bg-rose-50 text-rose-800 border-rose-200';
                                                        @endphp
                                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeStyle }} shrink-0">
                                                            {{ $pkg->category_badge }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Tarif per Jeep -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <div class="space-y-0.5">
                                            <span class="font-black text-base text-emerald-800 font-display block tracking-tight">
                                                Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Durasi & Destinasi -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-bold text-xs">
                                                <span>{{ $pkg->effective_duration_text }}</span>
                                            </span>
                                            <span class="text-[11px] text-slate-500 font-semibold flex items-center gap-1">
                                                <span>{{ $pkg->destinations_count }} Titik Destinasi</span>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Rute Kunjungan (Lebih Rapi & Enak Dibaca) -->
                                    <td class="py-4 px-5">
                                        <div class="flex flex-wrap gap-1.5 max-w-md">
                                            @forelse($routes as $r)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-900 rounded-lg text-[11px] font-semibold border border-slate-200/80 transition-colors">
                                                    <span>{{ $r }}</span>
                                                </span>
                                            @empty
                                                <span class="text-slate-400 italic text-[11px]">Belum ada rute rincian</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.packages.toggle', $pkg->id) }}" method="POST" class="inline-block"
                                              data-confirm-title="{{ $pkg->is_active ? 'Nonaktifkan Paket' : 'Aktifkan Paket' }}"
                                              data-confirm-message="Ubah status paket wisata <strong class='text-slate-800 font-bold'>{{ $pkg->name }}</strong> menjadi <strong class='{{ $pkg->is_active ? 'text-amber-700' : 'text-emerald-700' }} font-bold'>{{ $pkg->is_active ? 'DRAFT (NONAKTIF)' : 'AKTIF' }}</strong>?"
                                              data-confirm-type="{{ $pkg->is_active ? 'warning' : 'success' }}"
                                              data-confirm-text="{{ $pkg->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            <button type="submit" title="Klik untuk mengubah status aktif / draft" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs {{ $pkg->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}">
                                                <span>{{ $pkg->is_active ? 'Aktif' : 'Draft' }}</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <div class="max-w-sm mx-auto space-y-2">
                                            <p class="font-bold text-slate-700 text-sm">Belum ada paket wisata terdaftar</p>
                                            <p class="text-xs text-slate-500">Klik tombol "Tambah Paket Baru" di atas untuk menambahkan rute pertama.</p>
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

    <!-- MODAL: Tambah Paket Baru -->
    <div id="modalCreatePackage" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalCreatePackageCard" class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Paket Wisata Baru</h3>
                        <p class="text-xs text-slate-500">Lengkapi informasi tarif, durasi, dan rute destinasi Jeep</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreatePackageModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Paket <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Short Trip (Rute A)" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Badge Kategori</label>
                        <input type="text" name="category_badge" placeholder="Contoh: Paling Populer / Jalur Terlengkap" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tarif per Jeep (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price_per_jeep" required min="10000" step="5000" placeholder="Contoh: 450000" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Durasi (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_minutes" required min="15" step="15" placeholder="Contoh: 120" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Label Durasi Teks</label>
                        <input type="text" name="duration_text" placeholder="Contoh: 1.5 - 2 Jam" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Sampul Paket Wisata</label>
                    <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex items-center gap-3.5">
                            <div class="relative w-24 h-20 rounded-xl overflow-hidden bg-slate-200 border border-slate-300/80 shrink-0 shadow-inner flex items-center justify-center">
                                <img id="create_cover_preview" src="" alt="Preview Sampul" class="w-full h-full object-cover hidden">
                                <div id="create_cover_empty" class="flex flex-col items-center justify-center text-slate-400 p-2 text-center">
                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[9px] font-semibold leading-tight">Default Jeep</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <label for="create_cover_file_input" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold cursor-pointer transition shadow-2xs hover:shadow">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Pilih Foto dari Device</span>
                                    </label>
                                    <input type="file" id="create_cover_file_input" name="cover_image_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewCreateCover(this)">
                                    <button type="button" id="create_reset_file_btn" onclick="resetCreateCoverFile()" class="hidden px-2.5 py-1.5 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-lg font-medium transition cursor-pointer">
                                        Batal File
                                    </button>
                                </div>
                                <p id="create_file_name_display" class="text-[11px] text-slate-500 truncate">Format: JPG, PNG, WEBP (Maks. 5 MB)</p>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200/70">
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Atau gunakan URL Gambar online (Opsional)</label>
                            <input type="url" id="create_cover_image" name="cover_image" placeholder="https://images.unsplash.com/..." oninput="previewCreateCoverUrl(this.value)" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Destinasi Rute Kunjungan (Pisahkan dengan koma)</label>
                    <textarea name="routes_raw" rows="3" placeholder="Contoh: Susur Kali Gendol, Museum Kampung Hilang, Lorong Oksigen, Gerbang Tol Sukario, Manuver Air" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Lengkap Paket <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan daya tarik, keunggulan paket, dan pengalaman yang didapatkan wisatawan..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeCreatePackageModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-xs hover:shadow cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Paket Wisata</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit Paket Wisata -->
    <div id="modalEditPackage" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
        <div id="modalEditPackageCard" class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shadow-xs">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Informasi Paket</h3>
                        <p class="text-xs text-slate-500">Perbarui rincian tarif, durasi, atau daftar destinasi paket</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditPackageModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form id="editPackageForm" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Paket <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Badge Kategori</label>
                        <input type="text" id="edit_category_badge" name="category_badge" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tarif per Jeep (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="edit_price_per_jeep" name="price_per_jeep" required min="10000" step="5000" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-bold text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Durasi (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" id="edit_duration_minutes" name="duration_minutes" required min="15" step="15" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Label Durasi Teks</label>
                        <input type="text" id="edit_duration_text" name="duration_text" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Sampul Paket Wisata</label>
                    <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-3">
                        <div class="flex items-center gap-3.5">
                            <div class="relative w-24 h-20 rounded-xl overflow-hidden bg-slate-200 border border-slate-300/80 shrink-0 shadow-inner flex items-center justify-center">
                                <img id="edit_cover_preview" src="" alt="Preview Sampul" class="w-full h-full object-cover">
                                <div id="edit_cover_empty" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 text-slate-400 p-2 text-center hidden">
                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[9px] font-semibold leading-tight">Default Jeep</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <label for="edit_cover_file_input" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold cursor-pointer transition shadow-2xs hover:shadow">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Pilih Foto dari Device</span>
                                    </label>
                                    <input type="file" id="edit_cover_file_input" name="cover_image_file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="previewEditCover(this)">
                                    <button type="button" id="edit_reset_file_btn" onclick="resetEditCoverFile()" class="hidden px-2.5 py-1.5 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-lg font-medium transition cursor-pointer">
                                        Batal File
                                    </button>
                                </div>
                                <p id="edit_file_name_display" class="text-[11px] text-slate-500 truncate">Format: JPG, PNG, WEBP (Maks. 5 MB)</p>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200/70">
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Atau gunakan URL Gambar online (Opsional)</label>
                            <input type="url" id="edit_cover_image" name="cover_image" placeholder="https://images.unsplash.com/..." oninput="previewEditCoverUrl(this.value)" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <!-- Callout Kelola 4 Galeri di Detail Paket -->
                        <div class="mt-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200/90 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2 text-[11px] text-emerald-950">
                                <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Kelola <strong>Foto Cover & 4 Foto Galeri</strong> lebih mudah langsung di halaman detail paket.</span>
                            </div>
                            <a id="edit_view_package_detail_link" href="#" target="_blank" class="shrink-0 px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[11px] transition-all inline-flex items-center gap-1 shadow-2xs">
                                <span>Buka Halaman Detail</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Destinasi Rute Kunjungan (Pisahkan dengan koma)</label>
                    <textarea id="edit_routes_raw" name="routes_raw" rows="3" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Lengkap Paket <span class="text-rose-500">*</span></label>
                    <textarea id="edit_description" name="description" rows="3" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditPackageModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
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
        let originalEditCover = '';

        function previewCreateCover(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const previewImg = document.getElementById('create_cover_preview');
                const emptyBox = document.getElementById('create_cover_empty');
                const resetBtn = document.getElementById('create_reset_file_btn');
                const fileNameDisplay = document.getElementById('create_file_name_display');

                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
                if (emptyBox) emptyBox.classList.add('hidden');
                if (resetBtn) resetBtn.classList.remove('hidden');
                if (fileNameDisplay) fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            }
        }

        function resetCreateCoverFile() {
            const fileInput = document.getElementById('create_cover_file_input');
            if (fileInput) fileInput.value = '';
            const resetBtn = document.getElementById('create_reset_file_btn');
            if (resetBtn) resetBtn.classList.add('hidden');
            const fileNameDisplay = document.getElementById('create_file_name_display');
            if (fileNameDisplay) fileNameDisplay.textContent = 'Format: JPG, PNG, WEBP (Maks. 5 MB)';

            const urlInput = document.getElementById('create_cover_image');
            previewCreateCoverUrl(urlInput ? urlInput.value : '');
        }

        function previewCreateCoverUrl(url) {
            const fileInput = document.getElementById('create_cover_file_input');
            if (fileInput && fileInput.files && fileInput.files.length > 0) return;

            const previewImg = document.getElementById('create_cover_preview');
            const emptyBox = document.getElementById('create_cover_empty');
            if (url && url.trim().length > 5) {
                previewImg.src = url.trim();
                previewImg.classList.remove('hidden');
                if (emptyBox) emptyBox.classList.add('hidden');
            } else {
                previewImg.classList.add('hidden');
                if (emptyBox) emptyBox.classList.remove('hidden');
            }
        }

        function previewEditCover(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const previewImg = document.getElementById('edit_cover_preview');
                const emptyBox = document.getElementById('edit_cover_empty');
                const resetBtn = document.getElementById('edit_reset_file_btn');
                const fileNameDisplay = document.getElementById('edit_file_name_display');

                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
                if (emptyBox) emptyBox.classList.add('hidden');
                if (resetBtn) resetBtn.classList.remove('hidden');
                if (fileNameDisplay) fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            }
        }

        function resetEditCoverFile() {
            const fileInput = document.getElementById('edit_cover_file_input');
            if (fileInput) fileInput.value = '';
            const resetBtn = document.getElementById('edit_reset_file_btn');
            if (resetBtn) resetBtn.classList.add('hidden');
            const fileNameDisplay = document.getElementById('edit_file_name_display');
            if (fileNameDisplay) fileNameDisplay.textContent = 'Format: JPG, PNG, WEBP (Maks. 5 MB)';

            const urlInput = document.getElementById('edit_cover_image');
            previewEditCoverUrl(urlInput ? urlInput.value : '');
        }

        function previewEditCoverUrl(url) {
            const fileInput = document.getElementById('edit_cover_file_input');
            if (fileInput && fileInput.files && fileInput.files.length > 0) return;

            const previewImg = document.getElementById('edit_cover_preview');
            const emptyBox = document.getElementById('edit_cover_empty');
            const targetSrc = (url && url.trim().length > 5) ? url.trim() : originalEditCover;

            if (targetSrc) {
                previewImg.src = targetSrc;
                previewImg.classList.remove('hidden');
                if (emptyBox) emptyBox.classList.add('hidden');
            } else {
                previewImg.classList.add('hidden');
                if (emptyBox) emptyBox.classList.remove('hidden');
            }
        }

        function openCreatePackageModal() {
            const modal = document.getElementById('modalCreatePackage');
            const card = document.getElementById('modalCreatePackageCard');
            if (!modal) return;

            resetCreateCoverFile();
            const createUrlInput = document.getElementById('create_cover_image');
            if (createUrlInput) createUrlInput.value = '';
            const previewImg = document.getElementById('create_cover_preview');
            const emptyBox = document.getElementById('create_cover_empty');
            if (previewImg) previewImg.classList.add('hidden');
            if (emptyBox) emptyBox.classList.remove('hidden');

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCreatePackageModal() {
            const modal = document.getElementById('modalCreatePackage');
            const card = document.getElementById('modalCreatePackageCard');
            if (!modal) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        function openEditPackageModal(id, name, price, duration, durationText, badge, cover, description, routes, slug) {
            const form = document.getElementById('editPackageForm');
            const modal = document.getElementById('modalEditPackage');
            const card = document.getElementById('modalEditPackageCard');
            if (!modal || !form) return;

            form.action = '/admin/paket/' + id;
            document.getElementById('edit_name').value = name || '';
            document.getElementById('edit_price_per_jeep').value = price || '';
            document.getElementById('edit_duration_minutes').value = duration || '';
            document.getElementById('edit_duration_text').value = durationText || '';
            document.getElementById('edit_category_badge').value = badge || '';
            document.getElementById('edit_cover_image').value = cover || '';
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_routes_raw').value = routes || '';

            const detailLink = document.getElementById('edit_view_package_detail_link');
            if (detailLink) {
                detailLink.href = "{{ url('/packages') }}/" + (slug || id);
            }

            // Reset file upload state
            originalEditCover = cover || '';
            const fileInput = document.getElementById('edit_cover_file_input');
            if (fileInput) fileInput.value = '';
            const resetBtn = document.getElementById('edit_reset_file_btn');
            if (resetBtn) resetBtn.classList.add('hidden');
            const fileNameDisplay = document.getElementById('edit_file_name_display');
            if (fileNameDisplay) fileNameDisplay.textContent = 'Format: JPG, PNG, WEBP (Maks. 5 MB)';

            const previewImg = document.getElementById('edit_cover_preview');
            const emptyBox = document.getElementById('edit_cover_empty');
            if (cover) {
                if (previewImg) {
                    previewImg.src = cover;
                    previewImg.classList.remove('hidden');
                }
                if (emptyBox) emptyBox.classList.add('hidden');
            } else {
                if (previewImg) previewImg.classList.add('hidden');
                if (emptyBox) emptyBox.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function handleEditPackageClick(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const slug = btn.getAttribute('data-slug');
            const price = btn.getAttribute('data-price');
            const duration = btn.getAttribute('data-duration');
            const durationText = btn.getAttribute('data-duration-text');
            const badge = btn.getAttribute('data-category-badge');
            const cover = btn.getAttribute('data-cover-image');
            const description = btn.getAttribute('data-description');
            const routes = btn.getAttribute('data-routes');

            openEditPackageModal(id, name, price, duration, durationText, badge, cover, description, routes, slug);
        }

        function closeEditPackageModal() {
            const modal = document.getElementById('modalEditPackage');
            const card = document.getElementById('modalEditPackageCard');
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
                closeCreatePackageModal();
                closeEditPackageModal();
            }
        });

        // Close on backdrop click
        ['modalCreatePackage', 'modalEditPackage'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === el) {
                        if (id === 'modalCreatePackage') closeCreatePackageModal();
                        if (id === 'modalEditPackage') closeEditPackageModal();
                    }
                });
            }
        });
    </script>
</x-admin-layout>
