<?php

namespace App\Services;

use App\Models\Content\ScheduledEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduledEventService
{
    public function getUpcomingEvents(int $limit = 10): Collection
    {
        return ScheduledEvent::active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($event) {
                $nextOccurrence = $event->getNextOccurrence();

                return $nextOccurrence ? $this->formatEventData($event, $nextOccurrence) : null;
            })
            ->filter()
            ->values()
            ->take($limit);
    }

    private function formatEventData(ScheduledEvent $event, Carbon $startTime): array
    {
        return [
            'event_id' => $event->id,
            'name' => $event->name,
            'type' => $event->type,
            'start_time' => $startTime,
            'recurrence_type' => $event->recurrence_type,
            'interval_minutes' => $event->interval_minutes,
            'sort_order' => $event->sort_order,
            'schedule' => $event->schedule,
        ];
    }
}
