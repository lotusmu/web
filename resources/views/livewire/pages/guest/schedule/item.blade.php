<?php

use App\Models\Content\ScheduledEvent;
use Livewire\Volt\Component;
use App\Enums\Game\ScheduledEventType;


new class extends Component {
    public $event;
    public $highlightThreshold = 300;
}; ?>


<div
    x-data="{
    countdown: '',
    nextOccurrence: '',
    nextEndTime: '',
    totalSeconds: 0,
    isHighlighted: false,
    isActive: {{ $event['is_active'] ? 'true' : 'false' }},
    isCurrentlyRunning: false,
    highlightThreshold: {{ $highlightThreshold }},
    highlightStyle: {
        color: '#00AAAA',
        fontWeight: 'bold'
    },

    calculateNextOccurrence(startTime, recurrenceType, schedule, intervalMinutes) {
        const now = new Date();
        let nextOccurrence = null;

        if (recurrenceType === 'weekly' || recurrenceType === 'daily') {
            schedule.forEach(item => {
                let [hours, minutes] = item.time.split(':').map(Number);
                let itemDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);

                if (recurrenceType === 'weekly') {
                    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                    const dayDiff = (days.indexOf(item.day) - itemDate.getDay() + 7) % 7;
                    itemDate.setDate(itemDate.getDate() + dayDiff);
                }

                while (itemDate <= now) {
                    itemDate.setDate(itemDate.getDate() + (recurrenceType === 'weekly' ? 7 : 1));
                }

                if (!nextOccurrence || itemDate < nextOccurrence) {
                    nextOccurrence = itemDate;
                }
            });
        } else if (recurrenceType === 'interval') {
            let start = new Date(startTime);
            const minutesSinceStart = (now - start) / 60000;
            const intervalsPassed = Math.floor(minutesSinceStart / intervalMinutes);
            nextOccurrence = new Date(start.getTime() + (intervalsPassed + 1) * intervalMinutes * 60000);
        }

        return nextOccurrence || new Date(startTime);
    },

    calculateCountdown() {
        if (!this.isActive) {
            this.countdown = '';
            this.isHighlighted = false;
            return;
        }

        const now = new Date();
        const isEventType = '{{ $event['type']->value }}' === 'event';
        const hasDuration = {{ $event['duration_minutes'] ?? 0 }} > 0;

        // First check if today has an event that should be active now
        if (isEventType && hasDuration) {
            // Check today's scheduled occurrence
            const today = new Date();
            const schedule = JSON.parse('{{ json_encode($event['schedule']) }}');

            // Process schedule items for today
            for (const item of schedule) {
                if (item && item.time) {
                    const [hours, minutes] = item.time.split(':').map(Number);
                    const eventStart = new Date(today.getFullYear(), today.getMonth(), today.getDate(), hours, minutes);
                    const eventEnd = new Date(eventStart.getTime() + ({{ $event['duration_minutes'] ?? 0 }} * 60 * 1000));

                    // If current time is between event start and end, it's active
                    if (now >= eventStart && now < eventEnd) {
                        this.isCurrentlyRunning = true;
                        this.isHighlighted = true;
                        this.nextOccurrence = eventStart.toLocaleString('en-US', {
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', hour12: false
                        });
                        this.nextEndTime = eventEnd.toLocaleString('en-US', {
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', hour12: false
                        });
                        this.countdown = '{{ __('Active now') }}';

                        console.log('Event IS ACTIVE!', {
                            now: now.toISOString(),
                            eventStart: eventStart.toISOString(),
                            eventEnd: eventEnd.toISOString()
                        });

                        return;
                    }
                }
            }
        }

        const schedule = JSON.parse('{{ json_encode($event['schedule']) }}');
        let start = this.calculateNextOccurrence(
            '{{ $event['start_time']->toIso8601String() }}',
            '{{ $event['recurrence_type'] }}',
            schedule,
            {{ $event['interval_minutes'] ?? 0 }}
        );

        this.nextOccurrence = start.toLocaleString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        let diff = start.getTime() - now.getTime();
        this.totalSeconds = Math.floor(diff / 1000);

        // Simple check - if diff is negative (start time has passed) but within duration window
        if (isEventType && hasDuration && diff <= 0) {
            const durationMs = {{ $event['duration_minutes'] ?? 0 }} * 60 * 1000;
            const endTime = new Date(start.getTime() + durationMs);

            // Format the end time for display
            this.nextEndTime = endTime.toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            // If current time is before the end time, event is running
            if (now < endTime) {
                this.isCurrentlyRunning = true;
                this.isHighlighted = true;
                this.countdown = '{{ __('Active now') }}';

                console.log('Event IS active:', {
                    now: now.toISOString(),
                    start: start.toISOString(),
                    end: endTime.toISOString(),
                    diff: diff,
                    isEventType: isEventType,
                    hasDuration: hasDuration
                });

                return;
            }
        }

        // If we're here, event is not currently running
        this.isCurrentlyRunning = false;
        this.isHighlighted = this.totalSeconds <= this.highlightThreshold && this.totalSeconds > 0;

        if (diff > 0) {
            // Future event - show countdown
            const hours = Math.floor(diff / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            this.countdown = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            // Past event - find next occurrence
            const pastStart = new Date(start);
            const recurrence = '{{ $event['recurrence_type'] }}';

            // Calculate next occurrence based on recurrence type
            if (recurrence === 'daily') {
                start.setDate(start.getDate() + 1);
            } else if (recurrence === 'weekly') {
                start.setDate(start.getDate() + 7);
            } else if (recurrence === 'interval') {
                const intervalMin = {{ $event['interval_minutes'] ?? 60 }};
                start = new Date(pastStart.getTime() + intervalMin * 60000);
            }

            // Recalculate with the next occurrence
            diff = start.getTime() - now.getTime();
            this.totalSeconds = Math.floor(diff / 1000);
            this.nextOccurrence = start.toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            const hours = Math.floor(diff / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            this.countdown = `${hours}h ${minutes}m ${seconds}s`;
        }
    }
    }"
    x-init="calculateCountdown(); setInterval(() => calculateCountdown(), 1000);"
    x-bind:class="{ 'pulse': isHighlighted }"
    class="flex flex-col space-y-2"
>
    <flux:heading>
        {{ $event['name'] }}
    </flux:heading>

    <div class="flex items-center justify-between">
        <flux:text>
            <span
                x-text="$data.isCurrentlyRunning ? '{{ __('Running until:') }}' : '{{ __('Scheduled for:') }}'"></span>
            <span
                x-text="$data.isActive ? ($data.isCurrentlyRunning ? $data.nextEndTime : $data.nextOccurrence) : '{{ __('Coming soon') }}'"></span>
        </flux:text>

        <flux:text x-text="$data.isActive ? $data.countdown : ''"
                   x-bind:style="$data.isHighlighted ? $data.highlightStyle : {}">
        </flux:text>
    </div>
</div>
