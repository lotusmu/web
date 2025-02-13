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

        @if($character?->member?->AccountLevel !== AccountLevel::Regular)
            <flux:icon.fire variant="mini" class="text-{{ $character?->member?->AccountLevel->badgeColor() }}-500"/>
        @endif
    </flux:link>
</flux:cell>
