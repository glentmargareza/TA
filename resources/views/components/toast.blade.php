{{--
    Komponen Toast Pop-up Reusable (Mengadopsi Standar POS-Apotek)
    Menangani session flash ('success', 'error', 'warning', 'info')
    serta menyediakan fungsi global JS window.showToast(message, type, duration)
--}}
<div id="global-toast-container" class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] pointer-events-none flex flex-col items-center gap-3 max-w-[92vw] sm:max-w-md w-full px-4 select-none">
    @php
        $flashTypes = [
            'success' => [
                'icon' => 'check',
                'border' => 'border-emerald-200/90 shadow-emerald-600/15',
                'iconWrapper' => 'bg-emerald-100 text-emerald-700 ring-4 ring-emerald-50',
                'titleColor' => 'text-emerald-800',
                'barColor' => 'bg-emerald-600',
                'title' => 'Berhasil',
            ],
            'error' => [
                'icon' => 'x-mark',
                'border' => 'border-rose-200/90 shadow-rose-600/15',
                'iconWrapper' => 'bg-rose-100 text-rose-700 ring-4 ring-rose-50',
                'titleColor' => 'text-rose-800',
                'barColor' => 'bg-rose-600',
                'title' => 'Gagal',
            ],
            'warning' => [
                'icon' => 'x-mark',
                'border' => 'border-amber-200/90 shadow-amber-600/15',
                'iconWrapper' => 'bg-amber-100 text-amber-700 ring-4 ring-amber-50',
                'titleColor' => 'text-amber-800',
                'barColor' => 'bg-amber-600',
                'title' => 'Perhatian',
            ],
            'info' => [
                'icon' => 'eye',
                'border' => 'border-sky-200/90 shadow-sky-600/15',
                'iconWrapper' => 'bg-sky-100 text-sky-700 ring-4 ring-sky-50',
                'titleColor' => 'text-sky-800',
                'barColor' => 'bg-sky-600',
                'title' => 'Informasi',
            ],
        ];
    @endphp

    @foreach ($flashTypes as $type => $config)
        @if (session($type))
            <div class="toast-card pointer-events-auto w-full bg-white/95 backdrop-blur-md rounded-2xl border shadow-2xl overflow-hidden transform translate-y-0 opacity-100 scale-100 transition-all duration-300 {{ $config['border'] }}"
                 role="status"
                 data-toast-type="{{ $type }}">
                <div class="p-3.5 sm:p-4 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl {{ $config['iconWrapper'] }} flex items-center justify-center shrink-0">
                        <x-icon :name="$config['icon']" class="w-5 h-5" />
                    </div>

                    <div class="flex-1 min-w-0 pt-0.5">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider {{ $config['titleColor'] }}">{{ $config['title'] }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">Baru saja</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-700 leading-snug break-words">
                            {{ session($type) }}
                        </p>
                    </div>

                    <button type="button"
                            onclick="dismissToast(this.closest('.toast-card'))"
                            class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0 -mr-1 -mt-1 cursor-pointer"
                            aria-label="Tutup notifikasi">
                        <x-icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                {{-- Animated Progress Bar Countdown --}}
                <div class="h-1 w-full bg-slate-100 overflow-hidden">
                    <div class="toast-progress-bar h-full {{ $config['barColor'] }}" style="animation: toastProgressBar 3800ms linear forwards;"></div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Fallback jika terdapat validation error redirect back tanpa session('error') --}}
    @if (!empty($errors) && $errors->any() && !session('error'))
        <div class="toast-card pointer-events-auto w-full bg-white/95 backdrop-blur-md rounded-2xl border shadow-2xl overflow-hidden transform translate-y-0 opacity-100 scale-100 transition-all duration-300 {{ $flashTypes['error']['border'] }}"
             role="status"
             data-toast-type="error">
            <div class="p-3.5 sm:p-4 flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl {{ $flashTypes['error']['iconWrapper'] }} flex items-center justify-center shrink-0">
                    <x-icon :name="$flashTypes['error']['icon']" class="w-5 h-5" />
                </div>

                <div class="flex-1 min-w-0 pt-0.5">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider {{ $flashTypes['error']['titleColor'] }}">Gagal Validasi</span>
                        <span class="text-[10px] text-slate-400 font-medium">Baru saja</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-snug break-words">
                        {{ $errors->first() }}
                    </p>
                </div>

                <button type="button"
                        onclick="dismissToast(this.closest('.toast-card'))"
                        class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0 -mr-1 -mt-1 cursor-pointer"
                        aria-label="Tutup notifikasi">
                    <x-icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>

            {{-- Animated Progress Bar Countdown --}}
            <div class="h-1 w-full bg-slate-100 overflow-hidden">
                <div class="toast-progress-bar h-full {{ $flashTypes['error']['barColor'] }}" style="animation: toastProgressBar 3800ms linear forwards;"></div>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes toastProgressBar {
        from { width: 100%; }
        to { width: 0%; }
    }
    @keyframes toastSpringIn {
        0% { transform: translateY(-26px) scale(0.92); opacity: 0; }
        65% { transform: translateY(4px) scale(1.015); opacity: 1; }
        100% { transform: translateY(0) scale(1); opacity: 1; }
    }
    .toast-card {
        animation: toastSpringIn 420ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transition: all 350ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .toast-card:hover .toast-progress-bar {
        animation-play-state: paused !important;
    }
</style>

<script>
    function dismissToast(toastEl) {
        if (!toastEl) return;
        toastEl.classList.remove('translate-y-0', 'opacity-100', 'scale-100');
        toastEl.classList.add('-translate-y-4', 'opacity-0', 'scale-95');
        setTimeout(() => {
            if (toastEl && toastEl.parentElement) toastEl.remove();
        }, 320);
    }

    // Auto-dismiss setelah 3.8 detik
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toast-card').forEach(function (card) {
            let timeoutId = setTimeout(function () { dismissToast(card); }, 3800);
            card.addEventListener('mouseenter', () => clearTimeout(timeoutId));
            card.addEventListener('mouseleave', () => {
                timeoutId = setTimeout(() => dismissToast(card), 1500);
            });
        });
    });

    // Helper JS Global: window.showToast("Pesan", "success", 3800)
    window.showToast = function (message, type = 'success', duration = 3800) {
        const container = document.getElementById('global-toast-container');
        if (!container) return;

        const typeConfigs = {
            success: {
                border: 'border-emerald-200/90 shadow-emerald-600/15',
                iconWrapper: 'bg-emerald-100 text-emerald-700 ring-4 ring-emerald-50',
                titleColor: 'text-emerald-800',
                barColor: 'bg-emerald-600',
                title: 'Berhasil',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>'
            },
            error: {
                border: 'border-rose-200/90 shadow-rose-600/15',
                iconWrapper: 'bg-rose-100 text-rose-700 ring-4 ring-rose-50',
                titleColor: 'text-rose-800',
                barColor: 'bg-rose-600',
                title: 'Gagal',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
            },
            warning: {
                border: 'border-amber-200/90 shadow-amber-600/15',
                iconWrapper: 'bg-amber-100 text-amber-700 ring-4 ring-amber-50',
                titleColor: 'text-amber-800',
                barColor: 'bg-amber-600',
                title: 'Perhatian',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
            },
            info: {
                border: 'border-sky-200/90 shadow-sky-600/15',
                iconWrapper: 'bg-sky-100 text-sky-700 ring-4 ring-sky-50',
                titleColor: 'text-sky-800',
                barColor: 'bg-sky-600',
                title: 'Informasi',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            }
        };

        const cfg = typeConfigs[type] || typeConfigs.info;
        const card = document.createElement('div');
        card.className = `toast-card pointer-events-auto w-full bg-white/95 backdrop-blur-md rounded-2xl border shadow-2xl overflow-hidden transform translate-y-0 opacity-100 scale-100 transition-all duration-300 ${cfg.border}`;
        card.setAttribute('role', 'status');

        card.innerHTML = `
            <div class="p-3.5 sm:p-4 flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl ${cfg.iconWrapper} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">${cfg.iconSvg}</svg>
                </div>
                <div class="flex-1 min-w-0 pt-0.5">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider ${cfg.titleColor}">${cfg.title}</span>
                        <span class="text-[10px] text-slate-400 font-medium">Baru saja</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-snug break-words">${message}</p>
                </div>
                <button type="button" onclick="dismissToast(this.closest('.toast-card'))" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0 -mr-1 -mt-1 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="h-1 w-full bg-slate-100 overflow-hidden">
                <div class="toast-progress-bar h-full ${cfg.barColor}" style="animation: toastProgressBar ${duration}ms linear forwards;"></div>
            </div>
        `;

        container.appendChild(card);

        let timeoutId = setTimeout(() => dismissToast(card), duration);
        card.addEventListener('mouseenter', () => clearTimeout(timeoutId));
        card.addEventListener('mouseleave', () => {
            timeoutId = setTimeout(() => dismissToast(card), 1500);
        });
    };
</script>
