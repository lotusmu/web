<?php

namespace App\Models\Game\Ranking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyRankingReward extends Model
{
    protected $fillable = [
        'weekly_ranking_configuration_id',
        'position_from',
        'position_to',
        'rewards',
    ];

    protected $casts = [
        'position_from' => 'integer',
        'position_to' => 'integer',
        'rewards' => 'array',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(WeeklyRankingConfiguration::class, 'weekly_ranking_configuration_id');
    }
}
