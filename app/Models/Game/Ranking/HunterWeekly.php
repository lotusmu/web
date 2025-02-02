<?php

namespace App\Models\Game\Ranking;

use App\Models\Concerns\GameConnection;
use App\Models\Game\Character;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HunterWeekly extends Model
{
    use GameConnection;

    protected $table = 'RankingHuntersWeekly';

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'Account',
        'Name',
        'MonsterName',
        'MonsterClass',
        'KillCount',
        'PointsPerKill',
        'TotalPoints',
        'LastUpdated',
        'WeekNumber',
    ];

    protected $casts = [
        'MonsterClass' => 'integer',
        'KillCount' => 'integer',
        'PointsPerKill' => 'integer',
        'TotalPoints' => 'integer',
        'LastUpdated' => 'datetime',
        'WeekNumber' => 'integer',
    ];

    public function monster(): BelongsTo
    {
        return $this->belongsTo(Monster::class, 'MonsterName', 'MonsterName')
            ->where('MonsterClass', $this->MonsterClass);
    }

    public function scopeCurrentWeek($query)
    {
        $currentWeek = Carbon::now()->format('oW');

        return $query->where('WeekNumber', $currentWeek);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }

    // Optional: Helper method to get user if needed
    public function getUser()
    {
        return Character::findUserByCharacterName($this->Name);
    }
}
