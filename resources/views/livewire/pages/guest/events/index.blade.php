<?php

use App\Enums\Game\ScheduledEventType;
use App\Services\ScheduledEventService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $events = [];

    public function mount(ScheduledEventService $eventService): void
    {
        $this->events = $eventService->getUpcomingEvents();
    }
}; ?>

<flux:main container>
    <x-page-hero
        title="Join the Hunt"
        kicker="Events"
        description="Stay updated with upcoming events and invasions in the world of Lotus Mu."
    />

    <div class="space-y-12">
        <!-- Events -->
        <section>
            <flux:heading size="lg" class="mb-6">{{ __('Events') }}</flux:heading>

            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($this->events as $event)
                    @if ($event['type'] === ScheduledEventType::EVENT)
                        @include('livewire.partials.event', ['event' => $event])

                    @endif
                @endforeach
            </div>
        </section>

        <!-- Invasions -->
        <section>
            <flux:heading size="lg" class="mb-6">{{ __('Invasions') }}</flux:heading>

            <div class="grid md:grid-cols-2 gap-4">
                @foreach ($this->events as $event)
                    <!-- Changed from $events to $this->events -->
                    @if ($event['type'] === ScheduledEventType::INVASION)
                        @include('livewire.partials.event', ['event' => $event])
                    @endif
                @endforeach
            </div>
        </section>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</flux:main>
