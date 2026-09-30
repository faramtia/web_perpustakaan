@props(['routeName', 'label', 'icon' => '📄'])

@php
    $url = \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : '#';
    $isActive = request()->routeIs($routeName);
@endphp

<a
    href="{{ $url }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg transition
        {{ $isActive ? 'bg-gold-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}"
>
    <span class="w-5 text-center">{{ $icon }}</span>
    <span>{{ $label }}</span>
</a>
