@php use App\Enums\Game\AccountLevel; @endphp
@props(['character', 'accountLevel', 'accountCharacters'])

<div class="space-y-8">
    <div>
        <flux:heading size="lg" class="mb-2">
            {{ __('Account Information') }}
        </flux:heading>
        <flux:separator/>
    </div>

    <div
        class="flex items-start justify-start space-x-8">
        <div class="flex-1 max-w-64">
            <div class="space-y-4">
                <div>
                    <flux:subheading>{{ __('Account Level') }}</flux:subheading>
                    @if ($character->member->hasValidVipSubscription())
                        <flux:badge icon="fire" size="sm" color="{{ $accountLevel['color'] }}" inset="top bottom"
                                    class="mt-2">
                            {{ $accountLevel['label'] }}
                        </flux:badge>
                    @else
                        <flux:heading>{{__('Regular')}}</flux:heading>
                    @endif
                </div>

                <div>
                    <flux:subheading>{{ __('Last Login') }}</flux:subheading>
                    <flux:heading>
                        {{ $character->getDisplayLastLogin() ?? __('Never')}}
                    </flux:heading>
                </div>
            </div>
        </div>

        <div class="flex-1">
            <div class="space-y-4">
                <div>
                    <flux:subheading>{{ __('Current Status') }}</flux:subheading>
                    <flux:badge size="sm" color="{{ $character->member->status?->ConnectStat ? 'emerald' : 'rose' }}"
                                class="mt-2">
                        {{ $character->member->status?->currentStatus ?? __('Offline') }}
                    </flux:badge>
                </div>

                <div>
                    <flux:subheading>{{ __('Last Disconnect') }}</flux:subheading>
                    <flux:heading>
                        {{ $character->getDisplayLastDisconnect() ?? __('Never') }}
                    </flux:heading>
                </div>
            </div>
        </div>
    </div>

    @if(!$accountCharacters->isEmpty())
        <x-profile.character.account-characters :characters="$accountCharacters"/>
    @endif
</div>
