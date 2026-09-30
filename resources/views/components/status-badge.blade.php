@props(['status'])

@php
    $colors = [
        'pending'       => 'bg-yellow-100 text-yellow-700',
        'aktif'         => 'bg-blue-100 text-blue-700',
        'ditolak'       => 'bg-red-100 text-red-700',
        'dikembalikan'  => 'bg-green-100 text-green-700',
        'terlambat'     => 'bg-red-100 text-red-700',
        'menunggu'      => 'bg-yellow-100 text-yellow-700',
        'tersedia'      => 'bg-blue-100 text-blue-700',
        'dibatalkan'    => 'bg-gray-100 text-gray-600',
        'baru'          => 'bg-yellow-100 text-yellow-700',
        'diproses'      => 'bg-blue-100 text-blue-700',
        'selesai'       => 'bg-green-100 text-green-700',
        'diterima'      => 'bg-green-100 text-green-700',
        'hadir'         => 'bg-green-100 text-green-700',
        'diajukan'      => 'bg-yellow-100 text-yellow-700',
        'disetujui'     => 'bg-green-100 text-green-700',
        'belum'         => 'bg-gray-100 text-gray-600',
        'sudah'         => 'bg-green-100 text-green-700',
    ];

    $class = $colors[$status] ?? 'bg-gray-100 text-gray-600';
@endphp

<span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium capitalize {{ $class }}">
    {{ str_replace('_', ' ', $status) }}
</span>
