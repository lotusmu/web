<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <!-- Tokens Card -->
    <flux:card class="!p-4">
        <flux:subheading size="sm">
            {{__('Tokens')}}
        </flux:subheading>
        <flux:heading size="lg">
            {{ $this->resources->tokens }}
        </flux:heading>
    </flux:card>

    <!-- Credits Card -->
    <flux:card class="!p-4">
        <flux:subheading size="sm">
            {{__('Credits')}}
        </flux:subheading>
        <flux:heading size="lg">
            {{ $this->resources->credits }}
        </flux:heading>
    </flux:card>

    <!-- Zen Card -->
    <flux:card class="!p-4">
        <flux:subheading size="sm">
            {{__('Zen')}}
        </flux:subheading>
        <flux:heading size="lg">
            {{ $this->resources->zen }}
        </flux:heading>
    </flux:card>

    <!-- Account Level Card -->
    <flux:card class="!p-4">
        <flux:subheading size="sm">
            {{__('Account Level')}}
        </flux:subheading>

        @if ($this->accountLevel)
            <flux:badge icon="fire" size="sm" color="{{ $this->accountLevel['color'] }}" class="mt-2">
                {{ $this->accountLevel['label'] }}
            </flux:badge>
        @else
            <flux:heading size="lg">
                {{__('Regular')}}
            </flux:heading>
        @endif
    </flux:card>
</div>