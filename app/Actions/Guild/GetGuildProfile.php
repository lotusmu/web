<?php

namespace App\Actions\Guild;

use App\Models\Game\Guild;

class GetGuildProfile
{
    public function handle(string $name): ?Guild
    {
        return Guild::query()
            ->select([
                'G_Name', 'G_Mark', 'G_Master', 'CS_Wins',
            ])
            ->withCount('members')
            ->withSum('characters', 'ResetCount')
            ->withSum('characters', 'EventScore')
            ->withSum('characters', 'HunterScore')
            ->with([
                'master:Name,AccountID,Class',
                'master.member:memb___id,AccountLevel',
                'members' => fn ($query) => $query->orderByDesc('G_Status'),
                'members.character:Name,AccountID,cLevel,Class,ResetCount',
            ])
            ->where('G_Name', $name)
            ->first();
    }
}
