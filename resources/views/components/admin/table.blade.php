@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden']) }}>
    @if($title || isset($headerActions))
    <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
        <div>
            @if($title)
            <h3 class="text-base font-extrabold text-slate-900 font-display tracking-tight">{{ $title }}</h3>
            @endif
            @if($subtitle)
            <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($headerActions))
        <div class="flex items-center gap-2">
            {{ $headerActions }}
        </div>
        @endif
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs min-w-[680px]">
            @if(isset($head))
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] sm:text-[11px] border-b border-slate-200 select-none">
                {{ $head }}
            </thead>
            @endif
            <tbody class="divide-y divide-slate-100 text-slate-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($footer))
    <div class="p-4 border-t border-slate-100 bg-slate-50/60">
        {{ $footer }}
    </div>
    @endif
</div>
