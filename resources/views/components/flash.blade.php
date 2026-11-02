@props(['showErrors' => true])

{{-- Pesan sukses / error / validasi, seragam di semua halaman. --}}
@if (session('success'))
    <div class="mb-5 flex items-start gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
        <i class="bi bi-check-circle-fill mt-0.5"></i><span>{{ session('success') }}</span>
    </div>
@endif
@if (session('status'))
    <div class="mb-5 flex items-start gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
        <i class="bi bi-check-circle-fill mt-0.5"></i><span>{{ session('status') }}</span>
    </div>
@endif
@if (session('error'))
    <div class="mb-5 flex items-start gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
        <i class="bi bi-exclamation-circle-fill mt-0.5"></i><span>{{ session('error') }}</span>
    </div>
@endif
@if ($showErrors && $errors->any())
    <div class="mb-5 flex items-start gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
        <i class="bi bi-exclamation-circle-fill mt-0.5"></i>
        <ul class="space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
