<?php

namespace App\Actions\Rankings;

use App\Models\Game\Guild;
use Illuminate\Database\Eloquent\Builder;

class GetGuildsRanking
{
    public function handle(): Builder
    {
        return Guild::query()
            ->select([
                'Guild.G_Name',
                'Guild.G_Mark',
                'Guild.G_Master',
                'Guild.CS_Wins',
            ])
            ->withCount('members')
            ->withSum('characters', 'ResetCount')
            ->withSum('characters', 'EventScore')
            ->withSum('characters', 'HunterScore')
            ->with([
                'master:Name,AccountID,Class',
                'master.member:memb___id,AccountLevel',
            ]);
    }
}
