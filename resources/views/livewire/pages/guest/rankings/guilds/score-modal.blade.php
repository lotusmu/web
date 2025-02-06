<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Guild;
use App\Models\Game\Ranking\Hunter;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use App\Models\Game\Ranking\Event;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public Guild $guild;
    public RankingScoreType $type;

    #[Computed]
    public function scores()
    {
        if ($this->type === RankingScoreType::EVENTS) {
            return Event::query()
                ->select([
                    'EventID',
                    'EventName',
                    'PointsPerWin',
                    \DB::raw('SUM(WinCount) as WinCount'),
                    \DB::raw('SUM(TotalPoints) as TotalPoints')
                ])
                ->join('GuildMember', 'RankingEvents.Name', '=', 'GuildMember.Name')
                ->where('GuildMember.G_Name', $this->guild->G_Name)
                ->groupBy('EventID', 'EventName', 'PointsPerWin')
                ->with('event:EventID,EventName,image_path')
                ->get()
                ->map(fn($score) => [
                    'name'         => $score->EventName,
                    'count'        => number_format($score->WinCount),
                    'points'       => number_format($score->PointsPerWin),
                    'total_points' => number_format($score->TotalPoints),
                    'count_label'  => __('wins'),
                    'image'        => $score->event?->image_path ? asset($score->event->image_path) : null,
                ])
                ->sortByDesc('total_points');
        }

        return Hunter::query()
            ->select([
                'MonsterName',
                'MonsterClass',
                'PointsPerKill',
                \DB::raw('SUM(KillCount) as KillCount'),
                \DB::raw('SUM(TotalPoints) as TotalPoints')
            ])
            ->join('GuildMember', 'RankingHunters.Name', '=', 'GuildMember.Name')
            ->where('GuildMember.G_Name', $this->guild->G_Name)
            ->groupBy('MonsterName', 'MonsterClass', 'PointsPerKill')
            ->with('monster:MonsterClass,MonsterName,image_path')
            ->get()
            ->map(fn($score) => [
                'name'         => $score->MonsterName,
                'count'        => number_format($score->KillCount),
                'points'       => number_format($score->PointsPerKill),
                'total_points' => number_format($score->TotalPoints),
                'count_label'  => __('kills'),
                'image'        => $score->monster?->image_path ? asset($score->monster->image_path) : null,
            ])
            ->sortByDesc('total_points');
    }

    #[Computed]
    public function totalScore(): string
    {
        return number_format(
            $this->scores->sum(fn($score) => (int) str_replace(',', '', $score['total_points']))
        );
    }

    #[Computed]
    public function formatScore($score): string
    {
        return "{$score['count']} {$score['count_label']} × {$score['points']} ".__('points');
    }

    public function placeholder()
    {
        $rows = match ($this->type) {
            RankingScoreType::EVENTS => 4,
            RankingScoreType::HUNTERS => 10,
        };

        return view("livewire.pages.guest.rankings.players.placeholders.modal", [
            'rows' => $rows
        ]);
    }
} ?>

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
                {{ $this->totalScore }} points
            </flux:badge>
        </div>
    </div>
</div>
