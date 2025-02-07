<?php

namespace App\Models\Game\Ranking;

use App\Models\Utility\GameServer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getNextResetDate(): Carbon
    {
        return Carbon::now()
            ->next((int) $this->reset_day_of_week)
            ->setTimeFromTimeString($this->reset_time);
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

        return now()->gte($this->getNextResetDate());
    }
}
