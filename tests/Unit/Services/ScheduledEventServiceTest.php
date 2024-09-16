<?php

use App\Enums\Game\ScheduledEventType;
use App\Models\Content\ScheduledEvent;
use App\Services\ScheduledEventService;
use Carbon\Carbon;

beforeEach(function () {
    $this->service = new ScheduledEventService;
});

it('returns upcoming events in the correct order', function () {
    $event1 = ScheduledEvent::factory()->create([
        'name' => 'Event 1',
        'type' => ScheduledEventType::EVENT,
        'recurrence_type' => 'daily',
        'schedule' => [['time' => '10:00']],
        'is_active' => true,
        'sort_order' => 2,
    ]);

    $event2 = ScheduledEvent::factory()->create([
        'name' => 'Event 2',
        'type' => ScheduledEventType::INVASION,
        'recurrence_type' => 'weekly',
        'schedule' => [['day' => 'monday', 'time' => '14:00']],
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $event3 = ScheduledEvent::factory()->create([
        'name' => 'Event 3',
        'type' => ScheduledEventType::EVENT,
        'recurrence_type' => 'interval',
        'schedule' => [['time' => '08:00']],
        'interval_minutes' => 120,
        'is_active' => true,
        'sort_order' => 3,
    ]);

    Carbon::setTestNow(Carbon::create(2023, 1, 1, 9, 0, 0));

    $upcomingEvents = $this->service->getUpcomingEvents();

    expect($upcomingEvents)->toHaveCount(3)
        ->and($upcomingEvents[0]['name'])->toBe('Event 2')
        ->and($upcomingEvents[1]['name'])->toBe('Event 1')
        ->and($upcomingEvents[2]['name'])->toBe('Event 3');
});

it('only returns active events', function () {
    ScheduledEvent::factory()->create(['is_active' => true]);
    ScheduledEvent::factory()->create(['is_active' => false]);

    $upcomingEvents = $this->service->getUpcomingEvents();

    expect($upcomingEvents)->toHaveCount(1);
});

it('limits the number of returned events', function () {
    ScheduledEvent::factory()->count(5)->create(['is_active' => true]);

    $upcomingEvents = $this->service->getUpcomingEvents(3);

    expect($upcomingEvents)->toHaveCount(3);
});

it('correctly formats event data', function () {
    $event = ScheduledEvent::factory()->create([
        'name' => 'Test Event',
        'type' => ScheduledEventType::EVENT,
        'recurrence_type' => 'daily',
        'schedule' => [['time' => '10:00']],
        'is_active' => true,
    ]);

    Carbon::setTestNow(Carbon::create(2023, 1, 1, 9, 0, 0));

    $upcomingEvents = $this->service->getUpcomingEvents();

    expect($upcomingEvents)->toHaveCount(1)
        ->and($upcomingEvents[0])->toHaveKeys([
            'event_id',
            'name',
            'type',
            'start_time',
            'recurrence_type',
            'interval_minutes',
            'sort_order',
            'schedule',
        ])
        ->and($upcomingEvents[0]['name'])->toBe('Test Event')
        ->and($upcomingEvents[0]['start_time']->format('Y-m-d H:i'))->toBe('2023-01-01 10:00');
});

it('handles events with no upcoming occurrences', function () {
    ScheduledEvent::factory()->create([
        'recurrence_type' => 'weekly',
        'schedule' => [['day' => 'monday', 'time' => '10:00']],
        'is_active' => true,
    ]);

    Carbon::setTestNow(Carbon::create(2023, 1, 3, 9, 0, 0));

    $upcomingEvents = $this->service->getUpcomingEvents();

    expect($upcomingEvents)->toHaveCount(1)
        ->and($upcomingEvents[0]['start_time']->format('Y-m-d H:i'))->toBe('2023-01-09 10:00');
});

it('correctly calculates next occurrence for interval events', function () {
    ScheduledEvent::factory()->create([
        'recurrence_type' => 'interval',
        'schedule' => [['time' => '08:00']],
        'interval_minutes' => 120,
        'is_active' => true,
    ]);

    Carbon::setTestNow(Carbon::create(2023, 1, 1, 9, 30, 0));

    $upcomingEvents = $this->service->getUpcomingEvents();

    expect($upcomingEvents)->toHaveCount(1)
        ->and($upcomingEvents[0]['start_time']->format('Y-m-d H:i'))->toBe('2023-01-01 08:00');
});
