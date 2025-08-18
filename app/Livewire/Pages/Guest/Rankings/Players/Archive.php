<?php

namespace App\Livewire\Pages\Guest\Rankings\Players;

use App\Enums\Utility\RankingScoreType;
use App\Livewire\BaseComponent;
use App\Models\Game\Ranking\WeeklyRankingArchive;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class Archive extends BaseComponent
{
    public string $tab = RankingScoreType::EVENTS->value;

    #[Computed]
    public function periods(): Collection
    {
        $currentServerId = session('selected_server_id');

        return WeeklyRankingArchive::query()
            ->where('game_server_id', $currentServerId)
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
        $endDate = Carbon::parse($end)->format('M j, Y');

        return "{$startDate} - {$endDate}";
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.players.archive';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
