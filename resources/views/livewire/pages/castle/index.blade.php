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

        $this->guild = Guild::where('G_Name', $this->castle->OWNER_GUILD)->first();
    }

    #[Computed]
    public function canWithdraw(): bool
    {
        return auth()->user()->isCastleLord($this->castle);
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
    <livewire:pages.castle.header :guild="$this->guild"/>

    <x-info-card color="teal" icon="light-bulb">
        <flux:text>
            {{ __('Learn about event schedule and siege mechanics in our') }}
            <flux:link href="https://wiki.lotusmu.org/events/castle-siege"
                       external>{{ ' ' . __('wiki guide.') }}</flux:link>
        </flux:text>
    </x-info-card>

    <livewire:pages.castle.prize-pool :castle="$this->castle"/>

    <livewire:pages.castle.treasury :treasury="$this->castle->MONEY"/>

    @if($this->canWithdraw)
        <livewire:pages.castle.withdraw
            :treasury="$this->castle->MONEY"
            :castle="$this->castle"
        />
    @endif

    <livewire:pages.castle.tax-rates
        :store-tax="$this->castle->store_tax"
        :goblin-tax="$this->castle->goblinTax"
        :hunt-zone-tax="$this->castle->huntZoneTax"
    />

</div>
