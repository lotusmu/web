@props(['character'])

@php
    use App\Enums\Game\AccountLevel;
@endphp

<flux:cell class="flex items-center space-x-2">

    <x-rankings.table.cells.online-status :status="$character->member->status?->ConnectStat"/>

    <flux:link variant="ghost"
               :href="route('character', ['name' => $character->Name])"
               wire:navigate>
        {{ $character->Name }}
    </flux:link>

    @if($character?->member?->AccountLevel !== AccountLevel::Regular)
        <flux:tooltip :content="__(':level VIP Member', ['level' => $character?->member?->AccountLevel->getLabel()])">
            <flux:icon.fire variant="mini" class="text-{{ $character?->member?->AccountLevel->badgeColor() }}-500"/>
        </flux:tooltip>
    @endif
</flux:cell>
