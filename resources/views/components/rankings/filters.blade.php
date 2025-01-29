<div>
    <x-rankings.radio-group class="sm:flex items-center justify-center gap-8 hidden"
                            wire:model.live="filters.class">
        @foreach($filters->classes() as $class)
            <x-rankings.radio-group.option
                :value="$class['value']"
                class-checked="opacity-100"
                class-not-checked="opacity-80 dark:opacity-70"
                class="flex flex-col items-center justify-center hover:opacity-100 transition-opacity duration-200 cursor-pointer">

                <x-rankings.radio-group.description class="text-lg font-semibold">
                    <img src="{{ asset($class['image']) }}"
                         alt="{{ $class['label'] }}"
                         class="w-12 rounded-xl mb-2">
                </x-rankings.radio-group.description>

                <flux:text size="sm" class="text-center">
                    <x-rankings.radio-group.label>{{ $class['label'] }}</x-rankings.radio-group.label>
                </flux:text>
            </x-rankings.radio-group.option>
        @endforeach
    </x-rankings.radio-group>

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
</div>
