<?php

namespace App\Livewire;

use App\Services\ScheduledEventService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;

class UpcomingEvents extends Component
{
    public $events = [];

    public function mount(ScheduledEventService $eventService): void
    {
        $this->events = $eventService->getUpcomingEvents();
    }

    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.upcoming-events');
    }
}
