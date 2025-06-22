@props([
    'character',
])

@php
    use App\Enums\Game\AccountLevel;
@endphp

<flux:cell>
    <flux:link variant="ghost"
               :href="route('character', ['name' => $character->Name])"
               wire:navigate.hover
               class="flex items-center space-x-2">
        <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>

        <span>{{ $character->Name }}</span>

        @if($character?->member?->hasValidVipSubscription())
            <flux:tooltip
                :content="__(':level VIP Member', ['level' => $character?->member?->AccountLevel->getLabel()])">
                <flux:icon.fire variant="mini" class="text-{{ $character?->member?->AccountLevel->badgeColor() }}-500"/>
            </flux:tooltip>
        @endif
    </flux:link>
</flux:cell>
