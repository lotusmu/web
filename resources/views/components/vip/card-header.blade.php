@props(['tokens', 'duration', 'tier', 'isBestValue' => false])

@php
    $iconColors = [
        'Bronze' => 'text-orange-500 dark:text-orange-400',
        'Silver' => 'text-zinc-500 dark:text-zinc-400',
        'Gold' => 'text-yellow-500 dark:text-yellow-400',
    ];
    $iconColor = $iconColors[$tier] ?? 'text-zinc-500 dark:text-zinc-400';
@endphp

<div class="flex items-start w-full">
    <div class="space-y-4">
        <div class="flex items-center gap-1 text-zinc-800 dark:text-white font-medium">
            <flux:icon.fire class="{{ $iconColor }}"/>
            <span>{{ $tier }}</span>
        </div>

        <div class="flex gap-2 items-baseline">
            <div class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">
                {{ $tokens }}
            </div>
            <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">{{ __('tokens') }}</div>
        </div>

        <flux:subheading size="sm" class="!mt-1">{{ __(':duration days', ['duration' => $duration]) }}</flux:subheading>
    </div>

    <flux:spacer/>

    @if ($isBestValue)
        <flux:badge icon="fire" color="orange">
            {{ __('Best Value · 50% off') }}
        </flux:badge>
    @endif
</div>
