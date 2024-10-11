<?php

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public Ticket $ticket;

    public function mount(Ticket $ticket): void
    {
        if ($ticket->user_id !== auth()->id()) {
            throw new ModelNotFoundException(__('Ticket not found or you don\'t have permission to view it.'));
        }

        $this->ticket = $ticket;
    }

    public function reopenTicket(): void
    {
        $this->ticket->reopenTicket();
    }

    public function markAsResolved(): void
    {
        $this->ticket->markAsResolved();
    }

    public function navigateToTicket()
    {
        return $this->redirect(route('support.show-ticket', ['ticket' => $this->ticket->id]), navigate: true);
    }
}

?>

<flux:row class="cursor-pointer" wire:click="navigateToTicket">
    <flux:cell>{{ $this->ticket->truncatedTitle() }}</flux:cell>

    <flux:cell>{{ $this->ticket->category->name }}</flux:cell>

    <flux:cell>
        <flux:badge size="sm" :color="$this->ticket->priority->color()">
            {{ $this->ticket->priority->getLabel() }}
        </flux:badge>
    </flux:cell>

    <flux:cell>
        <flux:badge size="sm" :color="$this->ticket->status->color()"
                    :icon="$this->ticket->status->icon()">
            {{ $this->ticket->status->getLabel() }}
        </flux:badge>
    </flux:cell>

    <flux:cell align="end">
        <flux:dropdown align="end">
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"
                         wire:click.stop/>

            <flux:menu variant="solid">
                <flux:menu.item icon="eye"
                                wire:navigate
                                :href="route('support.show-ticket', $this->ticket->id)"
                                wire:click.stop>
                    {{ __('View Details') }}
                </flux:menu.item>

                @if(!in_array($this->ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
                    <flux:menu.item icon="check-circle"
                                    wire:click.stop="markAsResolved">
                        {{ __('Mark as Resolved') }}
                    </flux:menu.item>
                @else
                    <flux:menu.item icon="arrow-path"
                                    wire:click.stop="reopenTicket">
                        {{ __('Reopen Ticket') }}
                    </flux:menu.item>
                @endif
            </flux:menu>
        </flux:dropdown>
    </flux:cell>
</flux:row>
