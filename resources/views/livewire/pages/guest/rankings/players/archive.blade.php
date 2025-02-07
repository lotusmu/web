<?php

use App\Enums\Utility\RankingScoreType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\Ranking\WeeklyRankingArchive;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $tab = RankingScoreType::EVENTS->value;

    #[Computed]
    public function periods(): Collection
    {
        return WeeklyRankingArchive::query()
            ->where('type', RankingScoreType::from($this->tab))
            ->orderByDesc('cycle_end')
            ->get()
            ->groupBy(function ($record) {
                return $record->cycle_start->format('Y-m-d').' - '.$record->cycle_end->format('Y-m-d');
            })
            ->map(function ($rankings) {
                return $rankings->sortBy('rank');
            });
    }

    private function formatPeriodDate(string $period): string
    {
        [$start, $end] = explode(' - ', $period);
        $startDate = Carbon::parse($start)->format('M j, Y');
        $endDate   = Carbon::parse($end)->format('M j, Y');

        return "{$startDate} - {$endDate}";
    }
}; ?>

<flux:main container>
    <x-page-header
        title="Who's on top?"
        kicker="Rankings"
        description="The numbers don't lie — players and guilds ranked by their achievements."
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
                <flux:tab name="{{ RankingScoreType::EVENTS->value }}">Events Archive</flux:tab>
                <flux:tab name="{{ RankingScoreType::HUNTERS->value }}">Hunt Archive</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="{{ RankingScoreType::EVENTS->value }}">
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

            <flux:tab.panel name="{{ RankingScoreType::HUNTERS->value }}">
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
        </flux:tab.group>
    </flux:card>
</flux:main>
