
<?php

use App\Enums\Utility\RankingScoreType;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;

new class extends Component {
    private RankingScoreType $type = RankingScoreType::EVENTS;

    #[Computed]
    public function eventScores(): Collection
    {
        // This will pull from your database/config based on type
        return collect([
            // Example structure - adjust based on your needs
            [
                'name'   => 'Blood Castle',
                'points' => 100,
                'level'  => 95,
                'map'    => 'Dragon Valley',
                // Any other relevant info
            ]
        ]);
    }
} ?>


<div class="space-y-6">
    <header>
        <flux:heading size="lg">{{ __('Event Scoring Rules') }}</flux:heading>
        <flux:subheading>{{ __('Points awarded for defeating monsters in events.') }}</flux:subheading>
    </header>

    <div class="space-y-4">
        @foreach($this->eventScores as $event)
            <div class="flex justify-between items-center p-2">
                <div class="flex items-center gap-3">
                    {{-- We could add monster icon/image here --}}
                    <div>
                        <flux:text>{{ $event['name'] }}</flux:text>
                        <flux:text size="sm">
                            Level {{ $event['level'] }} • {{ $event['map'] }}
                        </flux:text>
                    </div>
                </div>
                <flux:badge size="lg">{{ $event['points'] }} points</flux:badge>
            </div>
        @endforeach
    </div>
</div>
