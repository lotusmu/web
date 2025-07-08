@props([
    'label' => true,
    'character'
 ])

<flux:cell>
    <div class="flex items-center gap-3">
        <flux:tooltip :content="$character->Class->getLabel()" position="right">
            <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>
        </flux:tooltip>

        @if($label)
            <span class="max-sm:hidden">{{ $character->Class->getLabel() }}</span>
        @endif
    </div>
</flux:cell>
