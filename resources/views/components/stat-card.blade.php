<div class="bg-white rounded-xl border p-5 shadow-sm">

    <div class="flex items-center justify-between">

        <div>
            <p class="text-sm text-gray-500">
                {{ $label }}
            </p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $value }}
            </h2>
        </div>


        <div class="w-12 h-12 rounded-xl bg-red-100 
                    flex items-center justify-center text-2xl">
            {{ $icon }}
        </div>

    </div>

</div>