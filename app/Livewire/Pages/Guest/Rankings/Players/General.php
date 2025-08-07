<?php

namespace App\Livewire\Pages\Guest\Rankings\Players;

use App\Livewire\BaseComponent;
use App\Livewire\Forms\Filters;
use App\Traits\HasCharacterRanking;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class General extends BaseComponent
{
    use HasCharacterRanking;
    use Searchable;
    use Sortable;
    use WithoutUrlPagination;
    use WithPagination;

    public Filters $filters;

    #[Computed]
    public function characters()
    {
        $query = $this->getBaseQuery('general')
            ->with(['quest:Name,Quest']);

        $query = $this->applySearch($query);
        $query = $this->filters->apply($query);
        $query = $this->applySorting($query);

        //        return $query->simplePaginate(10);
        return $this->paginateWithLimit($query);
    }

    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.table');
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.players.general';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
