<?php

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public function tickets()
    {
        return Ticket::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}; ?>

<div class="space-y-6">
    <header class="flex items-center">
        <div>
            <flux:heading size="xl">
                {{ __('Support') }}
            </flux:heading>

            <x-flux::subheading>
                {{ __('Get help with your questions, issues, or feedback.') }}
            </x-flux::subheading>
        </div>

        <flux:spacer/>

        <flux:button :href="route('support.create-ticket')"
                     size="sm" icon="plus" inset="top bottom">
            {{__('New Ticket')}}
        </flux:button>
    </header>

    <flux:table>
        <flux:columns>
            <flux:column>{{ __('Subject') }}</flux:column>
            <flux:column>{{ __('Category') }}</flux:column>
            <flux:column>{{ __('Status') }}</flux:column>
            <flux:column>{{ __('Priority') }}</flux:column>
        </flux:columns>
        <flux:rows>
            @forelse ($this->tickets() as $ticket)
                <livewire:support.ticket-row :$ticket/>
            @empty
                <flux:row>
                    <flux:cell colspan="4">
                        {{ __('No support tickets found.') }}
                    </flux:cell>
                </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>

    <livewire:support.faq/>
</div>

