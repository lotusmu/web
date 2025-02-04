<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Character;
use App\Models\Game\Ranking\MonsterSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;

new class extends Component {
    public Character $character;
    public RankingPeriodType $scope;

    #[Computed]
    public function scores()
    {
        $scores = $this->scope === RankingPeriodType::WEEKLY
            ? $this->character->weeklyHunterScores()->with('monster')->get()
            : $this->character->hunterScores()->with('monster')->get();

        return $scores->map(function ($score) {
            return [
                'name'         => $score->MonsterName,
                'kills'        => $score->KillCount,
                'points'       => $score->PointsPerKill,
                'total_points' => $score->TotalPoints,
                'image'        => $score->monster?->image_path ? asset($score->monster->image_path) : null,
            ];
        })->sortByDesc('total_points');
    }

    #[Computed]
    public function totalScore(): int
    {
        return $this->scope === RankingPeriodType::WEEKLY
            ? $this->character->HunterScoreWeekly
            : $this->character->HunterScore;
    }

    #[Computed]
    public function getTitle(): string
    {
        return __("{$this->scope->label()} Hunt Score");
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.hunters-modal');
    }
} ?>


<div class="space-y-12 ">
    <header>
        <flux:heading size="lg">{{ $this->character->Name }}</flux:heading>
        <flux:subheading>{{ $this->getTitle() }}</flux:subheading>
    </header>

    <div>
        @foreach($this->scores as $score)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if($score['image'])
                        <img src="{{ $score['image'] }}" alt="{{ $score['name'] }}"
                             class="w-12 h-12 object-cover">
                    @endif

                    <div>
                        <flux:text>{{ $score['name'] }}</flux:text>
                        <flux:text size="sm">
                            {{ $score['kills'] }} kills × {{ $score['points'] }} points
                        </flux:text>
                    </div>
                </div>
                <flux:badge size="sm" variant="solid">{{ $score['total_points'] }} points</flux:badge>
            </div>

            @if(!$loop->last)
                <flux:separator variant="subtle" class="my-6"/>
            @endif
        @endforeach

        <flux:separator class="my-6"/>

        <div class="flex justify-between items-center">
            <flux:heading>{{ __('Total Score') }}</flux:heading>
            <flux:badge size="sm" variant="solid">{{ $this->totalScore }} points</flux:badge>
        </div>
    </div>
</div>
