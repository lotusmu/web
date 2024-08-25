<?php

namespace App\Services;

use App\Models\Content\ScheduledEvent;
use Carbon\Carbon;

class ScheduledEventService
{
    public function getUpcomingEvents(int $limit = 10)
    {
        return ScheduledEvent::active()
            ->get()
            ->map(function ($event) {
                $nextOccurrence = $event->getNextOccurrence();

                return $nextOccurrence ? $this->formatEventData($event, $nextOccurrence) : null;
            })
            ->filter()
            ->sortBy('start_time')
            ->take($limit);
    }

    private function formatEventData(ScheduledEvent $event, Carbon $startTime): array
    {
        return [
            'event_id' => $event->id,
            'name' => $event->name,
            'start_time' => $startTime,
            'recurrence_type' => $event->recurrence_type,
            'interval_minutes' => $event->interval_minutes,
        ];
    }
}
