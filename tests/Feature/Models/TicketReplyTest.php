<?php

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has fillable fields', function () {
    $ticket = Ticket::factory()->create();
    $user = User::factory()->create();

    $replyData = [
        'content' => 'This is a test reply',
        'user_id' => $user->id,
        'ticket_id' => $ticket->id,
    ];

    $reply = TicketReply::create($replyData);

    expect($reply)->toBeInstanceOf(TicketReply::class)
        ->and($reply->content)->toBe($replyData['content'])
        ->and($reply->user_id)->toBe($user->id)
        ->and($reply->ticket_id)->toBe($ticket->id);
});

it('belongs to a ticket', function () {
    $ticket = Ticket::factory()->create();
    $reply = TicketReply::factory()->create(['ticket_id' => $ticket->id]);

    expect($reply->ticket)->toBeInstanceOf(Ticket::class)
        ->and($reply->ticket->id)->toBe($ticket->id);
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $reply = TicketReply::factory()->create(['user_id' => $user->id]);

    expect($reply->user)->toBeInstanceOf(User::class)
        ->and($reply->user->id)->toBe($user->id);
});

it('can set and get attributes', function () {
    $reply = TicketReply::factory()->create([
        'content' => 'Test content',
    ]);

    expect($reply->content)->toBe('Test content');
});

it('can be created and retrieved from database', function () {
    $reply = TicketReply::factory()->create();

    $fetchedReply = TicketReply::find($reply->id);

    expect($fetchedReply)->toBeInstanceOf(TicketReply::class)
        ->and($fetchedReply->id)->toBe($reply->id);
});

it('can be updated', function () {
    $reply = TicketReply::factory()->create();
    $newContent = 'Updated content';

    $reply->update(['content' => $newContent]);

    $updatedReply = TicketReply::find($reply->id);
    expect($updatedReply->content)->toBe($newContent);
});

it('can be deleted', function () {
    $reply = TicketReply::factory()->create();
    $replyId = $reply->id;

    $reply->delete();

    expect(TicketReply::find($replyId))->toBeNull();
});
