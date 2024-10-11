<?php

use App\Enums\Ticket\TicketPriority;
use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use Filament\Forms\Components\FileUpload;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ?string $title = '';

    public ?int $ticket_category_id = null;

    public ?string $priority = null;

    public ?string $description = '';

    #[Computed]
    public function categories()
    {
        return Cache::remember('ticket_categories', now()->addDay(), function () {
            return TicketCategory::select('id', 'name')->orderBy('name')->get();
        });
    }

    public function create()
    {
        $this->validate([
            'title'              => 'required|string|max:255',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'priority'           => 'required|in:'.implode(',', array_column(TicketPriority::cases(), 'value')),
            'description'        => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id'            => Auth::id(),
            'title'              => $this->title,
            'ticket_category_id' => $this->ticket_category_id,
            'priority'           => $this->priority,
            'description'        => nl2br($this->description),
            'status'             => TicketStatus::NEW,
        ]);

        if ($ticket) {
            Flux::toast(
                variant: 'success',
                heading: 'Success!',
                text: 'Ticket created successfully.'
            );

            return $this->redirect(route('support'), navigate: true);
        }

        Flux::toast(
            variant: 'danger',
            heading: 'Error! ',
            text: 'Failed to create ticket.Please try again.'
        );
    }
}; ?>

<div class="space-y-6">
    <header class="flex items-center max-sm:flex-col-reverse max-sm:items-start max-sm:gap-4">
        <div>
            <flux:heading size="xl">
                {{ __('Create New Ticket') }}
            </flux:heading>

            <x-flux::subheading>
                {{ __('Submit a new support ticket for your questions or issues.') }}
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

    <form wire:submit="create" class="space-y-6">

        <flux:input wire:model="title" label="Title"/>

        <div class="flex items-center gap-6 max-sm:flex-col">
            <flux:select wire:model="ticket_category_id" variant="listbox" placeholder="{{__('Choose category...')}}">
                @foreach($this->categories as $category)
                    <flux:option value="{{ $category->id }}">{{ $category->name }}</flux:option>
                @endforeach
            </flux:select>

            <flux:select wire:model="priority" variant="listbox"
                         placeholder="{{__('Choose priority...')}}">
                @foreach(TicketPriority::cases() as $priority)
                    <flux:option :value="$priority->value">
                        {{ $priority->getLabel() }}
                    </flux:option>
                @endforeach
            </flux:select>
        </div>

        <flux:textarea wire:model="description" label="Description" rows="8"/>

        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary">
                {{ __('Submit') }}
            </flux:button>
        </div>
    </form>
</div>
