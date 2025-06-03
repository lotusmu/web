<?php

namespace App\Models\Game\Ranking;

use App\Models\Concerns\GameConnection;
use App\Models\Game\Character;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quest extends Model
{
    use GameConnection;

    protected $table = 'CustomQuest';

    protected $primaryKey = 'Name';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Quest',
        'MonsterCount',
        'MonsterCount2',
        'MonsterCount3',
        'MonsterCount4',
        'MonsterCount5',
    ];

    protected $casts = [
        'Quest' => 'integer',
        'MonsterCount' => 'integer',
        'MonsterCount2' => 'integer',
        'MonsterCount3' => 'integer',
        'MonsterCount4' => 'integer',
        'MonsterCount5' => 'integer',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }
}
