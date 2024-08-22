<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource;
use App\Filament\Resources\TicketResource\Pages\EditTicket;
use App\Filament\Resources\TicketResource\Pages\ListTickets;
use App\Filament\Resources\TicketResource\Pages\ManageTicket;
use App\Models\Ticket\Ticket;
use App\Models\User\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->ticket = Ticket::factory()->create(['user_id' => $this->user->id]);
});

it('displays the correct navigation badge count', function () {
    Ticket::factory()->count(3)->create(['status' => TicketStatus::NEW]);
    Ticket::factory()->count(2)->create(['status' => TicketStatus::CLOSED]);

    expect(TicketResource::getNavigationBadge())->toBe('3');
});

it('uses the correct model', function () {
    expect(TicketResource::getModel())->toBe(Ticket::class);
});

it('is in the correct navigation group', function () {
    expect(TicketResource::getNavigationGroup())->toBe('Support');
});

it('has the correct navigation sort', function () {
    expect(TicketResource::getNavigationSort())->toBe(1);
});

it('cannot be created', function () {
    expect(TicketResource::canCreate())->toBeFalse();
});

describe('pages', function () {
    it('has the correct pages', function () {
        $pages = TicketResource::getPages();

        expect($pages)->toHaveKeys(['index', 'edit', 'manage'])
            ->and($pages['index']->getPage())->toBe(ListTickets::class)
            ->and($pages['edit']->getPage())->toBe(EditTicket::class)
            ->and($pages['manage']->getPage())->toBe(ManageTicket::class);
    });

    it('can render list page', function () {
        $this->get(TicketResource::getUrl('index'))->assertSuccessful();
    });

    it('can render edit page', function () {
        $this->get(TicketResource::getUrl('edit', ['record' => $this->ticket]))->assertSuccessful();
    });

    it('does not have create page', function () {
        expect(TicketResource::getPages())->not->toHaveKey('create');
    });
});

describe('edit operations', function () {
    it('fails validation with invalid data', function () {
        Livewire::test(TicketResource\Pages\EditTicket::class, ['record' => $this->ticket->id])
            ->set('data.title', '') // Empty title (required field)
            ->set('data.description', 'Valid description')
            ->set('data.ticket_category_id', $this->ticket->category->id)
            ->set('data.status', TicketStatus::NEW->value)
            ->set('data.priority', TicketPriority::MEDIUM->value)
            ->call('save')
            ->assertHasErrors(['data.title' => 'required']);
    });

    it('updates the ticket with valid data', function () {
        $newTitle = 'Updated Ticket Title';
        $newDescription = 'This is an updated description for the ticket.';

        Livewire::test(TicketResource\Pages\EditTicket::class, ['record' => $this->ticket->id])
            ->set('data.title', $newTitle)
            ->set('data.description', $newDescription)
            ->set('data.ticket_category_id', $this->ticket->category->id)
            ->set('data.status', TicketStatus::IN_PROGRESS->value)
            ->set('data.priority', TicketPriority::HIGH->value)
            ->call('save')
            ->assertHasNoErrors();

        $this->ticket->refresh();

        expect($this->ticket)
            ->title->toBe($newTitle)
            ->description->toBe($newDescription)
            ->ticket_category_id->toBe($this->ticket->category->id)
            ->status->toBe(TicketStatus::IN_PROGRESS)
            ->priority->toBe(TicketPriority::HIGH);
    });
});

describe('manage tickets', function () {
    it('can render manage ticket page', function () {
        $this->actingAs($this->user)
            ->get(ManageTicket::getUrl(['record' => $this->ticket]))
            ->assertSuccessful();
    });

    it('can add a reply to the ticket', function () {
        $replyContent = 'This is a test reply.';

        Auth::login($this->user);

        $component = livewire(ManageTicket::class, ['record' => $this->ticket->id]);

        expect(Auth::id())->toBe($this->user->id);

        $component->fillForm(['content' => $replyContent])
            ->call('addReply')
            ->assertHasNoFormErrors();

        $latestReply = $this->ticket->replies()->latest()->first();
        expect($latestReply)->not->toBeNull()
            ->and($latestReply->content)->toBe($replyContent)
            ->and($latestReply->user_id)->toBe($this->user->id);

        $this->assertDatabaseHas('ticket_replies', [
            'content' => $replyContent,
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
        ]);
    });

    it('requires content when adding a reply', function () {
        livewire(ManageTicket::class, ['record' => $this->ticket->id])
            ->fillForm(['content' => ''])
            ->call('addReply')
            ->assertHasFormErrors(['content' => 'required']);
    });

    it('can change ticket status', function () {
        $newStatus = TicketStatus::CLOSED;

        Auth::login($this->user);

        livewire(ManageTicket::class, ['record' => $this->ticket->id])
            ->callAction('changeStatus', [
                'status' => $newStatus,
            ])
            ->assertHasNoActionErrors();

        $this->ticket->refresh();
        expect($this->ticket->status)->toBe($newStatus);
    });

    it('displays ticket replies', function () {
        $reply = $this->ticket->replies()->create([
            'content' => 'Test reply content',
            'user_id' => $this->user->id,
        ]);

        livewire(ManageTicket::class, ['record' => $this->ticket->id])
            ->assertSee($reply->content)
            ->assertSee($reply->user->name);
    });
});
