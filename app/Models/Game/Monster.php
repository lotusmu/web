<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $connection = 'gamedb_main';

    protected $table = 'CustomMonster';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'MonsterClass',
        'MonsterName',
        'PointsPerKill',
        'image_path',
    ];

    protected $casts = [
        'MonsterClass' => 'integer',
        'PointsPerKill' => 'integer',
    ];
}
