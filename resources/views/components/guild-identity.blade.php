@props([
    'guildMember' => null,
    'href' => '#'
])

<div class="flex items-center w-full gap-2">
    @if($guildMember?->guild)
        <flux:link variant="ghost" :$href wire:navigate.hover class="flex items-center gap-x-2">
            <img src="{{ $guildMember->guild->getMarkUrl(24) }}"
                 alt="Guild Mark"
                 class="w-6 h-6 shrink-0 rounded border border-zinc-200 dark:border-white/10"
            />
            <span class="max-sm:hidden">
                {{ $guildMember->guild->G_Name }}
            </span>
        </flux:link>
    @else
        <flux:text>—</flux:text>
    @endif
</div>
