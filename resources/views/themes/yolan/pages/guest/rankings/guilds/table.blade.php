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