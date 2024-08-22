<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
