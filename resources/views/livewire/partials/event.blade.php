<li x-data="{
    countdown: '',
    nextOccurrence: '',
    totalSeconds: 0,
    isHighlighted: false,
    highlightThreshold: 115,
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

                // If the calculated time is in the past, move to next occurrence
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
        const now = new Date();
        const schedule = JSON.parse('{{ json_encode($event['schedule']) }}');
        let start = this.calculateNextOccurrence(
            '{{ $event['start_time']->toIso8601String() }}',
            '{{ $event['recurrence_type'] }}',
            schedule,
            {{ $event['interval_minutes'] ?? 0 }}
        );

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
    {{ $event['name'] }} ({{ $event['type']->getLabel() }}) -
    Next occurrence: <span x-text="nextOccurrence"></span> -
    Starts in <span x-text="countdown"></span>
</li>
