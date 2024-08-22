<?php

use App\Models\Ticket;
use App\Models\TicketCategory;

beforeEach(function () {
    $this->category = TicketCategory::factory()->create();
});

it('can create a ticket category', function () {
    expect($this->category)->toBeInstanceOf(TicketCategory::class)
        ->and($this->category->name)->not->toBeEmpty();
});

it('can update a ticket category', function () {
    $newName = 'Updated Category Name';

    $this->category->update(['name' => $newName]);

    expect($this->category->fresh()->name)->toBe($newName);
});

it('can delete a ticket category', function () {
    $categoryId = $this->category->id;

    $this->category->delete();

    expect(TicketCategory::find($categoryId))->toBeNull();
});

it('has many tickets', function () {
    $tickets = Ticket::factory()->count(3)->create(['ticket_category_id' => $this->category->id]);

    expect($this->category->tickets)->toHaveCount(3)
        ->and($this->category->tickets->first())->toBeInstanceOf(Ticket::class);
});
