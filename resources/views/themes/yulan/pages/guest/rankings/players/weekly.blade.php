@php
use App\Enums\Utility\RankingPeriodType;
@endphp

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