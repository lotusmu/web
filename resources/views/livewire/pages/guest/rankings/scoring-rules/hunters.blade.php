
<?php

use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Ranking\Monster;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public RankingScoreType $type = RankingScoreType::EVENTS;

    #[Computed]
    public function monsters(): Collection
    {
        return Monster::query()
            ->where('PointsPerKill', '>', 0)
            ->orderBy('PointsPerKill', 'desc')
            ->get()
            ->map(function ($monster) {
                return [
                    'name'   => $monster->MonsterName,
                    'points' => $monster->PointsPerKill,
                    'image'  => $monster->image_path ? asset($monster->image_path) : null,
                ];
            });
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.hunters-modal');
    }
} ?>


<div class="space-y-12">
    <header>
        <flux:heading size="lg">{{ __('Hunter Scoring Rules') }}</flux:heading>
        <flux:subheading>{{ __('Points awarded for monster hunting.') }}</flux:subheading>
    </header>

    <div>
        @foreach($this->monsters as $monster)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if($monster['image'])
                        <img src="{{ $monster['image'] }}" alt="{{ $monster['name'] }}"
                             class="w-12 h-12 object-cover">
                    @endif

                    <flux:text>{{ $monster['name'] }}</flux:text>
                </div>

                <flux:badge size="sm" variant="solid">
                    {{ $monster['points'] }} {{ __('points') }}
                </flux:badge>
            </div>

            @if(!$loop->last)
                <flux:separator variant="subtle" class="my-6"/>
            @endif
        @endforeach
    </div>
</div>
