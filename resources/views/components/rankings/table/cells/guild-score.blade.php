@props([
    'guild',
    'scoreType',
    'score',
])

<flux:cell>
    @if($score > 0)
        <flux:modal.trigger name="guild-{{ $scoreType->value }}-score-{{ $guild->G_Name }}">
            <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                <span>{{ number_format($score) }}</span>
            </flux:button>
        </flux:modal.trigger>
    @else
        <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
            <span>0</span>
        </flux:button>
    @endif

    <flux:modal name="guild-{{ $scoreType->value }}-score-{{ $guild->G_Name }}" variant="flyout" position="right">
        <livewire:pages.guest.rankings.guilds.score-modal
            :guild="$guild"
            :type="$scoreType"
            :wire:key="'guild-'.$scoreType->value.'-'.$guild->G_Name"
            lazy
        />
    </flux:modal>
</flux:cell>
