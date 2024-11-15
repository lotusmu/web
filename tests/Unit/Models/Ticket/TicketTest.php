<?php

use App\Enums\Ticket\TicketPriority;
use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use App\Models\Ticket\TicketReply;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

it('has fillable fields', function () {
    $category = TicketCategory::factory()->create();
    $user = User::factory()->create();

    $ticketData = [
        'title' => 'Test Ticket',
        'description' => 'This is a test ticket description',
        'status' => TicketStatus::NEW->value,
        'priority' => TicketPriority::MEDIUM->value,
        'ticket_category_id' => $category->id,
        'user_id' => $user->id,
    ];

    $ticket = Ticket::create($ticketData);

    expect($ticket)->toBeInstanceOf(Ticket::class)
        ->and($ticket->title)->toBe($ticketData['title'])
        ->and($ticket->description)->toBe($ticketData['description'])
        ->and($ticket->status)->toBe(TicketStatus::NEW)
        ->and($ticket->priority)->toBe(TicketPriority::MEDIUM)
        ->and($ticket->ticket_category_id)->toBe($category->id)
        ->and($ticket->user_id)->toBe($user->id);
});

it('casts status to TicketStatus enum', function () {
    $ticket = Ticket::factory()->create(['status' => TicketStatus::NEW]);

    expect($ticket->status)->toBeInstanceOf(TicketStatus::class)
        ->and($ticket->status)->toBe(TicketStatus::NEW);
});

it('casts priority to TicketPriority enum', function () {
    $ticket = Ticket::factory()->create(['priority' => TicketPriority::LOW]);

    expect($ticket->priority)->toBeInstanceOf(TicketPriority::class)
        ->and($ticket->priority)->toBe(TicketPriority::LOW);
});

it('belongs to a category', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->category)->toBeInstanceOf(TicketCategory::class);
});

it('belongs to a user', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->user)->toBeInstanceOf(User::class);
});

it('has many replies', function () {
    $ticket = Ticket::factory()->create();
    TicketReply::factory()->count(3)->create(['ticket_id' => $ticket->id]);

    expect($ticket->replies)->toHaveCount(3)
        ->and($ticket->replies->first())->toBeInstanceOf(TicketReply::class);
});

it('can set and get attributes', function () {
    $ticket = Ticket::factory()->create([
        'title' => 'Test Ticket',
        'description' => 'This is a test ticket',
        'status' => TicketStatus::NEW,
        'priority' => TicketPriority::MEDIUM,
    ]);

    expect($ticket->title)->toBe('Test Ticket')
        ->and($ticket->description)->toBe('This is a test ticket')
        ->and($ticket->status)->toBe(TicketStatus::NEW)
        ->and($ticket->priority)->toBe(TicketPriority::MEDIUM);
});

it('uses uuid as primary key', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->getKeyType())->toBe('string')
        ->and($ticket->getIncrementing())->toBeFalse()
        ->and(Str::isUuid($ticket->id))->toBeTrue();
});

it('can truncate title', function () {
    $ticket = Ticket::factory()->create([
        'title' => str_repeat('a', 100),
    ]);

    expect($ticket->truncatedTitle())
        ->toHaveLength(48) // 45 + 3 for '...'
        ->and($ticket->truncatedTitle())->toEndWith('...')
        ->and($ticket->truncatedTitle(10))->toHaveLength(13); // 10 + 3 for '...'
});

it('can mark ticket as resolved', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Flux::shouldReceive('toast')->once();

    $ticket = Ticket::factory()->create(['status' => TicketStatus::IN_PROGRESS]);
    $ticket->markAsResolved();

    $ticket->refresh();

    expect($ticket->status)->toBe(TicketStatus::RESOLVED);
});

it('can reopen ticket', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Flux::shouldReceive('toast')->once();

    $ticket = Ticket::factory()->create(['status' => TicketStatus::RESOLVED]);
    $ticket->reopenTicket();

    $ticket->refresh();

    expect($ticket->status)->toBe(TicketStatus::IN_PROGRESS);
});

test('status changes are logged with identity properties', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Flux::shouldReceive('toast')->once();

    $ticket = Ticket::factory()->create(['status' => TicketStatus::IN_PROGRESS]);
    $ticket->markAsResolved();

    $activity = Activity::query()->latest()->first();
    $properties = $activity->properties->toArray();

    expect($properties)
        ->toHaveKey('ticket_id')
        ->toHaveKey('ticket_title')
        ->toHaveKey('new_status')
        ->toHaveKey('action')
        ->toHaveKey('ip_address')
        ->toHaveKey('user_agent');
});

it('includes identity properties in activity log', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Flux::shouldReceive('toast')->once();

    $ticket = Ticket::factory()->create(['status' => TicketStatus::IN_PROGRESS]);
    $ticket->markAsResolved();

    $latestActivity = Activity::query()->latest()->first();

    expect($latestActivity->properties->toArray())
        ->toHaveKey('ip_address')
        ->toHaveKey('user_agent');
});
