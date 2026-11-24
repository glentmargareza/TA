<x-admin-layout :title="'Verifikasi Tiket ' . ($booking?->booking_code ?? $code) . ' | Jeep Wukirsari'">

    <!-- Header Banner -->
    <x-admin.header title="Verifikasi Tiket" subtitle="Scan QR Code untuk verifikasi dan pelunasan tiket di loket basecamp.">
        <x-slot:actions>
            <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-all border border-slate-200">
                <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                <span>Semua Booking</span>
            </a>
        </x-slot:actions>
    </x-admin.header>

    <!-- Page Content -->
    <main class="flex-1 px-4 sm:px-8 py-6 sm:py-8">

            @if(!$booking)
            {{-- ===== TIKET TIDAK DITEMUKAN ===== --}}
            <div class="max-w-lg mx-auto text-center py-16">
                <div class="w-20 h-20 rounded-3xl bg-rose-100 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 mb-2">Tiket Tidak Ditemukan</h2>
                <p class="text-sm text-slate-500 mb-2">Kode booking <strong class="font-bold text-slate-700">{{ strtoupper($code) }}</strong> tidak ditemukan dalam sistem.</p>
                <p class="text-xs text-slate-400 mb-8">QR Code mungkin tidak valid atau booking telah dihapus dari sistem.</p>
                <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#143B1E] hover:bg-[#0D2714] text-white text-sm font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    Kembali ke Manajemen Booking
                </a>
            </div>

            @else
            {{-- ===== TIKET DITEMUKAN ===== --}}
            <div class="max-w-2xl mx-auto space-y-5">

                {{-- STATUS BADGE --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($booking->booking_status === 'FULL_PAID')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-black border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            LUNAS
                        </span>
                        @elseif($booking->booking_status === 'DP_PAID')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-black border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            DP TERBAYAR
                        </span>
                        @elseif($booking->booking_status === 'PENDING')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-black border border-slate-200">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            MENUNGGU
                        </span>
                        @else
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100 text-rose-700 text-xs font-black border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            {{ $booking->booking_status }}
                        </span>
                        @endif
                    </div>
                    <span class="text-xs text-slate-400 font-mono">{{ $booking->booking_code }}</span>
                </div>

                {{-- INFORMASI BOOKING --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#143B1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Informasi Pemesan
                        </h2>
                    </div>
                    <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Nama Pemesan</span>
                            <span class="font-semibold text-slate-900">{{ $booking->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">No. WhatsApp</span>
                            <span class="font-semibold text-slate-900">{{ $booking->customer_phone }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Kode Booking</span>
                            <span class="font-mono font-bold text-[#143B1E]">{{ $booking->booking_code }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Tanggal Booking</span>
                            <span class="font-semibold text-slate-900">{{ $booking->created_at?->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI TRIP --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#143B1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Detail Trip
                        </h2>
                    </div>
                    <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Paket Wisata</span>
                            <span class="font-semibold text-slate-900">{{ $booking->package?->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Tanggal Trip</span>
                            <span class="font-semibold text-slate-900">
                                {{ $booking->trip_date?->translatedFormat('l, d F Y') ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Sesi Waktu</span>
                            <span class="font-semibold text-slate-900">{{ $booking->slot?->slot_label ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Jumlah Jeep</span>
                            <span class="font-semibold text-slate-900">{{ $booking->jeep_quantity }} Unit</span>
                        </div>
                        @if($booking->assignments->isNotEmpty())
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Armada & Driver</span>
                            @foreach($booking->assignments as $assign)
                            <span class="font-semibold text-slate-900 block">
                                {{ $assign->jeep?->model_name ?? 'Jeep' }}
                                @if($assign->driver) — {{ $assign->driver->full_name }} @endif
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- RINGKASAN PEMBAYARAN --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#143B1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Ringkasan Pembayaran
                        </h2>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Total Tagihan</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Sudah Dibayar</span>
                            <span class="font-bold text-emerald-700">Rp{{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($booking->remaining_amount > 0)
                        <div class="flex items-center justify-between text-sm border-t border-slate-100 pt-3 mt-1">
                            <span class="font-bold text-slate-700">Sisa Tagihan</span>
                            <span class="font-black text-amber-700 text-base">Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <div class="flex items-center justify-between text-sm border-t border-slate-100 pt-3 mt-1">
                            <span class="font-bold text-slate-700">Sisa Tagihan</span>
                            <span class="font-black text-emerald-700 text-base">Lunas</span>
                        </div>
                        @endif

                        {{-- Riwayat pembayaran --}}
                        @if($booking->payments->isNotEmpty())
                        <div class="border-t border-slate-100 pt-3 mt-1 space-y-2">
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Riwayat Pembayaran</span>
                            @foreach($booking->payments as $pay)
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $pay->payment_status === 'SETTLED' || $pay->payment_status === 'settlement' ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                                    <span>{{ $pay->payment_type ?? $pay->payment_method ?? 'Pembayaran' }}</span>
                                    @if($pay->paid_at)
                                    <span class="text-slate-400">— {{ $pay->paid_at?->format('d/m/Y H:i') }}</span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold">Rp{{ number_format($pay->gross_amount, 0, ',', '.') }}</span>
                                    @if(!empty($pay->admin_fee) && $pay->admin_fee > 0)
                                    <span class="text-[10px] text-slate-400 block">(Termasuk admin Rp{{ number_format($pay->admin_fee, 0, ',', '.') }})</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ===== ACTION SECTION ===== --}}
                @if($booking->booking_status === 'FULL_PAID')
                {{-- LUNAS: tampilkan status saja --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-emerald-900 text-sm mb-1">Pembayaran Lunas ✓</h3>
                        <p class="text-xs text-emerald-800 leading-relaxed">
                            Seluruh tagihan sebesar <strong>Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</strong> telah dilunasi.
                            Wisatawan dapat langsung berangkat trip sesuai jadwal.
                        </p>
                    </div>
                </div>

                @elseif($booking->booking_status === 'DP_PAID')
                {{-- DP_PAID: tampilkan tombol pelunasan --}}
                <div class="bg-amber-50 border border-amber-200 rounded-2xl px-5 py-5 space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-amber-900 text-sm mb-1">Sisa Tagihan Belum Dilunasi</h3>
                            <p class="text-xs text-amber-800 leading-relaxed">
                                Wisatawan telah membayar DP sebesar <strong>Rp{{ number_format($booking->paid_amount, 0, ',', '.') }}</strong>.
                                Tagih dan terima pelunasan sisa sebesar <strong>Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong>
                                sebelum wisatawan berangkat trip.
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Pelunasan Diterima --}}
                    <form
                        method="POST"
                        action="{{ route('admin.settle_ticket', $booking->booking_code) }}"
                        data-confirm-message="Konfirmasi bahwa Anda telah menerima uang tunai sebesar <strong>Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong> dari <strong>{{ $booking->customer_name }}</strong> sebagai pelunasan booking <strong>{{ $booking->booking_code }}</strong>.<br><br>Tindakan ini tidak dapat dibatalkan."
                        data-confirm-title="Konfirmasi Pelunasan Diterima"
                        data-confirm-type="success"
                        data-confirm-text="Ya, Pelunasan Diterima"
                    >
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-[#143B1E] hover:bg-[#0D2714] text-white font-black text-sm transition-all shadow-sm hover:shadow-md cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pelunasan Diterima — Rp{{ number_format($booking->remaining_amount, 0, ',', '.') }}
                        </button>
                    </form>
                </div>

                @elseif($booking->booking_status === 'PENDING' || $booking->booking_status === 'WAITING_CONFIRMATION')
                {{-- PENDING: belum bayar sama sekali --}}
                <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-700 text-sm mb-1">Belum Ada Pembayaran</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Booking ini masih dalam status menunggu. Wisatawan belum melakukan pembayaran apapun.
                            Arahkan ke loket untuk memilih metode pembayaran.
                        </p>
                    </div>
                </div>

                @elseif($booking->booking_status === 'CANCELLED')
                {{-- CANCELLED --}}
                <div class="bg-rose-50 border border-rose-200 rounded-2xl px-5 py-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-rose-800 text-sm mb-1">Booking Dibatalkan</h3>
                        <p class="text-xs text-rose-700 leading-relaxed">
                            Booking ini telah dibatalkan dan tidak dapat diproses lebih lanjut.
                        </p>
                    </div>
                </div>
                @endif

                {{-- Tombol kembali --}}
                <div class="flex justify-center pt-2">
                    <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke Manajemen Booking
                    </a>
                </div>

            </div>{{-- end max-w-2xl --}}
            @endif

        </main>

</x-admin-layout>

