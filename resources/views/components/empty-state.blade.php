@props(['icon' => 'bi-inbox', 'title' => 'Belum ada data', 'text' => null, 'cols' => null])

@php
    $isi = '<div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-stone-100 text-2xl text-stone-400"><i class="bi '.e($icon).'"></i></div>'
        .'<p class="font-semibold text-stone-700">'.e($title).'</p>'
        .($text ? '<p class="text-sm text-stone-400">'.e($text).'</p>' : '');
@endphp

@if ($cols)
    <tr><td colspan="{{ $cols }}" class="px-6 py-14 text-center">{!! $isi !!}</td></tr>
@else
    <div class="card px-6 py-14 text-center">{!! $isi !!}</div>
@endif
