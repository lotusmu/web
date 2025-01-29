<flux:cell>
    <div class="flex items-center gap-3">
        <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>

        <span class="max-sm:hidden">{{ $character->Class->getLabel() }}</span>
    </div>
</flux:cell>
