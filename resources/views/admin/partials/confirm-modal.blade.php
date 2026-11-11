<!-- Custom Global Confirmation Modal Component -->
<div id="globalCustomConfirmModal" class="fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden transition-opacity duration-200 select-none">
    <!-- Modal Card -->
    <div id="globalCustomConfirmCard" class="relative bg-white rounded-3xl max-w-sm sm:max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 transform transition-all duration-200 scale-95 opacity-0 text-center">
        
        <!-- Close Button (X) -->
        <button type="button" onclick="closeConfirmModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer" title="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Dynamic Icon Container -->
        <div id="confirmIconWrapper" class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xs border">
            <!-- Icon Success (Emerald) -->
            <div id="confirmIconSuccess" class="hidden text-emerald-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <!-- Icon Danger (Rose/Red) -->
            <div id="confirmIconDanger" class="hidden text-rose-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <!-- Icon Warning (Amber) -->
            <div id="confirmIconWarning" class="hidden text-amber-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <!-- Icon Info (Blue) -->
            <div id="confirmIconInfo" class="hidden text-blue-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h3 id="confirmModalTitle" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight mb-2">
            Konfirmasi Tindakan
        </h3>

        <!-- Description Message -->
        <div id="confirmModalMessage" class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal mb-6 px-1">
            Apakah Anda yakin ingin melanjutkan tindakan ini?
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <button type="button" id="btnConfirmCancel" onclick="closeConfirmModal()" class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs sm:text-sm transition-all cursor-pointer shadow-2xs">
                Batal
            </button>
            <button type="button" id="btnConfirmSubmit" class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm text-white transition-all cursor-pointer shadow-sm hover:shadow">
                Ya, Konfirmasi
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        let currentCallback = null;
        let currentForm = null;

        // Make sure modal is attached to document.body so no parent CSS overflow/stacking context interferes
        function ensureModalInBody() {
            const modal = document.getElementById('globalCustomConfirmModal');
            if (modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        }

        window.showConfirmModal = function (options) {
            ensureModalInBody();

            const modal = document.getElementById('globalCustomConfirmModal');
            const card = document.getElementById('globalCustomConfirmCard');
            const titleEl = document.getElementById('confirmModalTitle');
            const msgEl = document.getElementById('confirmModalMessage');
            const btnSubmit = document.getElementById('btnConfirmSubmit');
            const btnCancel = document.getElementById('btnConfirmCancel');
            const iconWrapper = document.getElementById('confirmIconWrapper');

            if (!modal) return;

            // Set content
            const title = options.title || 'Konfirmasi Tindakan';
            const message = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            const confirmText = options.confirmText || 'Ya, Lanjutkan';
            const cancelText = options.cancelText || 'Batal';
            const type = options.type || 'warning'; // 'success' | 'danger' | 'warning' | 'info'

            titleEl.textContent = title;
            msgEl.innerHTML = message;
            btnSubmit.textContent = confirmText;
            btnCancel.textContent = cancelText;

            // Store actions
            currentCallback = typeof options.onConfirm === 'function' ? options.onConfirm : null;
            currentForm = options.form || null;

            // Hide all icons
            ['confirmIconSuccess', 'confirmIconDanger', 'confirmIconWarning', 'confirmIconInfo'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });

            // Reset wrapper and submit button classes
            iconWrapper.className = 'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xs border ';
            btnSubmit.className = 'flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm text-white transition-all cursor-pointer shadow-sm hover:shadow ';

            if (type === 'success') {
                document.getElementById('confirmIconSuccess').classList.remove('hidden');
                iconWrapper.classList.add('bg-emerald-50', 'border-emerald-200');
                btnSubmit.classList.add('bg-emerald-700', 'hover:bg-emerald-800');
            } else if (type === 'danger') {
                document.getElementById('confirmIconDanger').classList.remove('hidden');
                iconWrapper.classList.add('bg-rose-50', 'border-rose-200');
                btnSubmit.classList.add('bg-rose-600', 'hover:bg-rose-700');
            } else if (type === 'info') {
                document.getElementById('confirmIconInfo').classList.remove('hidden');
                iconWrapper.classList.add('bg-blue-50', 'border-blue-200');
                btnSubmit.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else { // warning
                document.getElementById('confirmIconWarning').classList.remove('hidden');
                iconWrapper.classList.add('bg-amber-50', 'border-amber-200');
                btnSubmit.classList.add('bg-amber-600', 'hover:bg-amber-700');
            }

            // Show modal with animation
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        };

        window.closeConfirmModal = function () {
            const modal = document.getElementById('globalCustomConfirmModal');
            const card = document.getElementById('globalCustomConfirmCard');
            if (!modal) return;

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                currentCallback = null;
                currentForm = null;
            }, 150);
        };

        // Submit action trigger
        document.addEventListener('DOMContentLoaded', function () {
            ensureModalInBody();

            const modal = document.getElementById('globalCustomConfirmModal');
            const btnSubmit = document.getElementById('btnConfirmSubmit');

            if (btnSubmit) {
                btnSubmit.addEventListener('click', function () {
                    const callback = currentCallback;
                    const form = currentForm;
                    closeConfirmModal();

                    if (callback) {
                        callback();
                    } else if (form) {
                        form._confirmed = true;
                        form.submit();
                    }
                });
            }

            // Close when clicking modal backdrop
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        closeConfirmModal();
                    }
                });
            }

            // Keyboard shortcut support (Esc = cancel, Enter = confirm)
            document.addEventListener('keydown', function (e) {
                if (modal && !modal.classList.contains('hidden')) {
                    if (e.key === 'Escape') {
                        closeConfirmModal();
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        btnSubmit.click();
                    }
                }
            });

            // Automatic Form Interceptor:
            // Intercepts any form with data-confirm-message OR legacy onsubmit="return confirm(...)"
            function setupConfirmInterceptors() {
                document.querySelectorAll('form').forEach(form => {
                    const onsubmitAttr = form.getAttribute('onsubmit');
                    if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                        const match = onsubmitAttr.match(/confirm\(\s*(['"`])(.*?)\1\s*\)/s);
                        const confirmMsg = match ? match[2].replace(/\\'/g, "'").replace(/\\"/g, '"') : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

                        form.removeAttribute('onsubmit');
                        form.onsubmit = null;

                        if (!form.dataset.confirmMessage) {
                            form.dataset.confirmMessage = confirmMsg;
                        }
                    }

                    // Attach listener if form has data-confirm or data-confirm-message
                    if (!form._confirmBound && (form.dataset.confirmMessage || form.dataset.confirm)) {
                        form._confirmBound = true;
                        form.addEventListener('submit', function (e) {
                            if (form._confirmed) return; // Allow confirmed submission

                            e.preventDefault();

                            const msg = form.dataset.confirmMessage || form.dataset.confirm;
                            const title = form.dataset.confirmTitle || 'Konfirmasi Tindakan';
                            
                            // Auto detect type if not explicitly given
                            let type = form.dataset.confirmType;
                            if (!type) {
                                const lower = msg.toLowerCase();
                                if (lower.includes('batal') || lower.includes('hapus') || lower.includes('delete') || lower.includes('cancel')) {
                                    type = 'danger';
                                } else if (lower.includes('lunas') || lower.includes('aktif') || lower.includes('selesai') || lower.includes('settle')) {
                                    type = 'success';
                                } else {
                                    type = 'warning';
                                }
                            }

                            const confirmText = form.dataset.confirmText || (type === 'danger' ? 'Ya, Lanjutkan' : (type === 'success' ? 'Ya, Konfirmasi' : 'Lanjutkan'));
                            const cancelText = form.dataset.confirmCancel || 'Batal';

                            showConfirmModal({
                                title: title,
                                message: msg,
                                type: type,
                                confirmText: confirmText,
                                cancelText: cancelText,
                                form: form
                            });
                        });
                    }
                });
            }

            setupConfirmInterceptors();
            // Re-run interceptor after a short tick in case dynamic elements exist
            setTimeout(setupConfirmInterceptors, 500);
        });
    })();
</script>

