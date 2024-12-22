<?php

use App\Models\Game\CastleData;
use App\Models\Utility\CastlePrize;
use Livewire\Volt\Component;

new class extends Component {
    public ?CastleData $castle = null;

    public function mount(CastleData $castle)
    {
        $this->castle = $castle;
    }

    public function getPrizePool(): ?CastlePrize
    {
        $connection = session('selected_server_id');

        return CastlePrize::where('game_server_id', $connection)
            ->where('is_active', true)
            ->first();
    }

    public function getTimeUntilNextDistribution(): string
    {
        $now        = now();
        $nextSunday = $now->copy()->next('Sunday')->setHour(22)->setMinute(1)->setSecond(0);

        if ($now->isSunday() && $now->hour < 22) {
            $nextSunday = $now->copy()->setHour(22)->setMinute(1)->setSecond(0);
        }

        if ($now->isSunday() && $now->hour >= 22) {
            $nextSunday = $now->copy()->next('Sunday')->setHour(22)->setMinute(1)->setSecond(0);
        }

        $diff = $now->diff($nextSunday);

        return "{$diff->d}d {$diff->h}h {$diff->i}m";
    }
}; ?>

<flux:card class="space-y-6">
    @if($prize = $this->getPrizePool())
        <div class="flex max-sm:flex-col justify-evenly max-sm:gap-4 gap-2 text-center">
            <div class="flex-1 min-w-0">
                <flux:heading size="xl" class="flex items-baseline justify-center gap-1">
                    {{ number_format($prize->remaining_prize_pool) }}
                    <span>
                        <flux:text size="sm">credits</flux:text>
                    </span>
                </flux:heading>
                <flux:subheading>
                    {{__('Remaining Prize Pool')}}
                </flux:subheading>
            </div>

            <flux:separator vertical variant="subtle" class="sm:block hidden"/>
            <flux:separator variant="subtle" class="max-sm:block hidden"/>

            <div class="flex-1 min-w-0">
                <flux:heading size="xl" class="flex items-baseline justify-center gap-1">
                    {{ number_format($prize->weekly_amount) }}
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
                    {{ $this->getTimeUntilNextDistribution() }}
                </flux:heading>
                <flux:subheading>
                    {{__('Time Until Distribution')}}
                </flux:subheading>
            </div>
        </div>
    @else
        <flux:text class=" text-center">
            Prize Pool fully distributed.
        </flux:text>
    @endif
</flux:card>
