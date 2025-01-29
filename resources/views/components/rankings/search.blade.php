<flux:input {{ $attributes }}
            placeholder="Search..."
            icon="magnifying-glass"
            class="max-sm:max-w-none max-w-sm mx-auto">
    <x-slot name="iconTrailing">
        <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1"/>
    </x-slot>
</flux:input>
