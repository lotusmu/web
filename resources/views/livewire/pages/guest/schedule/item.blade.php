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

<script>
    window.eventCountdown = function (eventConfig) {
        return {
            // State variables
            countdown: '',
            nextOccurrence: '',
            nextEndTime: '',
            totalSeconds: 0,
            isHighlighted: false,
            isActive: eventConfig.isActive,
            isCurrentlyRunning: false,
            highlightThreshold: eventConfig.highlightThreshold,
            highlightStyle: {
                color: '#00AAAA',
                fontWeight: 'bold'
            },

            // Formatting methods
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

            // Day conversion helpers
            getDays() {
                return ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            },

            getDayIndex(jsDay) {
                return jsDay === 0 ? 6 : jsDay - 1;
            },

            getDayName(date) {
                const days = this.getDays();
                const jsDay = date.getDay();
                const customDayIndex = this.getDayIndex(jsDay);
                return days[customDayIndex];
            },

            // Get interval calculation data
            getIntervalData(now) {
                const initialStartTime = new Date(eventConfig.startTime);
                const intervalMs = eventConfig.intervalMinutes * 60 * 1000;
                const durationMs = eventConfig.durationMinutes * 60 * 1000;

                const msSinceStart = now.getTime() - initialStartTime.getTime();
                const intervalsPassed = Math.floor(msSinceStart / intervalMs);
                const lastStart = new Date(initialStartTime.getTime() + (intervalsPassed * intervalMs));
                const nextStart = new Date(initialStartTime.getTime() + (intervalsPassed + 1) * intervalMs);
                const nextEnd = new Date(lastStart.getTime() + durationMs);

                return {
                    lastStart,
                    nextStart,
                    nextEnd,
                    durationMs,
                    isRunning: (now >= lastStart && now < nextEnd)
                };
            },

            // Calculate the next occurrence of the event
            getNextOccurrence(now) {
                // Handle interval recurrence
                if (eventConfig.recurrenceType === 'interval') {
                    const data = this.getIntervalData(now);
                    return data.nextStart;
                }

                // Handle weekly and daily recurrence
                let nextOccurrence = null;

                eventConfig.schedule.forEach(item => {
                    if (!item || !item.time) return;

                    let [hours, minutes] = item.time.split(':').map(Number);
                    let itemDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);

                    // Adjust for weekly recurrence
                    if (eventConfig.recurrenceType === 'weekly' && item.day) {
                        const days = this.getDays();
                        const jsDay = itemDate.getDay();
                        const customDayIndex = this.getDayIndex(jsDay);
                        const scheduledDayIndex = days.indexOf(item.day);
                        const dayDiff = (scheduledDayIndex - customDayIndex + 7) % 7;

                        itemDate.setDate(itemDate.getDate() + dayDiff);
                    }

                    // If this time has already passed today, move to next occurrence
                    while (itemDate <= now) {
                        itemDate.setDate(itemDate.getDate() + (eventConfig.recurrenceType === 'weekly' ? 7 : 1));
                    }

                    // Keep the earliest occurrence
                    if (!nextOccurrence || itemDate < nextOccurrence) {
                        nextOccurrence = itemDate;
                    }
                });

                return nextOccurrence || new Date(eventConfig.startTime);
            },

            // Check if an event with duration is currently running
            checkCurrentlyRunning(now) {
                // Quick validation - only events with duration can be running
                if (eventConfig.eventType !== 'event' || eventConfig.durationMinutes <= 0) {
                    return false;
                }

                // Special handling for interval type
                if (eventConfig.recurrenceType === 'interval') {
                    const data = this.getIntervalData(now);
                    if (data.isRunning) {
                        return {
                            isRunning: true,
                            startTime: data.lastStart,
                            endTime: data.nextEnd
                        };
                    }
                    return false;
                }

                // For daily and weekly events, check recent occurrences
                const durationMs = eventConfig.durationMinutes * 60 * 1000;
                const daysToCheck = Math.ceil(durationMs / (24 * 60 * 60 * 1000));

                // Check for each potential start day (today and previous days)
                for (let dayOffset = 0; dayOffset < daysToCheck; dayOffset++) {
                    const checkDay = new Date(now);
                    checkDay.setDate(checkDay.getDate() - dayOffset);

                    // Process schedule items for the day we're checking
                    for (const item of eventConfig.schedule) {
                        if (!item || !item.time) continue;

                        // For weekly events, check if the day is correct
                        if (eventConfig.recurrenceType === 'weekly' && item.day) {
                            const checkDayName = this.getDayName(checkDay);

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
                        const eventEnd = new Date(eventStart.getTime() + durationMs);

                        if (now >= eventStart && now < eventEnd) {
                            return {
                                isRunning: true,
                                startTime: eventStart,
                                endTime: eventEnd
                            };
                        }
                    }
                }

                return false;
            },

            // Main countdown calculation function
            calculateCountdown() {
                if (!this.isActive) {
                    this.countdown = '';
                    this.isHighlighted = false;
                    return;
                }

                const now = new Date();

                // Check if event is currently running
                const runningState = this.checkCurrentlyRunning(now);

                if (runningState && runningState.isRunning) {
                    this.isCurrentlyRunning = true;
                    this.isHighlighted = true;
                    this.nextOccurrence = this.formatDateTime(runningState.startTime);
                    this.nextEndTime = this.formatDateTime(runningState.endTime);
                    this.countdown = eventConfig.activeNowText;
                    return;
                }

                // Get the next occurrence time
                const nextStart = this.getNextOccurrence(now);

                // Set the display values
                this.nextOccurrence = this.formatDateTime(nextStart);

                // Calculate remaining time
                const diff = nextStart.getTime() - now.getTime();
                this.totalSeconds = Math.floor(diff / 1000);

                // Reset running state (since we confirmed it's not running)
                this.isCurrentlyRunning = false;

                // Highlight if within threshold
                this.isHighlighted = this.totalSeconds <= this.highlightThreshold && this.totalSeconds > 0;

                // Format countdown display
                this.countdown = diff > 0
                    ? this.formatCountdown(diff)
                    : this.formatCountdown(1000); // Fallback if diff is negative
            },

            init() {
                this.calculateCountdown();
                setInterval(() => this.calculateCountdown(), 1000);
            }
        };
    }
</script>
