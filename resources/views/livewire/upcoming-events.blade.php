<div>
    <h2>Upcoming Events</h2>
    <ul>
        @foreach ($events as $event)
            <li>
                {{ $event['name'] }} -
                Next occurrence: {{ $event['start_time']->format('Y-m-d H:i:s') }} -
                Starts in
                <span x-data="{
                    countdown: '',
                    calculateCountdown() {
                        const now = new Date();
                        let start = new Date('{{ $event['start_time']->toIso8601String() }}');
                        let diff = start - now;

                        // If the event has started, calculate the next occurrence
                        if (diff <= 0) {
                            @if($event['recurrence_type'] === 'interval')
                                // For interval events, add the interval to get the next occurrence
                                start = new Date(start.getTime() + {{ $event['interval_minutes'] }} * 60000);
                            @elseif($event['recurrence_type'] === 'daily')
                                // For daily events, add a day
                                start.setDate(start.getDate() + 1);
                            @elseif($event['recurrence_type'] === 'weekly')
                                // For weekly events, add a week
                                start.setDate(start.getDate() + 7);
                            @endif
                            diff = start - now;
                        }

                        if (diff > 0) {
                            const hours = Math.floor(diff / 3600000);
                            const minutes = Math.floor((diff % 3600000) / 60000);
                            const seconds = Math.floor((diff % 60000) / 1000);
                            this.countdown = `${hours}h ${minutes}m ${seconds}s`;
                        } else {
                            this.countdown = 'Calculating next occurrence...';
                        }
                    }
                }" x-init="
                    calculateCountdown();
                    setInterval(() => calculateCountdown(), 1000)
                " x-text="countdown"></span>
            </li>
        @endforeach
    </ul>
</div>
