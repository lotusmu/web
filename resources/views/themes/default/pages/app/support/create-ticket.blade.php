@php
use App\Enums\Ticket\TicketPriority;
@endphp

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

        <flux:input wire:model="title" label="{{__('Title')}}"/>

        <div class="flex items-center gap-6 max-sm:flex-col">
            <div class="flex-1 w-full">
                <flux:select wire:model="ticket_category_id" variant="listbox"
                             placeholder="{{__('Choose category...')}}">
                    @foreach($this->categories as $category)
                        <flux:option value="{{ $category->id }}">{{ $category->name }}</flux:option>
                    @endforeach
                </flux:select>

                <flux:error name="ticket_category_id"/>
            </div>

            <div class="flex-1 w-full">
                <flux:select wire:model="priority" variant="listbox"
                             placeholder="{{__('Choose priority...')}}">
                    @foreach(TicketPriority::cases() as $priority)
                        <flux:option :value="$priority->value">
                            {{ $priority->getLabel() }}
                        </flux:option>
                    @endforeach
                </flux:select>

                <flux:error name="priority"/>
            </div>

        </div>

        <flux:editor wire:model="description" label="{{__('Description')}}"
                     toolbar="bold italic underline | bullet ordered highlight | link ~ undo redo"/>

        <flux:field>
            <flux:label badge="Optional">Discord</flux:label>

            <flux:input wire:model="contact_discord"/>

            <flux:description>
                {{ __('Some issues can be resolved faster through Discord chat. Add your username if you want this option.') }}
            </flux:description>

            <flux:error name="contact_discord"/>
        </flux:field>

        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary">
                {{ __('Submit') }}
            </flux:button>
        </div>
    </form>
</div>