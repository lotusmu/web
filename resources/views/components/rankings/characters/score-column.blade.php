@props([
    'sortBy',
    'sortDirection',
    'column',
    'scoreType',
    'label'
])

<flux:column>
    <flux:table.sortable
        wire:click="sort('{{ $column }}')"
        :sorted="$sortBy === $column"
        :direction="$sortDirection"
        class="flex items-center gap-2"
    >
        <span>{{ $label }}</span>
    </flux:table.sortable>

    <x-rankings.scoring-rules-trigger :score-type="$scoreType"/>
</flux:column>
