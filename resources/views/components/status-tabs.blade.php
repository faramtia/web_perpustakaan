{{-- Filter status berbentuk tab; mempertahankan parameter query lain. --}}
@props(['options', 'param' => 'status'])

@php $aktif = request($param, ''); @endphp

<div class="mb-4 flex flex-wrap gap-2">
    @foreach (['' => 'Semua'] + $options as $nilai => $label)
        <a href="{{ request()->fullUrlWithQuery([$param => $nilai === '' ? null : $nilai, 'page' => null]) }}"
           class="rounded-full px-4 py-1.5 text-xs font-semibold transition
                  {{ (string) $aktif === (string) $nilai ? 'bg-stone-900 text-white' : 'bg-white text-stone-600 ring-1 ring-stone-200 hover:bg-yellow-50' }}">
            {{ $label }}
        </a>
    @endforeach
</div>
