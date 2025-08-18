<div class="space-y-8">
    <header class="flex items-center max-md:flex-col-reverse max-md:items-start max-md:gap-4">
        <div>
            <flux:heading size="xl">
                {{ __('Account Level') }}
            </flux:heading>

            <flux:subheading>
                {{ __('Upgrade your account, or extend your VIP subscription for continued benefits.') }}
            </flux:subheading>
        </div>

        <flux:spacer/>

        <flux:modal.trigger name="extend-subscription">
            <flux:button size="sm" icon-trailing="chevron-right">
                {{__('Extend Now')}}
            </flux:button>
        </flux:modal.trigger>
    </header>

    <flux:card class="space-y-6">
        <div class="flex items-center">
            <div>
                <flux:heading size="lg">
                    {{__('Current Tier')}}
                </flux:heading>
                <flux:subheading>
                    {{__('Active until')}} {{ $this->accountLevel['expireDate']->format('F d, Y H:i') }}
                </flux:subheading>
            </div>

            <flux:spacer/>

            <flux:badge icon="fire" size="lg" color="{{ $this->accountLevel['color'] }}" inset="top bottom">
                {{ $this->accountLevel['label'] }}
            </flux:badge>
        </div>

        <flux:separator variant="subtle"/>

        <div>
            <flux:heading class="mb-4">
                {{__('Benefits included')}}
            </flux:heading>

            <div class="grid gap-3 sm:grid-cols-2">
                <x-vip.benefits-list/>
            </div>
        </div>
    </flux:card>

    <flux:modal name="extend-subscription" class="md:w-96 space-y-6">
        <div>
            <flux:heading size="lg">{{__('Extend Your Subscription')}}</flux:heading>
            <flux:subheading>{{__('Choose a package to extend your VIP.')}}</flux:subheading>
        </div>

        <form wire:submit="extend" class="space-y-6">
            <flux:radio.group wire:model="packageId" variant="cards" class="flex flex-col">
                @foreach($this->packages as $package)
                    <flux:radio value="{{$package->id}}"
                                label="{{$package->duration}} {{__('days')}}"
                                description="{{ $package->cost }} {{__('tokens')}}"
                    />
                @endforeach
            </flux:radio.group>

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">{{__('Cancel')}}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">{{__('Extend')}}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>