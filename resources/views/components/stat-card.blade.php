@props([
    'label',
    'value',
    'subtext' => null,
    'unit' => null,
    'trend' => null, // e.g. ['type' => 'up', 'value' => '+15%'] or number
    'icon' => null,  // icon name string or slot
    'iconWrapper' => 'bg-emerald-50 text-emerald-800',
])

<div {{ $attributes->merge(['class' => 'bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex items-center justify-between min-w-0 transition-all hover:shadow-sm']) }}>
    <div class="min-w-0 flex-1">
        <span class="text-[11px] sm:text-xs font-semibold text-slate-500 block truncate">{{ $label }}</span>
        <div class="flex items-baseline gap-1 mt-0.5 sm:mt-1">
            <span class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight font-display">
                {{ $value }}
            </span>
            @if($unit)
            <span class="text-[10px] sm:text-xs font-normal text-slate-400">{{ $unit }}</span>
            @endif
        </div>

        @if($subtext)
        <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-slate-600 font-medium mt-1 truncate">
            {{ $subtext }}
        </span>
        @endif
    </div>

    @if($icon || isset($iconSlot))
    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl {{ $iconWrapper }} flex items-center justify-center shrink-0 ml-3 shadow-2xs">
        @if($icon)
            <x-icon :name="$icon" class="w-5 h-5 sm:w-6 sm:h-6" />
        @else
            {{ $iconSlot }}
        @endif
    </div>
    @endif
</div>
