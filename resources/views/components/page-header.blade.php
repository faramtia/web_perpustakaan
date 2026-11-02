@props(['icon' => 'bi-journal-bookmark', 'title', 'subtitle' => null])

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-yellow-400 text-xl text-stone-900 shadow-sm">
            <i class="bi {{ $icon }}"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight text-stone-900">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-stone-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
