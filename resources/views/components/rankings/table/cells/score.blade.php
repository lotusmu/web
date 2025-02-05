@props([
    'character',
    'scoreType',
    'scope',
    'score',
    'modalKey',
])

<flux:cell>
    @if($score > 0)
        <flux:modal.trigger :name="$modalKey">
            <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                <span>{{ number_format($score) }}</span>
            </flux:button>
        </flux:modal.trigger>
    @else
        <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
            <span>{{ $score }}</span>
        </flux:button>
    @endif

    <flux:modal :name="$modalKey" variant="flyout" position="right">
        <livewire:pages.guest.rankings.modals.score
            :character="$character"
            :scope="$scope"
            :type="$scoreType"
            :wire:key="$modalKey"
            lazy
        />
    </flux:modal>
</flux:cell>
