@props(['character', 'accountLevel'])

<div class="w-full">
    <flux:subheading>{{ __('Last Login') }}</flux:subheading>
    <flux:heading>
        {{ $character->member->status?->lastLogin ?? __('Never')}}
    </flux:heading>
</div>

<div class="w-full">
    <flux:subheading>{{ __('Last Disconnect') }}</flux:subheading>
    <flux:heading>
        {{ $character->member->status?->lastDisconnect ?? __('Never') }}
    </flux:heading>
</div>

<div class="w-full">
    <flux:subheading class="mb-2">{{ __('Account Level') }}</flux:subheading>
    @if ($accountLevel)
        <flux:badge icon="fire" size="sm" color="{{ $accountLevel['color'] }}">
            {{ $accountLevel['label'] }}
        </flux:badge>
    @else
        <flux:heading>{{__('Regular')}}</flux:heading>
    @endif
</div>

<div class="w-full">
    <flux:subheading class="mb-2">{{ __('Current Status') }}</flux:subheading>
    <flux:badge size="sm" color="{{ $character->member->status?->currentStatus ? 'emerald' : 'rose' }}">
        {{ $character->member->status?->currentStatus ?? __('Offline') }}
    </flux:badge>
</div>
