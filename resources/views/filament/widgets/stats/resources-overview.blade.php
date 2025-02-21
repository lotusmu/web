<div class="flex flex-col h-full">
    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-sm mb-2">
        <x-heroicon-o-currency-dollar class="w-4 h-4"/>
        <span>Total Resources</span>
    </div>

    <div class="flex flex-col gap-2 mt-1">
        <div class="flex items-center gap-2">
            <span class="text-emerald-500 font-medium">Tokens:</span>
            <span class="font-bold">{{ $tokens }}</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-blue-500 font-medium">Credits:</span>
            <span class="font-bold">{{ $credits }}</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-amber-500 font-medium">Zen:</span>
            <span class="font-bold">{{ $zen }}</span>
        </div>
    </div>
</div>
