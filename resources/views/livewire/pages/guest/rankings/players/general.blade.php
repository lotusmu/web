<?php

use App\Enums\Utility\RankingPeriodType;
use App\Livewire\Forms\Filters;
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
        $query = $this->getBaseQuery('general')
            ->with(['quest:Name,Quest']);

        $query = $this->applySearch($query);
        $query = $this->filters->apply($query);
        $query = $this->applySorting($query);

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
} ?>

<div class="overflow-x-auto relative space-y-8">
    <x-rankings.filters :filters="$this->filters"/>

    <x-rankings.search wire:model.live.debounce="search"/>

    <flux:table wire:loading.class="opacity-50">
        <x-rankings.characters.general.columns
            :sort-by="$sortBy"
            :sort-direction="$sortDirection"
        />

        <x-rankings.characters.general.list
            :characters="$this->characters"
            :period="RankingPeriodType::TOTAL"
        />
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->characters" class="!border-0"/>
    </div>
</div>
