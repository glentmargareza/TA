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
            <span class="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block">
                FORMULIR RESERVASI ONLINE
            </span>
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
            
            <!-- Kolom Kiri: Stepper & 4 Kartu Formulir (lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Stepper Progress Bar -->
                <div class="bg-white border border-wukirsari-border rounded-2xl p-2 shadow-xs flex items-center justify-between gap-1 sm:gap-2 overflow-x-auto scrollbar-none sticky top-16 sm:top-20 z-30 backdrop-blur-md bg-white/95" id="stepper">
                    <button type="button" class="st on flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="1">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">1</i>
                        <span>1. Paket</span>
                    </button>
                    <div class="w-4 h-0.5 bg-wukirsari-border shrink-0"></div>
                    <button type="button" class="st flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="2">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">2</i>
                        <span>2. Jadwal</span>
                    </button>
                    <div class="w-4 h-0.5 bg-wukirsari-border shrink-0"></div>
                    <button type="button" class="st flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="3">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">3</i>
                        <span>3. Kapasitas</span>
                    </button>
                    <div class="w-4 h-0.5 bg-wukirsari-border shrink-0"></div>
                    <button type="button" class="st flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-merapiText-secondary hover:text-merapiText-primary transition-all shrink-0 cursor-pointer" data-s="4">
                        <i class="w-6 h-6 rounded-lg bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold not-italic">4</i>
                        <span>4. Kontak</span>
                    </button>
                </div>
 
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
                    <div class="flex items-center gap-3 pb-3 border-b border-wukirsari-border/70">
                        <span class="w-8 h-8 rounded-xl bg-wukirsari-primary text-white text-sm font-bold flex items-center justify-center font-display shadow-xs shrink-0">2</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-merapiText-primary font-display">Pilih Tanggal & Jam Trip</h2>
                            <p class="text-xs text-merapiText-secondary">Tentukan tanggal wisata dan pilih sesi keberangkatan yang tersedia.</p>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="date" class="block text-xs font-bold text-merapiText-primary">Tanggal Kunjungan *</label>
                            <input id="date" type="date" value="{{ $defaultDate ?? '' }}" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm font-semibold focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label for="session" class="block text-xs font-bold text-merapiText-primary">Sesi Waktu Keberangkatan *</label>
                            <select id="session" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm font-semibold focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all"></select>
                            <span class="block text-[11px] font-medium text-amber-600" id="sessHint"></span>
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
                        <div class="space-y-1.5">
                            <label for="wa" class="block text-xs font-bold text-merapiText-primary">Nomor WhatsApp Aktif *</label>
                            <input id="wa" class="w-full px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all" inputmode="tel" placeholder="Contoh: 081234567890" autocomplete="tel">
                            <span class="block text-[11px] text-merapiText-muted">*E-Tiket resmi & rincian dikirim ke nomor ini.</span>
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
                        <div class="flex justify-between items-center text-merapiText-secondary">
                            <span>BBM, Driver & Asuransi</span>
                            <strong class="text-emerald-700 font-bold">Gratis (Termasuk)</strong>
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
                    <div class="bg-wukirsari-dark text-white rounded-2xl p-4 flex items-center justify-between shadow-xs">
                        <div>
                            <span class="block text-[11px] text-wukirsari-sage font-medium" id="dueLbl">Wajib Dibayar Sekarang</span>
                            <strong class="text-xl sm:text-2xl font-extrabold font-display" id="vDue">Rp 0</strong>
                        </div>
                        <span class="text-xs text-wukirsari-sage font-semibold" id="dueSub">Lunas</span>
                    </div>
 
                    <!-- Tombol Lanjutkan -->
                    <button id="submit" type="button" class="w-full py-4 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm sm:text-base shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                        <span>Konfirmasi & Terbitkan E-Tiket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
 
                    <!-- Trust Badges -->
                    <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-[11px] text-merapiText-muted font-medium pt-1">
                        <span>🛡️ Pembayaran Resmi</span>
                        <span>•</span>
                        <span>🔄 Reschedule H-2</span>
                        <span>•</span>
                        <span>⚡ Konfirmasi Instan</span>
                    </div>
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
.st.on {
    background-color: #1E3F20 !important;
    color: #ffffff !important;
}
.st.on i {
    background-color: #ffffff !important;
    color: #1E3F20 !important;
}
.st.done {
    color: #1E3F20 !important;
}
.st.done i {
    background-color: #1E3F20 !important;
    color: #ffffff !important;
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
#stepper{-webkit-overflow-scrolling:touch;scroll-snap-type:x proximity}
#stepper .st{scroll-snap-align:center}
@media (max-width:640px){
  /* 16px mencegah iOS/Android zoom otomatis saat mengetuk input */
  input,select,textarea{font-size:16px!important}
  /* target sentuh minimal 44px */
  #paxMinus,#paxPlus,#minus,#plus{width:44px!important;height:44px!important;font-size:1.25rem!important}
  .po,.pkg{min-height:56px}
  #submit{min-height:52px}
  input[type=date]{min-height:48px;-webkit-appearance:none;appearance:none}
  #name,#wa,#session,#date{min-height:48px}
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
 
/* ====== 1. PAKET ====== */
const pkgsEl = $('#pkgs');
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
 
  pkgsEl.addEventListener('click', e => {
    const b = e.target.closest('.pkg'); if(!b) return;
    state.pkg = b.dataset.id;
    pkgsEl.querySelectorAll('.pkg').forEach(x => x.classList.toggle('sel', x === b));

    const p = curPkg();
    const isSunrise = p && (p.is_sunrise || /sunrise/i.test(p.slug || '') || /sunrise/i.test(p.name || ''));
    const isToday = dateEl.value === iso(new Date());
    const now = new Date();
    const nowMin = now.getHours() * 60 + now.getMinutes();

    // Jika beralih ke Sunrise dan tanggal masih hari ini tetapi jam 04:30 WIB sudah lewat:
    // Otomatis sesuaikan tanggal ke besok (H+1)
    if(isSunrise && isToday && nowMin >= (4 * 60 + 30)){
      const def = new Date();
      def.setDate(def.getDate() + 1);
      dateEl.value = iso(def);
    }

    renderSessions();
    update();
    setStep(2);
  });
}
 
/* ====== 2. TANGGAL & SESI ====== */
const dateEl = $('#date');
const iso = d => new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
dateEl.min = iso(new Date());
function getSmartDefaultDate(pkg){
  const now = new Date();
  const nowMin = now.getHours() * 60 + now.getMinutes();
  const isSunrise = pkg && (pkg.is_sunrise || /sunrise/i.test(pkg.slug || '') || /sunrise/i.test(pkg.name || ''));

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
let fetchTimer = null;
function fetchSlotAvailability(date, jeeps){
  if(!date) return;
  clearTimeout(fetchTimer);
  fetchTimer = setTimeout(async () => {
    try {
      const res = await fetch(`{{ route('booking.slot_availability') }}?trip_date=${encodeURIComponent(date)}&jeep_quantity=${encodeURIComponent(jeeps)}`);
      if(res.ok){
        const data = await res.json();
        if(data && Array.isArray(data.slots)){
          SESSIONS = SESSIONS.map(s => {
            const found = data.slots.find(x => x.id === s.id);
            return found ? { ...s, remaining: found.remaining, available: found.available, passed: found.passed } : s;
          });
          renderSessionOptions();
          update();
        }
      }
    } catch(e) {
      // Abaikan jika error jaringan/offline
    }
  }, 120);
}

function renderSessionOptions(){
  const sel = $('#session');
  if(!sel) return;
  const pkg = curPkg();
  const isSunrisePkg = pkg && (pkg.is_sunrise || /sunrise/i.test(pkg.slug || '') || /sunrise/i.test(pkg.name || ''));

  const isToday = dateEl.value === iso(new Date());
  const now = new Date();
  const nowMin = now.getHours() * 60 + now.getMinutes();
  const prev = state.session;

  // Filter aturan sesi keberangkatan:
  // - Paket Sunrise: HANYA sesi 04:30 WIB yang tampil / dapat dipilih. Sesi lain otomatis tidak bisa.
  // - Paket Reguler: Sesi 04:30 WIB dilarang/disembunyikan. Wisatawan bebas memilih sesi reguler (08:00, 10:30, 13:30 WIB).
  const filteredSlots = SESSIONS.filter(s => {
    const isSunSlot = s.is_sunrise || s.time === '04:30' || /sunrise/i.test(s.label);
    return isSunrisePkg ? isSunSlot : !isSunSlot;
  });

  if(filteredSlots.length === 0){
    sel.innerHTML = '<option value="" disabled selected>Tidak ada sesi yang tersedia</option>';
    state.session = null;
    $('#sessHint').textContent = 'Tidak ada sesi yang tersedia untuk paket ini.';
    return;
  }

  sel.innerHTML = filteredSlots.map(s => {
    let passed = s.passed ?? false;
    if(isToday && s.time){
      const [h, m] = s.time.split(':').map(Number);
      if((h * 60 + m) <= nowMin) passed = true;
    }
    const isFull = (typeof s.remaining === 'number' && s.remaining < state.qty);
    const disabled = passed || isFull;

    let badge = '';
    if(passed){
      badge = ' — (Sudah Lewat)';
    } else if(isFull){
      badge = ' — (Kuota Penuh)';
    } else if(typeof s.remaining === 'number'){
      badge = ` (Sisa ${s.remaining} Jeep)`;
    }

    return `<option value="${s.id}" ${disabled ? 'disabled' : ''}>${esc(s.label)}${badge}</option>`;
  }).join('');

  const firstOk = [...sel.options].find(o => !o.disabled && o.value);
  const keep = [...sel.options].find(o => o.value === String(prev) && !o.disabled);
  sel.value = keep ? keep.value : (firstOk ? firstOk.value : '');
  state.session = sel.value || null;

  if(!firstOk){
    if(isSunrisePkg){
      $('#sessHint').textContent = 'Sesi Sunrise (04:30 WIB) untuk tanggal ini tidak dapat dipilih (sudah lewat atau kuota habis). Silakan pilih tanggal berikutnya.';
    } else {
      $('#sessHint').textContent = 'Semua sesi jadwal reguler untuk tanggal ini sudah lewat atau penuh. Silakan pilih tanggal berikutnya.';
    }
  } else {
    $('#sessHint').textContent = isSunrisePkg ? '✓ Paket Sunrise Tour khusus beroperasi pada sesi Sunrise (04:30 WIB).' : '';
  }
}

function renderSessions(){
  renderSessionOptions();
  if(dateEl.value){
    fetchSlotAvailability(dateEl.value, state.qty);
  }
}

dateEl.addEventListener('change', () => { renderSessions(); update(); setStep(2); });
$('#session').addEventListener('change', e => { state.session = e.target.value; update(); setStep(3); });
 
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
  $('#dueLbl').textContent = state.pay === 'dp' ? 'Wajib Dibayar Sekarang (DP 30%)' : 'Wajib Dibayar Sekarang (Lunas)';
  $('#dueSub').textContent = state.pay === 'dp' ? 'Sisa ' + rp(total - due) : 'Lunas (100%)';
}
 
/* ====== STEPPER INTERACTION ====== */
function setStep(n){
  document.querySelectorAll('.st').forEach(s => {
    const k = +s.dataset.s;
    s.classList.toggle('on', k === n);
    s.classList.toggle('done', k < n);
    s.querySelector('i').textContent = k < n ? '✓' : k;
  });
}
 
document.querySelectorAll('.st').forEach(s => s.onclick = () => {
  const n = +s.dataset.s;
  const sec = document.querySelector(`[data-sec="${n}"]`);
  if (sec) {
    sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
    setStep(n);
  }
});
 
/* ====== TOAST ====== */
let tt;
function toast(msg){
  const t = $('#toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(tt);
  tt = setTimeout(() => t.classList.remove('show'), 3200);
}
 
/* ====== SUBMIT FORM ====== */
$('#submit').onclick = () => {
  const name = $('#name'), wa = $('#wa');
  [name, wa].forEach(x => x.classList.remove('err'));
 
  if(!dateEl.value){
    toast('Pilih tanggal kunjungan trip terlebih dahulu.');
    return dateEl.focus();
  }
  if(!state.session){
    toast('Pilih sesi waktu keberangkatan yang tersedia.');
    return $('#session').focus();
  }
  if(name.value.trim().length < 3){
    name.classList.add('err');
    toast('Isi nama lengkap pemesan.');
    return name.focus();
  }
  if(!/^(\+?62|0)8\d{7,12}$/.test(wa.value.replace(/[\s-]/g, ''))){
    wa.classList.add('err');
    toast('Nomor WhatsApp belum valid (gunakan format 08xx / 628xx).');
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
  set('customer_phone', wa.value.trim());
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
 
renderSessions();
update();
</script>
@endsection