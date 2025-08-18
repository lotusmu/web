<flux:card class="space-y-6">
    <div class="flex max-sm:flex-col justify-evenly max-sm:gap-4 gap-2 text-center">
        <div class="flex-1 min-w-0">
            <flux:heading size="xl" class="flex items-baseline justify-center gap-1">
                @if($prize = $this->getPrizePool())
                    {{ number_format($prize->remaining_prize_pool) }}
                @else
                    0
                @endif
                <span>
                        <flux:text size="sm">{{__('Credits')}}</flux:text>
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
                @if($prize = $this->getPrizePool())
                    {{ number_format($prize->weekly_amount) }}
                @else
                    0
                @endif
                <span>
                        <flux:text size="sm">{{__('Credits')}}</flux:text>
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
                @if($prize = $this->getPrizePool())
                    {{ $this->getTimeUntilNextDistribution() }}
                @else
                    {{__('Distributed')}}
                @endif
            </flux:heading>
            <flux:subheading>
                {{__('Time Until Distribution')}}
            </flux:subheading>
        </div>
    </div>
</flux:card>