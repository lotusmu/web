<?php

namespace App\Actions\Rankings;

use App\Models\Game\Character;
use Illuminate\Database\Eloquent\Builder;

class GetCharactersRanking
{
    protected array $fields = [
        'Name',
        'AccountID',
        'cLevel',
        'ResetCount',
        'Class',
        'MapNumber',
    ];

    public function handle(string $type = 'general'): Builder
    {
        return Character::query()
            ->select($this->getFields($type))
            ->with($this->getRelations());
    }

    protected function getFields(string $type): array
    {
        $scoreFields = match ($type) {
            'weekly' => ['HunterScoreWeekly', 'EventScoreWeekly'],
            default => ['HunterScore', 'EventScore', 'HofWins'],
        };

        return array_merge($this->fields, $scoreFields);
    }

    protected function getRelations(): array
    {
        $relations = [
            'member:memb___id,AccountLevel',
            'guildMember.guild',
        ];

        return $relations;
    }
}
