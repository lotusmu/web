<?php

namespace App\Livewire\Pages\Guest\Rankings;

use App\Enums\Utility\RankingScoreType;
use App\Livewire\BaseComponent;
use App\Models\Game\Ranking\EventSetting;
use App\Models\Game\Ranking\MonsterSetting;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class ScoringRulesModal extends BaseComponent
{
    public RankingScoreType $type;

    #[Computed]
    public function rules(): Collection
    {
        return match ($this->type) {
            RankingScoreType::EVENTS => EventSetting::query()
                ->orderBy('PointsPerWin', 'desc')
                ->get()
                ->map(fn ($event) => [
                    'name' => $event->EventName,
                    'points' => number_format($event->PointsPerWin),
                    'image' => $event->image_path ? asset($event->image_path) : null,
                ]),

            RankingScoreType::HUNTERS => MonsterSetting::query()
                ->where('PointsPerKill', '>', 0)
                ->orderBy('PointsPerKill', 'desc')
                ->get()
                ->map(fn ($monster) => [
                    'name' => $monster->MonsterName,
                    'points' => number_format($monster->PointsPerKill),
                    'image' => $monster->image_path ? asset($monster->image_path) : null,
                ]),
        };
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
        return 'pages.guest.rankings.scoring-rules-modal';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
