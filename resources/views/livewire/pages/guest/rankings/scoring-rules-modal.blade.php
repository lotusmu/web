<?php

use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;

new class extends Component {
    public string $type; // events/hunters

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return match ($this->type) {
            'events' => __('Event Scoring Rules'),
            'hunters' => __('Hunter Scoring Rules'),
            default => __('Scoring Rules'),
        };
    }

    public function getDescription(): string
    {
        return match ($this->type) {
            'events' => __("Points awarded for defeating monsters in events."),
            'hunters' => __("Points awarded for monster hunting."),
            default => __("Points breakdown."),
        };
    }

    #[Computed]
    public function monsterScores(): Collection
    {
        // This will pull from your database/config based on type
        return collect([
            // Example structure - adjust based on your needs
            [
                'name'   => 'Golden Dragon',
                'points' => 100,
                'level'  => 95,
                'map'    => 'Dragon Valley',
                // Any other relevant info
            ]
        ]);
    }
} ?>

<div class="flex items-center gap-2">
    <flux:modal.trigger :name="$type . '-scoring'">
        <flux:button icon="information-circle" size="sm" inset="top bottom" variant="ghost"/>
    </flux:modal.trigger>

    <flux:modal
        :name="$type . '-scoring'"
        variant="flyout"
        position="bottom"
    >
        <div class="space-y-6">
            <header>
                <flux:heading size="lg">{{ $this->getTitle() }}</flux:heading>
                <flux:subheading>{{ $this->getDescription() }}</flux:subheading>
            </header>

            <div class="space-y-4">
                @foreach($this->monsterScores as $monster)
                    <div class="flex justify-between items-center p-2">
                        <div class="flex items-center gap-3">
                            {{-- We could add monster icon/image here --}}
                            <div>
                                <flux:text>{{ $monster['name'] }}</flux:text>
                                <flux:text size="sm">
                                    Level {{ $monster['level'] }} • {{ $monster['map'] }}
                                </flux:text>
                            </div>
                        </div>
                        <flux:badge size="lg">{{ $monster['points'] }} points</flux:badge>
                    </div>
                @endforeach
            </div>
        </div>
    </flux:modal>
</div>
