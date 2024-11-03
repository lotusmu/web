<?php

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public Ticket $ticket;

    #[Rule('required|string')]
    public string $content = '';

    public function mount(Ticket $ticket)
    {
        $this->loadTicket($ticket);
    }

    public function submitReply()
    {
        $this->validate();

        $reply = $this->ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => nl2br($this->content),
        ]);

        if ($reply) {
            $this->ticket->update(['status' => TicketStatus::IN_PROGRESS]);
            $this->reset('content');
            $this->loadTicket($this->ticket);

            Flux::toast(
                text: __('Your reply has been successfully added to the ticket.'),
                heading: __('Success'),
                variant: 'success'
            );
        } else {
            Flux::toast(
                text: __('Failed to submit reply. Please try again.'),
                heading: __('Error'),
                variant: 'danger'
            );
        }
    }

    public function reopenTicket(): void
    {
        $this->ticket->reopenTicket();
        $this->loadTicket($this->ticket);
    }

    public function markAsResolved(): void
    {
        $this->ticket->markAsResolved();
        $this->loadTicket($this->ticket);
    }

    private function loadTicket(Ticket $ticket): void
    {
        $this->ticket = $ticket->fresh(['category', 'replies.user']);
    }
}; ?>

<div class="space-y-8">
    <header class="flex items-center max-sm:flex-col-reverse max-sm:items-start max-sm:gap-4">
        <div>
            <flux:heading size="xl">
                {{ __('Ticket Details') }}
            </flux:heading>

            <x-flux::subheading>
                {{ __('View and manage your support ticket.') }}
            </x-flux::subheading>
        </div>

        <flux:spacer/>

        <flux:button :href="route('support')"
                     wire:navigate
                     inset="left"
                     variant="ghost" size="sm" icon="arrow-left">
            {{__('Back to Tickets')}}
        </flux:button>
    </header>

    <flux:card class="space-y-8">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">
                {{ $ticket->title }}
            </flux:heading>

            @if(!in_array($ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
                <flux:button wire:click="markAsResolved"
                             size="sm"
                             variant="filled"
                             inset="top bottom"
                             icon="check-circle">
                    {{ __('Mark as Resolved') }}
                </flux:button>
            @else
                <flux:button wire:click="reopenTicket"
                             size="sm"
                             variant="filled"
                             inset="top bottom"
                             icon="arrow-path">
                    {{ __('Reopen Ticket') }}
                </flux:button>
            @endif
        </div>

        <div class="flex items-center justify-between max-sm:grid max-sm:grid-cols-2 max-sm:gap-4">
            <div>
                <flux:heading>
                    {{ __('Category') }}
                </flux:heading>
                <flux:subheading>
                    {{ $ticket->category->name }}
                </flux:subheading>
            </div>

            <div>
                <flux:heading>
                    {{ __('Created') }}
                </flux:heading>
                <flux:subheading>
                    {{ $ticket->created_at->format('M d, Y H:i') }}
                </flux:subheading>
            </div>

            <div>
                <flux:heading>
                    {{ __('Priority') }}
                </flux:heading>
                <flux:subheading>
                    <flux:badge size="sm" :color="$ticket->priority->color()">
                        {{ $ticket->priority->getLabel() }}
                    </flux:badge>
                </flux:subheading>
            </div>

            <div>
                <flux:heading>
                    {{ __('Status') }}
                </flux:heading>
                <flux:subheading>
                    <flux:badge size="sm" :color="$ticket->status->color()" :icon="$ticket->status->icon()">
                        {{ $ticket->status->getLabel() }}
                    </flux:badge>
                </flux:subheading>
            </div>
        </div>

        <div>
            <flux:heading>
                {{ __('Description') }}
            </flux:heading>
            <flux:text class="mt-2">
                {!! $ticket->description !!}
            </flux:text>
        </div>
    </flux:card>

    <flux:card class="space-y-8">
        <flux:heading size="lg">
            {{ __('Conversation') }}
        </flux:heading>

        <div class="space-y-4">
            @forelse ($ticket->replies as $reply)
                <flux:card class="space-y-6">
                    <div class="flex justify-between items-center">
                        <flux:heading class="flex items-center gap-1 !mb-0">
                            <flux:icon.user variant="mini"/>
                            {{ $reply->user->name ?? __('Unknown User') }}
                        </flux:heading>
                        <flux:subheading>
                            {{ $reply->created_at->format('M d, Y H:i') }}
                        </flux:subheading>
                    </div>

                    <flux:separator/>

                    <flux:text>
                        {!! $reply->content !!}
                    </flux:text>
                </flux:card>

            @empty
                <flux:text>{{ __('No replies yet.') }}</flux:text>
            @endforelse
        </div>

        @if(!in_array($ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
            <div>
                <form wire:submit="submitReply" class="space-y-4">
                    <flux:textarea wire:model="content" label="Reply"/>

                    <div class="flex">
                        <flux:spacer/>
                        <flux:button type="submit" variant="primary">
                            {{ __('Submit') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        @endif
    </flux:card>

</div>
