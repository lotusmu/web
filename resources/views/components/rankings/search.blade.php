<flux:input {{ $attributes }}
            placeholder="{{__('Search...')}}"
            clearable
            class="max-md:max-w-none max-w-lg mx-auto"
>
    <x-slot:icon-leading class="!z-0">
        <flux:icon.magnifying-glass variant="mini"/>
    </x-slot:icon-leading>
</flux:input>
