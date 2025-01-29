@include('components.rankings.table.rows.common', ['character' => $character])

<flux:cell>
    <livewire:pages.guest.rankings.character-score-modal
        type="hunters"
        :character="$character"
        scope="weekly"
        wire:key="character-{{ $character->Name }}-weekly"
    />
</flux:cell>

<flux:cell>
    <livewire:pages.guest.rankings.character-score-modal
        type="hunters"
        :character="$character"
        scope="total"
        wire:key="character-{{ $character->Name }}-total"
    />
</flux:cell>
