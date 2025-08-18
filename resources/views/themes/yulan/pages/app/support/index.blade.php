<div class="space-y-8">
    <header class="flex items-center max-sm:flex-col-reverse max-sm:items-start max-sm:gap-4">
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
                     wire:navigate size="sm" icon="plus">
            {{__('New Ticket')}}
        </flux:button>
    </header>

    <flux:table :paginate="$this->tickets">
        <flux:columns>
            <flux:column>{{ __('Subject') }}</flux:column>
            <flux:column>{{ __('Category') }}</flux:column>
            <flux:column>{{ __('Priority') }}</flux:column>
            <flux:column>{{ __('Status') }}</flux:column>
        </flux:columns>
        <flux:rows>
            @forelse ($this->tickets() as $ticket)
                <livewire:pages.app.support.ticket-row :$ticket :key="$ticket->id"/>
            @empty
                <flux:row>
                    <flux:cell colspan="4">
                        {{ __('No support tickets found.') }}
                    </flux:cell>
                </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>

    @themeComponent('support.faq')
</div>
