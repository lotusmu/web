<?php

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\RichEditor;

new #[Layout('layouts.app')] class extends Component implements HasForms {
    use InteractsWithForms;

    public Ticket $ticket;

    #[Rule('required|string')]
    public $replyContent = '';

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load(['category', 'replies.user']);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('replyContent')
                    ->label('')
                    ->placeholder(__('Type your reply here...'))
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                    ])
                    ->required(),
            ]);
    }

    public function submitReply()
    {
        $this->validate();

        $reply = $this->ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => $this->replyContent,
        ]);

        if ($reply) {
            $this->ticket->update(['status' => TicketStatus::IN_PROGRESS]);
            $this->ticket->refresh();
            $this->reset('replyContent');
            $this->form->fill();

            // Reload the ticket with fresh data including the new reply
            $this->ticket = $this->ticket->fresh(['replies.user']);

            Flux::toast(
                variant: 'success',
                heading: __('Reply Sent'),
                text: __('Your reply has been successfully added to the ticket.')
            );
        } else {
            Flux::toast(
                variant: 'danger',
                heading: __('Error'),
                text: __('Failed to submit reply. Please try again.')
            );
        }
    }

    public function reopenTicket()
    {
        $this->ticket->update(['status' => TicketStatus::IN_PROGRESS]);
        $this->ticket->refresh();

        Flux::toast(
            variant: 'success',
            heading: __('Ticket Reopened'),
            text: __('The ticket has been reopened. We\'ll address your concerns as soon as possible.')
        );
    }

    public function markAsResolved()
    {
        $this->ticket->update(['status' => TicketStatus::RESOLVED]);
        $this->ticket->refresh();

        Flux::toast(
            variant: 'success',
            heading: __('Ticket Resolved'),
            text: __('The ticket has been marked as resolved. Thank you for your patience.')
        );
    }
}; ?>

<div class="space-y-6">
    <header class="flex items-center">
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
                     variant="ghost" size="sm" icon="arrow-left"
                     inset="top bottom">{{__('Back to Tickets')}}</flux:button>
    </header>

    <flux:card>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <flux:heading size="lg">{{ $ticket->title }}</flux:heading>
                <div class="flex space-x-2">
                    <flux:badge size="sm" :color="$ticket->status->color()" :icon="$ticket->status->icon()">
                        {{ $ticket->status->getLabel() }}
                    </flux:badge>
                    <flux:badge size="sm" :color="$ticket->priority->color()">
                        {{ $ticket->priority->getLabel() }}
                    </flux:badge>
                </div>
            </div>
            <p class="text-sm text-gray-500">{{ __('Category') }}: {{ $ticket->category->name }}</p>
            <p class="text-sm text-gray-500">{{ __('Created') }}: {{ $ticket->created_at->diffForHumans() }}</p>
            <div class="prose max-w-none">
                {!! $ticket->description !!}
            </div>
        </div>
    </flux:card>

    <flux:card>
        <flux:heading size="lg" class="mb-4">{{ __('Replies') }}</flux:heading>
        <div class="space-y-4">
            @forelse ($ticket->replies as $reply)
                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                    <div class="flex justify-between items-center mb-2">
                        <p class="font-semibold">{{ $reply->user->name ?? __('Unknown User') }}</p>
                        <p class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="prose dark:prose-invert break-words">
                        {!! $reply->content !!}
                    </div>
                </div>
            @empty
                <p>{{ __('No replies yet.') }}</p>
            @endforelse
        </div>
    </flux:card>

    @if(!in_array($ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
        <flux:card>
            <flux:heading size="lg" class="mb-4">{{ __('Add Reply') }}</flux:heading>
            <form wire:submit="submitReply" class="space-y-4">
                <flux:textarea wire:model="replyContent"/>
                <flux:button type="submit" variant="primary">
                    {{ __('Submit Reply') }}
                </flux:button>
            </form>
        </flux:card>
    @else
        <flux:card>
            <div class="text-center">
                <p class="mb-4">{{ __('This ticket is currently closed or resolved.') }}</p>
                <flux:button wire:click="reopenTicket">
                    {{ __('Reopen Ticket') }}
                </flux:button>
            </div>
        </flux:card>
    @endif

    @if(!in_array($ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]))
        <div class="flex justify-end">
            <flux:button wire:click="markAsResolved">
                {{ __('Mark as Resolved') }}
            </flux:button>
        </div>
    @endif
</div>

@push('styles')
    @filamentStyles
@endpush
@push('scripts')
    @filamentScripts
@endpush
