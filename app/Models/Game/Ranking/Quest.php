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
    ];

    protected $casts = [
        'Quest' => 'integer',
        'MonsterCount' => 'integer',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }
}
