<div class="flex max-sm:flex-col w-full gap-6">
    <flux:card class="flex-1 flex flex-col items-start opacity-75">
        <flux:subheading class="flex items-center gap-2">
            <flux:icon.building-storefront/>
            <span>{{__('Stores')}}</span>
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->taxRates['store'] }}%
        </flux:heading>
        <flux:text size="sm">
            {{__('Tax Rate')}}
        </flux:text>
    </flux:card>

    <flux:card class="flex-1 flex flex-col items-start">
        <flux:subheading class="flex items-center gap-2">
            <flux:icon.sparkles/>
            <span>{{__('Chaos Goblin')}}</span>
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->taxRates['goblin'] }}%
        </flux:heading>
        <flux:text size="sm">
            {{__('Tax Rate')}}
        </flux:text>
    </flux:card>

    <flux:card class="flex-1 flex flex-col items-start">
        <flux:subheading class="flex items-center gap-2">
            <flux:icon.sword/>
            <span>{{__('Hunt Zone')}}</span>
        </flux:subheading>
        <flux:heading size="xl">
            {{ number_format($this->taxRates['huntZone']) }}
        </flux:heading>
        <flux:text size="sm">
            {{__('Entry Tax')}}
        </flux:text>
    </flux:card>
</div>