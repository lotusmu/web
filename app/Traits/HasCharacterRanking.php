<?php

namespace App\Traits;

use App\Actions\Rankings\GetCharactersRanking;
use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use Illuminate\Database\Eloquent\Builder;

trait HasCharacterRanking
{
    protected function getBaseQuery(string $type = 'general'): Builder
    {
        return app(GetCharactersRanking::class)->handle($type);
    }

    protected function applySearch($query): Builder
    {
        return $this->searchCharacter($query);
    }

    protected function applySorting($query): Builder
    {
        return $this->sortCharacters($query);
    }

    protected function getRowKey($character, string $type = 'general'): string
    {
        return "{$character->Name}-{$type}-row";
    }

    protected function getScoreKey($character, RankingScoreType $type, RankingPeriodType $period): string
    {
        return "{$character->Name}-{$period->value}-{$type->value}-score";
    }
}
