@props(['filters', 'disabled' => false])

<div>
    <flux:radio.group variant="cards"
                      wire:model.live="filters.class"
                      :$disabled
                      class="lg:flex hidden items-center justify-center"
    >
        @foreach($filters->classes() as $class)
            <flux:radio :value="$class['value']"
                        :wire:key="$class['value']"
                        class="flex flex-col items-center justify-center !gap-2 !flex-none min-w-28 cursor-pointer">
                <img src="{{ asset($class['image']) }}"
                     alt="{{ $class['label'] }}"
                     class="w-12 rounded-xl">

                <flux:text size="sm" class="text-center">
                    {{ $class['label'] }}
                </flux:text>
            </flux:radio>
        @endforeach
    </flux:radio.group>

    <div class="lg:hidden">
        <flux:select wire:model.live="filters.class"
                     variant="listbox"
                     :$disabled
                     placeholder="Select class..."
        >
            @foreach($filters->classes() as $class)
                <flux:option :value="$class['value']" :wire:key="$class['value']">
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
