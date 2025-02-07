<?php

namespace App\Models\Game\Ranking;

use App\Models\Utility\GameServer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

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

        // If today is reset day
        if ($now->dayOfWeek === $this->reset_day_of_week) {
            $resetDateTime = $now->copy()->setTime($resetTime->hour, $resetTime->minute);

            // If reset time hasn't passed today, use today
            if ($now->lt($resetDateTime)) {
                return $resetDateTime;
            }
        }

        // Otherwise, get next occurrence
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

        // Today's reset time
        $todayReset = $now->copy()->setTime($resetTime->hour, $resetTime->minute);

        // Check if it's reset day and time has passed
        $isResetDay = $now->dayOfWeek === $this->reset_day_of_week;
        $isAfterResetTime = $now->gte($todayReset);

        Log::info('Reset check', [
            'server' => $this->server->name,
            'current_time' => $now->format('Y-m-d H:i:s'),
            'is_reset_day' => $isResetDay,
            'reset_time' => $resetTime->format('H:i'),
            'today_reset' => $todayReset->format('Y-m-d H:i:s'),
            'is_after_reset_time' => $isAfterResetTime,
            'should_reset' => $isResetDay && $isAfterResetTime,
        ]);

        return $isResetDay && $isAfterResetTime;
    }
}
