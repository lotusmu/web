<?php

use App\Models\Content\ScheduledEvent;
use Livewire\Volt\Component;
use App\Enums\Game\ScheduledEventType;

new class extends Component {
    public $event;
    public $highlightThreshold = 300;
}; ?>

<div
    x-data="eventCountdown({
        isActive: {{ $event['is_active'] ? 'true' : 'false' }},
        highlightThreshold: {{ $highlightThreshold }},
        eventType: '{{ $event['type']->value }}',
        recurrenceType: '{{ $event['recurrence_type'] }}',
        startTime: '{{ $event['start_time']->toIso8601String() }}',
        durationMinutes: {{ $event['duration_minutes'] ?? 0 }},
        intervalMinutes: {{ $event['interval_minutes'] ?? 60 }},
        schedule: {{ json_encode($event['schedule']) }},
        activeNowText: '{{ __('Active now') }}'
    })"
    x-init="init()"
    x-bind:class="{ 'pulse': isHighlighted }"
    class="flex flex-col space-y-2"
>
    <flux:heading>
        {{ $event['name'] }}
    </flux:heading>

    <div class="flex items-center justify-between">
        <flux:text>
            <span
                x-text="isCurrentlyRunning ? '{{ __('Running until:') }}' : '{{ __('Scheduled for:') }}'"></span>
            <span
                x-text="isActive ? (isCurrentlyRunning ? nextEndTime : nextOccurrence) : '{{ __('Coming soon') }}'"></span>
        </flux:text>

        <flux:text x-text="isActive ? countdown : ''"
                   x-bind:style="isHighlighted ? highlightStyle : {}">
        </flux:text>
    </div>
</div>

<script src="{{ asset('js/event-countdown.js') }}"></script>
