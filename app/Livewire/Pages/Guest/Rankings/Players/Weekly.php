<?php

namespace App\Livewire\Pages\Guest\Rankings\Players;

use App\Livewire\BaseComponent;
use App\Livewire\Forms\Filters;
use App\Models\Game\Ranking\WeeklyRankingReward;
use App\Traits\HasCharacterRanking;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Weekly extends BaseComponent
{
    use HasCharacterRanking;
    use Searchable;
    use Sortable;
    use WithoutUrlPagination;
    use WithPagination;

    public Filters $filters;

    public function mount()
    {
        $this->sortBy = 'weekly-event-score';
    }

    #[Computed]
    public function characters()
    {
        $query = $this->getBaseQuery('weekly');

        $query = $this->applySorting($query);

        // return $query->simplePaginate(10);
        return $this->paginateWithLimit($query);
    }

    #[Computed]
    public function rankingRewards()
    {
        $currentServerId = session('selected_server_id');

        return WeeklyRankingReward::query()
            ->whereHas('configuration', function ($query) use ($currentServerId) {
                $query->where('game_server_id', $currentServerId);
            })
            ->orderBy('position_from')
            ->get();
    }

    protected function getRewardsForPosition(int $iteration): array
    {
        $position = ($this->characters->currentPage() - 1) * 10 + $iteration;

        return $this->rankingRewards
            ->first(fn ($reward) => $position >= $reward->position_from &&
                $position <= $reward->position_to
            )?->rewards ?? [];
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.table');
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.players.weekly';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
