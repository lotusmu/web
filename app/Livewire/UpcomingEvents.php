<?php

namespace App\Livewire;

use App\Services\ScheduledEventService;
use Livewire\Component;

class UpcomingEvents extends Component
{
    public $events = [];

    public function mount(ScheduledEventService $eventService)
    {
        $this->events = $eventService->getUpcomingEvents();
    }

    public function render()
    {
        return view('livewire.upcoming-events');
    }
}
