@props(['label', 'value', 'icon' => '📊'])

<div class="bg-white rounded-xl border p-5 flex items-center gap-4">
    <div class="w-11 h-11 rounded-lg bg-gold-50 text-gold-700 flex items-center justify-center text-xl">
        {{ $icon }}
    </div>
    <div>
        <p class="text-2xl font-semibold text-gray-800">{{ $value }}</p>
        <p class="text-sm text-gray-500">{{ $label }}</p>
    </div>
</div>
