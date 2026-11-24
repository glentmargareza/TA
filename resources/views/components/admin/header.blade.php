@props([
    'title',
    'subtitle' => null,
    'badge' => null,
])

<header {{ $attributes->merge(['class' => 'bg-white border-b border-slate-200 px-4 sm:px-8 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20 shadow-2xs']) }}>
    <div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-display">
                {{ $title }}
            </h1>
            @if($badge)
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase tracking-wide">
                {{ $badge }}
            </span>
            @endif
        </div>
        @if($subtitle)
        <p class="text-xs text-slate-500 mt-0.5">
            {{ $subtitle }}
        </p>
        @endif
    </div>

    @if(isset($actions))
    <div class="flex items-center gap-2.5 flex-wrap">
        {{ $actions }}
    </div>
    @endif
</header>
