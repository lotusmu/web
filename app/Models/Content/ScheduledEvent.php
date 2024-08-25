<?php

namespace App\Models\Content;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScheduledEvent extends Model
{
    protected $fillable = [
        'name',
        'recurrence_type',
        'schedule',
        'interval_minutes',
        'is_active',
    ];

    protected $casts = [
        'schedule' => 'array',
        'interval_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function activate(): void
    {
        $this->is_active = true;

        $this->save();
    }

    public function deactivate(): void
    {
        $this->is_active = false;

        $this->save();
    }

    public function getNextOccurrence(): ?Carbon
    {
        $nextOccurrence = null;

        foreach ($this->schedule as $scheduleItem) {
            if (! isset($scheduleItem['time']) || ! is_string($scheduleItem['time'])) {
                continue;
            }

            $startDateTime = Carbon::createFromFormat('H:i', $scheduleItem['time']);
            $occurrence = $this->calculateNextOccurrence($scheduleItem, $startDateTime);

            if (! $nextOccurrence || ($occurrence && $occurrence->lt($nextOccurrence))) {
                $nextOccurrence = $occurrence;
            }
        }

        return $nextOccurrence;
    }

    private function calculateNextOccurrence(array $scheduleItem, Carbon $startDateTime): ?Carbon
    {
        switch ($this->recurrence_type) {
            case 'daily':
                return $this->getNextDailyOccurrence($startDateTime);
            case 'weekly':
                return $this->getNextWeeklyOccurrence($scheduleItem, $startDateTime);
            case 'interval':
                return $this->getNextIntervalOccurrence($startDateTime);
            default:
                return null;
        }
    }

    private function getNextDailyOccurrence(Carbon $startDateTime): Carbon
    {
        return Carbon::now()
            ->setTimeFrom($startDateTime)
            ->addDay(Carbon::now()->gt($startDateTime));
    }

    private function getNextWeeklyOccurrence(array $scheduleItem, Carbon $startDateTime): Carbon
    {
        return Carbon::now()
            ->next($scheduleItem['day'])
            ->setTimeFrom($startDateTime)
            ->addWeek(Carbon::now()->gt($startDateTime));
    }

    private function getNextIntervalOccurrence(Carbon $startDateTime): Carbon
    {
        $occurrence = Carbon::now()->startOfDay()->setTimeFrom($startDateTime);

        while ($occurrence->lte(Carbon::now())) {
            $occurrence->addMinutes($this->interval_minutes);
        }

        return $occurrence;
    }
}
