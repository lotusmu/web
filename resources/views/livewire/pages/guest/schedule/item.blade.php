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
                        // Define days with Monday as first day (index 0)
                        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        // Adjust for JavaScript's getDay() (0=Sunday, 1=Monday)
                        const jsDay = itemDate.getDay();
                        // Convert JS day to your custom day index (making Monday index 0)
                        const customDayIndex = jsDay === 0 ? 6 : jsDay - 1;
                        const dayDiff = (days.indexOf(item.day) - customDayIndex + 7) % 7;
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

        formatDateTime(date) {
            return date.toLocaleString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        },

        formatCountdown(milliseconds) {
            const hours = Math.floor(milliseconds / 3600000);
            const minutes = Math.floor((milliseconds % 3600000) / 60000);
            const seconds = Math.floor((milliseconds % 60000) / 1000);
            return `${hours}h ${minutes}m ${seconds}s`;
        },

        checkCurrentlyRunning() {
            const now = new Date();
            const isEventType = '{{ $event['type']->value }}' === 'event';
            const hasDuration = {{ $event['duration_minutes'] ?? 0 }} > 0;
            const recurrenceType = '{{ $event['recurrence_type'] }}';

            if (!isEventType || !hasDuration) return false;

            // Check today's scheduled occurrence
            const today = new Date();
            const schedule = JSON.parse('{{ json_encode($event['schedule']) }}');

            // Process schedule items for today
            for (const item of schedule) {
                if (item && item.time) {
                    // For weekly events, check if today is the correct day
                    if (recurrenceType === 'weekly' && item.day) {
                        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        const jsDay = today.getDay();
                        const customDayIndex = jsDay === 0 ? 6 : jsDay - 1; // Convert JS day to custom day index
                        const todayDayName = days[customDayIndex];

                        // Skip if today is not the scheduled day
                        if (todayDayName !== item.day) {
                            continue;
                        }
                    }

                    const [hours, minutes] = item.time.split(':').map(Number);
                    const eventStart = new Date(today.getFullYear(), today.getMonth(), today.getDate(), hours, minutes);
                    const eventEnd = new Date(eventStart.getTime() + ({{ $event['duration_minutes'] ?? 0 }} * 60 * 1000));

                    // If current time is between event start and end, it's active
                    if (now >= eventStart && now < eventEnd) {
                        this.isCurrentlyRunning = true;
                        this.isHighlighted = true;
                        this.nextOccurrence = this.formatDateTime(eventStart);
                        this.nextEndTime = this.formatDateTime(eventEnd);
                        this.countdown = '{{ __('Active now') }}';

                        return true;
                    }
                }
            }

            return false;
        },

        checkEventRunningPastStartTime(startTime) {
            const now = new Date();
            const isEventType = '{{ $event['type']->value }}' === 'event';
            const hasDuration = {{ $event['duration_minutes'] ?? 0 }} > 0;
            const diff = startTime.getTime() - now.getTime();

            if (isEventType && hasDuration && diff <= 0) {
                const durationMs = {{ $event['duration_minutes'] ?? 0 }} * 60 * 1000;
                const endTime = new Date(startTime.getTime() + durationMs);

                this.nextEndTime = this.formatDateTime(endTime);

                if (now < endTime) {
                    this.isCurrentlyRunning = true;
                    this.isHighlighted = true;
                    this.countdown = '{{ __('Active now') }}';
                    return true;
                }
            }

            return false;
        },

        handleFutureEvent(diff) {
            this.isCurrentlyRunning = false;
            this.isHighlighted = this.totalSeconds <= this.highlightThreshold && this.totalSeconds > 0;
            this.countdown = this.formatCountdown(diff);
        },

        handlePastEvent(startDate) {
            const now = new Date();
            const pastStart = new Date(startDate);
            const recurrence = '{{ $event['recurrence_type'] }}';
            let nextStart = new Date(pastStart);

            // Calculate next occurrence based on recurrence type
            if (recurrence === 'daily') {
                nextStart.setDate(nextStart.getDate() + 1);
            } else if (recurrence === 'weekly') {
                nextStart.setDate(nextStart.getDate() + 7);
            } else if (recurrence === 'interval') {
                const intervalMin = {{ $event['interval_minutes'] ?? 60 }};
                nextStart = new Date(pastStart.getTime() + intervalMin * 60000);
            }

            // Recalculate with the next occurrence
            const diff = nextStart.getTime() - now.getTime();
            this.totalSeconds = Math.floor(diff / 1000);
            this.nextOccurrence = this.formatDateTime(nextStart);
            this.countdown = this.formatCountdown(diff);
        },

        calculateCountdown() {
            if (!this.isActive) {
                this.countdown = '';
                this.isHighlighted = false;
                return;
            }

            // First check if event is currently running
            if (this.checkCurrentlyRunning()) {
                return;
            }

            // Calculate next occurrence
            const schedule = JSON.parse('{{ json_encode($event['schedule']) }}');
            let start = this.calculateNextOccurrence(
                '{{ $event['start_time']->toIso8601String() }}',
                '{{ $event['recurrence_type'] }}',
                schedule,
                {{ $event['interval_minutes'] ?? 0 }}
            );

            this.nextOccurrence = this.formatDateTime(start);

            const now = new Date();
            let diff = start.getTime() - now.getTime();
            this.totalSeconds = Math.floor(diff / 1000);

            // Check if event is running past its start time
            if (this.checkEventRunningPastStartTime(start)) {
                return;
            }

            // Handle countdown for future or past events
            if (diff > 0) {
                this.handleFutureEvent(diff);
            } else {
                this.handlePastEvent(start);
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
