<?php

namespace App\Livewire\Pages\Guest\Schedule;

use App\Models\Content\ScheduledEvent;
use App\Enums\Game\ScheduledEventType;
use App\Livewire\BaseComponent;

class Item extends BaseComponent
{
    public $event;
        public $highlightThreshold = 300;

    protected function getViewName(): string
    {
        return 'pages.guest.schedule.item';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}