<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Ulasan Wisatawan | Admin Jeep Wukirsari</title>
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-display, h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row font-sans">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between">
            <h2 class="text-sm sm:text-base font-bold text-slate-800 truncate">
                Manajemen Ulasan & Testimoni Wisatawan
            </h2>
            
        </header>

        <main class="p-3.5 sm:p-6 space-y-4 sm:space-y-6 flex-1 overflow-y-auto">

            <!-- Flash Alert -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-lg leading-none cursor-pointer">&times;</button>
            </div>
            @endif

            <!-- 3 KARTU STATISTIK ULASAN -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
                <div class="bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Ulasan Masuk</span>
                        <div class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">{{ $totalReviews }} Ulasan</div>
                    </div>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 block uppercase tracking-wider">Rata-Rata Penilaian</span>
                        <div class="text-xl sm:text-2xl font-extrabold text-amber-500 mt-1">★ {{ number_format($avgRating, 1) }} <span class="text-xs text-slate-400 font-normal">/ 5.0</span></div>
                    </div>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 block uppercase tracking-wider">Ulasan Bintang 5</span>
                        <div class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">{{ $fiveStarCount }} Ulasan</div>
                    </div>
                </div>
            </div>

            <!-- FILTER & PENCARIAN -->
            <div class="bg-white p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 sm:gap-4">
                <!-- Filter Bintang -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 w-full md:w-auto text-xs no-scrollbar">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1 shrink-0">Bintang:</span>
                    <a href="{{ route('admin.reviews', array_filter(['search' => $search])) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ empty($rating) ? 'bg-gradient-to-r from-emerald-700 to-[#102b17] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
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
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Daftar Seluruh Ulasan Masuk</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Total ditampilkan: {{ $reviews->total() }} ulasan wisatawan</p>
                    </div>
                </div>

                <!-- 1. TAMPILAN MOBILE: KARTU ULASAN (NO HORIZONTAL SCROLL) -->
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse($reviews as $rev)
                    <div class="p-4 space-y-3 hover:bg-slate-50/60 transition-colors">
                        <!-- Header Kartu: Wisatawan & Tombol Aksi -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-wukirsari-sage text-wukirsari-primary font-bold flex items-center justify-center shrink-0 text-sm">
                                    {{ strtoupper(substr($rev->user->name ?? 'W', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 text-xs block truncate">{{ $rev->user->name ?? 'Wisatawan' }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate">{{ $rev->city ?? 'Indonesia' }} &bull; {{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi (Icon Edit & Hapus) -->
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" 
                                    onclick="handleAdminEditReview(this)"
                                    data-id="{{ $rev->id }}"
                                    data-rating="{{ $rev->rating }}"
                                    data-city="{{ $rev->city }}"
                                    data-comment="{{ $rev->comment }}"
                                    class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 flex items-center justify-center border border-emerald-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer"
                                    title="Edit Ulasan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" class="inline"
                                      data-confirm-title="Hapus Ulasan"
                                      data-confirm-message="Apakah Anda yakin ingin menghapus ulasan dari <strong class='text-slate-800 font-bold'>{{ $rev->reviewer_name }}</strong> secara permanen?"
                                      data-confirm-type="danger"
                                      data-confirm-text="Ya, Hapus Ulasan"
                                      data-confirm-cancel="Batal">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 flex items-center justify-center border border-rose-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer" title="Hapus Ulasan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Paket & Rating -->
                        <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100/80">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-semibold text-[11px] truncate">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="truncate">{{ $rev->package->name ?? 'Tour Jeep Merapi' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 text-amber-600 font-bold bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200 text-xs shrink-0">
                                ★ {{ $rev->rating }}.0
                            </span>
                        </div>

                        <!-- Komentar Pengalaman -->
                        <div class="p-3 bg-slate-50 rounded-xl text-slate-700 text-xs italic leading-relaxed border border-slate-100">
                            "{{ $rev->comment }}"
                        </div>
                    </div>
                    @empty
                    <div class="py-10 px-4 text-center text-slate-400">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div class="font-semibold text-xs">Tidak ditemukan ulasan yang sesuai.</div>
                        <div class="text-[10px] text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter bintang.</div>
                    </div>
                    @endforelse
                </div>

                <!-- 2. TAMPILAN DESKTOP: TABEL STANDAR -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[640px]">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4 text-center whitespace-nowrap w-24">Aksi</th>
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
                                        <!-- Tombol Edit Admin (Icon) -->
                                        <button type="button" 
                                            onclick="handleAdminEditReview(this)"
                                            data-id="{{ $rev->id }}"
                                            data-rating="{{ $rev->rating }}"
                                            data-city="{{ $rev->city }}"
                                            data-comment="{{ $rev->comment }}"
                                            class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 flex items-center justify-center border border-emerald-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer"
                                            title="Edit Ulasan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Tombol Hapus Admin (Icon) -->
                                        <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" class="inline"
                                              data-confirm-title="Hapus Ulasan"
                                              data-confirm-message="Apakah Anda yakin ingin menghapus ulasan dari <strong class='text-slate-800 font-bold'>{{ $rev->reviewer_name }}</strong> secara permanen?"
                                              data-confirm-type="danger"
                                              data-confirm-text="Ya, Hapus Ulasan"
                                              data-confirm-cancel="Batal">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 flex items-center justify-center border border-rose-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer" title="Hapus Ulasan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-wukirsari-sage text-wukirsari-primary font-bold flex items-center justify-center shrink-0">
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
                                    <span class="inline-flex items-center gap-1 text-yellow-600 font-bold bg-yellow-50 px-2.5 py-0.5 rounded-full border border-yellow-200">
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
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <div class="font-semibold">Tidak ditemukan ulasan yang sesuai.</div>
                                    <div class="text-[11px] text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter bintang.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reviews->hasPages())
                <div class="p-4 border-t border-slate-100 flex justify-center">
                    {{ $reviews->links() }}
                </div>
                @endif
            </div>

        </main>
    </div>

    <!-- MODAL EDIT ULASAN KHUSUS ADMIN -->
    <div id="adminEditReviewModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-lg w-full p-5 sm:p-7 shadow-2xl border border-slate-200 relative max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-lg font-bold">
                        <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Edit Ulasan Wisatawan</h3>
                        <p class="text-xs text-slate-400">Moderasi konten ulasan dan rating bintang</p>
                    </div>
                </div>
                <button type="button" onclick="closeAdminEditReviewModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-lg font-bold cursor-pointer">
                    &times;
                </button>
            </div>

            <form id="adminEditReviewForm" method="POST" action="" class="space-y-4 pt-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Penilaian Bintang *</label>
                    <select name="rating" id="adminEditRatingSelect" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary bg-white">
                        <option value="5">⭐⭐⭐⭐⭐ (5/5) Sangat Puas</option>
                        <option value="4">⭐⭐⭐⭐ (4/5) Puas</option>
                        <option value="3">⭐⭐⭐ (3/5) Cukup</option>
                        <option value="2">⭐⭐ (2/5) Kurang</option>
                        <option value="1">⭐ (1/5) Kecewa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kota Asal</label>
                    <input type="text" name="city" id="adminEditCityInput" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Ulasan / Komentar *</label>
                    <textarea name="comment" id="adminEditCommentTextarea" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-wukirsari-primary"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeAdminEditReviewModal()" class="px-4 py-2.5 rounded-full border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

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

</body>
</html>
