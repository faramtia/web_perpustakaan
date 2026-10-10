@extends('layouts.app')
@section('title', 'Feedback & Tanya Pustakawan')
@section('page-title', 'Feedback & Tanya Pustakawan')

@section('content')
    @php
        $userRole = auth()->user()->role ?? null;
        $isPetugas = in_array($userRole, ['admin', 'petugas'], true);
    @endphp

    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl font-bold shadow-sm">
            <i class="bi bi-chat-left-dots-fill"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-stone-900">Feedback & Tanya Pustakawan</h1>
            <p class="text-sm text-stone-500">Baca masukan dari anggota dan berikan balasan secara langsung.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-2xl px-5 py-3.5 mb-6 text-sm flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        @forelse ($feedback as $item)
            @php
                $statusLabel = $item->status === 'selesai' ? 'Sudah Dibalas' : 'Belum Dibalas';
                $badgeClass = $item->status === 'selesai'
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                    : 'bg-stone-100 text-stone-600 border border-stone-200';
            @endphp

            <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-yellow-100 text-yellow-800 grid place-items-center font-bold text-xs">
                            {{ strtoupper(substr(($item->user->name ?? 'U'), 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-stone-900">{{ $item->user->name ?? 'User' }}</h3>
                            <p class="text-[11px] text-stone-400">{{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold {{ $badgeClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="space-y-2">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-stone-500">{{ str_replace('_', ' ', $item->jenis) }}</p>
                    <p class="text-xs font-medium text-stone-700 bg-stone-50 p-4 rounded-2xl">
                        {{ $item->isi }}
                    </p>
                </div>

                @if ($item->balasan)
                    <div class="bg-yellow-50/80 border border-yellow-200/70 p-4 rounded-2xl text-xs space-y-1">
                        <p class="font-bold text-yellow-900"><i class="bi bi-reply-fill"></i> Balasan pustakawan:</p>
                        <p class="text-stone-800">{{ $item->balasan }}</p>
                    </div>
                @endif

                @if ($isPetugas)
                    <form method="POST" action="{{ route('petugas.feedback.balas', $item) }}" class="space-y-3 pt-2">
                        @csrf
                        <textarea name="balasan" rows="2" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs font-medium text-stone-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition">{{ old('balasan', $item->balasan) }}</textarea>

                        <button type="submit" class="px-5 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-stone-900 font-extrabold rounded-xl text-xs transition shadow-sm flex items-center gap-2">
                            <i class="bi bi-{{ $item->balasan ? 'arrow-repeat' : 'send-fill' }}"></i>
                            {{ $item->balasan ? 'Perbarui Balasan' : 'Kirim Balasan' }}
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm text-sm text-stone-500">
                Belum ada feedback yang masuk.
            </div>
        @endforelse
    </div>
@endsection