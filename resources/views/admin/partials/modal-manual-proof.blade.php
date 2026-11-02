<!-- MODAL VERIFIKASI BUKTI TRANSFER MANUAL BANK BPD DIY -->
<div id="modalManualProof" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-200 my-8">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Verifikasi Bukti Transfer BPD DIY</h3>
                    <span id="modalBookingCode" class="text-xs font-mono font-semibold text-emerald-800">WKR-XXXXXX</span>
                </div>
            </div>
            <button type="button" onclick="closeProofModal()" class="w-8 h-8 rounded-full bg-slate-200/70 hover:bg-slate-300 text-slate-600 flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto text-xs">
            
            <!-- Rincian Setoran -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-2 gap-3">
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Pemesan:</span>
                    <span id="modalCustomerName" class="font-bold text-slate-800 text-sm">--</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Nominal Ditransfer:</span>
                    <span id="modalGrossAmount" class="font-mono font-extrabold text-emerald-800 text-sm">Rp --</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Atas Nama Rekening Pengirim:</span>
                    <span id="modalSenderName" class="font-semibold text-slate-700">--</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Bank Asal Pengirim:</span>
                    <span id="modalSenderBank" class="font-semibold text-slate-700">--</span>
                </div>
            </div>

            <!-- Tampilan Berkas Bukti Transfer -->
            <div>
                <span class="text-xs font-bold text-slate-700 block mb-2 flex items-center justify-between">
                    <span>Foto / Dokumen Struk Transfer:</span>
                    <a id="modalProofFullLink" href="#" target="_blank" class="text-emerald-700 hover:text-emerald-800 font-semibold underline text-[11px] flex items-center gap-1">
                        <span>Buka Ukuran Penuh</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </span>
                
                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-900/5 text-center p-2 flex items-center justify-center min-h-[220px]">
                    <img id="modalProofImage" src="" alt="Bukti Transfer" class="max-h-72 w-auto object-contain rounded-xl mx-auto shadow-2xs">
                    <div id="modalPdfNotice" class="hidden p-6 text-center">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-xs text-slate-600 block font-semibold">Dokumen PDF Terlampir</span>
                        <a id="modalPdfLink" href="#" target="_blank" class="mt-2 inline-block px-4 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold">
                            Unduh / Buka Dokumen PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Aksi Persetujuan (SETUJUI) -->
            <div class="pt-2 border-t border-slate-200">
                <form id="formVerifyPayment" action="#" method="POST" onsubmit="return confirm('Konfirmasi persetujuan pembayaran ini? Tindakan ini akan menerbitkan E-Tiket resmi bagi wisatawan.')">
                    @csrf
                    <button type="submit" class="w-full py-3 px-5 rounded-2xl bg-gradient-to-r from-[#1E3F20] via-emerald-800 to-teal-800 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-950/20 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Setujui Pembayaran & Terbitkan Tiket</span>
                    </button>
                </form>
            </div>

            <!-- Form Aksi Penolakan (TOLAK) -->
            <div class="pt-3 border-t border-slate-100">
                <details class="group">
                    <summary class="text-xs font-bold text-rose-700 hover:text-rose-800 cursor-pointer flex items-center justify-between py-1">
                        <span>Opsi Penolakan: Tolak Bukti Transfer Ini</span>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <form id="formRejectPayment" action="#" method="POST" class="mt-3 space-y-2.5">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                                Alasan Penolakan (Akan Dibaca oleh Wisatawan):
                            </label>
                            <textarea name="rejection_reason" required rows="2" placeholder="Contoh: Nominal transfer kurang Rp 50.000, atau foto buram tidak terbaca." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-rose-500 bg-white shadow-2xs"></textarea>
                        </div>
                        <button type="submit" onclick="return confirm('Konfirmasi tolak bukti transfer ini? Wisatawan akan diminta mengunggah ulang.')" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-colors cursor-pointer">
                            Tolak Bukti Transfer
                        </button>
                    </form>
                </details>
            </div>

        </div>

    </div>
</div>

<script>
    function openProofModal(bookingId, bookingCode, customerName, senderName, senderBank, grossAmount, proofUrl, verifyUrl, rejectUrl) {
        document.getElementById('modalBookingCode').innerText = bookingCode;
        document.getElementById('modalCustomerName').innerText = customerName;
        document.getElementById('modalSenderName').innerText = senderName;
        document.getElementById('modalSenderBank').innerText = senderBank;
        document.getElementById('modalGrossAmount').innerText = 'Rp ' + grossAmount;

        const imgEl = document.getElementById('modalProofImage');
        const pdfNotice = document.getElementById('modalPdfNotice');
        const pdfLink = document.getElementById('modalPdfLink');
        const fullLink = document.getElementById('modalProofFullLink');

        fullLink.href = proofUrl;

        if (proofUrl.endsWith('.pdf')) {
            imgEl.classList.add('hidden');
            pdfNotice.classList.remove('hidden');
            pdfLink.href = proofUrl;
        } else {
            pdfNotice.classList.add('hidden');
            imgEl.classList.remove('hidden');
            imgEl.src = proofUrl;
        }

        document.getElementById('formVerifyPayment').action = verifyUrl;
        document.getElementById('formRejectPayment').action = rejectUrl;

        document.getElementById('modalManualProof').classList.remove('hidden');
    }

    function closeProofModal() {
        document.getElementById('modalManualProof').classList.add('hidden');
    }
</script>

