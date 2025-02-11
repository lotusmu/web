<?php

namespace App\Actions\Character;

use App\Models\Game\Character;
use Illuminate\Database\Eloquent\Collection;

class GetCharacterAccountCharacters
{
    public function handle(string $accountId, string $excludeName): Collection
    {
        return cache()->remember(
            "account_characters_{$excludeName}",
            now()->addMinutes(5),
            fn () => Character::with(['guildMember.guild'])
                ->select(['Name', 'AccountID', 'cLevel', 'Class', 'ResetCount'])
                ->where('AccountID', $accountId)
                ->where('Name', '!=', $excludeName)
                ->get()
        );
    }
}
