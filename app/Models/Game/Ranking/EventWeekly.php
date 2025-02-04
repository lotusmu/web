<?php

namespace App\Models\Game\Ranking;

use App\Models\Concerns\GameConnection;
use App\Models\Game\Character;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventWeekly extends Model
{
    use GameConnection;

    protected $table = 'RankingEventsWeekly';

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'Name',
        'EventID',
        'EventName',
        'PointsPerWin',
        'WinCount',
        'TotalPoints',
        'WeekNumber',
        'LastUpdated',
    ];

    protected $casts = [
        'EventID' => 'integer',
        'PointsPerWin' => 'integer',
        'WinCount' => 'integer',
        'TotalPoints' => 'integer',
        'WeekNumber' => 'integer',
        'LastUpdated' => 'datetime',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }

    public function eventSetting(): BelongsTo
    {
        return $this->belongsTo(EventSetting::class, 'EventID', 'EventID');
    }

    public function scopeCurrentWeek($query)
    {
        $currentWeek = Carbon::now()->format('oW');

        return $query->where('WeekNumber', $currentWeek);
    }

    public function getUser()
    {
        return Character::findUserByCharacterName($this->Name);
    }
}
