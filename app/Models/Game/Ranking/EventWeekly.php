<?php

namespace App\Models\Game\Ranking;

use App\Models\Concerns\GameConnection;
use App\Models\Game\Character;
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
        'LastUpdated',
    ];

    protected $casts = [
        'EventID' => 'integer',
        'PointsPerWin' => 'integer',
        'WinCount' => 'integer',
        'TotalPoints' => 'integer',
        'LastUpdated' => 'datetime',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventSetting::class, 'EventID', 'EventID');
    }

    public function getUser()
    {
        return Character::findUserByCharacterName($this->Name);
    }
}
