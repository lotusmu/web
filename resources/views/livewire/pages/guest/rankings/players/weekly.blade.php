<?php

use App\Enums\Utility\RankingPeriodType;
use App\Livewire\Forms\Filters;
use App\Models\Game\Ranking\WeeklyRankingReward;
use App\Traits\HasCharacterRanking;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;
    use WithoutUrlPagination;
    use Searchable;
    use Sortable;
    use HasCharacterRanking;

    public Filters $filters;

    #[Computed]
    public function characters()
    {
        $query = $this->getBaseQuery('weekly');

        $query = $this->applySorting($query);

        return $query->simplePaginate(10);
    }

    #[Computed]
    public function rankingRewards()
    {
        return WeeklyRankingReward::query()
            ->orderBy('position_from')
            ->get();
    }

    protected function getRewardsForPosition(int $iteration): array
    {
        $position = ($this->characters->currentPage() - 1) * 10 + $iteration;

        return $this->rankingRewards()
            ->first(fn($reward) => $position >= $reward->position_from &&
                $position <= $reward->position_to
            )?->rewards ?? [];
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.table');
    }
} ?>

<div class="overflow-x-auto relative space-y-8">
    <x-rankings.filters :filters="$this->filters" :disabled="true"/>

    <x-rankings.search disabled/>

    <flux:table wire:loading.class="opacity-50">
        <x-rankings.characters.weekly.columns
            :sort-by="$sortBy"
            :sort-direction="$sortDirection"
        />

        <x-rankings.characters.weekly.list
            :characters="$this->characters"
            :get-rewards-for-position="$this->getRewardsForPosition(...)"
            :period="RankingPeriodType::WEEKLY"
        />
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->characters" class="!border-0"/>
    </div>
</div>
