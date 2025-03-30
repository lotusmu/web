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
            timeZoneConfig: {
                server: 'Europe/Sofia'
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

            parseISODate(isoString) {
                return new Date(isoString);
            },

            formatCountdown(milliseconds) {
                const totalHours = Math.floor(milliseconds / 3600000);
                const days = Math.floor(totalHours / 24);
                const hours = totalHours % 24;
                const minutes = Math.floor((milliseconds % 3600000) / 60000);
                const seconds = Math.floor((milliseconds % 60000) / 1000);

                if (totalHours < 24) {
                    return `${hours}h ${minutes}m ${seconds}s`;
                } else {
                    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
                }
            },

            // Calculate timezone offset between Sofia and local time
            getTimezoneOffsetDiff() {
                // Get current date
                const now = new Date();

                /* Original DST calculation (commented out)
                // Check if current date is within Bulgaria's DST period
                // DST starts on last Sunday of March (March 30, 2025)
                // DST ends on last Sunday of October (October 26, 2025)
                const year = now.getFullYear();
                const isDST = (() => {
                    // DST starts at 3:00 AM on the last Sunday of March
                    const dstStart = new Date(year, 2, 31); // March 31
                    dstStart.setDate(31 - dstStart.getDay()); // Last Sunday

                    // DST ends at 4:00 AM on the last Sunday of October
                    const dstEnd = new Date(year, 9, 31); // October 31
                    dstEnd.setDate(31 - dstEnd.getDay()); // Last Sunday

                    return now >= dstStart && now < dstEnd;
                })();

                // Sofia is UTC+3 during DST and UTC+2 during standard time
                const sofiaOffset = isDST ? 180 : 120;
                */

                // Fixed Sofia offset to UTC+2 (120 minutes) regardless of DST
                const sofiaOffset = 120;


                // Get local offset in minutes (positive is ahead of UTC)
                const localOffset = -now.getTimezoneOffset();

                // Return the difference (how many minutes to add/subtract)
                return localOffset - sofiaOffset;
            },

            // Convert Sofia time to local time
            convertSofiaTimeToLocal(timeStr, date = null) {
                if (!timeStr) return null;

                const [hours, minutes] = timeStr.split(':').map(Number);
                const baseDate = date || new Date();

                // Create a new date with the provided hours/minutes
                const result = new Date(
                    baseDate.getFullYear(),
                    baseDate.getMonth(),
                    baseDate.getDate(),
                    hours,
                    minutes
                );

                // Apply the offset difference to convert from Sofia to local time
                const offsetDiff = this.getTimezoneOffsetDiff();
                result.setMinutes(result.getMinutes() + offsetDiff);

                return result;
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

                    // Convert Sofia time to local time
                    let itemDate = this.convertSofiaTimeToLocal(item.time, now);

                    // Adjust for weekly recurrence
                    if (eventConfig.recurrenceType === 'weekly' && item.day) {
                        const days = this.getDays();
                        const jsDay = now.getDay();
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

                        // Convert Sofia time to local time
                        const eventStart = this.convertSofiaTimeToLocal(item.time, checkDay);
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
                this.serverStartTime = this.parseISODate(eventConfig.startTime);
                this.calculateCountdown();
                setInterval(() => this.calculateCountdown(), 1000);
            }
        };
    }
</script>
