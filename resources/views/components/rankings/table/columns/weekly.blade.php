@php
    use App\Enums\Utility\RankingScoreType;
@endphp

<flux:column>{{ __('Character') }}</flux:column>

<flux:column>{{ __('Class') }}</flux:column>

<flux:column>{{ __('Level') }}</flux:column>

<flux:column>{{ __('Resets') }}</flux:column>

<flux:column>{{ __('Guild') }}</flux:column>

<flux:column>
    <div class="flex items-center gap-2">
        <span>{{ __('Weekly Event Score') }}</span>
        <x-rankings.scoring-modals-trigger :type="RankingScoreType::EVENTS"/>
    </div>
</flux:column>

<flux:column>
    <div class="flex items-center gap-2">
        <span>{{ __('Weekly Hunt Score') }}</span>
        <x-rankings.scoring-modals-trigger :type="RankingScoreType::HUNTERS"/>
    </div>
</flux:column>

<flux:column>{{ __('Location') }}</flux:column>
