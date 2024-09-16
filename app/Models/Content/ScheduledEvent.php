<?php

namespace App\Models\Content;

use App\Enums\Game\ScheduledEventType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'recurrence_type',
        'schedule',
        'interval_minutes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'type' => ScheduledEventType::class,
        'schedule' => 'array',
        'interval_minutes' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->sort_order) {
                $model->sort_order = static::max('sort_order') + 1;
            }
        });
    }

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
        return match ($this->recurrence_type) {
            'daily' => $this->getNextDailyOccurrence($startDateTime),
            'weekly' => $this->getNextWeeklyOccurrence($scheduleItem, $startDateTime),
            'interval' => $this->getNextIntervalOccurrence($startDateTime),
            default => null,
        };
    }

    private function getNextDailyOccurrence(Carbon $startDateTime): Carbon
    {
        $now = Carbon::now();
        $occurrence = $now->copy()->setTimeFrom($startDateTime);

        if ($occurrence->lte($now)) {
            $occurrence->addDay();
        }

        return $occurrence;
    }

    private function getNextWeeklyOccurrence(array $scheduleItem, Carbon $startDateTime): Carbon
    {
        $now = Carbon::now();
        $occurrence = $now->copy()->next($scheduleItem['day'])->setTimeFrom($startDateTime);

        if ($occurrence->lte($now)) {
            $occurrence->addWeek();
        }

        return $occurrence;
    }

    private function getNextIntervalOccurrence(Carbon $initialStartTime): Carbon
    {
        $now = Carbon::now();
        $minutesSinceStart = $now->diffInMinutes($initialStartTime);
        $intervalsPassed = floor($minutesSinceStart / $this->interval_minutes);
        $nextOccurrence = $initialStartTime->copy()->addMinutes(($intervalsPassed + 1) * $this->interval_minutes);

        return $nextOccurrence;
    }
}
