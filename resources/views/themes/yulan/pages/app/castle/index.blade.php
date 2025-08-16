<div class="space-y-6">
    <livewire:pages.app.castle.header :guild="$this->guild"/>

    <x-info-card color="teal" icon="light-bulb">
        <flux:text>
            {{ __('Learn about event schedule and siege mechanics in our') }}
            <flux:link href="https://wiki.yulanmu.com/events/castle-siege"
                       external>{{ ' ' . __('wiki guide.') }}</flux:link>
        </flux:text>
    </x-info-card>

    <livewire:pages.app.castle.prize-pool :castle="$this->castle"/>

    <livewire:pages.app.castle.treasury :treasury="$this->castle->MONEY"/>

    @if($this->canWithdraw)
        <livewire:pages.app.castle.withdraw
            :treasury="$this->castle->MONEY"
            :castle="$this->castle"
        />
    @endif

    <livewire:pages.app.castle.tax-rates
        :store-tax="$this->castle->store_tax"
        :goblin-tax="$this->castle->goblinTax"
        :hunt-zone-tax="$this->castle->huntZoneTax"
    />

</div>
