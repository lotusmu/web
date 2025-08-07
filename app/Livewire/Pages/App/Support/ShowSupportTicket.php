<?php

namespace App\Livewire\Pages\App\Support;

use App\Actions\Ticket\SubmitReply;
use App\Actions\User\SendNotification;
use App\Livewire\BaseComponent;
use App\Models\Ticket\Ticket;
use Flux;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;

class ShowSupportTicket extends BaseComponent
{
    public Ticket $ticket;

    #[Rule('required|string|max:16777215')]
    public string $content = '';

    public function mount(Ticket $ticket)
    {
        if (! Gate::allows('view', $ticket)) {
            throw new ModelNotFoundException(__('Ticket not found or you don\'t have permission to view it.'));
        }

        $this->loadTicket($ticket);
    }

    public function submitReply(SubmitReply $action)
    {
        $this->validate([
            'content' => 'required|string|max:16777215',
        ]);

        if ($reply = $action->handle($this->ticket, auth()->id(), $this->content)) {

            $this->reset('content');
            $this->loadTicket($this->ticket);

            SendNotification::make('New Ticket Reply')
                ->body('A new reply has been added to ticket: :title', [
                    'title' => $this->ticket->title,
                ])
                ->action('View Ticket', '/admin/tickets/'.$this->ticket->id.'/manage')
                ->sendToAdmins();

            Flux::toast(
                text: __('Your reply has been successfully added to the ticket.'),
                heading: __('Success'),
                variant: 'success'
            );
        }
    }

    public function reopenTicket(): void
    {
        if (! Gate::allows('update', $this->ticket)) {
            Flux::toast(
                text: __('You do not have permission to modify to this ticket.'),
                heading: __('Permission Denied'),
                variant: 'danger'
            );

            return;
        }

        $this->ticket->reopenTicket();
        $this->loadTicket($this->ticket);
    }

    public function markAsResolved(): void
    {
        if (! Gate::allows('update', $this->ticket)) {
            Flux::toast(
                text: __('You do not have permission to modify to this ticket.'),
                heading: __('Permission Denied'),
                variant: 'danger'
            );

            return;
        }

        $this->ticket->markAsResolved();
        $this->loadTicket($this->ticket);
    }

    private function loadTicket(Ticket $ticket): void
    {
        $this->ticket = $ticket->fresh(['category', 'replies.user']);
    }

    protected function getViewName(): string
    {
        return 'pages.app.support.show-ticket';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
