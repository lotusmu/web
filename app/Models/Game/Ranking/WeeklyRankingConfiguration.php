<?php

namespace App\Models\Game\Ranking;

use App\Models\Utility\GameServer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyRankingConfiguration extends Model
{
    protected $fillable = [
        'game_server_id',
        'first_cycle_start',
        'reset_day_of_week',
        'reset_time',
        'is_enabled',
    ];

    protected $casts = [
        'first_cycle_start' => 'date',
        'reset_day_of_week' => 'integer',
        'is_enabled' => 'boolean',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(GameServer::class, 'game_server_id');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(WeeklyRankingReward::class);
    }

    public function getNextResetDate(): Carbon
    {
        $now = Carbon::now();
        $resetTime = Carbon::createFromTimeString($this->reset_time);

        if ($now->dayOfWeek === $this->reset_day_of_week) {
            $resetDateTime = $now->copy()->setTime($resetTime->hour, $resetTime->minute);

            if ($now->lt($resetDateTime)) {
                return $resetDateTime;
            }
        }

        return $now->copy()
            ->next($this->reset_day_of_week)
            ->setTime($resetTime->hour, $resetTime->minute);
    }

    public function isFirstCyclePending(): bool
    {
        return $this->is_enabled &&
            $this->first_cycle_start->isFuture() &&
            now()->lt($this->getNextResetDate());
    }

    public function shouldProcessReset(): bool
    {
        if (! $this->is_enabled) {
            return false;
        }

        if ($this->isFirstCyclePending()) {
            return false;
        }

        $now = now();
        $resetTime = Carbon::createFromTimeString($this->reset_time);
        $todayReset = $now->copy()->setTime($resetTime->hour, $resetTime->minute);

        $isResetDay = $now->dayOfWeek === $this->reset_day_of_week;
        $isAfterResetTime = $now->gte($todayReset);

        return $isResetDay && $isAfterResetTime;
    }
}
