<div class="mb-8">
    <x-radio-group class="sm:flex items-center justify-center gap-8 mb-8 hidden" wire:model.live="filters.class">
        @foreach($filters->classes() as $class)
            <x-radio-group.option
                :value="$class['value']"
                class-checked="opacity-100"
                class-not-checked="opacity-70"
                class="flex flex-col items-center justify-center hover:opacity-100 transition-opacity duration-200 cursor-pointer">

                <x-radio-group.description class="text-lg font-semibold">
                    <img src="{{ asset($class['image']) }}"
                         alt="{{ $class['label'] }}"
                         class="w-12 rounded-xl mb-2">
                </x-radio-group.description>

                <flux:text size="sm" class="text-center">
                    <x-radio-group.label>{{ $class['label'] }}</x-radio-group.label>
                </flux:text>
            </x-radio-group.option>
        @endforeach
    </x-radio-group>

    <div class="sm:hidden mb-8">
        <flux:select wire:model.live="filters.class" variant="listbox" placeholder="Select class...">
            @foreach($filters->classes() as $class)
                <flux:option value="{{ $class['value'] }}">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset($class['image']) }}"
                             alt="{{ $class['label'] }}"
                             class="w-6 h-6 rounded-lg">
                        {{ $class['label'] }}
                    </div>
                </flux:option>
            @endforeach
        </flux:select>
    </div>

    <flux:input placeholder="Search character..." icon="magnifying-glass"
                class="max-w-sm mx-auto">
        <x-slot name="iconTrailing">
            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1"/>
        </x-slot>
    </flux:input>
</div>
