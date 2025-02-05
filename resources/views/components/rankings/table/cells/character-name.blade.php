@php
    use App\Enums\Game\AccountLevel;
@endphp

<flux:cell class="flex items-center space-x-2">
    <span>{{ 'No' }}.</span>

    <flux:link variant="ghost" href="#">{{ $character->Name }}</flux:link>

    @if($character->member->AccountLevel !== AccountLevel::Regular)
        <flux:icon.fire variant="mini" class="text-{{ $character->member->AccountLevel->badgeColor() }}-500"/>
    @endif
</flux:cell>
