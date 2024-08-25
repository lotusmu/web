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
    <ul>
        @foreach ($events as $event)
            <li x-data="{
                countdown: '',
                nextOccurrence: '',
                totalSeconds: 0,
                isHighlighted: false,
                highlightThreshold: 55, // Highlight threshold in seconds
                highlightStyle: {
                    color: '#00AAAA',
                    fontWeight: 'bold'
                },
                calculateNextOccurrence(startTime, recurrenceType, intervalMinutes) {
                    const now = new Date();
                    let start = new Date(startTime);

                    while (start <= now) {
                        if (recurrenceType === 'interval') {
                            start = new Date(start.getTime() + intervalMinutes * 60000);
                        } else if (recurrenceType === 'daily') {
                            start.setDate(start.getDate() + 1);
                        } else if (recurrenceType === 'weekly') {
                            start.setDate(start.getDate() + 7);
                        }
                    }

                    return start;
                },
                calculateCountdown() {
                    const now = new Date();
                    let start = this.calculateNextOccurrence('{{ $event['start_time']->toIso8601String() }}', '{{ $event['recurrence_type'] }}', {{ $event['interval_minutes'] ?? 0 }});

                    this.nextOccurrence = start.toLocaleString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: false });

                    let diff = start.getTime() - now.getTime();
                    this.totalSeconds = Math.floor(diff / 1000);
                    this.isHighlighted = this.totalSeconds <= this.highlightThreshold && this.totalSeconds > 0;

                    if (diff > 0) {
                        const hours = Math.floor(diff / 3600000);
                        const minutes = Math.floor((diff % 3600000) / 60000);
                        const seconds = Math.floor((diff % 60000) / 1000);
                        this.countdown = `${hours}h ${minutes}m ${seconds}s`;
                    } else {
                        this.countdown = 'Event is occurring now!';
                        this.totalSeconds = 0;
                        this.isHighlighted = false;
                    }
                }
            }"
                x-init="
                calculateCountdown();
                setInterval(() => calculateCountdown(), 1000);
            "
                x-bind:style="isHighlighted ? highlightStyle : {}"
                x-bind:class="{ 'pulse': isHighlighted }"
                class="transition-all duration-300 ease-in-out"
            >
                {{ $event['name'] }} -
                Next occurrence: <span x-text="nextOccurrence"></span> -
                Starts in <span x-text="countdown"></span>
            </li>
        @endforeach
    </ul>
</div>
