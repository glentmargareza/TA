<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket Wisata | Admin Jeep Wukirsari</title>
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
                <h2 class="text-base font-bold text-slate-800">Kelola Paket Wisata Jeep</h2>
                <p class="text-xs text-slate-500">Atur paket tour, tarif per jeep, destinasi rute, dan ketersediaan paket</p>
            </div>
            <div>
                <button type="button" onclick="openCreatePackageModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 via-[#183c21] to-[#102b17] hover:from-emerald-600 hover:to-[#183c21] text-white text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Paket Baru</span>
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
                        <span>Gagal memproses data paket:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-4">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ringkasan Statistik Paket -->
            @php
                $totalPackages = $packages->count();
                $activePackages = $packages->where('is_active', true)->count();
                $inactivePackages = $packages->where('is_active', false)->count();
                $avgPrice = $packages->avg('price_per_jeep') ?: 0;
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-slate-400 text-xs font-medium uppercase tracking-wider">Total Paket</span>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $totalPackages }} <span class="text-xs font-normal text-slate-500">paket</span></p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-emerald-600 text-xs font-medium uppercase tracking-wider">Paket Aktif (Tampil)</span>
                    <p class="text-2xl font-black text-emerald-700 mt-1">{{ $activePackages }} <span class="text-xs font-normal text-slate-500">paket</span></p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-slate-500 text-xs font-medium uppercase tracking-wider">Non-Aktif / Draft</span>
                    <p class="text-2xl font-black text-slate-700 mt-1">{{ $inactivePackages }} <span class="text-xs font-normal text-slate-500">paket</span></p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <span class="text-slate-400 text-xs font-medium uppercase tracking-wider">Rata-rata Tarif / Jeep</span>
                    <p class="text-xl font-black text-wukirsari-primary mt-1">Rp {{ number_format($avgPrice, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Tabel Daftar Paket Wisata -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Paket Tour Merapi</h3>
                        <p class="text-xs text-slate-500">Paket yang berstatus aktif akan muncul di halaman pemesanan wisatawan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">Paket Wisata</th>
                                <th class="py-3 px-4">Tarif / Jeep</th>
                                <th class="py-3 px-4">Durasi & Destinasi</th>
                                <th class="py-3 px-4">Rute Kunjungan</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($packages as $idx => $pkg)
                                @php
                                    $routes = is_array($pkg->routes_list) ? $pkg->routes_list : [];
                                    $routesRawText = implode(', ', $routes);
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ !$pkg->is_active ? 'opacity-70 bg-slate-50/40' : '' }}">
                                    <td class="py-3 px-4 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $pkg->cover_image ?: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $pkg->name }}" class="w-14 h-12 rounded-lg object-cover border border-slate-200 shadow-2xs shrink-0">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-slate-800 text-sm truncate">{{ $pkg->name }}</span>
                                                    @if($pkg->category_badge)
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0">
                                                            {{ $pkg->category_badge }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-slate-400 block truncate max-w-xs">{{ Str::limit($pkg->description, 60) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-extrabold text-sm text-wukirsari-primary">Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-slate-400 block">per unit jeep (max 4 orang)</span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-1.5 font-medium text-slate-700">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>{{ $pkg->effective_duration_text }}</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                {{ $pkg->destinations_count }} Destinasi Wisata
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 max-w-xs">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($routes as $r)
                                                <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] border border-slate-200">
                                                    {{ $r }}
                                                </span>
                                            @empty
                                                <span class="text-slate-400 italic text-[11px]">Belum ada rute rincian</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <form action="{{ route('admin.packages.toggle', $pkg->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" title="Klik untuk mengubah status aktif/non-aktif" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-all cursor-pointer {{ $pkg->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
                                                <span class="w-2 h-2 rounded-full {{ $pkg->is_active ? 'bg-emerald-600 animate-pulse' : 'bg-slate-400' }}"></span>
                                                <span>{{ $pkg->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button"
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer"
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
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ addslashes($pkg->name) }}?')" class="inline-block">
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
                                        <p class="font-medium text-sm">Belum ada paket wisata yang terdaftar.</p>
                                        <p class="text-xs mt-1">Klik tombol "Tambah Paket Baru" di atas untuk menambahkan rute pertama.</p>
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
    <div id="modalCreatePackage" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Tambah Paket Wisata Baru</h3>
                        <p class="text-xs text-slate-500">Lengkapi informasi tarif, durasi, dan rute destinasi jeep</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreatePackageModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.packages.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Paket <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Paket Sunrise Merapi" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Kategori</label>
                        <input type="text" name="category_badge" placeholder="Contoh: Terpopuler / Sunrise / Extreme" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tarif per Jeep (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price_per_jeep" required min="10000" step="5000" placeholder="Contoh: 450000" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Durasi (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_minutes" required min="15" step="15" placeholder="Contoh: 120" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Label Durasi Teks</label>
                        <input type="text" name="duration_text" placeholder="Contoh: 2 Jam (Sunrise)" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">URL Gambar Sampul (Opsional)</label>
                    <input type="url" name="cover_image" placeholder="https://images.unsplash.com/..." class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika ingin memakai gambar foto jeep default</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Destinasi Rute Kunjungan (Pisahkan dengan koma atau baris baru)</label>
                    <textarea name="routes_raw" rows="3" placeholder="Contoh: Bunker Kaliadem, Batu Alien, Museum Sisa Hartaku, Offroad Manuver Air Kalikuning" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Lengkap Paket <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan daya tarik, keunggulan paket, dan pengalaman yang didapatkan wisatawan..." class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeCreatePackageModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                        Simpan Paket Wisata
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit Paket Wisata -->
    <div id="modalEditPackage" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Edit Informasi Paket</h3>
                        <p class="text-xs text-slate-500">Perbarui rincian tarif, durasi, atau daftar destinasi paket</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditPackageModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center font-bold">&times;</button>
            </div>

            <form id="editPackageForm" action="" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Paket <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Kategori</label>
                        <input type="text" id="edit_category_badge" name="category_badge" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tarif per Jeep (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="edit_price_per_jeep" name="price_per_jeep" required min="10000" step="5000" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Durasi (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" id="edit_duration_minutes" name="duration_minutes" required min="15" step="15" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Label Durasi Teks</label>
                        <input type="text" id="edit_duration_text" name="duration_text" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">URL Gambar Sampul</label>
                    <input type="url" id="edit_cover_image" name="cover_image" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Destinasi Rute Kunjungan (Pisahkan dengan koma)</label>
                    <textarea id="edit_routes_raw" name="routes_raw" rows="3" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Lengkap Paket <span class="text-rose-500">*</span></label>
                    <textarea id="edit_description" name="description" rows="3" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-wukirsari-primary/20 focus:border-wukirsari-primary"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditPackageModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
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
        function openCreatePackageModal() {
            document.getElementById('modalCreatePackage').classList.remove('hidden');
        }

        function closeCreatePackageModal() {
            document.getElementById('modalCreatePackage').classList.add('hidden');
        }

        function openEditPackageModal(id, name, price, duration, durationText, badge, cover, description, routes) {
            const form = document.getElementById('editPackageForm');
            form.action = '/admin/paket/' + id;
            document.getElementById('edit_name').value = name || '';
            document.getElementById('edit_price_per_jeep').value = price || '';
            document.getElementById('edit_duration_minutes').value = duration || '';
            document.getElementById('edit_duration_text').value = durationText || '';
            document.getElementById('edit_category_badge').value = badge || '';
            document.getElementById('edit_cover_image').value = cover || '';
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_routes_raw').value = routes || '';
            document.getElementById('modalEditPackage').classList.remove('hidden');
        }

        function handleEditPackageClick(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const price = btn.getAttribute('data-price');
            const duration = btn.getAttribute('data-duration');
            const durationText = btn.getAttribute('data-duration-text');
            const badge = btn.getAttribute('data-category-badge');
            const cover = btn.getAttribute('data-cover-image');
            const description = btn.getAttribute('data-description');
            const routes = btn.getAttribute('data-routes');

            openEditPackageModal(id, name, price, duration, durationText, badge, cover, description, routes);
        }

        function closeEditPackageModal() {
            document.getElementById('modalEditPackage').classList.add('hidden');
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreatePackageModal();
                closeEditPackageModal();
            }
        });
    </script>
</body>
</html>
