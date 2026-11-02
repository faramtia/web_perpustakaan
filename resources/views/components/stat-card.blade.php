@props(['label', 'value', 'hint' => null, 'icon' => 'bi-bar-chart', 'tone' => 'yellow', 'href' => null])

@php
    $tones = [
        'yellow' => 'bg-yellow-100 text-yellow-700',
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-emerald-100 text-emerald-600',
        'red' => 'bg-red-100 text-red-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'stone' => 'bg-stone-100 text-stone-600',
    ];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif class="card flex items-center gap-4 p-5 {{ $href ? 'transition hover:ring-yellow-400' : '' }}">
    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl text-xl {{ $tones[$tone] ?? $tones['yellow'] }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-wide text-stone-400">{{ $label }}</p>
        <p class="truncate text-2xl font-extrabold text-stone-900">{{ $value }}</p>
        @if ($hint)
            <p class="text-xs text-stone-400">{{ $hint }}</p>
        @endif
    </div>
</{{ $tag }}>
