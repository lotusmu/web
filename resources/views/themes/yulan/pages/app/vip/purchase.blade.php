<div class="space-y-8">
    <header>
        <flux:heading size="xl">
            {{ __('Upgrade Your Account') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Get a head start and accelerate your progress with our premium packages.') }}
        </flux:subheading>
    </header>

    <div class="grid sm:grid-cols-2 gap-4">
        @foreach ($this->packages as $index => $package)
            <livewire:pages.app.vip.card
                :$package
                :is-featured="$loop->first"
                :wire:key="'package-' . $package->id"
            />
        @endforeach
    </div>
</div>
