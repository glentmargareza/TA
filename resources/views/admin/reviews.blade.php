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
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row font-sans">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">
                Manajemen Ulasan & Testimoni Wisatawan
            </h2>
            <div class="flex items-center gap-2 text-xs text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-full font-semibold border border-emerald-200/60">
                <span>⭐ Moderasi Ulasan Aktif</span>
            </div>
        </header>

        <main class="p-6 space-y-6 flex-1 overflow-y-auto">

            <!-- Flash Alert -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">✕</button>
            </div>
            @endif

            <!-- 3 KARTU STATISTIK ULASAN -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Ulasan Masuk</span>
                        <div class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalReviews }} Ulasan</div>
                        <span class="text-[11px] text-slate-500 mt-0.5 block">Seluruh review terverifikasi</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Rata-Rata Penilaian</span>
                        <div class="text-2xl font-extrabold text-yellow-600 mt-1">★ {{ number_format($avgRating, 1) }} <span class="text-xs text-slate-400">/ 5.0</span></div>
                        <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">Kepuasan Sangat Baik</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Ulasan Bintang 5</span>
                        <div class="text-2xl font-extrabold text-slate-800 mt-1">{{ $fiveStarCount }} Ulasan</div>
                        <span class="text-[11px] text-slate-500 mt-0.5 block">Kategori Sangat Puas</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                </div>
            </div>

            <!-- FILTER & PENCARIAN -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Filter Bintang -->
                <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Bintang:</span>
                    <a href="{{ route('admin.reviews', array_filter(['search' => $search])) }}" class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ empty($rating) ? 'bg-gradient-to-r from-emerald-700 to-[#102b17] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </a>
                    @for($s = 5; $s >= 1; $s--)
                    <a href="{{ route('admin.reviews', array_filter(['rating' => $s, 'search' => $search])) }}" class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ (string)$rating === (string)$s ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        ★ {{ $s }}
                    </a>
                    @endfor
                </div>

                <!-- Form Search -->
                <form method="GET" action="{{ route('admin.reviews') }}" class="flex items-center gap-2 w-full md:w-auto">
                    @if($rating)
                    <input type="hidden" name="rating" value="{{ $rating }}">
                    @endif
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kota, ulasan..." class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-emerald-600 bg-slate-50 w-full sm:w-64">
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-700 via-[#1E4D2B] to-[#14351C] hover:from-emerald-600 hover:to-[#1a4424] text-white text-xs font-semibold shadow-xs hover:shadow transition-all shrink-0 active:scale-95">
                        Cari
                    </button>
                    @if($rating || $search)
                    <a href="{{ route('admin.reviews') }}" class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold transition-colors shrink-0" title="Reset filter">
                        Reset
                    </a>
                    @endif
                </form>
            </div>

            <!-- TABEL DAFTAR ULASAN -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Daftar Seluruh Ulasan Masuk</h3>
                        <p class="text-xs text-slate-400">Total ditampilkan: {{ $reviews->total() }} ulasan wisatawan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Wisatawan</th>
                                <th class="py-3 px-4">Paket Wisata</th>
                                <th class="py-3 px-4">Rating</th>
                                <th class="py-3 px-4">Komentar / Pengalaman</th>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($reviews as $rev)
                            <tr class="hover:bg-slate-50/70 transition-colors">
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
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Edit Admin -->
                                        <button type="button" 
                                            onclick="handleAdminEditReview(this)"
                                            data-id="{{ $rev->id }}"
                                            data-rating="{{ $rev->rating }}"
                                            data-city="{{ $rev->city }}"
                                            data-comment="{{ $rev->comment }}"
                                            class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-200 transition-colors cursor-pointer"
                                            title="Edit Ulasan Ini">
                                            Edit
                                        </button>

                                        <!-- Tombol Hapus Admin -->
                                        <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold border border-rose-200 transition-colors cursor-pointer" title="Hapus Ulasan Ini">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
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
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative">
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
                <button type="button" onclick="closeAdminEditReviewModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-bold">
                    ✕
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
