<?php

namespace App\Actions\Character;

use App\Models\Game\Character;
use Illuminate\Database\Eloquent\Collection;

class GetAccountCharacters
{
    public function handle(string $accountId, string $excludeName): Collection
    {
        return Character::query()
            ->select(['Name', 'AccountID', 'cLevel', 'Class', 'ResetCount'])
            ->with([
                'guildMember:Name,G_Name,G_Status,G_Level',
                'guildMember.guild:G_Name,G_Mark,G_Master',
            ])
            ->where('AccountID', $accountId)
            ->where('Name', '!=', $excludeName)
            ->get();
    }
}
