<?php

namespace App\Traits;

use App\Actions\Rankings\GetCharactersRanking;
use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

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

    /**
     * Paginate results with a hard limit on total records
     */
    protected function paginateWithLimit($query, int $perPage = 10, int $maxTotal = 100): Paginator
    {
        $limitedIds = (clone $query)->select('Name')->limit($maxTotal)->pluck('Name');

        $query->whereIn('Name', $limitedIds);

        return $query->simplePaginate($perPage);
    }
}
