<?php

namespace App\Actions\Character;

use App\Enums\Game\AccountLevel;
use App\Models\Game\Character;

class GetCharacterProfile
{
    public function handle(string $name): ?Character
    {
        return cache()->remember(
            "character_{$name}",
            now()->addMinutes(5),
            fn () => Character::with([
                'member:memb___id,AccountLevel',
                'member.status:memb___id,ConnectStat,ConnectTM,DisConnectTM',
                'guildMember:Name,G_Name,G_Status',
                'guildMember.guild:G_Name,G_Mark',
                'quest:Name,Quest',
            ])
                ->select([
                    'Name', 'AccountID', 'cLevel', 'Class', 'ResetCount', 'MapNumber',
                    'Strength', 'Dexterity', 'Vitality', 'Energy', 'Leadership',
                    'HofWins', 'EventScore', 'HunterScore',
                ])
                ->where('Name', $name)
                ->first()
        );
    }

    public function getAccountLevelDetails(?AccountLevel $level): ?array
    {
        if (! $level || $level === AccountLevel::Regular) {
            return null;
        }

        return [
            'label' => $level->getLabel(),
            'color' => $level->badgeColor(),
        ];
    }
}
