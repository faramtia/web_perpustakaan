@extends('layouts.app')
@section('title', 'Feedback & Tanya Pustakawan')
@section('page-title', 'Feedback & Tanya Pustakawan')

@section('content')

    @php $role = auth()->user()->role; @endphp

    @if (in_array($role, ['mahasiswa', 'dosen']))
        <div class="bg-white border rounded-xl p-5 mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Kirim Pesan Baru</h3>
            <form method="POST" action="{{ route('anggota.feedback.store') }}" class="space-y-3">
                @csrf
                <select name="jenis" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <option value="tanya_pustakawan">Tanya Pustakawan</option>
                    <option value="saran">Saran</option>
                    <option value="keluhan">Keluhan</option>
                </select>
                <textarea name="isi" rows="3" required placeholder="Tulis pesan kamu..."
                          class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400"></textarea>
                <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Kirim</button>
            </form>
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($feedback as $f)
            <div class="bg-white border rounded-xl p-5">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        @if (in_array($role, ['admin','petugas']))
                            <span class="font-medium text-gray-700">{{ $f->user->name }}</span>
                        @endif
                        <span class="text-xs text-gray-400 capitalize ml-1">({{ str_replace('_',' ',$f->jenis) }})</span>
                    </div>
                    <x-status-badge :status="$f->status" />
                </div>
                <p class="text-sm text-gray-600 mb-3">{{ $f->isi }}</p>

                @if ($f->balasan)
                    <div class="bg-gold-50 border border-gold-100 rounded-lg p-3 text-sm text-gray-700">
                        <span class="font-medium text-gold-700">Balasan pustakawan:</span> {{ $f->balasan }}
                    </div>
                @elseif (in_array($role, ['admin','petugas']))
                    <form method="POST" action="{{ route('petugas.feedback.balas', $f) }}" class="flex gap-2 mt-2">
                        @csrf
                        <input type="text" name="balasan" required placeholder="Tulis balasan..."
                               class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                        <button class="bg-gray-800 hover:bg-gray-900 text-white text-xs px-4 py-2 rounded-lg">Balas</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-400 italic text-center py-10">Belum ada pesan.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $feedback->links() }}</div>

@endsection
