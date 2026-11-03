@extends('layouts.app')

@section('title', 'Pembayaran Booking ' . $booking->booking_code . ' | Jeep Wukirsari')

@section('content')
<div class="py-10 sm:py-14 bg-wukirsari-cream min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- STEPPER INDIKATOR PROGRES RESERVASI -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl border border-wukirsari-border p-4 sm:p-5 shadow-xs max-w-3xl mx-auto">
                <div class="flex items-center justify-between text-xs sm:text-sm font-medium">
                    <!-- Step 1: Selesai -->
                    <div class="flex items-center gap-2 text-wukirsari-primary font-semibold">
                        <span class="w-7 h-7 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center text-xs font-bold shrink-0">
                            <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="hidden sm:inline">Data Pesanan</span>
                    </div>

                    <div class="w-8 sm:w-16 h-0.5 bg-wukirsari-primary"></div>

                    <!-- Step 2: Pembayaran (Aktif) -->
                    <div class="flex items-center gap-2 text-wukirsari-primary font-bold">
                        <span class="w-7 h-7 rounded-full bg-wukirsari-primary text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm shadow-wukirsari-primary/30">
                            2
                        </span>
                        <span>Pembayaran</span>
                    </div>

                    <div class="w-8 sm:w-16 h-0.5 bg-wukirsari-border"></div>

                    <!-- Step 3: Tiket & Konfirmasi -->
                    <div class="flex items-center gap-2 text-merapiText-muted">
                        <span class="w-7 h-7 rounded-full bg-slate-100 text-merapiText-muted flex items-center justify-center text-xs font-bold shrink-0">
                            3
                        </span>
                        <span class="hidden sm:inline">E-Tiket</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEADER HALAMAN -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                        Pembayaran Reservasi
                    </h1>
                    <p class="text-merapiText-secondary text-sm mt-1">
                        Selesaikan pembayaran dengan aman melalui metode pembayaran resmi Paguyuban Jeep Wukirsari.
                    </p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-xs font-medium text-merapiText-muted">Kode Booking:</span>
                    <span class="px-3.5 py-1.5 rounded-full bg-wukirsari-sage text-wukirsari-primary font-mono font-bold text-xs sm:text-sm">
                        {{ $booking->booking_code }}
                    </span>
                </div>
            </div>
        </div>

        <!-- FLASH NOTIFIKASI / FEEDBACK -->
        @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold">Perhatian</p>
                <p class="text-xs text-red-800 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm">
            <p class="font-bold mb-1">Terdapat kesalahan pengisian formulir:</p>
            <ul class="list-disc pl-5 text-xs text-red-800 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- TATA LETAK 2 KOLOM (DESKTOP) / BERTUMPUK (MOBILE) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ========================================== -->
            <!-- KOLOM KIRI (7 SPAN): METODE & FORMULIR     -->
            <!-- ========================================== -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- PILIHAN 3 METODE PEMBAYARAN UTAMA -->
                <div class="space-y-3">
                    
                    <!-- TAB 1 & TAB 2 (GRID 2 KOLOM) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        
                        <!-- TAB 1: BANK BPD DIY (UTAMA) -->
                        <div id="tabBtnBpd" onclick="switchTab('bpd')" 
                             class="p-4 sm:p-5 rounded-2xl border-2 transition-all cursor-pointer text-left bg-white border-slate-900 ring-2 ring-slate-900/10 shadow-sm flex items-start gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-slate-900 text-white font-extrabold flex items-center justify-center text-xs tracking-wider shrink-0 shadow-xs">
                                BPD
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-sm text-merapiText-primary">Bank BPD DIY</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-900 text-white">
                                        Utama
                                    </span>
                                </div>
                                <p class="text-xs text-merapiText-secondary mt-1 line-clamp-1">
                                    Transfer manual & upload bukti
                                </p>
                            </div>
                        </div>

                        <!-- TAB 2: DANA MIDTRANS (NONAKTIF SEMENTARA) -->
                        <div id="tabBtnDana" onclick="switchTab('dana')" 
                             class="p-4 sm:p-5 rounded-2xl border-2 transition-all cursor-pointer text-left bg-white border-wukirsari-border hover:border-slate-300 opacity-90 flex items-start gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-sky-500 text-white font-extrabold flex items-center justify-center text-xs tracking-wider shrink-0 shadow-xs">
                                DANA
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-sm text-merapiText-primary">DANA Midtrans</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                                        Nonaktif
                                    </span>
                                </div>
                                <p class="text-xs text-merapiText-secondary mt-1 line-clamp-1">
                                    E-Wallet Digital Instan
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- TAB 3: BAYAR TUNAI DI LOKET KASIR (FULL WIDTH) -->
                    <div id="tabBtnCash" onclick="switchTab('cash')" 
                         class="p-4 sm:p-5 rounded-2xl border-2 transition-all cursor-pointer text-left bg-white border-wukirsari-border hover:border-slate-300 flex items-center justify-between gap-3.5">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-emerald-700 text-white font-extrabold flex items-center justify-center text-xs tracking-wider shrink-0 shadow-xs">
                                CASH
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-sm text-merapiText-primary">Bayar Tunai di Loket Kasir</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                        Lokasi Basecamp
                                    </span>
                                </div>
                                <p class="text-xs text-merapiText-secondary mt-0.5 line-clamp-1">
                                    Bayar langsung secara tunai saat tiba di basecamp sebelum jadwal keberangkatan
                                </p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-merapiText-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- PANEL METODE 1: BANK BPD DIY (UTAMA)       -->
                <!-- ========================================== -->
                <div id="panelBpd" class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-7 shadow-xs space-y-6">
                    
                    <!-- REKENING RESMI PAGUYUBAN -->
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-merapiText-secondary mb-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                            <span>REKENING RESMI PAGUYUBAN</span>
                        </div>

                        <!-- Baris No. Rekening & Tombol Salin -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-wukirsari-border">
                            <div>
                                <span class="text-xs text-merapiText-muted block">Nomor Rekening Bank BPD DIY:</span>
                                <span class="text-2xl sm:text-3xl font-mono font-extrabold text-merapiText-primary tracking-wider block mt-0.5" id="rekDisplay">
                                    069211001060
                                </span>
                            </div>
                            <button type="button" onclick="copyToClipboard('069211001060', 'btnCopyRekText')" 
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-xs sm:text-sm font-semibold text-merapiText-primary shadow-xs transition-all active:scale-95">
                                <svg class="w-4 h-4 text-merapiText-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span id="btnCopyRekText">Salin Rekening</span>
                            </button>
                        </div>

                        <!-- Baris Atas Nama & Nominal Transfer Wajib -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-5 border-b border-wukirsari-border">
                            <div>
                                <span class="text-xs text-merapiText-muted block">Atas Nama Pemilik Rekening:</span>
                                <span class="text-base sm:text-lg font-bold text-merapiText-primary block mt-0.5 font-display">
                                    Ariyanto
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-merapiText-muted block">Nominal Transfer Wajib:</span>
                                <div class="flex items-center gap-2.5 mt-0.5">
                                    <span class="text-xl sm:text-2xl font-extrabold text-wukirsari-primary font-display" id="nominalDisplay">
                                        Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}
                                    </span>
                                    <button type="button" onclick="copyToClipboard('{{ (int)$booking->paid_amount }}', 'btnCopyNominalText')" 
                                            class="text-xs font-semibold text-wukirsari-primary bg-wukirsari-sage/60 hover:bg-wukirsari-sage px-2.5 py-1 rounded-lg transition-all active:scale-95">
                                        <span id="btnCopyNominalText">Salin</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Peringatan Transfer -->
                        <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 leading-relaxed mt-5">
                            Pastikan melakukan transfer tepat hingga nominal tagihan di atas. Setelah transfer berhasil, unggah foto bukti transfer pada formulir di bawahnya.
                        </div>
                    </div>

                    <!-- FORMULIR KONFIRMASI PENGIRIMAN BUKTI -->
                    <div class="pt-3">
                        <div class="flex items-center gap-2 text-sm font-bold text-merapiText-primary mb-4">
                            <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span>Formulir Konfirmasi Pengiriman Bukti</span>
                        </div>

                        <form action="{{ route('booking.confirm_transfer', $booking->booking_code) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-merapiText-secondary mb-1">
                                        Nama Pengirim (Sesuai Rekening / Struk) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name', $booking->customer_name) }}" required 
                                           placeholder="Contoh: Budi Santoso" 
                                           class="w-full px-4 py-2.5 rounded-xl border border-wukirsari-border focus:ring-2 focus:ring-wukirsari-primary focus:border-wukirsari-primary text-sm bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-merapiText-secondary mb-1">
                                        Bank Asal Transfer <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sender_bank" required 
                                            class="w-full px-4 py-2.5 rounded-xl border border-wukirsari-border focus:ring-2 focus:ring-wukirsari-primary focus:border-wukirsari-primary text-sm bg-white">
                                        <option value="">Pilih Bank Asal</option>
                                        <option value="Bank BCA" {{ old('sender_bank') === 'Bank BCA' ? 'selected' : '' }}>Bank BCA</option>
                                        <option value="Bank Mandiri" {{ old('sender_bank') === 'Bank Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                        <option value="Bank BNI" {{ old('sender_bank') === 'Bank BNI' ? 'selected' : '' }}>Bank BNI</option>
                                        <option value="Bank BRI" {{ old('sender_bank') === 'Bank BRI' ? 'selected' : '' }}>Bank BRI</option>
                                        <option value="Bank BPD DIY" {{ old('sender_bank') === 'Bank BPD DIY' ? 'selected' : '' }}>Bank BPD DIY</option>
                                        <option value="Bank Syariah Indonesia (BSI)" {{ old('sender_bank') === 'Bank Syariah Indonesia (BSI)' ? 'selected' : '' }}>Bank Syariah Indonesia (BSI)</option>
                                        <option value="Bank CIMB Niaga" {{ old('sender_bank') === 'Bank CIMB Niaga' ? 'selected' : '' }}>Bank CIMB Niaga</option>
                                        <option value="Bank Permata" {{ old('sender_bank') === 'Bank Permata' ? 'selected' : '' }}>Bank Permata</option>
                                        <option value="Bank Lainnya / E-Wallet" {{ old('sender_bank') === 'Bank Lainnya / E-Wallet' ? 'selected' : '' }}>Bank Lainnya / E-Wallet</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-merapiText-secondary mb-1">
                                    Unggah Foto Struk / Tangkapan Layar Mutasi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative border-2 border-dashed border-slate-300 hover:border-wukirsari-primary rounded-2xl p-6 text-center cursor-pointer transition-all bg-slate-50/50 hover:bg-white" id="dropzoneProof">
                                    <input type="file" name="payment_proof" id="fileProof" accept="image/jpeg,image/png,image/jpg,image/webp" required 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    
                                    <div id="previewDefault" class="space-y-2 pointer-events-none">
                                        <svg class="w-8 h-8 text-merapiText-muted mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div class="text-sm font-semibold text-merapiText-primary">
                                            Pilih berkas atau seret foto ke sini
                                        </div>
                                        <div class="text-xs text-merapiText-muted">
                                            Format JPG, JPEG, PNG, atau WEBP (Maksimal 5MB)
                                        </div>
                                    </div>

                                    <div id="previewSelected" class="hidden pointer-events-none items-center justify-center gap-3.5">
                                        <img id="imgThumbnail" class="w-14 h-14 object-cover rounded-xl border border-wukirsari-border shadow-xs" src="" alt="Thumbnail Bukti">
                                        <div class="text-left">
                                            <div id="fileNameDisplay" class="text-xs font-bold text-merapiText-primary truncate max-w-xs"></div>
                                            <div id="fileSizeDisplay" class="text-[11px] text-merapiText-secondary"></div>
                                            <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">✓ Berkas siap diunggah</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full py-4 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-wukirsari-primary/25 transition-all active:scale-98">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- PANEL METODE 2: DANA MIDTRANS (NONAKTIF)   -->
                <!-- ========================================== -->
                <div id="panelDana" class="hidden bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                        <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>DANA Midtrans (E-Wallet Digital)</span>
                        </h3>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold bg-amber-100 text-amber-800">
                            Nonaktif Sementara
                        </span>
                    </div>

                    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200/80 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-amber-950">Kanal Merchant Midtrans Belum Aktif</h4>
                                <p class="text-xs text-amber-900 mt-1 leading-relaxed">
                                    Kanal pembayaran digital instan melalui DANA saat ini masih dalam proses aktivasi izin merchant Midtrans. Demi kelancaran reservasi Anda, silakan gunakan metode transfer manual ke <strong>Bank BPD DIY</strong> atau pilih opsi <strong>Bayar Tunai di Loket Kasir</strong>.
                                </p>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-amber-200/60 flex flex-wrap gap-2.5">
                            <button type="button" onclick="switchTab('bpd')" 
                                    class="px-4 py-2 rounded-xl bg-wukirsari-primary text-white text-xs font-semibold hover:bg-wukirsari-hover transition-all">
                                ← Gunakan Bank BPD DIY
                            </button>
                            <button type="button" onclick="switchTab('cash')" 
                                    class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-merapiText-primary text-xs font-semibold hover:bg-slate-50 transition-all">
                                Pilih Bayar di Loket Kasir →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- PANEL METODE 3: BAYAR TUNAI DI LOKET KASIR -->
                <!-- ========================================== -->
                <div id="panelCash" class="hidden bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-7 shadow-xs space-y-6">
                    <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                        <h3 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Bayar Tunai di Loket Kasir Basecamp</span>
                        </h3>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold bg-emerald-100 text-emerald-800">
                            Lokasi Basecamp
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-merapiText-secondary">Kode Booking Anda:</span>
                            <span class="font-mono font-extrabold text-sm text-wukirsari-primary bg-white px-3 py-1 rounded-lg border border-emerald-200">
                                {{ $booking->booking_code }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-merapiText-secondary">Nominal Tagihan Kasir:</span>
                            <span class="font-display font-extrabold text-xl text-emerald-950">
                                Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs text-merapiText-secondary leading-relaxed">
                        <p class="font-bold text-merapiText-primary text-sm">Petunjuk Pembayaran di Loket:</p>
                        <ol class="list-decimal pl-4 space-y-2">
                            <li>Datang ke Basecamp Paguyuban Jeep Wukirsari paling lambat <strong>30 menit sebelum jadwal keberangkatan</strong> ({{ $booking->slot->slot_label }}).</li>
                            <li>Tunjukkan kode booking <strong>{{ $booking->booking_code }}</strong> kepada petugas kasir di loket.</li>
                            <li>Lakukan pelunasan tiket secara tunai (Cash) sesuai nominal tagihan kasir.</li>
                            <li>Petugas kasir akan memvalidasi pesanan dan mengarahkan Anda langsung ke armada Jeep paguyuban yang telah ditugaskan.</li>
                        </ol>
                    </div>

                    <form action="{{ route('booking.confirm_cash', $booking->booking_code) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full py-4 px-6 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-wukirsari-primary/25 transition-all active:scale-98">
                            <span>Konfirmasi Bayar Tunai di Loket Basecamp →</span>
                        </button>
                    </form>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- KOLOM KANAN (5 SPAN): LANGKAH & RINGKASAN  -->
            <!-- ========================================== -->
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
                
                <!-- KARTU 1: LANGKAH PEMBAYARAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-merapiText-secondary border-b border-wukirsari-border pb-3">
                        <svg class="w-4 h-4 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>LANGKAH PEMBAYARAN</span>
                    </div>

                    <ol class="space-y-3 text-xs text-merapiText-secondary" id="paymentStepsList">
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-[11px] shrink-0 mt-0.5">1</span>
                            <span>Buka aplikasi m-Banking atau kunjungi mesin ATM bank Anda.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-[11px] shrink-0 mt-0.5">2</span>
                            <span>Pilih menu Transfer Antar Bank (Kode BPD DIY: <strong>112</strong>) atau Sesama BPD DIY.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-[11px] shrink-0 mt-0.5">3</span>
                            <span>Masukkan no. rekening tujuan <strong>069211001060</strong> a.n. <strong>Ariyanto</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-[11px] shrink-0 mt-0.5">4</span>
                            <span>Masukkan nominal transfer tepat sebesar <strong>Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center font-bold text-[11px] shrink-0 mt-0.5">5</span>
                            <span>Simpan foto struk atau screenshot mutasi rekening, lalu unggah pada formulir konfirmasi bukti transfer.</span>
                        </li>
                    </ol>
                </div>

                <!-- KARTU 2: RINGKASAN TAGIHAN -->
                <div class="bg-white rounded-3xl border border-wukirsari-border p-6 shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-wukirsari-border pb-3">
                        <h2 class="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-wukirsari-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Ringkasan Tagihan</span>
                        </h2>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $booking->booking_status === 'PENDING' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $booking->booking_status === 'PENDING' ? 'Menunggu Pembayaran' : $booking->booking_status }}
                        </span>
                    </div>

                    <!-- Informasi Paket Wisata -->
                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-wukirsari-cream/60 border border-wukirsari-border">
                        <img src="{{ asset($booking->package->cover_image) }}" alt="{{ $booking->package->name }}" 
                             class="w-16 h-16 rounded-xl object-cover shrink-0 border border-wukirsari-border"
                             onerror="this.onerror=null; this.src='/img/short_trip.jpeg';">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-wukirsari-primary">
                                    Paket Wisata
                                </span>
                                @if($booking->package->category_badge)
                                <span class="px-2 py-0.5 rounded-full bg-wukirsari-primary text-white text-[9px] font-semibold">
                                    {{ $booking->package->category_badge }}
                                </span>
                                @endif
                            </div>
                            <h3 class="text-sm font-bold text-merapiText-primary truncate font-display mt-0.5">
                                {{ $booking->package->name }}
                            </h3>
                            <div class="text-[11px] text-merapiText-secondary mt-0.5">
                                {{ $booking->jeep_quantity }} Jeep • {{ $booking->passenger_count }} Penumpang
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal & Opsi Kedatangan -->
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-merapiText-muted block text-[10px]">Tanggal Trip:</span>
                            <span class="font-bold text-merapiText-primary">
                                {{ $booking->trip_date->format('d M Y') }}
                            </span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-wukirsari-border">
                            <span class="text-merapiText-muted block text-[10px]">Sesi Waktu:</span>
                            <span class="font-bold text-merapiText-primary truncate block">
                                {{ $booking->slot->slot_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Rincian Biaya -->
                    <div class="space-y-2 text-xs pt-2 border-t border-wukirsari-border">
                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Sewa Jeep ({{ $booking->jeep_quantity }} unit)</span>
                            <span class="font-semibold text-merapiText-primary">Rp {{ number_format($booking->package_subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($booking->pickup_fee > 0)
                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Biaya Layanan Antar-Jemput</span>
                            <span class="font-semibold text-merapiText-primary">Rp {{ number_format($booking->pickup_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-merapiText-secondary">
                            <span>Asuransi & Tiket Masuk</span>
                            <span class="font-semibold text-emerald-600">Gratis (Termasuk)</span>
                        </div>
                        <div class="pt-2 border-t border-wukirsari-border flex justify-between items-baseline">
                            <span class="font-bold text-merapiText-primary">Total Biaya Reservasi</span>
                            <span class="text-base font-extrabold text-merapiText-primary font-display">
                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Kotak Tagihan Wajib Sekarang -->
                    <div class="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border space-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[11px] text-merapiText-secondary block">Skema Pembayaran:</span>
                                <span class="font-bold text-xs text-wukirsari-primary">
                                    {{ $booking->payment_mode === 'FULL' ? 'Pembayaran Lunas (100%)' : 'Uang Muka (DP 30%)' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[11px] text-merapiText-secondary block">Wajib Dibayar:</span>
                                <span class="text-lg font-extrabold text-wukirsari-primary font-display">
                                    Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        @if($booking->remaining_amount > 0)
                        <div class="pt-2 border-t border-wukirsari-border flex justify-between items-center text-xs text-amber-900">
                            <span>Sisa Pembayaran di Loket:</span>
                            <span class="font-bold">
                                Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    function copyToClipboard(text, elementId) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function () {
                showCopiedFeedback(elementId);
            }).catch(function () {
                fallbackCopy(text, elementId);
            });
        } else {
            fallbackCopy(text, elementId);
        }
    }

    function fallbackCopy(text, elementId) {
        var temp = document.createElement('input');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        try {
            document.execCommand('copy');
            showCopiedFeedback(elementId);
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(temp);
    }

    function showCopiedFeedback(elementId) {
        var el = document.getElementById(elementId);
        if (!el) return;
        var originalText = el.innerText;
        el.innerText = 'Tersalin!';
        setTimeout(function () {
            el.innerText = originalText;
        }, 2000);
    }

    function switchTab(method) {
        var tabBtnBpd = document.getElementById('tabBtnBpd');
        var tabBtnDana = document.getElementById('tabBtnDana');
        var tabBtnCash = document.getElementById('tabBtnCash');

        var panelBpd = document.getElementById('panelBpd');
        var panelDana = document.getElementById('panelDana');
        var panelCash = document.getElementById('panelCash');

        // Reset semua tombol tab ke style inaktif
        [tabBtnBpd, tabBtnDana, tabBtnCash].forEach(function (btn) {
            btn.classList.remove('border-slate-900', 'ring-2', 'ring-slate-900/10', 'shadow-sm');
            btn.classList.add('border-wukirsari-border');
        });

        // Sembunyikan semua panel
        panelBpd.classList.add('hidden');
        panelDana.classList.add('hidden');
        panelCash.classList.add('hidden');

        // Aktifkan tab dan panel yang dipilih
        if (method === 'bpd') {
            tabBtnBpd.classList.remove('border-wukirsari-border');
            tabBtnBpd.classList.add('border-slate-900', 'ring-2', 'ring-slate-900/10', 'shadow-sm');
            panelBpd.classList.remove('hidden');
        } else if (method === 'dana') {
            tabBtnDana.classList.remove('border-wukirsari-border');
            tabBtnDana.classList.add('border-slate-900', 'ring-2', 'ring-slate-900/10', 'shadow-sm');
            panelDana.classList.remove('hidden');
        } else if (method === 'cash') {
            tabBtnCash.classList.remove('border-wukirsari-border');
            tabBtnCash.classList.add('border-slate-900', 'ring-2', 'ring-slate-900/10', 'shadow-sm');
            panelCash.classList.remove('hidden');
        }
    }

    // Penanganan preview file bukti transfer
    document.addEventListener('DOMContentLoaded', function () {
        var fileInput = document.getElementById('fileProof');
        var previewDefault = document.getElementById('previewDefault');
        var previewSelected = document.getElementById('previewSelected');
        var imgThumbnail = document.getElementById('imgThumbnail');
        var fileNameDisplay = document.getElementById('fileNameDisplay');
        var fileSizeDisplay = document.getElementById('fileSizeDisplay');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    var file = this.files[0];
                    
                    fileNameDisplay.textContent = file.name;
                    fileSizeDisplay.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        imgThumbnail.src = e.target.result;
                        previewDefault.classList.add('hidden');
                        previewSelected.classList.remove('hidden');
                        previewSelected.classList.add('flex');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewDefault.classList.remove('hidden');
                    previewSelected.classList.add('hidden');
                    previewSelected.classList.remove('flex');
                }
            });
        }
    });
</script>
@endsection
