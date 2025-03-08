<?php

use App\Enums\Game\ScheduledEventType;
use App\Services\ScheduledEventService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
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
            'events'    => $grouped->get('events', collect()),
            'invasions' => $grouped->get('invasions', collect())
        ];
    }
}; ?>

<flux:main container>
    <x-page-header
        :title="__('Time Your Adventures')"
        :kicker="__('Schedule')"
        :description="__('Stay ahead with real-time tracking of events and invasions across the realm.')"
    />

    <flux:tab.group class="max-w-2xl mx-auto">
        <flux:tabs variant="segmented" wire:model="tab" class="w-full">
            <flux:tab name="events">{{ __('Events') }}</flux:tab>
            <flux:tab name="invasions">{{ __('Invasions') }}</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="events">
            <div class="space-y-6">
                @foreach ($this->getFilteredEvents()['events'] as $event)
                    @unless($loop->first)
                        <flux:separator variant="subtle"/>
                    @endunless

                    <livewire:pages.guest.schedule.item :$event/>
                @endforeach
            </div>
        </flux:tab.panel>

        <flux:tab.panel name="invasions">
            <div class="space-y-6">
                @foreach ($this->getFilteredEvents()['invasions'] as $event)
                    @unless($loop->first)
                        <flux:separator variant="subtle"/>
                    @endunless

                    <livewire:pages.guest.schedule.item :$event/>
                @endforeach
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
