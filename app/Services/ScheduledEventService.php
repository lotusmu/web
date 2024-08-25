<?php

namespace App\Services;

use App\Models\Content\ScheduledEvent;
use Carbon\Carbon;

class ScheduledEventService
{
    public function getUpcomingEvents($limit = 10)
    {
        $events = ScheduledEvent::where('is_active', true)->get();
        $upcomingEvents = collect();

        foreach ($events as $event) {
            $nextOccurrence = $this->getNextOccurrence($event);
            if ($nextOccurrence) {
                $upcomingEvents->push([
                    'event_id' => $event->id,
                    'name' => $event->name,
                    'start_time' => $nextOccurrence,
                    'recurrence_type' => $event->recurrence_type,
                    'interval_minutes' => $event->interval_minutes,
                ]);
            }
        }

        return $upcomingEvents->sortBy('start_time')->take($limit);
    }

    private function getNextOccurrence($event): ?Carbon
    {
        $now = Carbon::now();
        $nextOccurrence = null;

        foreach ($event->schedule as $scheduleItem) {
            if (! isset($scheduleItem['time']) || ! is_string($scheduleItem['time'])) {
                continue;
            }
            $startDateTime = Carbon::createFromFormat('H:i', $scheduleItem['time']);

            switch ($event->recurrence_type) {
                case 'daily':
                    $occurrence = $this->getNextDailyOccurrence($startDateTime);
                    break;
                case 'weekly':
                    $occurrence = $this->getNextWeeklyOccurrence($scheduleItem, $startDateTime);
                    break;
                case 'interval':
                    $occurrence = $this->getNextIntervalOccurrence($event, $startDateTime);
                    break;
                default:
                    continue 2; // Skip to next schedule item if recurrence type is invalid
            }

            if (! $nextOccurrence || $occurrence->lt($nextOccurrence)) {
                $nextOccurrence = $occurrence;
            }
        }

        return $nextOccurrence;
    }

    private function getNextDailyOccurrence($startDateTime): Carbon
    {
        $now = Carbon::now();
        $occurrence = $now->copy()->setTimeFrom($startDateTime);
        if ($occurrence->isPast()) {
            $occurrence->addDay();
        }

        return $occurrence;
    }

    private function getNextWeeklyOccurrence($scheduleItem, $startDateTime): Carbon
    {
        $now = Carbon::now();
        $day = $scheduleItem['day'];
        $occurrence = $now->copy()->next($day)->setTimeFrom($startDateTime);
        if ($occurrence->isPast()) {
            $occurrence->addWeek();
        }

        return $occurrence;
    }

    private function getNextIntervalOccurrence($event, $startDateTime): Carbon
    {
        $now = Carbon::now();
        $occurrence = $now->copy()->startOfDay()->setTimeFrom($startDateTime);

        while ($occurrence->lte($now)) {
            $occurrence->addMinutes($event->interval_minutes);
        }

        return $occurrence;
    }
}
