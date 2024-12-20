<?php

use App\Enums\PaymentProvider;
use App\Models\Game\CastleData;
use App\Models\Game\Guild;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ?Guild $guild = null;
    public ?CastleData $castle = null;

    public function mount()
    {
        $this->castle = CastleData::first();
        $this->guild  = Guild::where('G_Name', $this->castle->OWNER_GUILD)->first();
    }

    #[Computed]
    public function treasury(): int
    {
        return $this->castle->MONEY;
    }

    #[Computed]
    public function storeTax(): int
    {
        return $this->castle->store_tax;
    }

    #[Computed]
    public function goblinTax(): int
    {
        return $this->castle->goblinTax;
    }

    #[Computed]
    public function huntZoneTax(): int
    {
        return $this->castle->huntZoneTax;
    }
}; ?>

<div class="space-y-6">
    <header class="flex items-center gap-4">
        <img src="{{ $this->guild->getMarkUrl(72) }}"
             alt="Guild Mark"
             class="shrink-0 rounded-xl"
        />

        <div class="flex flex-col items-start">
            <flux:heading size="xl" class="flex items-center gap-2">
                <flux:link variant="ghost" href="#">
                    {{ __('LotusMu') }}
                </flux:link>

                <flux:badge variant="pill" size="sm" inset="top bottom" color="amber">
                    {{__('Castle Owner')}}
                </flux:badge>
            </flux:heading>

            <flux:subheading class="flex items-center gap-2">
                <flux:icon.crown variant="micro" color="orange"/>
                {{ __('Castle Lord:') }}
                <flux:link variant="ghost" href="#">void</flux:link>
            </flux:subheading>
        </div>
    </header>

    <flux:card class="space-y-6">
        <div class="flex max-sm:flex-col justify-evenly max-sm:gap-4 gap-2 text-center">
            <div class="flex-1 min-w-0">
                <flux:heading size="xl" class="flex items-baseline justify-center gap-1">
                    70,000
                    <span>
                        <flux:text size="sm">credits</flux:text>
                    </span>
                </flux:heading>
                <flux:subheading>
                    {{__('Total Prize Pool')}}
                </flux:subheading>
            </div>

            <flux:separator vertical variant="subtle" class="sm:block hidden"/>
            <flux:separator variant="subtle" class="max-sm:block hidden"/>

            <div class="flex-1 min-w-0">
                <flux:heading size="xl" class="flex items-baseline justify-center gap-1">
                    10,000
                    <span>
                        <flux:text size="sm">credits</flux:text>
                    </span>
                </flux:heading>
                <flux:subheading>
                    {{__('Next Distribution')}}
                </flux:subheading>
            </div>

            <flux:separator vertical variant="subtle" class="sm:block hidden"/>
            <flux:separator variant="subtle" class="max-sm:block hidden"/>

            <div class="flex-1 min-w-0">
                <flux:heading size="xl" class="flex gap-2 items-center justify-center">
                    <flux:icon.clock/>
                    {{ $this->castle->remaining_time }}
                </flux:heading>
                <flux:subheading>
                    {{__('Time Remaining')}}
                </flux:subheading>
            </div>
        </div>
    </flux:card>

    <flux:card class="space-y-6 bg-zinc-800 dark:!bg-white !border-zinc-950 dark:!border-white relative">
        <flux:heading size="lg" class="flex items-center gap-2 !text-white dark:!text-zinc-800">
            <flux:icon.piggy-bank variant="mini" color="orange"/>
            <span>{{__('Castle Treasury')}}</span>

            <flux:icon.piggy-bank class="absolute max-sm:w-20 w-28 max-sm:h-20 h-28 mr-4 right-0 bottom-0 opacity-25"/>
        </flux:heading>

        <div>
            <div class="text-white dark:text-zinc-800 text-3xl font-black mb-2">
                {{ number_format($this->treasury) }}
            </div>

            <flux:subheading class="dark:!text-zinc-500 !text-white/70">
                {{ __('Available Zen') }}
            </flux:subheading>
        </div>
    </flux:card>

    <flux:card
        class="space-y-6"
        x-data="{
            selected: '',
            amount: null,
            maxAmount: {{ $this->treasury }},
            calculateAmount() {
                if (this.selected !== 'custom') {
                    return Math.floor(this.maxAmount * (parseInt(this.selected) / 100));
                }

                return this.amount;
            }
        }"
    >
        <flux:heading size="lg">
            {{__('Quick Withdraw')}}
        </flux:heading>

        <flux:radio.group
            variant="cards"
            :indicator="false"
            class="max-sm:flex-col text-center"
            x-model="selected"
            wire:model="selected"
        >
            <flux:radio value="25" label="25%"/>
            <flux:radio value="50" label="50%"/>
            <flux:radio value="75" label="75%"/>
            <flux:radio value="100" label="100%"/>
            <flux:radio value="custom" label="Custom"/>
        </flux:radio.group>

        <flux:input
            x-model="amount"
            wire:model="amount"
            type="number"
            x-bind:disabled="selected !== 'custom'"
            :min="1"
            placeholder="Enter amount to withdraw"
            x-bind:value="selected !== 'custom' ? calculateAmount() : amount"
        />

        <flux:button
            variant="primary"
            icon-trailing="chevron-right"
            class="w-full"
        >
            {{__('Withdraw')}}
        </flux:button>
    </flux:card>

    <div class="flex max-sm:flex-col w-full gap-6">
        <flux:card class="flex-1 flex flex-col items-start opacity-75">
            <flux:subheading class="flex items-center gap-2">
                <flux:icon.building-storefront/>
                <span>Stores</span>
            </flux:subheading>
            <flux:heading size="xl">
                {{ $this->storeTax }}%
            </flux:heading>
            <flux:text size="sm">
                Tax Rate
            </flux:text>
        </flux:card>

        <flux:card class="flex-1 flex flex-col items-start">
            <flux:subheading class="flex items-center gap-2">
                <flux:icon.sparkles/>
                <span>Chaos Goblin</span>
            </flux:subheading>
            <flux:heading size="xl">
                {{ $this->goblinTax }}%
            </flux:heading>
            <flux:text size="sm">
                Tax Rate
            </flux:text>
        </flux:card>

        <flux:card class="flex-1 flex flex-col items-start">
            <flux:subheading class="flex items-center gap-2">
                <flux:icon.sword/>
                <span>Hunt Zone</span>
            </flux:subheading>
            <flux:heading size="xl">
                {{ number_format($this->huntZoneTax) }}
            </flux:heading>
            <flux:text size="sm">
                Entry Tax
            </flux:text>
        </flux:card>
    </div>
</div>
