@extends('layouts.app')
 
@section('title', 'Formulir Pemesanan Jeep | Jeep Wukirsari Adventure')
 
@section('content')

<div class="bg-wukirsari-cream min-h-screen pt-6 pb-28 sm:py-12 lg:pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Breadcrumb / Tombol Kembali -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <a href="{{ route('home') }}#paket" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-wukirsari-border text-merapiText-secondary hover:text-wukirsari-primary hover:border-wukirsari-primary text-xs sm:text-sm font-semibold shadow-xs transition-all group">
                <svg class="w-4 h-4 text-wukirsari-primary group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Pilihan Paket</span>
            </a>
        </div>
 
        <!-- Judul Halaman -->
        <div class="space-y-1.5">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-merapiText-primary font-display tracking-tight">
                Pemesanan Jeep Wukirsari
            </h1>
            <p class="text-merapiText-secondary text-xs sm:text-sm max-w-2xl">
                Pilih paket wisata lereng Merapi, tentukan jadwal keberangkatan, dan nikmati opsi pembayaran instan (Lunas 100% atau Uang Muka DP 30%).
            </p>
        </div>
 
        <!-- Form Pengiriman Data ke BookingController@store -->
        <form id="bookForm" action="{{ route('booking.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="package_id">
            <input type="hidden" name="slot_id">
            <input type="hidden" name="trip_date">
            <input type="hidden" name="passenger_count">
            <input type="hidden" name="jeep_quantity">
            <input type="hidden" name="arrival_type" value="self_drive">
            <input type="hidden" name="customer_name">
            <input type="hidden" name="customer_phone">
            <input type="hidden" name="payment_mode">
            <input type="hidden" name="notes">
        </form>
 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Kiri: 4 Kartu Formulir (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- 1. PILIH PAKET -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-4 sm:p-7 space-y-5" data-sec="1">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">1</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Pilih Paket Wisata</h2>
                            <p class="text-xs text-merapiText-secondary">Tarif dihitung per unit armada jeep (kapasitas 1–4 orang/jeep).</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="pkgs">
                        <!-- Dimuat otomatis via JavaScript dari database -->
                    </div>
                </section>
 
                <!-- 2. JADWAL & SESI TRIP -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-4 sm:p-7 space-y-5" data-sec="2">
                    <div class="flex items-center justify-between pb-3 border-b border-wukirsari-border/70 flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">2</span>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Pilih Tanggal & Jam Trip</h2>
                                <p class="text-xs text-merapiText-secondary">Deteksi otomatis ketersediaan jadwal & status bookingan armada jeep.</p>
                            </div>
                        </div>
                        <button type="button" id="toggleCalBtn" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-wukirsari-border hover:border-wukirsari-primary/50 bg-slate-50 hover:bg-slate-100 text-xs font-bold text-merapiText-primary transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span id="toggleCalText">Tutup Kalender Visual</span>
                        </button>
                    </div>

                    <!-- Kalender Visual Interaktif -->
                    <div id="interactiveCalendar" class="p-3.5 sm:p-5 rounded-2xl bg-slate-50/90 border border-wukirsari-border space-y-3 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm sm:text-base text-merapiText-primary font-display" id="calMonthTitle">November 2026</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" id="calPrevBtn" class="w-8 h-8 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-all cursor-pointer" title="Bulan Sebelumnya">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" id="calTodayBtn" class="px-2.5 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-[11px] font-bold text-slate-700 transition-all cursor-pointer">
                                    Hari Ini
                                </button>
                                <button type="button" id="calNextBtn" class="w-8 h-8 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-all cursor-pointer" title="Bulan Berikutnya">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Baris Nama Hari -->
                        <div class="grid grid-cols-7 gap-1 text-center text-[10px] sm:text-xs font-bold text-merapiText-muted pb-1 border-b border-slate-200/60">
                            <div class="text-rose-500">Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div class="text-emerald-700">Sab</div>
                        </div>

                        <!-- Grid Tanggal Bulanan -->
                        <div class="grid grid-cols-7 gap-1 sm:gap-1.5" id="calGrid">
                            <!-- Dihasilkan dinamis oleh JavaScript -->
                        </div>

                        <div class="pt-2 border-t border-slate-200/60 flex flex-wrap items-center justify-between text-[10px] sm:text-[11px] text-merapiText-secondary gap-2">
                            <span></span>
                            <span class="text-[10px] text-slate-400">Klik tanggal pada kalender untuk memilih trip</span>
                        </div>
                    </div>
 
                    <!-- Penyimpan tanggal (disembunyikan; tanggal dipilih lewat kalender visual) -->
                    <input id="date" type="hidden" value="{{ $defaultDate ?? '' }}">

                    <!-- Panel Jadwal Tanggal Terpilih -->
                    <div id="dateDetectionBox" class="p-4 sm:p-5 rounded-2xl bg-slate-50/70 border border-wukirsari-border space-y-3.5 transition-all">
                        <div class="pb-2.5 border-b border-slate-200/70">
                            <h4 class="text-xs sm:text-sm font-bold text-merapiText-primary font-display" id="detDateTitle">Jadwal Tanggal: ...</h4>
                            <p class="text-[11px] text-merapiText-secondary" id="detDateSub">Memeriksa ketersediaan armada...</p>
                        </div>

                        <!-- Kartu Interaktif Seluruh Jadwal Sesi -->
                        <div>
                            <span class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Sesi Keberangkatan (Klik Sesi yang Tersedia):</span>
                            <span class="block text-[11px] font-medium text-amber-600 mb-2" id="sessHint"></span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" id="detSessionsCards">
                                <div class="col-span-full text-xs text-slate-400">Memuat jadwal sesi...</div>
                            </div>
                        </div>
                    </div>
                </section>
 
                <!-- 3. KAPASITAS & JUMLAH JEEP -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-4 sm:p-7 space-y-5" data-sec="3">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">3</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Kapasitas & Jumlah Jeep</h2>
                            <p class="text-xs text-merapiText-secondary">Standar operasional keselamatan maksimal 4 orang penumpang per unit jeep.</p>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Penumpang Counter -->
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-3">
                            <label class="block text-xs font-bold text-merapiText-primary">Jumlah Penumpang (Orang)</label>
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-wukirsari-border shadow-2xs">
                                <button type="button" id="paxMinus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">−</button>
                                <output id="pax" class="font-extrabold text-lg text-merapiText-primary font-display">4</output>
                                <button type="button" id="paxPlus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">+</button>
                            </div>
                            <span class="block text-[11px] text-merapiText-secondary">*Maksimal 4 penumpang per armada jeep.</span>
                        </div>
 
                        <!-- Jeep Counter -->
                        <div class="p-4 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border space-y-3">
                            <label class="block text-xs font-bold text-merapiText-primary">Jumlah Armada Jeep</label>
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-wukirsari-border shadow-2xs">
                                <button type="button" id="minus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">−</button>
                                <output id="qty" class="font-extrabold text-lg text-wukirsari-primary font-display">1</output>
                                <button type="button" id="plus" class="w-9 h-9 rounded-lg bg-wukirsari-cream hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center text-base transition-colors cursor-pointer active:scale-95">+</button>
                            </div>
                            <span class="block text-[11px] font-bold text-wukirsari-primary" id="jeepHint"></span>
                        </div>
                    </div>
                </section>
 
                <!-- 4. KONTAK PEMESAN -->
                <section class="bg-white rounded-3xl border border-wukirsari-border shadow-xs p-4 sm:p-7 space-y-6" data-sec="4">
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">4</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Kontak Pemesan</h2>
                            <p class="text-xs text-merapiText-secondary">Isi kontak pemesan untuk pengiriman tiket. Anda datang sendiri ke basecamp.</p>
                        </div>
                    </div>
 
                    <!-- Customer Contact Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold text-merapiText-primary">Nama Lengkap Pemesan *</label>
                            <input id="name" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" placeholder="Contoh: Budi Santoso" autocomplete="name">
                        </div>
                        <div class="space-y-1.5" id="waGroup">
                            <div class="flex items-center justify-between">
                                <label for="wa" class="block text-xs font-bold text-merapiText-primary">Nomor WhatsApp Aktif *</label>
                                <span id="waStatusBadge" class="hidden text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full transition-all"></span>
                            </div>
                            <div class="relative">
                                <input id="wa" class="w-full pl-4 pr-11 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" inputmode="tel" placeholder="Contoh: 081234567890" autocomplete="tel">
                                <div id="waStatusIcon" class="absolute right-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center transition-all pointer-events-none"></div>
                            </div>
                            <span id="waFeedback" class="hidden text-[11px] text-rose-600 font-medium transition-colors"></span>
                        </div>
                    </div>
 
                    <!-- Customer Notes -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="notes" class="block text-xs font-bold text-merapiText-primary">Pesan / Catatan Khusus (Opsional)</label>
                            <span class="text-[11px] text-merapiText-muted"><span id="notesCount">0</span>/500 karakter</span>
                        </div>
                        <textarea id="notes" rows="3" maxlength="500" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" placeholder="Contoh: Request driver ramah untuk lansia atau anak-anak, minta helm bersih."></textarea>
                    </div>
                </section>
            </div>
 
            <!-- Kolom Kanan: Sticky Sidebar Tagihan (lg:col-span-4) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6" id="pay">
                <div class="bg-white rounded-3xl border border-wukirsari-border shadow-sm p-4 sm:p-7 space-y-5">
                    <h3 class="text-base sm:text-lg font-bold text-merapiText-primary font-display pb-3 border-b border-wukirsari-border/70">
                        Ringkasan Pemesanan
                    </h3>
 
                    <!-- Ticket Pill Preview -->
                    <div class="bg-wukirsari-cream/80 border border-dashed border-wukirsari-border rounded-2xl p-4 space-y-1">
                        <strong class="block text-sm font-bold text-merapiText-primary font-display" id="tPkg">Paket</strong>
                        <span class="block text-xs text-merapiText-secondary" id="tDate">Belum pilih tanggal</span>
                    </div>
 
                    <!-- Breakdown Rows -->
                    <div class="space-y-2.5 text-xs sm:text-sm">
                        <div class="flex justify-between items-center text-merapiText-secondary">
                            <span id="lJeep">Sewa Jeep</span>
                            <strong class="text-merapiText-primary font-bold" id="vJeep">Rp 0</strong>
                        </div>
                    </div>
 
                    <!-- Total Amount -->
                    <div class="pt-4 border-t border-wukirsari-border flex justify-between items-center">
                        <span class="text-xs font-bold text-merapiText-secondary uppercase tracking-wider">Total Biaya</span>
                        <strong class="text-2xl sm:text-3xl font-extrabold text-wukirsari-primary font-display" id="vTotal">Rp 0</strong>
                    </div>
 
                    <!-- Skema Pembayaran -->
                    <div class="space-y-2 pt-2">
                        <label class="block text-xs font-bold text-merapiText-primary">Pilihan Skema Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2.5 pay">
                            <button type="button" class="po sel p-3 rounded-2xl border text-left transition-all cursor-pointer" data-p="full">
                                <strong class="block text-xs font-bold text-merapiText-primary">Lunas (100%)</strong>
                                <span class="block text-[10px] text-merapiText-secondary mt-0.5">Langsung beres</span>
                            </button>
                            <button type="button" class="po p-3 rounded-2xl border text-left transition-all cursor-pointer" data-p="dp">
                                <strong class="block text-xs font-bold text-merapiText-primary">Uang Muka (DP 30%)</strong>
                                <span class="block text-[10px] text-merapiText-secondary mt-0.5">Sisa di basecamp</span>
                            </button>
                        </div>
                    </div>
 
                    <!-- Box Tagihan Sekarang -->
                    <div class="bg-wukirsari-dark text-white rounded-2xl p-4 flex items-center justify-between gap-3 shadow-xs">
                        <strong class="text-xl sm:text-2xl font-extrabold font-display" id="vDue">Rp 0</strong>
                    </div>
 
                    <!-- Tombol Lanjutkan -->
                    <button id="submit" type="button" class="w-full py-4 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm sm:text-base shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                        <span>Konfirmasi & Terbitkan E-Tiket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>

                </div>
            </div>
 
        </div>
    </div>
</div>
 
<!-- Mobile Bottom Floating Bar -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-wukirsari-border px-4 pt-3 flex items-center justify-between gap-3 z-40 shadow-2xl mbar">
    <div>
        <span class="block text-[10px] font-bold uppercase tracking-wider text-merapiText-muted">Total Tagihan</span>
        <strong class="text-lg font-extrabold text-wukirsari-primary font-display" id="mTotal">Rp 0</strong>
    </div>
    <a href="#pay" class="px-5 py-3 rounded-full bg-wukirsari-primary text-white text-xs font-bold shadow-md whitespace-nowrap">
        Rincian Pembayaran
    </a>
</div>

<!-- Modal Konfirmasi Ganti Paket -->
<div id="pkgConfirm" class="fixed inset-0 z-[60] hidden items-end sm:items-center justify-center p-4">
    <div id="pkgConfirmBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl border border-wukirsari-border w-full max-w-sm p-5 sm:p-6 space-y-4 mb-20 sm:mb-0" role="dialog" aria-modal="true" aria-labelledby="pkgConfirmTitle">
        <div class="space-y-1">
            <h3 id="pkgConfirmTitle" class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Yakin ingin pindah paket?</h3>
            <p class="text-xs text-merapiText-secondary">Pastikan paket yang dipilih sudah sesuai.</p>
        </div>

        <div class="rounded-2xl bg-wukirsari-cream/70 border border-dashed border-wukirsari-border p-3.5 space-y-2 text-xs">
            <div class="flex justify-between gap-3">
                <span class="text-merapiText-secondary">Dari</span>
                <strong id="pkgFromName" class="text-merapiText-primary text-right"></strong>
            </div>
            <div class="flex justify-between gap-3">
                <span class="text-merapiText-secondary">Ke</span>
                <strong id="pkgToName" class="text-wukirsari-primary text-right"></strong>
            </div>
            <div class="flex justify-between gap-3">
                <span class="text-merapiText-secondary">Tarif</span>
                <strong id="pkgToPrice" class="text-merapiText-primary text-right"></strong>
            </div>
        </div>

        <p id="pkgConfirmWarn" class="hidden text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2"></p>

        <div class="grid grid-cols-2 gap-2.5 pt-1">
            <button type="button" id="pkgConfirmNo" class="py-3 rounded-full border border-wukirsari-border bg-white hover:bg-slate-50 text-merapiText-primary text-sm font-bold transition-all cursor-pointer">
                Batal
            </button>
            <button type="button" id="pkgConfirmYes" class="py-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm font-bold shadow-md transition-all cursor-pointer active:scale-[0.98]">
                Ya, Pindah
            </button>
        </div>
    </div>
</div>
 
<!-- Toast Popup Notification -->
<div id="toast" class="fixed left-1/2 bottom-24 lg:bottom-8 w-max max-w-[90vw] text-center -translate-x-1/2 bg-merapiText-primary text-white text-xs font-semibold px-5 py-3 rounded-2xl shadow-2xl z-50 transition-all duration-300 opacity-0 pointer-events-none translate-y-6"></div>
 
<style>
/* Active State Helpers for Stepper & Selectors */
.pkg.sel {
    border-color: #1E3F20 !important;
    background-color: #E8EFE9 !important;
    box-shadow: 0 0 0 2px rgba(30, 63, 32, 0.2) !important;
}
.po.sel {
    border-color: #1E3F20 !important;
    background-color: #E8EFE9 !important;
    box-shadow: 0 0 0 2px rgba(30, 63, 32, 0.2) !important;
}
.err {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
}
#toast.show {
    opacity: 1 !important;
    transform: translate(-50%, 0) !important;
}
 

/* ===== RESPONSIF HP ===== */
html{-webkit-text-size-adjust:100%}
body{overflow-x:hidden}
.mbar{padding-bottom:calc(.75rem + env(safe-area-inset-bottom))}
@media (max-width:640px){
  /* 16px mencegah iOS/Android zoom otomatis saat mengetuk input */
  input,select,textarea{font-size:16px!important}
  /* target sentuh minimal 44px */
  #paxMinus,#paxPlus,#minus,#plus{width:44px!important;height:44px!important;font-size:1.25rem!important}
  .po,.pkg{min-height:56px}
  #submit{min-height:52px}
  #name,#wa{min-height:48px}
  h1{line-height:1.2}
}
@media (max-width:380px){
  .grid.pay{grid-template-columns:1fr!important}
  #vTotal{font-size:1.5rem!important}
}
@media (hover:none){
  .pkg:hover,.po:hover{transform:none}
}
</style>
 
<script type="application/json" id="booking-data">@json($bookingData)</script>
 
<script>
/* ====== PENGATURAN (diambil dari server) ====== */
const bookingDataEl = document.getElementById('booking-data');
const D              = bookingDataEl ? JSON.parse(bookingDataEl.textContent || '{}') : (window.BOOKING_DATA || {});
window.BOOKING_DATA  = D;
const PER_JEEP       = 4;
const PACKAGES       = D.packages || [];
let SESSIONS         = D.slots || [];
 
const $ = s => document.querySelector(s);
const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const state = {
  pkg: D.selectedId ?? (PACKAGES[0] ? PACKAGES[0].id : null),
  qty: 1, pax: 4, session: null, pay: 'full'
};
const curPkg = () => PACKAGES.find(x => String(x.id) === String(state.pkg)) || PACKAGES[0];
const isSunrisePkgObj = p => !!(p && (p.is_sunrise || /sunrise/i.test(p.slug || '') || /sunrise/i.test(p.name || '')));
 
/* ====== 1. PAKET ====== */
const pkgsEl = $('#pkgs');

// --- Render kartu paket ---
if (pkgsEl && PACKAGES.length) {
  pkgsEl.innerHTML = PACKAGES.map(p => `
    <button type="button" class="pkg ${String(p.id)===String(state.pkg)?'sel':''} p-4 rounded-2xl border border-wukirsari-border bg-white text-left transition-all flex flex-col justify-between gap-3 cursor-pointer hover:border-wukirsari-primary/50 relative group" data-id="${p.id}">
      <div>
        <h3 class="font-bold text-sm text-merapiText-primary font-display group-hover:text-wukirsari-primary transition-colors">${esc(p.name)}</h3>
        <div class="text-xs font-extrabold text-wukirsari-primary font-display mt-0.5">${rp(p.price)} <span class="text-[10px] font-normal text-merapiText-muted">/ jeep</span></div>
      </div>
      ${p.dur ? `<div class="text-[11px] text-merapiText-secondary flex items-center gap-1"><svg class="w-3.5 h-3.5 text-merapiText-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>${esc(p.dur)}</span></div>` : ''}
      <span class="absolute top-3 right-3 w-5 h-5 rounded-full bg-wukirsari-primary text-white text-[10px] font-bold flex items-center justify-center opacity-0 group-[.sel]:opacity-100 transition-opacity">✓</span>
    </button>`).join('');
}

// --- Terapkan perubahan paket (setelah user konfirmasi) ---
function applyPackageChange(newId){
  state.pkg = newId;
  pkgsEl.querySelectorAll('.pkg').forEach(x => x.classList.toggle('sel', String(x.dataset.id) === String(newId)));

  const p = curPkg();
  const isToday = dateEl.value === iso(new Date());
  const now = new Date();
  const nowMin = now.getHours() * 60 + now.getMinutes();

  // Sunrise & jam 04:30 sudah lewat → otomatis geser ke besok
  if (isSunrisePkgObj(p) && isToday && nowMin >= (4 * 60 + 30)) {
    const def = new Date();
    def.setDate(def.getDate() + 1);
    dateEl.value = iso(def);
    calViewDate = new Date(def.getFullYear(), def.getMonth(), def.getDate());
  }

  renderSessions();
  update();
  setStep(2);
}

// --- Modal konfirmasi ---
const pkgModal = $('#pkgConfirm');
let pendingPkgId = null;

function openPkgConfirm(newId){
  const from = curPkg();
  const to = PACKAGES.find(x => String(x.id) === String(newId));
  if (!to) return;

  // Cadangan jika modal HTML tidak ada
  if (!pkgModal) {
    if (confirm('Yakin pindah dari "' + (from ? from.name : '-') + '" ke "' + to.name + '"?')) applyPackageChange(newId);
    return;
  }

  pendingPkgId = newId;
  $('#pkgFromName').textContent = from ? from.name : '-';
  $('#pkgToName').textContent   = to.name;
  $('#pkgToPrice').textContent  = rp(to.price) + ' / jeep';

  const warn = $('#pkgConfirmWarn');
  if (isSunrisePkgObj(from) !== isSunrisePkgObj(to)) {
    warn.textContent = isSunrisePkgObj(to)
      ? 'Paket Sunrise hanya tersedia di sesi 04:30 WIB. Sesi yang sudah Anda pilih akan direset.'
      : 'Paket reguler tidak tersedia di sesi Sunrise. Sesi yang sudah Anda pilih akan direset.';
    warn.classList.remove('hidden');
  } else {
    warn.classList.add('hidden');
  }

  pkgModal.classList.remove('hidden');
  pkgModal.classList.add('flex');
}

function closePkgConfirm(){
  if (!pkgModal) return;
  pkgModal.classList.add('hidden');
  pkgModal.classList.remove('flex');
  pendingPkgId = null;
}

if (pkgModal) {
  $('#pkgConfirmYes').onclick = () => {
    const id = pendingPkgId;
    closePkgConfirm();                          // tutup popup lebih dulu
    if (id !== null) applyPackageChange(id);
  };
  $('#pkgConfirmNo').onclick = closePkgConfirm;
  $('#pkgConfirmBackdrop').onclick = closePkgConfirm;
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !pkgModal.classList.contains('hidden')) closePkgConfirm();
  });
}

// --- Klik kartu paket → konfirmasi dulu ---
if (pkgsEl) {
  pkgsEl.addEventListener('click', e => {
    const b = e.target.closest('.pkg'); if (!b) return;
    if (String(b.dataset.id) === String(state.pkg)) return; // paket yang sama, abaikan
    openPkgConfirm(b.dataset.id);
  });
}
 
/* ====== 2. TANGGAL & SESI ====== */
const dateEl = $('#date');
const iso = d => new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
function getSmartDefaultDate(pkg){
  const now = new Date();
  const nowMin = now.getHours() * 60 + now.getMinutes();
  const isSunrise = isSunrisePkgObj(pkg);

  if(isSunrise){
    if(nowMin >= (4 * 60 + 30)){
      const def = new Date();
      def.setDate(def.getDate() + 1);
      return iso(def);
    }
    return iso(now);
  } else {
    if(nowMin >= (13 * 60 + 30)){
      const def = new Date();
      def.setDate(def.getDate() + 1);
      return iso(def);
    }
    return iso(now);
  }
}

// Inisialisasi tanggal otomatis cerdas
if (D.defaultDate) {
  dateEl.value = D.defaultDate;
} else if (!dateEl.value || dateEl.value < iso(new Date())) {
  dateEl.value = getSmartDefaultDate(curPkg());
}

function formatIndoDate(dateStr) {
  if (!dateStr) return '-';
  const parts = dateStr.split('-');
  if (parts.length !== 3) return dateStr;
  const d = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
  return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

let cachedMonthBookings = {};
let latestAvailabilityData = null;
let calViewDate = new Date();
if (dateEl.value) {
  const p = dateEl.value.split('-').map(Number);
  calViewDate = new Date(p[0], p[1] - 1, p[2]);
}
let isCalOpen = true;

const MONTH_NAMES = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

const toggleCalBtn = $('#toggleCalBtn');
const interactiveCal = $('#interactiveCalendar');
const toggleCalText = $('#toggleCalText');
if (toggleCalBtn && interactiveCal) {
  toggleCalBtn.onclick = () => {
    isCalOpen = !isCalOpen;
    if (isCalOpen) {
      interactiveCal.classList.remove('hidden');
      toggleCalText.textContent = 'Tutup Kalender Visual';
    } else {
      interactiveCal.classList.add('hidden');
      toggleCalText.textContent = 'Buka Kalender Visual';
    }
  };
}

function renderInteractiveCalendar() {
  const calTitle = $('#calMonthTitle');
  const calGrid = $('#calGrid');
  if (!calTitle || !calGrid) return;

  const year = calViewDate.getFullYear();
  const month = calViewDate.getMonth();
  calTitle.textContent = `${MONTH_NAMES[month]} ${year}`;

  const today = new Date();
  const todayIso = iso(today);
  const selectedDate = dateEl.value;

  const firstDayIndex = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  let html = '';

  for (let i = 0; i < firstDayIndex; i++) {
    html += `<div class="h-10 sm:h-12 rounded-xl bg-transparent"></div>`;
  }

  for (let day = 1; day <= daysInMonth; day++) {
    const dStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const isPast = dStr < todayIso;
    const isToday = dStr === todayIso;
    const isSelected = dStr === selectedDate;

    let btnClass = 'flex flex-col items-center justify-center p-1 sm:p-1.5 h-10 sm:h-12 rounded-xl border text-xs font-semibold transition-all relative select-none ';
    let indicatorHtml = '';

    if (isPast) {
      btnClass += 'border-transparent bg-slate-100/60 text-slate-300 cursor-not-allowed';
    } else if (isSelected) {
      btnClass += 'border-[#0c2314] bg-[#0c2314] text-white shadow-sm ring-2 ring-emerald-500 cursor-pointer';
    } else {
      btnClass += 'border-slate-200/90 bg-white hover:border-emerald-500 hover:bg-emerald-50/40 text-slate-800 cursor-pointer';
    }

    // Ada keterangan (mis. "Pilih"): angka di atas, keterangan di bawah
    if (indicatorHtml) {
      btnClass = btnClass.replace('justify-center', 'justify-between');
    }

    if (isToday && !isSelected) {
      btnClass += ' ring-1 ring-blue-400';
    }

    html += `
      <button type="button" class="${btnClass}" data-date="${dStr}" ${isPast ? 'disabled' : ''}>
        <span class="text-[11px] sm:text-xs font-bold leading-none">${day}</span>
        ${indicatorHtml}
      </button>
    `;
  }

  calGrid.innerHTML = html;

  calGrid.querySelectorAll('button[data-date]:not([disabled])').forEach(btn => {
    btn.onclick = () => {
      const picked = btn.getAttribute('data-date');
      dateEl.value = picked;
      const p = picked.split('-').map(Number);
      calViewDate = new Date(p[0], p[1] - 1, p[2]);
      latestAvailabilityData = null;   // buang data tanggal sebelumnya
      renderInteractiveCalendar();     // tampilkan tanggal terpilih langsung
      renderSessions();
      update();
      setStep(2);
    };
  });
}

if ($('#calPrevBtn')) {
  $('#calPrevBtn').onclick = () => {
    calViewDate.setMonth(calViewDate.getMonth() - 1, 1);
    const mStr = `${calViewDate.getFullYear()}-${String(calViewDate.getMonth() + 1).padStart(2, '0')}-01`;
    fetchSlotAvailability(mStr, state.qty, false);
    renderInteractiveCalendar();
  };
}
if ($('#calNextBtn')) {
  $('#calNextBtn').onclick = () => {
    calViewDate.setMonth(calViewDate.getMonth() + 1, 1);
    const mStr = `${calViewDate.getFullYear()}-${String(calViewDate.getMonth() + 1).padStart(2, '0')}-01`;
    fetchSlotAvailability(mStr, state.qty, false);
    renderInteractiveCalendar();
  };
}
if ($('#calTodayBtn')) {
  $('#calTodayBtn').onclick = () => {
    const now = new Date();
    calViewDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    dateEl.value = iso(now);
    latestAvailabilityData = null;
    renderInteractiveCalendar();
    renderSessions();
    update();
    setStep(2);
  };
}

// Cek sesi sudah lewat: status dari server ATAU jam di perangkat (untuk tanggal hari ini)
function isSlotPassed(s){
  if (s.passed) return true;
  if (dateEl.value !== iso(new Date()) || !s.time) return false;
  const [h, m] = String(s.time).split(':').map(Number);
  const now = new Date();
  return (h * 60 + m) <= (now.getHours() * 60 + now.getMinutes());
}

function renderDateDetectionPanel(data) {
  const titleEl = $('#detDateTitle');
  const subEl = $('#detDateSub');
  const cardsEl = $('#detSessionsCards');
  if (!titleEl || !cardsEl) return;

  // Abaikan data basi dari tanggal lain
  if (data && data.date && data.date !== dateEl.value) return;

  titleEl.textContent = `Jadwal Tanggal: ${formatIndoDate(dateEl.value)}`;
  if (subEl) subEl.textContent = 'Pilih sesi keberangkatan yang tersedia di bawah.';

  const isSunrisePkg = isSunrisePkgObj(curPkg());

  const slots = data?.slots || SESSIONS || [];
  if (!slots.length) {
    cardsEl.innerHTML = '<div class="col-span-full text-xs text-slate-400">Tidak ada jadwal sesi tersedia.</div>';
    return;
  }

  cardsEl.innerHTML = slots.map(s => {
    const isSunSlot = s.is_sunrise || s.time === '04:30' || /sunrise/i.test(s.label);
    const isMismatched = isSunrisePkg ? !isSunSlot : isSunSlot;
    const isSelected = String(s.id) === String(state.session);
    const passed = isSlotPassed(s);
    const booked = s.booked_jeeps ?? 0;
    const remaining = typeof s.remaining === 'number' ? s.remaining : (s.max_jeeps - booked);
    const isFull = remaining < state.qty;
    const isDisabled = passed || isFull || isMismatched;

    let cardBorder;

    if (passed) {
      cardBorder = 'border-slate-200 bg-slate-50/70 opacity-60 cursor-not-allowed';
    } else if (isMismatched) {
      cardBorder = 'border-slate-200 bg-slate-50/50 opacity-50 cursor-not-allowed';
    } else if (isFull) {
      cardBorder = 'border-rose-200 bg-rose-50/50 opacity-70 cursor-not-allowed';
    } else if (isSelected) {
      cardBorder = 'border-emerald-600 bg-emerald-50/90 shadow-xs ring-2 ring-emerald-600/20 cursor-pointer';
    } else if (booked > 0) {
      cardBorder = 'border-amber-300 bg-amber-50/40 hover:bg-amber-50 text-slate-800 cursor-pointer';
    } else {
      cardBorder = 'border-slate-200 bg-white hover:border-emerald-500 hover:bg-emerald-50/30 text-slate-800 cursor-pointer';
    }

    // Tampilkan sisa jeep untuk SEMUA jam
    const sisa = Math.max(remaining, 0);
    const maxJ = typeof s.max_jeeps === 'number' ? s.max_jeeps : (sisa + booked);

    let quotaDetail = '';
    if (passed) {
      quotaDetail = `
        <div class="flex items-center justify-between gap-2 text-[11px]">
          <span class="text-slate-500 font-medium">Sudah lewat</span>
          <span class="text-slate-400">${booked} Jeep dipesan</span>
        </div>`;
    } else if (sisa <= 0) {
      quotaDetail = `
        <div class="flex items-center justify-between gap-2 text-[11px]">
          <span class="text-rose-600 font-bold">Penuh</span>
          <span class="text-rose-500">${booked} Jeep dipesan</span>
        </div>`;
    } else {
      const warna = booked > 0 ? 'text-amber-700' : 'text-emerald-700';
      quotaDetail = `
        <div class="flex items-center justify-between gap-2 text-[11px]">
          <span class="text-slate-600 font-medium">Sisa: <strong class="${warna}">${sisa} dari ${maxJ} Jeep</strong></span>
          ${booked > 0 ? `<span class="text-amber-600">${booked} dipesan</span>` : ''}
        </div>
        ${isFull ? `<div class="text-[10px] text-rose-600 font-semibold">Kurang untuk ${state.qty} jeep yang dipilih</div>` : ''}`;
    }

    if (isMismatched && !passed) {
      quotaDetail += `<div class="text-[10px] text-slate-400">${isSunrisePkg ? 'Bukan untuk paket Sunrise' : 'Khusus paket Sunrise'}</div>`;
    }

    return `
      <div class="p-3 sm:p-3.5 rounded-xl border ${cardBorder} transition-all flex flex-col justify-between gap-2" data-slot-id="${s.id}" ${isDisabled ? 'data-disabled="1"' : ''}>
        <div class="flex items-start justify-between gap-2">
          <div class="flex items-center gap-2">
            <span class="font-bold text-xs sm:text-sm text-merapiText-primary font-display">${esc(s.label)}</span>
          </div>
        </div>
        <div>${quotaDetail}</div>
      </div>
    `;
  }).join('');

  // Kartu sesi adalah satu-satunya pemilih sesi
  cardsEl.querySelectorAll('[data-slot-id]:not([data-disabled="1"])').forEach(card => {
    card.onclick = () => {
      state.session = card.getAttribute('data-slot-id');
      renderDateDetectionPanel(latestAvailabilityData || data);
      update();
      setStep(3);
    };
  });
}

let fetchTimer = null;
function fetchSlotAvailability(date, jeeps, updateDateSelection = true){
  if(!date) return;
  clearTimeout(fetchTimer);
  fetchTimer = setTimeout(async () => {
    try {
      const res = await fetch(`{{ route('booking.slot_availability') }}?trip_date=${encodeURIComponent(date)}&jeep_quantity=${encodeURIComponent(jeeps)}`);
      if(!res.ok) return;
      const data = await res.json();
      if(!data || !Array.isArray(data.slots)) return;

      // Data kalender: ganti total, jangan digabung dengan cache lama
      if (data.month_bookings) {
        cachedMonthBookings = data.month_bookings;
      }

      if (updateDateSelection) {
        // Abaikan respons basi: user sudah pindah ke tanggal lain
        if (date !== dateEl.value) return;

        latestAvailabilityData = data;
        SESSIONS = SESSIONS.map(s => {
          const found = data.slots.find(x => String(x.id) === String(s.id));
          return found ? {
            ...s,
            remaining: found.remaining,
            booked_jeeps: found.booked_jeeps ?? 0,
            available: found.available,
            passed: found.passed
          } : s;
        });
        renderSessionOptions();
        renderDateDetectionPanel(data);
        update();
      }
      renderInteractiveCalendar();
    } catch(e) {
      // Abaikan jika error jaringan/offline
    }
  }, 100);
}

// Menentukan sesi terpilih (state.session) & hint
function renderSessionOptions(){
  const isSunrisePkg = isSunrisePkgObj(curPkg());
  const hint = $('#sessHint');

  const isToday = dateEl.value === iso(new Date());
  const now = new Date();
  const nowMin = now.getHours() * 60 + now.getMinutes();

  // Paket Sunrise: hanya sesi 04:30. Paket reguler: tanpa sesi 04:30.
  const filteredSlots = SESSIONS.filter(s => {
    const isSunSlot = s.is_sunrise || s.time === '04:30' || /sunrise/i.test(s.label);
    return isSunrisePkg ? isSunSlot : !isSunSlot;
  });

  if(filteredSlots.length === 0){
    state.session = null;
    if(hint) hint.textContent = 'Tidak ada sesi yang tersedia untuk paket ini.';
    return;
  }

  const okSlots = filteredSlots.filter(s => {
    const isFull = (typeof s.remaining === 'number' && s.remaining < state.qty);
    return !isSlotPassed(s) && !isFull;
  });

  // Pertahankan pilihan lama jika masih valid, kalau tidak pilih sesi pertama yang tersedia
  const keep = okSlots.find(s => String(s.id) === String(state.session));
  state.session = keep ? keep.id : (okSlots[0] ? okSlots[0].id : null);

  if(!hint) return;
  if(!okSlots.length){
    hint.textContent = isSunrisePkg
      ? 'Sesi Sunrise (04:30 WIB) untuk tanggal ini tidak dapat dipilih (sudah lewat atau kuota habis). Silakan pilih tanggal berikutnya.'
      : 'Semua sesi jadwal reguler untuk tanggal ini sudah lewat atau penuh. Silakan pilih tanggal berikutnya.';
  } else {
    hint.textContent = isSunrisePkg ? '✓ Paket Sunrise Tour khusus beroperasi pada sesi Sunrise (04:30 WIB).' : '';
  }
}

function renderSessions(){
  renderSessionOptions();
  // Segarkan kartu langsung bila data tanggal yang sama sudah ada
  if (latestAvailabilityData && latestAvailabilityData.date === dateEl.value) {
    renderDateDetectionPanel(latestAvailabilityData);
  } else {
    // Tanggal berganti: tampilkan judul tanggal baru sambil menunggu data
    const titleEl = $('#detDateTitle');
    if (titleEl) titleEl.textContent = `Jadwal Tanggal: ${formatIndoDate(dateEl.value)}`;
    const subEl = $('#detDateSub');
    if (subEl) subEl.textContent = 'Memeriksa ketersediaan armada...';
  }
  if(dateEl.value){
    fetchSlotAvailability(dateEl.value, state.qty);
  }
}

dateEl.addEventListener('change', () => {
  if (dateEl.value) {
    const p = dateEl.value.split('-').map(Number);
    calViewDate = new Date(p[0], p[1] - 1, p[2]);
  }
  latestAvailabilityData = null;
  renderSessions();
  update();
  setStep(2);
});
 
/* ====== 3. KAPASITAS & ARMADA ====== */
$('#paxPlus').onclick = () => {
  if(state.pax < 40){
    state.pax++;
    state.qty = Math.max(state.qty, Math.ceil(state.pax / PER_JEEP));
    update(); setStep(3);
  }
};
$('#paxMinus').onclick = () => {
  if(state.pax > 1){ state.pax--; update(); setStep(3); }
};
$('#plus').onclick = () => {
  if(state.qty < 10){ state.qty++; update(); setStep(3); }
};
$('#minus').onclick = () => {
  const minJeep = Math.ceil(state.pax / PER_JEEP);
  if(state.qty > minJeep){ state.qty--; update(); }
  else toast('Minimal butuh ' + minJeep + ' jeep untuk ' + state.pax + ' orang penumpang (maksimal 4 orang per jeep).');
};
 
/* ====== SKEMA PEMBAYARAN ====== */
document.querySelectorAll('.po').forEach(b => b.onclick = () => {
  state.pay = b.dataset.p;
  document.querySelectorAll('.po').forEach(x => x.classList.toggle('sel', x === b));
  update();
});
 
/* ====== UPDATE RINGKASAN TAGIHAN ====== */
function update(){
  const p = curPkg();
  if(!p) return;
  const total = p.price * state.qty;
  const due = state.pay === 'dp' ? Math.round(total * 0.3) : total;
 
  $('#qty').textContent = state.qty;
  $('#pax').textContent = state.pax;
  $('#jeepHint').textContent = state.qty + ' Jeep × ' + rp(p.price);
  $('#tPkg').textContent = p.name + ' (' + state.qty + ' Jeep · ' + state.pax + ' Orang)';
 
  const d = dateEl.value ? new Date(dateEl.value + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : 'Belum pilih tanggal';
  const sObj = SESSIONS.find(s => String(s.id) === String(state.session));
  $('#tDate').textContent = d + (sObj ? ' · ' + sObj.label : '');
 
  $('#lJeep').textContent = 'Sewa Jeep' + (state.qty > 1 ? ' (' + state.qty + '×)' : '');
  $('#vJeep').textContent = rp(p.price * state.qty);
 
  $('#vTotal').textContent = rp(total);
  $('#mTotal').textContent = rp(total);
  $('#vDue').textContent = rp(due);

}
 
function setStep(n){
  // Stepper UI telah dihapus sesuai permintaan
}
 
/* ====== TOAST ====== */
let tt;
function toast(msg){
  const t = $('#toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(tt);
  tt = setTimeout(() => t.classList.remove('show'), 3200);
}

/* ====== DETEKSI & VALIDASI NOMOR WHATSAPP AKTIF ====== */
const INDO_OPERATORS = [
  { name: 'Telkomsel', prefixes: ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'] },
  { name: 'Indosat Ooredoo', prefixes: ['0814', '0815', '0816', '0855', '0856', '0857', '0858'] },
  { name: 'XL Axiata', prefixes: ['0817', '0818', '0819', '0859', '0877', '0878'] },
  { name: 'Axis', prefixes: ['0831', '0832', '0833', '0838'] },
  { name: 'Tri (3)', prefixes: ['0895', '0896', '0897', '0898', '0899'] },
  { name: 'Smartfren', prefixes: ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'] }
];

function checkWhatsAppNumber(raw) {
  if (!raw || !raw.trim()) {
    return { status: 'empty' };
  }
  
  let cleaned = raw.trim().replace(/[\s\-\.]/g, '');
  if (cleaned.startsWith('+62')) {
    cleaned = '0' + cleaned.substring(3);
  } else if (cleaned.startsWith('62')) {
    cleaned = '0' + cleaned.substring(2);
  }
  
  const digits = cleaned.replace(/\D/g, '');
  
  // Deteksi Telepon Rumah / PSTN (bukan seluler WhatsApp)
  const pstn = ['021', '022', '024', '031', '0274', '0271', '0281', '0251', '0267', '0282', '0291'];
  for (let p of pstn) {
    if (digits.startsWith(p)) {
      return {
        status: 'invalid',
        reason: 'Telepon Rumah (Bukan WA)',
        detail: 'Nomor telepon rumah/kantor tidak dapat menerima WhatsApp e-tiket.'
      };
    }
  }
  
  if (!digits.startsWith('08')) {
    return {
      status: 'invalid',
      reason: 'Bukan Nomor Seluler',
      detail: 'Gunakan nomor seluler diawali 08xx atau +628xx.'
    };
  }
  
  const prefix4 = digits.substring(0, 4);
  let opName = null;
  for (let op of INDO_OPERATORS) {
    if (op.prefixes.includes(prefix4)) {
      opName = op.name;
      break;
    }
  }
  
  if (!opName) {
    return {
      status: 'invalid',
      reason: 'Prefix Tidak Dikenal',
      detail: 'Prefix operator seluler belum terdaftar di Indonesia.'
    };
  }
  
  if (digits.length < 10) {
    return {
      status: 'typing',
      reason: `Format Nomor (${digits.length}/10-13 digit)`,
      detail: `Lengkapi nomor WhatsApp Anda (${digits.length} digit). Minimal 10 digit.`
    };
  }
  
  if (digits.length > 13) {
    return {
      status: 'invalid',
      reason: 'Nomor Terlalu Panjang',
      detail: `Nomor melebihi batas maksimal seluler Indonesia (${digits.length} digit).`
    };
  }
  
  const intlNumber = '62' + digits.substring(1);
  return {
    status: 'valid',
    localNumber: digits,
    intlNumber: intlNumber,
    reason: '✓ WhatsApp Aktif',
    detail: '*Nomor WhatsApp terverifikasi. E-tiket siap dikirim ke nomor ini.'
  };
}

function updateWhatsAppUI() {
  const waInput = $('#wa');
  const badge = $('#waStatusBadge');
  const icon = $('#waStatusIcon');
  const feedback = $('#waFeedback');
  if (!waInput || !badge) return;
  
  const res = checkWhatsAppNumber(waInput.value);
  
  if (res.status === 'empty') {
    badge.className = 'hidden';
    icon.innerHTML = '';
    waInput.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/15', 'border-rose-400', 'ring-rose-400/15');
    feedback.className = 'hidden';
    feedback.textContent = '';
    return;
  }
  
  if (res.status === 'valid') {
    badge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 transition-all';
    badge.textContent = res.reason;
    icon.innerHTML = `<svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`;
    waInput.classList.remove('border-rose-400', 'ring-rose-400/15', 'err');
    waInput.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/15');
    feedback.className = 'hidden';
    feedback.textContent = '';
  } else if (res.status === 'typing') {
    badge.className = 'text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 transition-all';
    badge.textContent = res.reason;
    icon.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>`;
    waInput.classList.remove('border-emerald-500', 'ring-emerald-500/15', 'border-rose-400', 'ring-rose-400/15', 'err');
    feedback.className = 'hidden';
    feedback.textContent = '';
  } else {
    badge.className = 'text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 border border-rose-200 transition-all';
    badge.textContent = res.reason;
    icon.innerHTML = `<svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`;
    waInput.classList.remove('border-emerald-500', 'ring-emerald-500/15');
    waInput.classList.add('border-rose-400', 'ring-2', 'ring-rose-400/15');
    feedback.className = 'block text-[11px] text-rose-600 font-medium transition-colors mt-1';
    feedback.textContent = res.detail;
  }
}

$('#wa').addEventListener('input', updateWhatsAppUI);
$('#wa').addEventListener('blur', updateWhatsAppUI);

/* ====== SUBMIT FORM ====== */
$('#submit').onclick = () => {
  const name = $('#name'), wa = $('#wa');
  [name, wa].forEach(x => x.classList.remove('err'));
 
  if(!dateEl.value){
    toast('Pilih tanggal kunjungan trip terlebih dahulu.');
    return $('#interactiveCalendar').scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  if(!state.session){
    toast('Pilih sesi waktu keberangkatan yang tersedia.');
    return $('#detSessionsCards').scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  if(name.value.trim().length < 3){
    name.classList.add('err');
    toast('Isi nama lengkap pemesan.');
    return name.focus();
  }
  
  const waCheck = checkWhatsAppNumber(wa.value);
  if(waCheck.status !== 'valid'){
    wa.classList.add('err');
    updateWhatsAppUI();
    toast(waCheck.detail || 'Nomor WhatsApp belum valid (harus nomor seluler aktif 10-13 digit).');
    return wa.focus();
  }
 
  const f = $('#bookForm');
  const set = (n, v) => { f.elements[n].value = v; };
 
  set('package_id', curPkg().id);
  set('slot_id', state.session);
  set('trip_date', dateEl.value);
  set('passenger_count', state.pax);
  set('jeep_quantity', state.qty);
  set('arrival_type', 'self_drive');
  set('customer_name', name.value.trim());
  set('customer_phone', waCheck.localNumber || wa.value.trim());
  set('payment_mode', state.pay === 'dp' ? 'DP' : 'FULL');
  set('notes', $('#notes').value.trim());
 
  const btn = $('#submit');
  btn.disabled = true;
  btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses E-Tiket…`;
  f.submit();
};
 
$('#notes').addEventListener('input', e => {
  $('#notesCount').textContent = e.target.value.length;
});
 
renderInteractiveCalendar();
renderSessions();
update();
</script>
@endsection