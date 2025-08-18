<?php

namespace App\Livewire\Pages\Guest\Schedule;

use App\Enums\Game\ScheduledEventType;
use App\Livewire\BaseComponent;
use App\Services\ScheduledEventService;

class Schedule extends BaseComponent
{
    public $events = [];

    #[\Livewire\Attributes\Url]
    public string $tab = 'events';

    public function mount(ScheduledEventService $eventService): void
    {
        $this->events = $eventService->getUpcomingEvents();
    }

    public function getFilteredEvents()
    {
        $grouped = collect($this->events)->groupBy(function ($event) {
            return $event['type'] === ScheduledEventType::EVENT ? 'events' : 'invasions';
        });

        return [
            'events' => $grouped->get('events', collect()),
            'invasions' => $grouped->get('invasions', collect()),
        ];
    }

    protected function getViewName(): string
    {
        return 'pages.guest.schedule.index';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
