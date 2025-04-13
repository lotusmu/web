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
        'last_processing_start',
        'last_successful_processing',
        'processing_state',
    ];

    protected $casts = [
        'first_cycle_start' => 'date',
        'reset_day_of_week' => 'integer',
        'is_enabled' => 'boolean',
        'last_processing_start' => 'datetime',
        'last_successful_processing' => 'datetime',
        'processing_state' => 'array',
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
        // Skip if not enabled or first cycle hasn't started
        if (! $this->is_enabled || $this->isFirstCyclePending()) {
            return false;
        }

        // Only process if it's reset day and after reset time
        if (! $this->isCurrentlyInResetWindow()) {
            return false;
        }

        // Don't process if we've already processed this cycle
        return ! $this->hasAlreadyProcessedCurrentCycle();
    }

    private function isCurrentlyInResetWindow(): bool
    {
        $now = now();
        $resetTime = Carbon::createFromTimeString($this->reset_time);
        $currentReset = $now->copy()->setTime($resetTime->hour, $resetTime->minute);

        $isResetDay = $now->dayOfWeek === $this->reset_day_of_week;
        $isAfterResetTime = $now->gte($currentReset);

        return $isResetDay && $isAfterResetTime;
    }

    private function hasAlreadyProcessedCurrentCycle(): bool
    {
        $resetTime = Carbon::createFromTimeString($this->reset_time);
        $currentReset = now()->copy()->setTime($resetTime->hour, $resetTime->minute);

        return $this->last_successful_processing &&
            $this->last_successful_processing->gte($currentReset);
    }
}
