@extends('layouts.app')

@section('title', 'Profil Akun Wisatawan | Jeep Wukirsari')

@section('content')
<div class="py-8 sm:py-12 bg-wukirsari-cream">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs">
            <span class="font-bold block">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('info'))
        <div class="p-4 bg-sky-50 border border-sky-200 text-sky-800 rounded-2xl text-xs">
            <span class="font-bold block">{{ session('info') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs">
            <span class="font-bold block">{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs">
            <span class="font-bold block">Gagal memperbarui profil:</span>
            <ul class="list-disc list-inside mt-1 space-y-0.5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Header Profile Card -->
        <div class="bg-white rounded-3xl border border-wukirsari-border p-5 sm:p-7 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5 sm:gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-center gap-4 sm:gap-5 text-center sm:text-left">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl bg-wukirsari-sage/80 border border-wukirsari-border/60 text-wukirsari-primary flex items-center justify-center text-2xl sm:text-3xl font-extrabold shadow-2xs font-display shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold text-merapiText-primary font-display leading-tight">
                            {{ $user->name }}
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-wukirsari-sage text-wukirsari-primary border border-wukirsari-border/70">
                            Wisatawan
                        </span>
                    </div>
                    <div class="text-xs text-merapiText-secondary flex flex-wrap items-center justify-center sm:justify-start gap-x-2 gap-y-0.5 pt-0.5">
                        <span>{{ $user->email }}</span>
                        @if($user->phone)
                        <span class="text-merapiText-muted/60">•</span>
                        <span>{{ $user->phone }}</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-merapiText-muted">
                        Bergabung sejak: {{ $user->created_at->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            <!-- Action Logout & Booking & Edit Profil -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 w-full md:w-auto justify-center md:justify-end pt-3 md:pt-0 border-t border-wukirsari-border/50 md:border-t-0">
                <button type="button" onclick="openEditProfileModal()" class="px-4 py-2 sm:py-2.5 rounded-full border border-wukirsari-border bg-white text-merapiText-primary hover:border-wukirsari-primary hover:text-wukirsari-primary text-xs font-semibold shadow-2xs transition-all cursor-pointer active:scale-95">
                    Ubah Profil
                </button>
                <a href="{{ route('booking.create') }}" class="px-5 py-2 sm:py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                    Booking Baru
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 sm:py-2.5 rounded-full border border-rose-200/80 bg-white text-rose-700 hover:bg-rose-50 text-xs font-semibold transition-all cursor-pointer active:scale-95">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Section Riwayat Pemesanan (My Bookings) -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <h2 class="text-lg font-bold text-merapiText-primary font-display">
                        Riwayat Tiket & Pesanan Saya
                    </h2>
                    <span class="text-xs font-bold px-2.5 py-0.5 bg-wukirsari-sage text-wukirsari-primary rounded-full border border-wukirsari-border/60">
                        {{ $myBookings->count() }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <a href="{{ route('ticket.check') }}" class="px-3.5 py-1.5 rounded-full border border-wukirsari-border bg-white text-merapiText-secondary hover:text-wukirsari-primary hover:border-wukirsari-primary text-xs font-medium transition-colors shadow-2xs">
                        Lacak Tiket dengan Kode
                    </a>
                    <button type="button" onclick="openClaimModal()" class="px-3.5 py-1.5 rounded-full border border-wukirsari-primary/20 bg-wukirsari-sage/60 hover:bg-wukirsari-primary hover:text-white text-wukirsari-primary text-xs font-semibold transition-all shadow-2xs cursor-pointer">
                        Klaim Booking dengan Kode
                    </button>
                </div>
            </div>

            @if($myBookings->isEmpty())
            <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 text-center space-y-4 shadow-2xs">
                <div class="space-y-1.5">
                    <h3 class="text-base font-bold text-merapiText-primary font-display">Belum Ada Riwayat Pemesanan</h3>
                    <p class="text-xs text-merapiText-muted max-w-md mx-auto leading-relaxed">
                        Anda belum memiliki tiket tour jeep. Pilih paket petualangan seru lereng Merapi sekarang dan nikmati liburan tak terlupakan!
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2.5 justify-center pt-1">
                    <a href="{{ route('booking.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-wukirsari-primary text-white text-xs font-bold shadow-xs hover:bg-wukirsari-hover transition-all active:scale-95">
                        Pilih Paket & Booking Sekarang
                    </a>
                    <button type="button" onclick="openClaimModal()" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full border border-wukirsari-border bg-white text-wukirsari-primary text-xs font-bold hover:bg-wukirsari-sage/50 transition-all cursor-pointer active:scale-95 shadow-2xs">
                        Klaim Booking dengan Kode
                    </button>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myBookings as $booking)
                <div class="bg-white rounded-3xl border border-wukirsari-border p-5 shadow-xs space-y-4 hover:border-wukirsari-primary/40 transition-all">
                    <div class="flex items-start justify-between border-b border-wukirsari-border/70 pb-3 gap-3">
                        <div class="min-w-0">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-merapiText-muted block">Kode Booking</span>
                            <h3 class="text-base font-bold font-mono text-wukirsari-primary truncate">
                                {{ $booking->booking_code }}
                            </h3>
                        </div>
                        <div class="shrink-0">
                            @if($booking->booking_status === 'FULL_PAID')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/90 shadow-2xs tracking-wide">
                                Lunas (100%)
                            </span>
                            @elseif($booking->booking_status === 'DP_PAID')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200/90 shadow-2xs tracking-wide">
                                DP (Sisa Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }})
                            </span>
                            @elseif($booking->booking_status === 'PENDING')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs tracking-wide">
                                Menunggu Pembayaran
                            </span>
                            @elseif($booking->booking_status === 'CANCELLED')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/90 shadow-2xs tracking-wide">
                                Dibatalkan
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/90 shadow-2xs tracking-wide">
                                {{ $booking->booking_status }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 text-xs py-0.5">
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-merapiText-muted shrink-0">Paket Wisata</span>
                            <span class="font-bold text-merapiText-primary text-right">{{ $booking->package->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-merapiText-muted shrink-0">Jadwal Tour</span>
                            <span class="font-semibold text-merapiText-primary text-right">
                                {{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d M Y') }}
                                @if($booking->slot)
                                <span class="text-merapiText-muted font-normal">• {{ $booking->slot->slot_label }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-merapiText-muted shrink-0">Jumlah Armada</span>
                            <span class="font-semibold text-merapiText-primary text-right">{{ $booking->jeep_quantity }} Jeep <span class="text-merapiText-muted font-normal">({{ $booking->passenger_count }} Orang)</span></span>
                        </div>
                        <div class="flex items-center justify-between pt-2.5 border-t border-dashed border-wukirsari-border">
                            <span class="text-merapiText-muted font-medium">Total Tagihan</span>
                            <span class="font-bold text-sm text-merapiText-primary">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-2.5 flex items-center justify-between border-t border-wukirsari-border/60">
                        <span class="text-[11px] text-merapiText-muted">
                            {{ $booking->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('booking.success', $booking->booking_code) }}" class="inline-flex items-center justify-center px-4 py-1.5 rounded-full border border-wukirsari-primary/20 bg-wukirsari-sage/70 hover:bg-wukirsari-primary hover:text-white text-wukirsari-primary text-xs font-bold transition-all shadow-2xs active:scale-95">
                            Buka E-Tiket
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Section Ulasan & Testimoni Saya -->
        <div class="space-y-4 pt-6 border-t border-wukirsari-border">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <h2 class="text-lg font-bold text-merapiText-primary font-display">
                        Ulasan & Testimoni Saya
                    </h2>
                    <span class="text-xs font-bold px-2.5 py-0.5 bg-wukirsari-sage text-wukirsari-primary rounded-full border border-wukirsari-border/60">
                        {{ $myReviews->count() }}
                    </span>
                </div>
                <a href="{{ route('home') }}#tulis-ulasan" class="px-3.5 py-1.5 rounded-full border border-wukirsari-border bg-white text-wukirsari-primary hover:bg-wukirsari-sage/50 text-xs font-semibold transition-colors shadow-2xs">
                    Tulis Ulasan Baru
                </a>
            </div>

            @if($myReviews->isEmpty())
            <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 text-center space-y-3 shadow-2xs">
                <p class="text-xs sm:text-sm text-merapiText-secondary max-w-md mx-auto leading-relaxed">
                    Anda belum menuliskan ulasan. Bagikan cerita keseruan Anda setelah trip offroad lereng Merapi!
                </p>
                <div>
                    <a href="{{ route('home') }}#tulis-ulasan" class="inline-flex items-center px-5 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold transition-all shadow-xs active:scale-95">
                        Tulis Ulasan Sekarang
                    </a>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($myReviews as $rev)
                <div class="bg-white rounded-3xl border border-wukirsari-border p-5 shadow-2xs space-y-3 hover:border-wukirsari-primary/40 transition-all flex flex-col justify-between">
                    <div class="space-y-2.5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="text-xs font-bold text-merapiText-primary font-display block">
                                    {{ $rev->package->name ?? 'Tour Jeep Wukirsari' }}
                                </span>
                                <div class="text-xs mt-0.5 text-merapiText-secondary">
                                    <span>Nilai: <strong class="text-merapiText-primary font-bold">{{ $rev->rating }}.0 / 5.0</strong></span>
                                </div>
                            </div>

                            <!-- Tombol Edit & Delete -->
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button"
                                    onclick="handleEditReviewClick(this)"
                                    data-id="{{ $rev->id }}"
                                    data-rating="{{ $rev->rating }}"
                                    data-city="{{ $rev->city }}"
                                    data-comment="{{ $rev->comment }}"
                                    class="px-2.5 py-1 rounded-full bg-wukirsari-sage/70 hover:bg-wukirsari-sage text-wukirsari-primary text-[11px] font-semibold border border-wukirsari-border/70 transition-all cursor-pointer shadow-2xs"
                                    title="Edit Ulasan">
                                    Edit
                                </button>
                                <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2.5 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-semibold border border-rose-200/80 transition-all cursor-pointer shadow-2xs"
                                        title="Hapus Ulasan">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                            "{{ $rev->comment }}"
                        </p>
                    </div>

                    <div class="pt-2 border-t border-wukirsari-border/60 text-[10px] text-merapiText-muted flex items-center justify-between">
                        <span>Kota: {{ $rev->city ?? 'Wisatawan' }}</span>
                        <span>{{ $rev->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL: Klaim Booking dengan Kode -->
<div id="claimBookingModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-wukirsari-border animate-in fade-in zoom-in-95 duration-200">
        <div class="p-5 sm:p-6 border-b border-wukirsari-border flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10 rounded-t-3xl">
            <div>
                <h3 class="text-base font-bold text-merapiText-primary font-display">Klaim Booking Saya</h3>
                <p class="text-xs text-merapiText-secondary">Hubungkan tiket booking ke akun profil Anda</p>
            </div>
            <button type="button" onclick="closeClaimModal()" class="px-3 py-1.5 rounded-full hover:bg-slate-100 text-slate-500 hover:text-slate-700 text-xs font-semibold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>

        <form action="{{ route('booking.claim') }}" method="POST" class="p-5 sm:p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Kode Booking *</label>
                <input type="text" name="booking_code" required placeholder="Contoh: WKR-XXXXXXXX" maxlength="20"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs font-mono uppercase focus:outline-none focus:border-wukirsari-primary tracking-widest"
                    oninput="this.value = this.value.toUpperCase()">
                <p class="text-[11px] text-merapiText-muted mt-1.5 leading-relaxed">
                    Kode booking tercantum di halaman konfirmasi atau pesan WhatsApp yang Anda terima saat memesan.
                </p>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeClaimModal()" class="px-5 py-2.5 rounded-full border border-wukirsari-border text-merapiText-secondary hover:bg-slate-50 text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95">
                    Klaim Booking
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Ubah Profil -->
<div id="editProfileModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 {{ $errors->any() ? '' : 'hidden' }}">
    <div class="bg-white rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-wukirsari-border animate-in fade-in zoom-in-95 duration-200">
        <div class="p-5 sm:p-6 border-b border-wukirsari-border flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-10 rounded-t-3xl">
            <div>
                <h3 class="text-base font-bold text-merapiText-primary font-display">Ubah Profil Saya</h3>
                <p class="text-xs text-merapiText-secondary">Perbarui informasi data diri atau kata sandi akun</p>
            </div>
            <button type="button" onclick="closeEditProfileModal()" class="px-3 py-1.5 rounded-full hover:bg-slate-100 text-slate-500 hover:text-slate-700 text-xs font-semibold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="p-5 sm:p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Nomor WhatsApp / HP *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>
            </div>

            <div class="pt-2 border-t border-wukirsari-border/70">
                <span class="block text-xs font-bold text-merapiText-primary mb-1">Ganti Kata Sandi (Opsional)</span>
                <p class="text-[11px] text-merapiText-muted mb-3">Kosongkan jika tidak ingin mengganti kata sandi.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-merapiText-secondary mb-1">Kata Sandi Baru</label>
                        <input type="password" name="password" id="profileNewPassword" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-merapiText-secondary mb-1">Konfirmasi Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="profileConfirmPassword" placeholder="Ketik ulang sandi" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-wukirsari-border">
                <button type="button" onclick="closeEditProfileModal()" class="px-5 py-2.5 rounded-full border border-wukirsari-border text-merapiText-secondary hover:bg-slate-50 text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditProfileModal() {
        const modal = document.getElementById('editProfileModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeEditProfileModal() {
        const modal = document.getElementById('editProfileModal');
        if (modal) modal.classList.add('hidden');
    }

    function openClaimModal() {
        const modal = document.getElementById('claimBookingModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeClaimModal() {
        const modal = document.getElementById('claimBookingModal');
        if (modal) modal.classList.add('hidden');
    }
</script>
@endsection
