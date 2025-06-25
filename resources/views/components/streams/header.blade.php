@props(['streams', 'totalViewers', 'viewMode'])

<header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
    <flux:spacer/>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
            <flux:text class="font-medium">{{ count($streams) }} {{ __('Live') }}</flux:text>
            <flux:text>•</flux:text>
            <flux:text>{{ number_format($totalViewers) }} {{ __('viewers') }}</flux:text>
        </div>

        <flux:radio.group wire:model.live="viewMode" variant="segmented" size="sm">
            <flux:radio value="grid" icon="squares-2x2"/>
            <flux:radio value="featured" icon="tv"/>
        </flux:radio.group>
    </div>
</header>
