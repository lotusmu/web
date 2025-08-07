@php
use App\Enums\Utility\RankingScoreType;
use App\Enums\Utility\ResourceType;
@endphp

<flux:main container>
    <x-page-header
        :title="__('Who\'s on top?')"
        :kicker="__('Rankings')"
        :description="__('The numbers don\'t lie — players and guilds ranked by their achievements.')"
    />

    <flux:card class="max-w-xl space-y-12 mx-auto">
        <div>
            <flux:heading size="lg">
                {{ __('Weekly Rankings Archive') }}
            </flux:heading>
            <flux:subheading>
                {{ __('View past rankings and their rewards.') }}
            </flux:subheading>
        </div>

        <flux:tab.group>
            <flux:tabs variant="segmented" wire:model.live="tab" class="w-full">
                <flux:tab name="{{ RankingScoreType::EVENTS->value }}">{{__('Events Archive')}}</flux:tab>
                <flux:tab name="{{ RankingScoreType::HUNTERS->value }}">{{__('Hunt Archive')}}</flux:tab>
            </flux:tabs>

            @foreach(RankingScoreType::cases() as $scoreType)
                <flux:tab.panel name="{{ $scoreType }}">
                    <flux:accordion transition>
                        @forelse($this->periods as $period => $rankings)
                            <flux:accordion.item>
                                <flux:accordion.heading>
                                    <div class="flex items-center gap-2">
                                        <flux:icon.calendar-date-range variant="mini"/>
                                        <span>{{ $this->formatPeriodDate($period) }}</span>
                                    </div>
                                </flux:accordion.heading>

                                <flux:accordion.content>
                                    <flux:table>
                                        <flux:columns>
                                            <flux:column>#</flux:column>
                                            <flux:column>{{ __('Character') }}</flux:column>
                                            <flux:column>{{ __('Score') }}</flux:column>
                                            <flux:column>{{ __('Reward') }}</flux:column>
                                        </flux:columns>

                                        <flux:rows>
                                            @foreach($rankings as $ranking)
                                                <flux:row>
                                                    <flux:cell>{{ $ranking->rank }}.</flux:cell>
                                                    <flux:cell>{{ $ranking->character_name }}</flux:cell>
                                                    <flux:cell>{{ number_format($ranking->score) }}</flux:cell>
                                                    <flux:cell class="space-x-1">
                                                        @foreach($ranking->rewards_given as $reward)
                                                            <x-resource-badge
                                                                :value="$reward['amount']"
                                                                :resource="ResourceType::from($reward['type'])"
                                                            />
                                                        @endforeach
                                                    </flux:cell>
                                                </flux:row>
                                            @endforeach
                                        </flux:rows>
                                    </flux:table>
                                </flux:accordion.content>
                            </flux:accordion.item>
                        @empty
                            <div class="text-center py-4">
                                {{ __('No archived rankings found.') }}
                            </div>
                        @endforelse
                    </flux:accordion>
                </flux:tab.panel>
            @endforeach

        </flux:tab.group>
    </flux:card>
</flux:main>