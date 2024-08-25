@php use App\Enums\ScheduledEventType; @endphp
<div>
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

    <h2>Upcoming Events</h2>

    <!-- List for 'event' events -->
    <h3>Events</h3>
    <ul>
        @foreach ($events as $event)
            @if ($event['type'] === ScheduledEventType::EVENT)
                @include('livewire.partials.event', ['event' => $event])
            @endif
        @endforeach
    </ul>

    <!-- List for 'invasion' events -->
    <h3>Invasions</h3>
    <ul>
        @foreach ($events as $event)
            @if ($event['type'] === ScheduledEventType::INVASION)
                @include('livewire.partials.event', ['event' => $event])
            @endif
        @endforeach
    </ul>
</div>
