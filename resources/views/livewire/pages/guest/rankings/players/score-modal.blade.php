<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public Character $character;
    public RankingPeriodType $scope;
    public RankingScoreType $type;

    #[Computed]
    public function scores()
    {
        $relation = $this->scope->relationName($this->type);
        $schema   = $this->type->scoreSchema();

        return $this->character->$relation()
            ->with($this->type->model())
            ->get()
            ->map(fn($score) => [
                'name'         => $score->{$schema['name_field']},
                'count'        => number_format($score->{$schema['count_field']}),
                'points'       => number_format($score->{$schema['points_field']}),
                'total_points' => number_format($score->TotalPoints),
                'count_label'  => $schema['count_label'],
                'image'        => $this->getImagePath($score),
            ])
            ->sortByDesc('total_points');
    }

    protected function getImagePath($score): ?string
    {
        $model = $this->type->model();

        return $score->$model?->image_path
            ? asset($score->$model->image_path)
            : null;
    }

    #[Computed]
    public function totalScore(): string
    {
        $field = $this->scope->scoreField($this->type);

        return number_format($this->character->$field);
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
            {{ $this->character->Name }}
        </flux:heading>

        <flux:subheading>
            {{ $type->scoreTitle($scope) }}
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
