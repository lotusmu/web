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
            const durationMinutes = {{ $event['duration_minutes'] ?? 0 }};

            if (!isEventType || durationMinutes <= 0) return false;

            // Calculate how many days back we need to check based on duration
            const durationInMs = durationMinutes * 60 * 1000;
            const daysToCheck = Math.ceil(durationInMs / (24 * 60 * 60 * 1000));
            const recurrenceType = '{{ $event['recurrence_type'] }}';
            const schedule = JSON.parse('{{ json_encode($event['schedule']) }}');

            // For interval type, handle it specially
            if (recurrenceType === 'interval') {
                return this.checkIntervalCurrentlyRunning();
            }

            // Check for each potential start day (today and previous days based on duration)
            for (let dayOffset = 0; dayOffset < daysToCheck; dayOffset++) {
                const checkDay = new Date(now);
                checkDay.setDate(checkDay.getDate() - dayOffset);

                // Process schedule items for the day we're checking
                for (const item of schedule) {
                    if (!item || !item.time) continue;

                    // For weekly events, check if the day is correct
                    if (recurrenceType === 'weekly' && item.day) {
                        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        const jsDay = checkDay.getDay();
                        const customDayIndex = jsDay === 0 ? 6 : jsDay - 1; // Convert JS day to custom day index
                        const checkDayName = days[customDayIndex];

                        // Skip if the day we're checking is not the scheduled day
                        if (checkDayName !== item.day) {
                            continue;
                        }
                    }

                    const [hours, minutes] = item.time.split(':').map(Number);
                    const eventStart = new Date(
                        checkDay.getFullYear(),
                        checkDay.getMonth(),
                        checkDay.getDate(),
                        hours,
                        minutes
                    );
                    const eventEnd = new Date(eventStart.getTime() + durationInMs);

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

        // Separate method for interval recurrence type
        checkIntervalCurrentlyRunning() {
            const now = new Date();
            const intervalMinutes = {{ $event['interval_minutes'] ?? 60 }};
            const durationMinutes = {{ $event['duration_minutes'] ?? 0 }};
            const initialStartTime = new Date('{{ $event['start_time']->toIso8601String() }}');

            // Calculate milliseconds for interval and duration
            const intervalMs = intervalMinutes * 60 * 1000;
            const durationMs = durationMinutes * 60 * 1000;

            // Calculate time elapsed since the initial start
            const msSinceStart = now.getTime() - initialStartTime.getTime();

            // Find the most recent interval start time
            const intervalsPassed = Math.floor(msSinceStart / intervalMs);
            const lastIntervalStart = new Date(initialStartTime.getTime() + (intervalsPassed * intervalMs));
            const nextIntervalEnd = new Date(lastIntervalStart.getTime() + durationMs);

            // Check if now is within the duration window of the most recent interval
            if (now >= lastIntervalStart && now < nextIntervalEnd) {
                this.isCurrentlyRunning = true;
                this.isHighlighted = true;
                this.nextOccurrence = this.formatDateTime(lastIntervalStart);
                this.nextEndTime = this.formatDateTime(nextIntervalEnd);
                this.countdown = '{{ __('Active now') }}';
                return true;
            }

            return false;
        },

        checkEventRunningPastStartTime(startTime) {
            const now = new Date();
            const isEventType = '{{ $event['type']->value }}' === 'event';
            const hasDuration = {{ $event['duration_minutes'] ?? 0 }} > 0;
            const recurrenceType = '{{ $event['recurrence_type'] }}';

            if (isEventType && hasDuration) {
                const durationMs = {{ $event['duration_minutes'] ?? 0 }} * 60 * 1000;

                if (recurrenceType === 'interval') {
                    // For interval type, we need to find the most recent occurrence
                    const intervalMinutes = {{ $event['interval_minutes'] ?? 60 }};
                    const intervalMs = intervalMinutes * 60 * 1000;
                    const initialStart = new Date('{{ $event['start_time']->toIso8601String() }}');

                    // Find how many intervals have passed
                    const msSinceInitial = now.getTime() - initialStart.getTime();
                    const intervalsPassed = Math.floor(msSinceInitial / intervalMs);

                    // Find the most recent start time
                    const lastStart = new Date(initialStart.getTime() + (intervalsPassed * intervalMs));
                    const nextEnd = new Date(lastStart.getTime() + durationMs);

                    if (now >= lastStart && now < nextEnd) {
                        this.isCurrentlyRunning = true;
                        this.isHighlighted = true;
                        this.nextOccurrence = this.formatDateTime(lastStart);
                        this.nextEndTime = this.formatDateTime(nextEnd);
                        this.countdown = '{{ __('Active now') }}';
                        return true;
                    }
                } else {
                    // Original code for other recurrence types
                    const diff = startTime.getTime() - now.getTime();
                    if (diff <= 0) {
                        const endTime = new Date(startTime.getTime() + durationMs);
                        this.nextEndTime = this.formatDateTime(endTime);

                        if (now < endTime) {
                            this.isCurrentlyRunning = true;
                            this.isHighlighted = true;
                            this.countdown = '{{ __('Active now') }}';
                            return true;
                        }
                    }
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
