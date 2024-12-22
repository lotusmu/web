<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CastlePrize extends Model
{
    protected $fillable = [
        'game_server_id',
        'total_prize_pool',
        'remaining_prize_pool',
        'distribution_weeks',
        'period_starts_at',
        'period_ends_at',
        'is_active',
    ];

    protected $casts = [
        'total_prize_pool' => 'integer',
        'remaining_prize_pool' => 'integer',
        'distribution_weeks' => 'integer',
        'weekly_amount' => 'integer',
        'period_starts_at' => 'datetime',
        'period_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function settings(): self
    {
        return static::firstOrCreate([
            'id' => 1,
        ], [
            'total_prize_pool' => 0,
            'remaining_prize_pool' => 0,
            'distribution_weeks' => 1,
            'period_starts_at' => now(),
            'period_ends_at' => now()->addWeeks(1),
            'is_active' => false,
        ]);
    }

    public function isWithinActivePeriod(): bool
    {
        return $this->is_active
            && now()->between($this->period_starts_at, $this->period_ends_at)
            && $this->remaining_prize_pool > 0;
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(CastlePrizeDistribution::class);
    }

    public function gameServer()
    {
        return $this->belongsTo(GameServer::class);
    }
}
