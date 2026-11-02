@props(['status'])

@php
    // Status di database ditulis kapital (Dipinjam, Menunggu, dst.), jadi dicocokkan huruf kecil.
    $key = strtolower(trim((string) $status));

    $colors = [
        // Peminjaman
        'menunggu'      => 'bg-yellow-100 text-yellow-700',
        'dipinjam'      => 'bg-blue-100 text-blue-700',
        'dikembalikan'  => 'bg-emerald-100 text-emerald-700',
        'terlambat'     => 'bg-red-100 text-red-700',
        'ditolak'       => 'bg-red-100 text-red-700',
        // Reservasi
        'diproses'      => 'bg-blue-100 text-blue-700',
        'selesai'       => 'bg-emerald-100 text-emerald-700',
        'dibatalkan'    => 'bg-stone-100 text-stone-600',
        // Tugas akhir
        'disetujui'     => 'bg-emerald-100 text-emerald-700',
        // Feedback
        'belum dibalas' => 'bg-yellow-100 text-yellow-700',
        'sudah dibalas' => 'bg-emerald-100 text-emerald-700',
        // Event
        'terdaftar'     => 'bg-emerald-100 text-emerald-700',
        'hadir'         => 'bg-blue-100 text-blue-700',
        // Denda
        'belum lunas'   => 'bg-red-100 text-red-700',
        'lunas'         => 'bg-emerald-100 text-emerald-700',
    ];

    $class = $colors[$key] ?? 'bg-stone-100 text-stone-600';
@endphp

<span class="inline-block whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold {{ $class }}">
    {{ str_replace('_', ' ', $status) }}
</span>
