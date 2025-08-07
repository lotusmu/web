<?php

namespace App\Livewire\Pages\Guest\Rankings\Guilds;

use App\Actions\Rankings\GetGuildsRanking;
use App\Livewire\BaseComponent;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Table extends BaseComponent
{
    use Searchable;
    use Sortable;
    use WithoutUrlPagination;
    use WithPagination;

    public function mount()
    {
        $this->sortBy = 'total-resets';
    }

    #[Computed]
    public function guilds()
    {
        $query = app(GetGuildsRanking::class)->handle();

        $query = $this->applySearch($query);
        $query = $this->applySorting($query);

        return $query->simplePaginate(10);
    }

    protected function applySearch($query)
    {
        return $this->searchGuild($query);
    }

    protected function applySorting($query)
    {
        return $this->sortGuilds($query);
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.table', [
            'filters' => false,
        ]);
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.guilds.table';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
