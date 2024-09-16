<?php

use App\Enums\Game\ScheduledEventType;
use App\Filament\Resources\ScheduledEventResource;
use App\Models\Content\ScheduledEvent;

use function Pest\Livewire\livewire;

it('can render index page', function () {
    $this->get(ScheduledEventResource::getUrl('index'))->assertSuccessful();
});

it('can render create page', function () {
    $this->get(ScheduledEventResource::getUrl('create'))->assertSuccessful();
});

it('can render edit page', function () {
    $event = ScheduledEvent::factory()->create();

    $this->get(ScheduledEventResource::getUrl('edit', ['record' => $event]))
        ->assertSuccessful();
});

it('can create a scheduled event', function () {
    $component = Livewire::test(ScheduledEventResource\Pages\CreateScheduledEvent::class);

    $component
        ->set('data.name', 'Test Event')
        ->set('data.type', ScheduledEventType::EVENT)
        ->set('data.recurrence_type', 'daily')
        ->set('data.is_active', true)
        ->set('data.schedule', [
            ['time' => '14:30'],
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('scheduled_events', [
        'name' => 'Test Event',
        'type' => ScheduledEventType::EVENT,
        'recurrence_type' => 'daily',
        'is_active' => true,
    ]);

    $event = ScheduledEvent::where('name', 'Test Event')->first();
    expect($event)->not->toBeNull()
        ->and($event->schedule)->toHaveCount(1)
        ->and($event->schedule[0]['time'])->toBe('14:30');
});

it('can create a weekly scheduled event', function () {
    $component = Livewire::test(ScheduledEventResource\Pages\CreateScheduledEvent::class);

    $component
        ->set('data.name', 'Weekly Test Event')
        ->set('data.type', ScheduledEventType::INVASION)
        ->set('data.recurrence_type', 'weekly')
        ->set('data.is_active', true)
        ->set('data.schedule', [
            ['day' => 'monday', 'time' => '10:00'],
            ['day' => 'friday', 'time' => '15:00'],
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('scheduled_events', [
        'name' => 'Weekly Test Event',
        'type' => ScheduledEventType::INVASION,
        'recurrence_type' => 'weekly',
        'is_active' => true,
    ]);

    $event = ScheduledEvent::where('name', 'Weekly Test Event')->first();
    expect($event)->not->toBeNull()
        ->and($event->schedule)->toHaveCount(2)
        ->and($event->schedule[0]['day'])->toBe('monday')
        ->and($event->schedule[0]['time'])->toBe('10:00')
        ->and($event->schedule[1]['day'])->toBe('friday')
        ->and($event->schedule[1]['time'])->toBe('15:00');
});

it('can create an interval scheduled event', function () {
    $component = Livewire::test(ScheduledEventResource\Pages\CreateScheduledEvent::class);

    $component
        ->set('data.name', 'Interval Test Event')
        ->set('data.type', ScheduledEventType::EVENT)
        ->set('data.recurrence_type', 'interval')
        ->set('data.interval_minutes', 120)
        ->set('data.is_active', true)
        ->set('data.schedule', [
            ['time' => '08:00'],
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('scheduled_events', [
        'name' => 'Interval Test Event',
        'type' => ScheduledEventType::EVENT,
        'recurrence_type' => 'interval',
        'interval_minutes' => 120,
        'is_active' => true,
    ]);

    $event = ScheduledEvent::where('name', 'Interval Test Event')->first();
    expect($event)->not->toBeNull()
        ->and($event->schedule)->toHaveCount(1)
        ->and($event->schedule[0]['time'])->toBe('08:00');
});

it('can update scheduled event', function () {
    $event = ScheduledEvent::factory()->create();
    $newData = ScheduledEvent::factory()->make();

    Livewire::test(ScheduledEventResource\Pages\EditScheduledEvent::class, ['record' => $event->id])
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('scheduled_events', [
        'id' => $event->id,
        'name' => $newData->name,
    ]);
});

it('can delete scheduled event', function () {
    $event = ScheduledEvent::factory()->create();

    livewire(ScheduledEventResource\Pages\ListScheduledEvents::class)
        ->callTableBulkAction('delete', [$event->id]);

    $this->assertDatabaseMissing('scheduled_events', [
        'id' => $event->id,
    ]);
});

it('can activate scheduled event', function () {
    $event = ScheduledEvent::factory()->inactive()->create();

    $component = livewire(ScheduledEventResource\Pages\ListScheduledEvents::class);

    $component
        ->callTableAction('activate', $event)
        ->callTableAction('activate', $event, data: [
            'confirmation' => true,
        ]);

    $event->refresh();
    expect($event->is_active)->toBeTrue();

    $component->assertNotified('Success!');
});

it('can deactivate scheduled event', function () {
    $event = ScheduledEvent::factory()->active()->create();

    $component = livewire(ScheduledEventResource\Pages\ListScheduledEvents::class);

    $component
        ->callTableAction('deactivate', $event)
        ->callTableAction('deactivate', $event, data: [
            'confirmation' => true,
        ]);

    $event->refresh();
    expect($event->is_active)->toBeFalse();

    $component->assertNotified('Success!');
});

it('shows activate action for inactive events', function () {
    $event = ScheduledEvent::factory()->inactive()->create();

    Livewire::test(ScheduledEventResource\Pages\ListScheduledEvents::class)
        ->assertTableActionVisible('activate', $event);
});

it('shows deactivate action for active events', function () {
    $event = ScheduledEvent::factory()->active()->create();

    Livewire::test(ScheduledEventResource\Pages\ListScheduledEvents::class)
        ->assertTableActionVisible('deactivate', $event);
});

it('has delete bulk action', function () {
    Livewire::test(ScheduledEventResource\Pages\ListScheduledEvents::class)
        ->assertTableBulkActionExists('delete');
});
