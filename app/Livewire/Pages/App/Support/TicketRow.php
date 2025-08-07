<?php

namespace App\Livewire\Pages\App\Support;

use App\Livewire\BaseComponent;
use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class TicketRow extends BaseComponent
{
    public Ticket $ticket;

    public function mount(Ticket $ticket): void
    {
        if (! Gate::allows('view', $ticket)) {
            throw new ModelNotFoundException(__('Ticket not found or you don\'t have permission to view it.'));
        }

        $this->ticket = $ticket;
    }

    public function reopenTicket(): void
    {
        if (! Gate::allows('update', $this->ticket)) {
            return;
        }
        $this->ticket->reopenTicket();
    }

    public function markAsResolved(): void
    {
        if (! Gate::allows('update', $this->ticket)) {
            return;
        }
        $this->ticket->markAsResolved();
    }

    public function navigateToTicket()
    {
        return $this->redirect(route('support.show-ticket', ['ticket' => $this->ticket->id]), navigate: true);
    }

    protected function getViewName(): string
    {
        return 'pages.app.support.ticket-row';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
