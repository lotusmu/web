<?php

namespace App\Models\Game\Ranking;

use App\Enums\Utility\RankingScoreType;
use App\Models\Utility\GameServer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyRankingArchive extends Model
{
    protected $fillable = [
        'game_server_id',
        'type',
        'cycle_start',
        'cycle_end',
        'rank',
        'character_name',
        'score',
        'rewards_given',
    ];

    protected $casts = [
        'cycle_start' => 'date',
        'cycle_end' => 'date',
        'rank' => 'integer',
        'score' => 'integer',
        'rewards_given' => 'array',
        'type' => RankingScoreType::class,
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(GameServer::class, 'game_server_id');
    }

    public function getFormattedType(): string
    {
        return $this->type->label();
    }
}
