<li x-data="{
    countdown: '',
    nextOccurrence: '',
    totalSeconds: 0,
    isHighlighted: false,
    highlightThreshold: 55,
    highlightStyle: {
        color: '#00AAAA',
        fontWeight: 'bold'
    },
       calculateNextOccurrence(startTime, recurrenceType, intervalMinutes) {
    const now = new Date();
    let start = new Date(startTime);

    if (recurrenceType === 'weekly') {
        const dayDiff = (start.getDay() - now.getDay() + 7) % 7;

        start.setDate(now.getDate() + dayDiff);
        start.setFullYear(now.getFullYear());
        start.setMonth(now.getMonth());

        if (start <= now) {
            start.setDate(start.getDate() + 7);
        }
    } else if (recurrenceType === 'daily') {
        start.setFullYear(now.getFullYear());
        start.setMonth(now.getMonth());
        start.setDate(now.getDate());

        if (start <= now) {
            start.setDate(start.getDate() + 1);
        }
    } else if (recurrenceType === 'interval') {
        const minutesSinceStart = (now - start) / 60000;
        const intervalsPassed = Math.floor(minutesSinceStart / intervalMinutes);
        start = new Date(start.getTime() + (intervalsPassed + 1) * intervalMinutes * 60000);
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
    {{ $event['name'] }} ({{ $event['type']->getLabel() }}) -
    Next occurrence: <span x-text="nextOccurrence"></span> -
    Starts in <span x-text="countdown"></span>
</li>
