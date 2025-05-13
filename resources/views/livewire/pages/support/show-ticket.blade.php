<?php

use App\Actions\Ticket\SubmitReply;
use App\Actions\User\SendNotification;
use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public Ticket $ticket;

    #[Rule('required|string|max:16777215')]
    public string $content = '';

    public function mount(Ticket $ticket)
    {
        if ( ! Gate::allows('view', $ticket)) {
            throw new ModelNotFoundException(__('Ticket not found or you don\'t have permission to view it.'));
        }

        $this->loadTicket($ticket);
    }

    public function submitReply(SubmitReply $action)
    {
        $this->validate([
            'content' => 'required|string|max:16777215'
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
        if ( ! Gate::allows('update', $this->ticket)) {
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
        if ( ! Gate::allows('update', $this->ticket)) {
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

            <x-prose :content="$ticket->description" class="mt-2"/>
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
                            {{
                             $reply->user->name === 'kodovoime'
                                ? __('Support')
                                : ($reply->user->name ?? __('Unknown User'))
                            }}
                        </flux:heading>
                        <flux:subheading>
                            {{ $reply->created_at->format('M d, Y H:i') }}
                        </flux:subheading>
                    </div>

                    <flux:separator/>

                    <x-prose :content="$reply->content"/>
                </flux:card>

            @empty
                <flux:text>{{ __('No replies yet.') }}</flux:text>
            @endforelse
        </div>

        @if(!in_array($ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
            <div>
                <form wire:submit="submitReply" class="space-y-4">
                    <flux:editor wire:model="content" label="{{__('Reply')}}"
                                 toolbar="bold italic underline | bullet ordered highlight | link ~ undo redo"/>

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
