<?php

use App\Enums\Game\ScheduledEventType;
use App\Models\Content\ScheduledEvent;
use Carbon\Carbon;

it('can create a scheduled event', function () {
    $event = ScheduledEvent::factory()->create();

    expect($event)->toBeInstanceOf(ScheduledEvent::class);
    $this->assertModelExists($event);
});

it('can activate a scheduled event', function () {
    $event = ScheduledEvent::factory()->inactive()->create();

    $event->activate();

    expect($event->is_active)->toBeTrue();
});

it('can deactivate a scheduled event', function () {
    $event = ScheduledEvent::factory()->active()->create();

    $event->deactivate();

    expect($event->is_active)->toBeFalse();
});

it('can get active scheduled events', function () {
    ScheduledEvent::factory()->count(3)->active()->create();
    ScheduledEvent::factory()->count(2)->inactive()->create();

    $activeEvents = ScheduledEvent::active()->get();

    expect($activeEvents)->toHaveCount(3);
});

it('can get the next occurrence for a daily event', function () {
    $event = ScheduledEvent::factory()->daily()->create();

    $nextOccurrence = $event->getNextOccurrence();
    $scheduleTime = Carbon::createFromFormat('H:i', $event->schedule[0]['time']);
    $expectedNextOccurrence = Carbon::today()->setTimeFrom($scheduleTime);

    if ($expectedNextOccurrence->isPast()) {
        $expectedNextOccurrence->addDay();
    }

    expect($nextOccurrence->format('Y-m-d H:i'))->toBe($expectedNextOccurrence->format('Y-m-d H:i'));
});

it('can get the next occurrence for a weekly event', function () {
    $event = ScheduledEvent::factory()->weekly()->create();

    $nextOccurrence = $event->getNextOccurrence();
    $scheduleDay = $event->schedule[0]['day'];
    $scheduleTime = Carbon::createFromFormat('H:i', $event->schedule[0]['time']);
    $expectedNextOccurrence = Carbon::parse("next {$scheduleDay}")->setTimeFrom($scheduleTime);

    expect($nextOccurrence->format('Y-m-d H:i'))->toBe($expectedNextOccurrence->format('Y-m-d H:i'));
});

it('can get the next occurrence for an interval event', function () {
    $now = Carbon::parse('2023-01-01 12:00:00');
    Carbon::setTestNow($now);

    $event = ScheduledEvent::factory()->interval()->create();

    $nextOccurrence = $event->getNextOccurrence();
    $scheduleTime = Carbon::createFromFormat('H:i', $event->schedule[0]['time']);
    $initialStartTime = $now->copy()->setTimeFrom($scheduleTime);
    $minutesSinceStart = $now->diffInMinutes($initialStartTime);
    $intervalsPassed = floor($minutesSinceStart / $event->interval_minutes);
    $expectedNextOccurrence = $initialStartTime->addMinutes(($intervalsPassed + 1) * $event->interval_minutes);

    expect($nextOccurrence->format('Y-m-d H:i'))->toBe($expectedNextOccurrence->format('Y-m-d H:i'));

    Carbon::setTestNow(); // Reset the mock time
});

it('sets sort_order automatically if not provided', function () {
    $maxSortOrder = ScheduledEvent::max('sort_order') ?? 0;
    $event = ScheduledEvent::factory()->create();
    $event->refresh();

    expect($event->sort_order)->toBeGreaterThan($maxSortOrder);

    $this->assertEquals(1, ScheduledEvent::where('sort_order', $event->sort_order)->count());
});

it('casts type to ScheduledEventType enum', function () {
    $event = ScheduledEvent::factory()->create();

    expect($event->type)->toBeInstanceOf(ScheduledEventType::class);
});

it('casts schedule to array', function () {
    $event = ScheduledEvent::factory()->create();

    expect($event->schedule)->toBeArray();
});

it('casts is_active to boolean', function () {
    $event = ScheduledEvent::factory()->create();

    expect($event->is_active)->toBeBool();
});
