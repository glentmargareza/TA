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
                Pilih paket wisata lereng Merapi, tentukan jadwal keberangkatan, dan nikmati kemudahan pembayaran instan.
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
                <div class="bg-white border border-wukirsari-border rounded-2xl p-2 shadow-xs flex items-center justify-between gap-1 sm:gap-2 overflow-x-auto scrollbar-none" id="stepper">
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
                        <div class="space-y-1.5 relative" id="dateWrap">
                            <label for="dateBtn" class="block text-xs font-bold text-merapiText-primary">Tanggal Kunjungan *</label>
                            <!-- Nilai tanggal (YYYY-MM-DD) disimpan di input tersembunyi; dipilih lewat kalender di bawah -->
                            <input id="date" type="hidden" value="{{ $defaultDate ?? '' }}">
                            <button type="button" id="dateBtn" aria-haspopup="dialog" aria-expanded="false" class="w-full flex items-center justify-between gap-2 px-4 py-3 rounded-2xl border border-wukirsari-border bg-white text-merapiText-primary text-sm font-semibold text-left cursor-pointer focus:outline-none focus:border-wukirsari-primary focus:ring-2 focus:ring-wukirsari-primary/15 transition-all">
                                <span id="dateText">Pilih tanggal</span>
                                <svg class="w-4 h-4 text-wukirsari-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </button>

                            <!-- Kalender ketersediaan -->
                            <div id="cal" class="hidden absolute left-0 right-0 sm:right-auto sm:w-[22rem] top-full mt-2 z-40 bg-white border border-wukirsari-border rounded-2xl shadow-2xl p-4" role="dialog" aria-label="Pilih tanggal kunjungan">
                                <div class="flex items-center justify-between mb-3">
                                    <button type="button" id="calPrev" class="w-9 h-9 rounded-lg hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center cursor-pointer" aria-label="Bulan sebelumnya">‹</button>
                                    <strong id="calTitle" class="text-sm font-bold text-merapiText-primary font-display capitalize"></strong>
                                    <button type="button" id="calNext" class="w-9 h-9 rounded-lg hover:bg-wukirsari-sage text-merapiText-primary font-bold flex items-center justify-center cursor-pointer" aria-label="Bulan berikutnya">›</button>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-bold text-merapiText-muted mb-1">
                                    <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                                </div>
                                <div id="calGrid" class="grid grid-cols-7 gap-1"></div>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 pt-3 border-t border-wukirsari-border/70 text-[11px] text-merapiText-secondary">
                                    <span class="inline-flex items-center gap-1.5"><i class="w-2 h-2 rounded-full bg-green-600 inline-block"></i>Tersedia</span>
                                    <span class="inline-flex items-center gap-1.5"><i class="w-2 h-2 rounded-full bg-amber-500 inline-block"></i>Sebagian sesi penuh/lewat</span>
                                    <span class="inline-flex items-center gap-1.5"><i class="w-2 h-2 rounded-full bg-red-500 inline-block"></i>Penuh</span>
                                </div>
                                <p class="text-[11px] text-merapiText-muted mt-2" id="calNote">Status mengikuti paket, jumlah jeep, dan jam sesi (WIB).</p>
                            </div>
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
 
                    <!-- Box Tagihan Sekarang -->
                    <div class="bg-wukirsari-dark text-white rounded-2xl p-4 flex items-center justify-between shadow-xs">
                        <strong class="text-xl sm:text-2xl font-extrabold font-display" id="vDue">Rp 0</strong>
                    </div>
 
                    <!-- Tombol Lanjutkan -->
                    <button id="submit" type="button" class="w-full py-4 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm sm:text-base shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                        <span>Konfirmasi & Terbitkan E-Tiket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
 
                    <!-- Trust Badges -->
                    
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
 
/* Kalender ketersediaan */
#cal .cd{height:2.5rem;display:flex;align-items:center;justify-content:center;position:relative;border-radius:.75rem;font-size:.8125rem;font-weight:700;color:#1f2a20;cursor:pointer;transition:background .15s}
#cal .cd:hover:not(:disabled){background:#E8EFE9}
#cal .cd:disabled{cursor:not-allowed}
#cal .cd.past{color:#c5c9c6}
#cal .cd.full{color:#b91c1c;background:#fef2f2;text-decoration:line-through}
#cal .cd.today{box-shadow:inset 0 0 0 1.5px #1E3F20}
#cal .cd.sel,#cal .cd.sel:hover{background:#1E3F20;color:#fff}

/* ===== RESPONSIF HP ===== */
html{-webkit-text-size-adjust:100%}
/* clip (bukan hidden) agar tidak membuat scroll container yang mematikan position: sticky */
html,body{overflow-x:clip}
/* Ringkasan ikut menempel saat scroll; jika lebih tinggi dari layar, bisa di-scroll sendiri */
@media (min-width:1024px){
  #pay{position:sticky;top:6rem;align-self:start;max-height:calc(100vh - 7rem);overflow-y:auto;scrollbar-width:none}
  #pay::-webkit-scrollbar{display:none}
}
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
  input[type=date]{min-height:48px}
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
const SESSIONS       = D.slots || [];
const TZ             = 'Asia/Jakarta';   // semua jadwal mengikuti WIB, bukan jam perangkat
 
const $ = s => document.querySelector(s);
const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const state = {
  pkg: D.selectedId ?? (PACKAGES[0] ? PACKAGES[0].id : null),
  qty: 1, pax: 4, session: null, pay: 'full'
};
const curPkg = () => PACKAGES.find(x => String(x.id) === String(state.pkg)) || PACKAGES[0];
 
/* ====== UTILITAS WAKTU (WIB) ====== */
function wibNow(){
  const parts = new Intl.DateTimeFormat('en-CA', {
    timeZone: TZ, year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit', hourCycle: 'h23'
  }).formatToParts(new Date());
  const g = t => parts.find(p => p.type === t).value;
  return { date: `${g('year')}-${g('month')}-${g('day')}`, min: (+g('hour')) * 60 + (+g('minute')) };
}
const addDays = (dateStr, n) => {
  const d = new Date(dateStr + 'T00:00:00Z');
  d.setUTCDate(d.getUTCDate() + n);
  return d.toISOString().slice(0, 10);
};
const toMin = t => { const [h, m] = String(t).split(':').map(Number); return h * 60 + m; };
const fmtDate = ds => new Date(ds + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

/* ====== UTILITAS JADWAL ====== */
const isSunrisePkg  = p => !!p && (p.is_sunrise || /sunrise/i.test(p.slug || '') || /sunrise/i.test(p.name || ''));
const isSunriseSlot = s => !!s.is_sunrise || s.time === '04:30' || /sunrise/i.test(s.label || '');
// Paket Sunrise hanya sesi 04:30; paket reguler hanya sesi reguler.
const slotsFor = p => SESSIONS.filter(s => isSunrisePkg(p) ? isSunriseSlot(s) : !isSunriseSlot(s));
// Sesi dianggap lewat jika tanggalnya sudah lewat, atau hari ini dan jamnya sudah lewat (WIB).
const isPassed = (s, date) => {
  const n = wibNow();
  if(date < n.date) return true;
  return date === n.date && toMin(s.time) <= n.min;
};
// Tanggal paling awal (hari ini s/d 30 hari ke depan) yang masih punya sesi belum lewat.
function firstValidDate(pkg, from){
  const start = from || wibNow().date;
  const list = slotsFor(pkg);
  for(let i = 0; i < 30; i++){
    const d = addDays(start, i);
    if(list.some(s => !isPassed(s, d))) return d;
  }
  return start;
}
// Cache kuota per tanggal: AVAIL[tanggal][slotId] = sisa jeep
const AVAIL = {};
 
/* ====== 1. PAKET ====== */
const dateEl = $('#date');
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

    // Jika tanggal terpilih tidak punya sesi tersisa untuk tipe paket ini
    // (mis. Sunrise 04:30 sudah lewat hari ini), pindah ke tanggal terdekat yang tersedia.
    const p = curPkg();
    const hasSlot = dateEl.value && slotsFor(p).some(s => !isPassed(s, dateEl.value));
    if(!hasSlot){
      const nd = firstValidDate(p);
      if(nd !== dateEl.value){
        dateEl.value = nd;
        toast('Tanggal disesuaikan ke ' + fmtDate(nd) + ' karena sesi paket ini hari ini sudah lewat.');
      }
    }

    renderSessions();
    update();
    setStep(2);
  });
}
 
/* ====== 2. TANGGAL & SESI ====== */
dateEl.min = wibNow().date;

// Tanggal awal: dari server jika masih valid untuk paket terpilih, jika tidak hitung sendiri (WIB)
(function initDate(){
  const p = curPkg();
  const cand = D.defaultDate || dateEl.value;
  const ok = cand && cand >= wibNow().date && slotsFor(p).some(s => !isPassed(s, cand));
  dateEl.value = ok ? cand : firstValidDate(p);
})();

/* ====== KALENDER KETERSEDIAAN ======
   Tiap tanggal dinilai dari: tipe paket (Sunrise / reguler), jumlah jeep, sisa kuota per sesi
   (dari server), dan jam sesi yang sudah lewat (WIB). */
const dateBtn = $('#dateBtn');
const calEl   = $('#cal');
const MONTH_LOADING = {};
let calYm = null;   // bulan yang sedang ditampilkan, 'YYYY-MM'

function dayInfo(date){
  const list = slotsFor(curPkg());
  if(date < wibNow().date) return { st: 'past', dot: '', tip: 'Tanggal sudah lewat' };
  if(list.length === 0)   return { st: 'full', dot: '', tip: 'Tidak ada sesi untuk paket ini' };

  const av = AVAIL[date];
  let ok = 0, passedN = 0;
  const lines = list.map(s => {
    const rem = av ? av[s.id] : undefined;
    const known = typeof rem === 'number';
    if(isPassed(s, date))            { passedN++; return `${s.time} WIB — sudah lewat`; }
    if(known && rem < state.qty)     return `${s.time} WIB — ${rem <= 0 ? 'penuh' : 'sisa ' + rem + ' jeep (kurang)'}`;
    ok++;
    return `${s.time} WIB — ${known ? 'sisa ' + rem + ' jeep' : 'tersedia'}`;
  });

  if(ok === 0){
    return passedN === list.length
      ? { st: 'past', dot: '', tip: 'Semua sesi hari ini sudah lewat' }
      : { st: 'full', dot: '', tip: 'Kuota penuh\n' + lines.join('\n') };
  }
  if(!av) return { st: 'unk', dot: '', tip: 'Memeriksa ketersediaan…' };
  return { st: ok < list.length ? 'part' : 'ok', dot: ok < list.length ? 'part' : 'ok', tip: lines.join('\n') };
}

// Tanggal terdekat (setelah `from`) yang masih punya sesi tersedia
function nextOpenDate(from){
  for(let i = 0; i < 90; i++){
    const d = addDays(from, i);
    if(['ok', 'part', 'unk'].includes(dayInfo(d).st)) return d;
  }
  return from;
}

function loadMonth(ym){
  if(MONTH_LOADING[ym]) return;
  MONTH_LOADING[ym] = true;
  fetch(`{{ route('booking.slot_availability') }}?month=${encodeURIComponent(ym)}`)
    .then(r => r.ok ? r.json() : null)
    .then(data => {
      MONTH_LOADING[ym] = false;
      if(!data || !data.dates) return;
      Object.keys(data.dates).forEach(d => {
        AVAIL[d] = {};
        data.dates[d].forEach(x => { AVAIL[d][x.id] = x.remaining; });
      });
      renderCalendar();
      if(dateEl.value){ renderSessionOptions(); update(); }
    })
    .catch(() => { MONTH_LOADING[ym] = false; });
}

function renderCalendar(){
  if(!calEl || calEl.classList.contains('hidden') || !calYm) return;
  const [y, m] = calYm.split('-').map(Number);
  const first = new Date(Date.UTC(y, m - 1, 1));
  const startDow = (first.getUTCDay() + 6) % 7;            // minggu dimulai Senin
  const days = new Date(Date.UTC(y, m, 0)).getUTCDate();
  const today = wibNow().date;

  $('#calTitle').textContent = first.toLocaleDateString('id-ID', { month: 'long', year: 'numeric', timeZone: 'UTC' });

  let html = '';
  for(let i = 0; i < startDow; i++) html += '<span></span>';
  for(let d = 1; d <= days; d++){
    const ds = `${calYm}-${String(d).padStart(2, '0')}`;
    const info = dayInfo(ds);
    const disabled = info.st === 'past' || info.st === 'full';
    const cls = ['cd', info.st === 'past' ? 'past' : '', info.st === 'full' ? 'full' : '',
                 ds === dateEl.value ? 'sel' : '', ds === today ? 'today' : ''].join(' ').replace(/\s+/g, ' ').trim();
    html += `<button type="button" class="${cls}" data-d="${ds}" ${disabled ? 'disabled' : ''} title="${esc(info.tip)}">${d}${info.dot ? `<i class="${info.dot}"></i>` : ''}</button>`;
  }
  $('#calGrid').innerHTML = html;

  const atFirst = calYm <= today.slice(0, 7);
  $('#calPrev').disabled = atFirst;
  $('#calPrev').classList.toggle('opacity-40', atFirst);
}

function showMonth(ym){
  calYm = ym;
  loadMonth(ym);        // selalu ambil data terbaru agar kuota akurat
  renderCalendar();
}
function shiftMonth(delta){
  const [y, m] = calYm.split('-').map(Number);
  const d = new Date(Date.UTC(y, m - 1 + delta, 1));
  const ym = d.toISOString().slice(0, 7);
  if(ym < wibNow().date.slice(0, 7)) return;
  showMonth(ym);
}
function openCal(){
  calEl.classList.remove('hidden');
  dateBtn.setAttribute('aria-expanded', 'true');
  showMonth((dateEl.value || wibNow().date).slice(0, 7));
}
function closeCal(){
  calEl.classList.add('hidden');
  dateBtn.setAttribute('aria-expanded', 'false');
}

dateBtn.addEventListener('click', () => calEl.classList.contains('hidden') ? openCal() : closeCal());
document.addEventListener('click', e => { if(!$('#dateWrap').contains(e.target)) closeCal(); });
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeCal(); });
$('#calPrev').addEventListener('click', () => shiftMonth(-1));
$('#calNext').addEventListener('click', () => shiftMonth(1));
$('#calGrid').addEventListener('click', e => {
  const b = e.target.closest('.cd');
  if(!b || b.disabled) return;
  dateEl.value = b.dataset.d;
  closeCal();
  dateEl.dispatchEvent(new Event('change'));   // memakai handler 'change' yang sudah ada
});
loadMonth(wibNow().date.slice(0, 7));          // ambil kuota bulan ini sejak awal

function fetchSlotAvailability(date){
  if(!date) return;
  fetch(`{{ route('booking.slot_availability') }}?trip_date=${encodeURIComponent(date)}&jeep_quantity=1`)
    .then(r => r.ok ? r.json() : null)
    .then(data => {
      if(!data || !Array.isArray(data.slots)) return;
      AVAIL[date] = {};
      data.slots.forEach(x => { AVAIL[date][x.id] = x.remaining; });
      // Abaikan respons lama jika pengguna sudah pindah tanggal
      if(dateEl.value === date){ renderSessionOptions(); update(); }
    })
    .catch(() => { /* abaikan jika offline */ });
}

function renderSessionOptions(){
  const sel = $('#session');
  if(!sel) return;
  const pkg = curPkg();
  const sunrise = isSunrisePkg(pkg);
  const date = dateEl.value;
  const prev = state.session;
  const list = slotsFor(pkg);

  if(!date){
    sel.innerHTML = '<option value="" disabled selected>Pilih tanggal dulu</option>';
    state.session = null;
    $('#sessHint').textContent = 'Pilih tanggal kunjungan terlebih dahulu.';
    return;
  }
  if(list.length === 0){
    sel.innerHTML = '<option value="" disabled selected>Tidak ada sesi yang tersedia</option>';
    state.session = null;
    $('#sessHint').textContent = 'Tidak ada sesi yang tersedia untuk paket ini.';
    return;
  }

  const avail = AVAIL[date] || {};
  sel.innerHTML = list.map(s => {
    const passed = isPassed(s, date);
    const rem = avail[s.id];
    const known = typeof rem === 'number';
    const isFull = known && rem < state.qty;
    const disabled = passed || isFull;

    let badge = '';
    if(passed) badge = ' — (Sudah Lewat)';
    else if(isFull) badge = rem <= 0 ? ' — (Kuota Penuh)' : ` — (Sisa ${rem} Jeep, kurang)`;
    else if(known) badge = ` (Sisa ${rem} Jeep)`;

    return `<option value="${s.id}" ${disabled ? 'disabled' : ''}>${esc(s.label)}${badge}</option>`;
  }).join('');

  const firstOk = [...sel.options].find(o => !o.disabled && o.value);
  const keep = [...sel.options].find(o => o.value === String(prev) && !o.disabled);
  sel.value = keep ? keep.value : (firstOk ? firstOk.value : '');
  state.session = sel.value || null;

  if(!firstOk){
    const next = nextOpenDate(addDays(date, 1));
    const tip = ' Coba tanggal ' + fmtDate(next) + '.';
    $('#sessHint').textContent = sunrise
      ? 'Sesi Sunrise (04:30 WIB) pada tanggal ini sudah lewat atau kuota tidak cukup.' + tip
      : 'Semua sesi pada tanggal ini sudah lewat atau kuota tidak cukup.' + tip;
  } else {
    $('#sessHint').textContent = sunrise ? '✓ Paket Sunrise hanya berjalan pada sesi 04:30 WIB.' : '';
  }
}

function renderSessions(){
  renderSessionOptions();
  if(dateEl.value) fetchSlotAvailability(dateEl.value);
}

// Muat ulang daftar sesi (tanpa fetch) lalu hitung ulang ringkasan
function refresh(){ renderSessionOptions(); update(); renderCalendar(); }

dateEl.addEventListener('change', () => {
  const today = wibNow().date;
  if(!dateEl.value || dateEl.value < today){
    dateEl.value = firstValidDate(curPkg());
    toast('Tanggal yang sudah lewat tidak bisa dipilih.');
  }
  renderSessions(); update(); renderCalendar(); setStep(2);
});
$('#session').addEventListener('change', e => { state.session = e.target.value; update(); setStep(3); });
 
/* ====== 3. KAPASITAS & ARMADA ====== */
$('#paxPlus').onclick = () => {
  if(state.pax < 40){
    state.pax++;
    state.qty = Math.max(state.qty, Math.ceil(state.pax / PER_JEEP));
    refresh(); setStep(3);
  }
};
$('#paxMinus').onclick = () => {
  if(state.pax > 1){ state.pax--; refresh(); setStep(3); }
};
$('#plus').onclick = () => {
  if(state.qty < 10){ state.qty++; refresh(); setStep(3); }
};
$('#minus').onclick = () => {
  const minJeep = Math.ceil(state.pax / PER_JEEP);
  if(state.qty > minJeep){ state.qty--; refresh(); }
  else toast('Minimal butuh ' + minJeep + ' jeep untuk ' + state.pax + ' orang penumpang (maksimal 4 orang per jeep).');
};
 
/* ====== UPDATE RINGKASAN TAGIHAN ====== */
function update(){
  const p = curPkg();
  if(!p) return;
  const total = p.price * state.qty;
  const due = total;
 
  $('#qty').textContent = state.qty;
  $('#pax').textContent = state.pax;
  $('#jeepHint').textContent = state.qty + ' Jeep × ' + rp(p.price);
  $('#tPkg').textContent = p.name + ' (' + state.qty + ' Jeep · ' + state.pax + ' Orang)';
 
  const d = dateEl.value ? fmtDate(dateEl.value) : 'Belum pilih tanggal';
  $('#dateText').textContent = dateEl.value ? fmtDate(dateEl.value) : 'Pilih tanggal';
  const sObj = SESSIONS.find(s => String(s.id) === String(state.session));
  $('#tDate').textContent = d + (sObj ? ' · ' + sObj.label : '');
 
  $('#lJeep').textContent = 'Sewa Jeep' + (state.qty > 1 ? ' (' + state.qty + '×)' : '');
  $('#vJeep').textContent = rp(p.price * state.qty);
 
  $('#vTotal').textContent = rp(total);
  $('#mTotal').textContent = rp(total);
  $('#vDue').textContent = rp(due);
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
    return dateBtn.focus();
  }
  if(!state.session){
    toast('Pilih sesi waktu keberangkatan yang tersedia.');
    return $('#session').focus();
  }
  // Cek ulang saat submit: sesi bisa saja baru lewat sejak halaman dibuka
  const chosen = SESSIONS.find(s => String(s.id) === String(state.session));
  if(chosen && isPassed(chosen, dateEl.value)){
    renderSessionOptions(); update();
    toast('Sesi yang dipilih sudah lewat. Pilih sesi atau tanggal lain.');
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
  set('payment_mode', 'FULL');
  set('notes', $('#notes').value.trim());
 
  const btn = $('#submit');
  btn.disabled = true;
  btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses E-Tiket…`;
  f.submit();
};
 
$('#notes').addEventListener('input', e => {
  $('#notesCount').textContent = e.target.value.length;
});
 
/* ====== PERBAIKAN STICKY RINGKASAN ======
   position: sticky mati jika ada induk (di layout) ber-overflow:hidden.
   'clip' tetap memotong konten seperti 'hidden' tetapi tidak membuat scroll container. */
(function fixStickyAncestors(){
  let el = $('#pay') ? $('#pay').parentElement : null;
  while(el && el !== document.documentElement){
    const cs = getComputedStyle(el);
    if(cs.overflowX === 'hidden') el.style.overflowX = 'clip';
    if(cs.overflowY === 'hidden') el.style.overflowY = 'clip';
    el = el.parentElement;
  }
})();

renderSessions();
update();
</script>
@endsection