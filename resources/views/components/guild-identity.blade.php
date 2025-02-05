@props([
    'guild' => null,
    'guildMember' => null,
    'href' => '#'
])

@php
    $guildInstance = $guild ?? ($guildMember?->guild()->first());
@endphp

<div class="flex items-center w-full gap-2">
    @if($guildInstance)
        <flux:link variant="ghost" :$href wire:navigate.hover class="flex items-center gap-x-2">
            <img src="{{ $guildInstance->getMarkUrl(24) }}"
                 alt="Guild Mark"
                 class="w-6 h-6 shrink-0 rounded border border-zinc-200 dark:border-white/10"
            />
            <span class="max-sm:hidden">
                {{ $guildInstance->G_Name }}
            </span>
        </flux:link>
    @else
        <flux:text>—</flux:text>
    @endif
</div>
