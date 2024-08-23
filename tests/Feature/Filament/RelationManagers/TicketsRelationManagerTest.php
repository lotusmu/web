<?php

use App\Filament\Resources\TicketResource;
use App\Filament\Resources\UserResource\RelationManagers\TicketsRelationManager;
use App\Models\Ticket\Ticket;
use App\Models\User\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->ticket = Ticket::factory()->create(['user_id' => $this->user->id]);
});

it('can render tickets table', function () {
    livewire(TicketsRelationManager::class, [
        'ownerRecord' => $this->user,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($this->user->tickets);
});

it('has correct columns', function () {
    livewire(TicketsRelationManager::class, [
        'ownerRecord' => $this->user,
    ])
        ->assertTableColumnExists('title')
        ->assertTableColumnExists('category.name')
        ->assertTableColumnExists('priority')
        ->assertTableColumnExists('status')
        ->assertTableColumnExists('created_at');
});

it('has correct record url', function () {
    $ticket = $this->user->tickets->first();
    $expectedUrl = TicketResource::getUrl('manage', ['record' => $ticket]);

    livewire(TicketsRelationManager::class, [
        'ownerRecord' => $this->user,
    ])
        ->assertSee($ticket->title)
        ->assertSeeHtml('href="'.$expectedUrl.'"');
});
