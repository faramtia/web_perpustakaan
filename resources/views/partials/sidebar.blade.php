@php
    $role = auth()->user()->role ?? 'mahasiswa';
@endphp

<aside
    id="sidebar"
    class="w-64 flex-shrink-0 bg-gray-900 text-white flex flex-col fixed lg:static inset-y-0 left-0 z-20 transform -translate-x-full lg:translate-x-0 transition-transform duration-200"
>
    <div class="px-6 py-5 border-b border-gray-700 flex items-center gap-2">
        <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-8 w-8 object-contain bg-white rounded-full p-0.5">
        <span class="font-bold text-sm leading-tight">Perpustakaan<br>PNL</span>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">
        @if ($role === 'admin')
            @include('partials.menu-admin')
        @elseif ($role === 'petugas')
            @include('partials.menu-petugas')
        @elseif ($role === 'dosen')
            @include('partials.menu-dosen')
        @else
            @include('partials.menu-mahasiswa')
        @endif
    </nav>

    <div class="px-4 py-4 border-t border-gray-700 text-xs text-gray-400">
        Masuk sebagai <span class="font-semibold capitalize text-gold-400">{{ $role }}</span>
    </div>
</aside>
