<?php

use App\Models\Game\CastleData;
use Livewire\Volt\Component;

new class extends Component {
    public ?CastleData $castle = null;

    public function mount(CastleData $castle)
    {
        $this->castle = $castle;
    }
}; ?>

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
