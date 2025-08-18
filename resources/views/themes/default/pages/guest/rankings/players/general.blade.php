@php
use App\Enums\Utility\RankingPeriodType;
@endphp

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