{{-- Head bersama untuk semua layout: font, Tailwind (CDN), ikon, dan kelas komponen UI. --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('favicon.ico') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } }
    }
</script>

<style type="text/tailwindcss">
    @layer components {
        /* Satu set kelas untuk semua role supaya tampilan seragam. */
        .card      { @apply bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm; }
        .btn       { @apply inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition disabled:opacity-50; }
        .btn-sm    { @apply rounded-lg px-3 py-1.5 text-xs; }
        .btn-primary { @apply bg-yellow-400 text-stone-900 shadow-sm hover:bg-yellow-500; }
        .btn-dark    { @apply bg-stone-900 text-white hover:bg-stone-700; }
        .btn-soft    { @apply bg-stone-100 text-stone-700 hover:bg-stone-200; }
        .btn-success { @apply bg-emerald-600 text-white hover:bg-emerald-700; }
        .btn-danger  { @apply bg-red-50 text-red-600 hover:bg-red-100; }
        .input     { @apply w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder-stone-400 focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-200; }
        .label     { @apply mb-1 block text-sm font-medium text-stone-600; }
        .hint      { @apply mt-1 text-xs text-stone-400; }
        .table-wrap { @apply card overflow-hidden overflow-x-auto; }
        .tbl       { @apply w-full text-sm; }
        .tbl thead { @apply border-b border-stone-200 bg-stone-50/60 text-left text-stone-500; }
        .tbl th    { @apply px-5 py-3.5 font-semibold whitespace-nowrap; }
        .tbl td    { @apply px-5 py-3.5 align-middle; }
        .tbl tbody { @apply divide-y divide-stone-100; }
        .tbl tbody tr { @apply hover:bg-yellow-50/40; }
        .chip      { @apply inline-block rounded-md px-2.5 py-1 text-xs font-semibold; }
    }
</style>
<style>
    body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
    @media print { body { background: #fff !important; } }
</style>
@stack('head')
