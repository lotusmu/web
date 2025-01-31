
<?php

use App\Enums\Utility\RankingScoreType;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;

new class extends Component {
    public RankingScoreType $type = RankingScoreType::EVENTS;

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


<div class="space-y-6">
    <header>
        <flux:heading size="lg">{{ __('Hunter Scoring Rules') }}</flux:heading>
        <flux:subheading>{{ __('Points awarded for monster hunting.') }}</flux:subheading>
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
