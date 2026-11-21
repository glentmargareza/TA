@props([
    'type' => 'success', // 'success' | 'danger' | 'error' | 'warning' | 'info'
    'message' => null,
    'dismissible' => true,
])

@php
    $normalizedType = match ($type) {
        'error', 'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        default => 'success',
    };

    $themes = [
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-900', 'icon' => 'check', 'iconColor' => 'text-emerald-600'],
        'danger'  => ['bg' => 'bg-rose-50 border-rose-200 text-rose-900', 'icon' => 'x-mark', 'iconColor' => 'text-rose-600'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200 text-amber-900', 'icon' => 'x-mark', 'iconColor' => 'text-amber-600'],
        'info'    => ['bg' => 'bg-sky-50 border-sky-200 text-sky-900', 'icon' => 'eye', 'iconColor' => 'text-sky-600'],
    ];

    $t = $themes[$normalizedType];
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-2xl border text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs ' . $t['bg']]) }}>
    <div class="flex items-center gap-2.5">
        <span class="w-6 h-6 rounded-full bg-white/80 flex items-center justify-center shrink-0">
            <x-icon :name="$t['icon']" class="w-3.5 h-3.5 {{ $t['iconColor'] }}" />
        </span>
        <div>
            @if($message)
                <span>{{ $message }}</span>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
    @if($dismissible)
    <button type="button" 
            onclick="this.closest('[class*=\'rounded-2xl\']').remove()" 
            class="text-current opacity-70 hover:opacity-100 font-bold text-base cursor-pointer px-1 transition-opacity" 
            aria-label="Tutup pesan">&times;</button>
    @endif
</div>
