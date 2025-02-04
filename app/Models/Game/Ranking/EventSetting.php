<?php

namespace App\Models\Game\Ranking;

use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    protected $connection = 'gamedb_main';

    protected $table = 'CustomEvents';

    protected $primaryKey = 'EventID';

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'EventID',
        'EventName',
        'PointsPerWin',
        'image_path',
    ];

    protected $casts = [
        'EventID' => 'integer',
        'PointsPerWin' => 'integer',
    ];
}
