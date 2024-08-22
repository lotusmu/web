<?php

use App\Filament\Resources\TicketResource;
use App\Filament\Resources\UserResource\RelationManagers\TicketsRelationManager;

use function Pest\Livewire\livewire;

//beforeEach(function () {
//    // Create a user with tickets
//    $this->user = User::factory()
//        ->has(
//            Ticket::factory()
//                ->count(3)
//                ->state(new Sequence(
//                    ['title' => 'Ticket 1'],
//                    ['title' => 'Ticket 2'],
//                    ['title' => 'Ticket 3']
//                ))
//                ->for(TicketCategory::factory())
//        )
//        ->create();
//});

todo('can render tickets table', function () {
    livewire(TicketsRelationManager::class, [
        'ownerRecord' => $this->user,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($this->user->tickets);
});

todo('has correct columns', function () {
    livewire(TicketsRelationManager::class, [
        'ownerRecord' => $this->user,
    ])
        ->assertTableColumnExists('title')
        ->assertTableColumnExists('category.name')
        ->assertTableColumnExists('priority')
        ->assertTableColumnExists('status')
        ->assertTableColumnExists('created_at');
});

todo('has correct record url', function () {
    $ticket = $this->user->tickets->first();
    $expectedUrl = TicketResource::getUrl('manage', ['record' => $ticket]);

    livewire(TicketsRelationManager::class, [
        'ownerRecord' => $this->user,
    ])
        ->assertTableRecordUrlsSet([$ticket])
        ->assertTableRecordUrlIs($ticket, $expectedUrl);
});
