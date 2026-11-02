{{-- Avatar inisial + nama + keterangan kecil (role / NIM). --}}
@props(['user', 'sub' => null])

@php
    $nama = $user->nama ?? '-';
    $inisial = strtoupper(mb_substr(trim($nama), 0, 2));
    $keterangan = $sub ?? ucfirst((string) ($user->role ?? ''));
@endphp

<div class="flex items-center gap-3">
    <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-yellow-100 text-xs font-bold text-yellow-800">{{ $inisial }}</div>
    <div class="min-w-0 leading-tight">
        <p class="truncate font-semibold text-stone-800">{{ $nama }}</p>
        @if ($keterangan)
            <p class="truncate text-xs text-stone-400">{{ $keterangan }}</p>
        @endif
    </div>
</div>
