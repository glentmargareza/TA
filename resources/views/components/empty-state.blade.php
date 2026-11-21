@props([
    'title' => 'Tidak Ada Data Ditemukan',
    'description' => 'Belum ada data yang tercatat atau cocok dengan kriteria filter.',
    'icon' => 'search',
])

<div {{ $attributes->merge(['class' => 'p-8 sm:p-12 text-center flex flex-col items-center justify-center']) }}>
    <div class="w-14 h-14 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3 shadow-inner">
        <x-icon :name="$icon" class="w-7 h-7" />
    </div>
    <h4 class="text-sm sm:text-base font-bold text-slate-700 font-display">{{ $title }}</h4>
    <p class="text-xs text-slate-400 max-w-sm mt-1 leading-relaxed">{{ $description }}</p>
    @if(isset($action))
    <div class="mt-4">
        {{ $action }}
    </div>
    @endif
</div>
