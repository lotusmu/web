<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Character;
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
        return __("{$this->scope->label()} {$this->type->label()} Score: {$this->character->Name}");
    }

    #[Computed]
    public function characterScores(): Collection
    {
        return collect([
            [
                'monster_name'    => 'Golden Dragon',
                'kills'           => 50,
                'points_per_kill' => 100,
                'total_points'    => 5000,
            ],
            [
                'monster_name'    => 'Golden Tantal',
                'kills'           => 25,
                'points_per_kill' => 500,
                'total_points'    => 12500,
            ],
        ]);
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
        position="bottom"
    >
        <div class="space-y-6">
            <header>
                <flux:heading size="lg">{{ $this->getTitle() }}</flux:heading>
            </header>

            <div class="space-y-4">
                @foreach($this->characterScores as $score)
                    <div class="flex justify-between items-center p-2">
                        <div>
                            <flux:text>{{ $score['monster_name'] }}</flux:text>
                            <flux:text size="sm">
                                {{ $score['kills'] }} kills × {{ $score['points_per_kill'] }} points
                            </flux:text>
                        </div>
                        <flux:badge>{{ $score['total_points'] }}</flux:badge>
                    </div>
                @endforeach

                <flux:separator class="my-2"/>

                <div class="flex justify-between items-center">
                    <flux:text>{{ __('Total Score') }}</flux:text>
                    <flux:badge>{{ $this->totalScore }}</flux:badge>
                </div>
            </div>
        </div>
    </flux:modal>
</div>
