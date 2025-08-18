@php
use App\Enums\Utility\RankingPeriodType;
@endphp

<div class="space-y-12">
    <header>
        <flux:heading size="lg">
            <x-guild-identity :$guild/>
        </flux:heading>

        <flux:subheading>
            {{ $type->scoreTitle(RankingPeriodType::TOTAL) }}
        </flux:subheading>
    </header>

    <div>
        @foreach($this->scores as $score)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if($score['image'])
                        <img src="{{ $score['image'] }}"
                             alt="{{ $score['name'] }}"
                             class="w-12 h-12 object-cover">
                    @endif

                    <div>
                        <flux:text>
                            {{ $score['name'] }}
                        </flux:text>

                        <flux:text size="sm">
                            {{ $this->formatScore($score) }}
                        </flux:text>
                    </div>
                </div>
                <flux:badge size="sm" variant="solid">
                    {{ $score['total_points'] }} {{ __('points') }}
                </flux:badge>
            </div>

            @if(!$loop->last)
                <flux:separator variant="subtle" class="my-6"/>
            @endif
        @endforeach

        <flux:separator class="my-6"/>

        <div class="flex justify-between items-center">
            <flux:heading>
                {{ __('Total Score') }}
            </flux:heading>

            <flux:badge size="sm" variant="solid">
                {{ $this->totalScore }} {{ __('points') }}
            </flux:badge>
        </div>
    </div>
</div>