<x-admin-layout title="Manajemen Ulasan Wisatawan | Admin Jeep Wukirsari">

    <!-- Header Banner -->
    <x-admin.header title="Manajemen Ulasan & Testimoni Wisatawan" subtitle="Moderasi ulasan, verifikasi rating bintang, dan tanggapan pengalaman wisatawan Jeep Wukirsari." />

    <main class="p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-6 flex-1 overflow-y-auto">

        <!-- Flash Alert -->
        @if(session('success'))
        <x-alert type="success" :message="session('success')" />
        @endif

        <!-- 3 KARTU STATISTIK ULASAN -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
            <x-stat-card label="Total Ulasan Masuk" :value="$totalReviews" unit="Ulasan" subtext="Semua testimoni tersimpan" icon="users" />
            <x-stat-card label="Rata-Rata Penilaian" :value="number_format($avgRating, 1)" unit="/ 5.0" subtext="Indeks kepuasan wisatawan" icon="chart-bar" iconWrapper="bg-amber-100 text-amber-700" />
            <x-stat-card label="Ulasan Bintang 5" :value="$fiveStarCount" unit="Ulasan" subtext="Rating sempurna bintang lima" icon="check" iconWrapper="bg-emerald-100 text-emerald-800" />
        </div>

        <!-- FILTER & PENCARIAN -->
        <div class="bg-white p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 sm:gap-4">
            <!-- Filter Bintang -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 w-full md:w-auto text-xs no-scrollbar">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1 shrink-0">Bintang:</span>
                <a href="{{ route('admin.reviews', array_filter(['search' => $search])) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ empty($rating) ? 'bg-gradient-to-r from-emerald-700 to-[#102b17] text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                @for($s = 5; $s >= 1; $s--)
                <a href="{{ route('admin.reviews', array_filter(['rating' => $s, 'search' => $search])) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ (string)$rating === (string)$s ? 'bg-emerald-700 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    ★ {{ $s }}
                </a>
                @endfor
            </div>

            <!-- Form Search -->
            <form method="GET" action="{{ route('admin.reviews') }}" class="flex items-center gap-2 w-full md:w-auto">
                @if($rating)
                <input type="hidden" name="rating" value="{{ $rating }}">
                @endif
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kota, ulasan..." class="flex-1 sm:w-64 px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 shadow-2xs">
                <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-700 to-[#14351C] hover:from-emerald-600 hover:to-[#1a4424] text-white text-xs font-semibold shadow-xs hover:shadow transition-all shrink-0 active:scale-95 cursor-pointer">
                    Cari
                </button>
                @if($rating || $search)
                <a href="{{ route('admin.reviews') }}" class="px-3 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold transition-colors shrink-0 shadow-2xs" title="Reset filter">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- TABEL DAFTAR ULASAN -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm font-display">Daftar Seluruh Ulasan Masuk</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Total ditampilkan: {{ $reviews->total() }} ulasan wisatawan</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[680px]">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] sm:text-[11px] border-b border-slate-200 select-none">
                        <tr>
                            <th class="py-3 px-4 text-center whitespace-nowrap">Aksi</th>
                            <th class="py-3 px-4">Wisatawan</th>
                            <th class="py-3 px-4">Paket Wisata</th>
                            <th class="py-3 px-4">Rating</th>
                            <th class="py-3 px-4">Komentar / Pengalaman</th>
                            <th class="py-3 px-4">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($reviews as $rev)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Edit Admin -->
                                    <button type="button" 
                                        onclick="handleAdminEditReview(this)"
                                        data-id="{{ $rev->id }}"
                                        data-rating="{{ $rev->rating }}"
                                        data-city="{{ $rev->city }}"
                                        data-comment="{{ $rev->comment }}"
                                        class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 text-xs shadow-2xs transition-all cursor-pointer"
                                        title="Edit Ulasan">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </button>

                                    <!-- Tombol Hapus Admin -->
                                    <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" class="inline"
                                          data-confirm-title="Hapus Ulasan"
                                          data-confirm-message="Apakah Anda yakin ingin menghapus ulasan dari <strong class='text-slate-800 font-bold'>{{ $rev->reviewer_name }}</strong> secara permanen?"
                                          data-confirm-type="danger"
                                          data-confirm-text="Ya, Hapus Ulasan"
                                          data-confirm-cancel="Batal">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-300 text-slate-400 hover:text-rose-600 text-xs shadow-2xs transition-all cursor-pointer" title="Hapus Ulasan">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($rev->user->name ?? 'W', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">{{ $rev->user->name ?? 'Wisatawan' }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $rev->city ?? 'Indonesia' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ $rev->package->name ?? 'Tour Jeep Merapi' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 text-amber-600 font-bold bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                                    ★ {{ $rev->rating }}.0
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-sm text-slate-600 italic">
                                "{{ $rev->comment }}"
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 text-[11px] whitespace-nowrap">
                                <div>{{ $rev->created_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400">{{ $rev->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state 
                                    title="Tidak Ada Ulasan yang Sesuai" 
                                    description="Coba sesuaikan kata kunci pencarian atau pilih filter bintang lainnya." 
                                    icon="search" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/60">
                {{ $reviews->links() }}
            </div>
            @endif
        </div>

    </main>

    <!-- MODAL EDIT ULASAN (ADMIN) -->
    <div id="adminEditReviewModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden select-none">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all duration-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-slate-800 text-sm font-display">Edit Ulasan Wisatawan</h3>
                <button type="button" onclick="closeAdminEditReviewModal()" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form id="adminEditReviewForm" action="" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rating Bintang *</label>
                    <select name="rating" id="adminEditRatingSelect" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="5">★ 5 - Sangat Puas</option>
                        <option value="4">★ 4 - Puas</option>
                        <option value="3">★ 3 - Cukup</option>
                        <option value="2">★ 2 - Kurang</option>
                        <option value="1">★ 1 - Kecewa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kota Asal Wisatawan</label>
                    <input type="text" name="city" id="adminEditCityInput" placeholder="Contoh: Jakarta / Surabaya" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Ulasan / Komentar *</label>
                    <textarea name="comment" id="adminEditCommentTextarea" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeAdminEditReviewModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            function handleAdminEditReview(btn) {
                if (!btn) return;
                const id = btn.getAttribute('data-id');
                const rating = btn.getAttribute('data-rating') || '5';
                const city = btn.getAttribute('data-city') || '';
                const comment = btn.getAttribute('data-comment') || '';

                const modal = document.getElementById('adminEditReviewModal');
                const form = document.getElementById('adminEditReviewForm');
                const ratingSelect = document.getElementById('adminEditRatingSelect');
                const cityInput = document.getElementById('adminEditCityInput');
                const commentText = document.getElementById('adminEditCommentTextarea');

                if (!modal || !form) return;

                form.action = "{{ url('/reviews') }}/" + id;
                ratingSelect.value = rating;
                cityInput.value = city;
                commentText.value = comment;

                modal.classList.remove('hidden');
            }

            function closeAdminEditReviewModal() {
                const modal = document.getElementById('adminEditReviewModal');
                if (modal) modal.classList.add('hidden');
            }
        </script>
    </x-slot:scripts>

</x-admin-layout>
