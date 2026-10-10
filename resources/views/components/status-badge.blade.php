@props(['status'])

@php
    // Status di database ditulis kapital (Dipinjam, Menunggu, dst.), jadi dicocokkan huruf kecil.
    $key = strtolower(trim((string) $status));

    $colors = [
        // Peminjaman
        'menunggu'      => 'bg-yellow-100 text-yellow-700',
        'dipinjam'      => 'bg-blue-100 text-blue-700',
        'dikembalikan'  => 'bg-green-100 text-green-700',
        'terlambat'     => 'bg-red-100 text-red-700',
        'ditolak'       => 'bg-red-100 text-red-700',
        // Reservasi
        'diproses'      => 'bg-blue-100 text-blue-700',
        'selesai'       => 'bg-green-100 text-green-700',
        'dibatalkan'    => 'bg-gray-100 text-gray-600',
        // Tugas akhir
        'disetujui'     => 'bg-green-100 text-green-700',
        // Feedback
        'belum dibalas' => 'bg-yellow-100 text-yellow-700',
        'sudah dibalas' => 'bg-green-100 text-green-700',
        // Event
        'terdaftar'     => 'bg-green-100 text-green-700',
        // Denda
        'belum lunas'   => 'bg-red-100 text-red-700',
        'lunas'         => 'bg-green-100 text-green-700',
    ];

    $class = $colors[$key] ?? 'bg-gray-100 text-gray-600';
@endphp

<span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium capitalize {{ $class }}">
    {{ str_replace('_', ' ', $status) }}
</span>
