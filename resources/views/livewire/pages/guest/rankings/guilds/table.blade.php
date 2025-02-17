<?php

use App\Actions\Rankings\GetGuildsRanking;
use App\Enums\Game\AccountLevel;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use App\Enums\Utility\RankingScoreType;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Traits\Searchable;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;
    use WithoutUrlPagination;
    use Searchable;
    use Sortable;

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
            'filters' => false
        ]);
    }
} ?>

<div class="overflow-x-auto relative space-y-8">
    <x-rankings.search wire:model.live.debounce="search"/>

    <flux:table wire:loading.class="opacity-50">
        <x-rankings.guilds.columns
            :sort-by="$sortBy"
            :sort-direction="$sortDirection"
        />

        <x-rankings.guilds.list
            :guilds="$this->guilds"
        />
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->guilds" class="!border-0"/>
    </div>
</div>
