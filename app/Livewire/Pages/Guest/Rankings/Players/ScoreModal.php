<?php

namespace App\Livewire\Pages\Guest\Rankings\Players;

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Livewire\BaseComponent;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;

class ScoreModal extends BaseComponent
{
    public Character $character;

    public RankingPeriodType $scope;

    public RankingScoreType $type;

    #[Computed]
    public function scores()
    {
        $relation = $this->scope->relationName($this->type);
        $schema = $this->type->scoreSchema();

        return $this->character->$relation()
            ->with($this->type->model())
            ->get()
            ->sortByDesc('TotalPoints')
            ->map(fn ($score) => [
                'name' => $score->{$schema['name_field']},
                'count' => number_format($score->{$schema['count_field']}),
                'points' => number_format($score->{$schema['points_field']}),
                'total_points' => number_format($score->TotalPoints),
                'count_label' => $schema['count_label'],
                'image' => $this->getImagePath($score),
            ]);
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

        return view('components.placeholders.modal', [
            'rows' => $rows,
        ]);
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.players.score-modal';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
