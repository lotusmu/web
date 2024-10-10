<?php

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public Ticket $ticket;

    public function reopenTicket($ticketId)
    {
        $ticket = $this->getUserTicket($ticketId);

        if ( ! $ticket) {
            return $this->showTicketError();
        }

        $ticket->update(['status' => TicketStatus::IN_PROGRESS]);

        $this->ticket->refresh();

        return Flux::toast(
            variant: 'success',
            heading: __('Ticket Reopened'),
            text: __('We\'re on it! We\'ll reach out about this ticket as soon as possible.')
        );
    }

    public function markAsResolved($ticketId)
    {
        $ticket = $this->getUserTicket($ticketId);

        if ( ! $ticket) {
            return $this->showTicketError();
        }

        $ticket->update(['status' => TicketStatus::RESOLVED]);

        $this->ticket->refresh();

        Flux::toast(
            variant: 'success',
            heading: __('Ticket Resolved'),
            text: __('Thank you for your patience. The ticket has been marked as resolved.')
        );
    }

    private function getUserTicket($ticketId)
    {
        return Ticket::where('id', $ticketId)
            ->where('user_id', Auth::id())
            ->first();
    }

    private function showTicketError()
    {
        return Flux::toast(
            variant: 'danger',
            heading: __('Error'),
            text: __('Ticket not found or you don\'t have permission to modify it.')
        );
    }
}; ?>

<flux:row>
    <flux:cell>{{ $this->ticket->truncatedTitle() }}</flux:cell>
    <flux:cell>{{ $this->ticket->category->name }}</flux:cell>
    <flux:cell>
        <flux:badge inset="top bottom" size="sm" :color="$this->ticket->status->color()"
                    :icon="$this->ticket->status->icon()">
            {{ $this->ticket->status->getLabel() }}
        </flux:badge>
    </flux:cell>
    <flux:cell>
        <flux:badge inset="top bottom" size="sm" :color="$this->ticket->priority->color()">
            {{ $this->ticket->priority->getLabel() }}
        </flux:badge>
    </flux:cell>
    <flux:cell>
        <flux:dropdown align="end">
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"
                         inset="top bottom"></flux:button>

            <flux:menu variant="solid">
                <flux:menu.item :href="route('support.show-ticket', $this->ticket->id)" icon="eye">
                    {{ __('View Details') }}
                </flux:menu.item>
                @if(!in_array($this->ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
                    <flux:menu.item icon="check-circle" wire:click="markAsResolved({{ $this->ticket->id }})">
                        {{ __('Mark as Resolved') }}
                    </flux:menu.item>
                @else
                    <flux:menu.item icon="arrow-path" wire:click="reopenTicket({{ $this->ticket->id }})">
                        {{ __('Reopen Ticket') }}
                    </flux:menu.item>
                @endif
            </flux:menu>
        </flux:dropdown>
    </flux:cell>
</flux:row>

