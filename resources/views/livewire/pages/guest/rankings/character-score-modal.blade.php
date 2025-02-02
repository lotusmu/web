<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Character;
use App\Models\Game\Ranking\Monster;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;

new class extends Component {
    public RankingScoreType $type;

    public Character $character;

    public RankingPeriodType $scope;

    public function mount(
        RankingScoreType $type,
        Character $character,
        RankingPeriodType $scope,
    ) {
        $this->type      = $type;
        $this->character = $character;
        $this->scope     = $scope;
    }

    public function getTitle(): string
    {
        return __("{$this->scope->label()} {$this->type->label()} Score");
    }

    #[Computed]
    public function characterScores(): Collection
    {
        // Get eagerly loaded relation instead of making a new query
        $scores = $this->scope === RankingPeriodType::WEEKLY
            ? $this->character->weeklyHunterScores
            : $this->character->hunterScores;

        return $scores->map(function ($score) {
            $monster = $score->monster?->MonsterClass === $score->MonsterClass
                ? $score->monster
                : null;

            return [
                'name'         => $score->MonsterName,
                'kills'        => $score->KillCount,
                'points'       => $score->PointsPerKill,
                'total_points' => $score->TotalPoints,
                'image'        => $monster?->image_path ? asset($monster->image_path) : null,
            ];
        })->sortByDesc('total_points');
    }

    #[Computed]
    public function totalScore(): int
    {
        return $this->characterScores->sum('total_points');
    }
} ?>

<div>
    <flux:modal.trigger :name="$type->value . '-score-' . $scope->value . '-' . $character->Name">
        <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
            <span>{{ $this->totalScore }}</span>
        </flux:button>
    </flux:modal.trigger>

    <flux:modal
        :name="$type->value . '-score-' . $scope->value . '-' . $character->Name"
        variant="flyout"
        position="right"
    >
        <div class="space-y-12 ">
            <header>
                <flux:heading size="lg">{{ $this->character->Name }}</flux:heading>
                <flux:subheading>{{ $this->getTitle() }}</flux:subheading>
            </header>

            <div>
                @foreach($this->characterScores as $score)
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
    </flux:modal>
</div>
