
<?php

use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Ranking\EventSetting;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;

new class extends Component {
    private RankingScoreType $type = RankingScoreType::EVENTS;


    #[Computed]
    public function events(): Collection
    {
        return EventSetting::query()
            ->orderBy('PointsPerWin', 'desc')
            ->get()
            ->map(function ($event) {
                return [
                    'name'   => $event->EventName,
                    'points' => $event->PointsPerWin,
                    'image'  => $event->image_path ? asset($event->image_path) : null,
                ];
            });
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.events-modal');
    }
} ?>


<div class="space-y-6">
    <header>
        <flux:heading size="lg">{{ __('Event Scoring Rules') }}</flux:heading>
        <flux:subheading>{{ __('Points awarded for winning in events.') }}</flux:subheading>
    </header>

    <div>
        @foreach($this->events as $event)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if($event['image'])
                        <img src="{{ $event['image'] }}" alt="{{ $event['name'] }}"
                             class="w-12 h-12 object-cover">
                    @endif

                    <flux:text>{{ $event['name'] }}</flux:text>
                </div>

                <flux:badge size="sm" variant="solid">
                    {{ $event['points'] }} {{ __('points') }}
                </flux:badge>
            </div>

            @if(!$loop->last)
                <flux:separator variant="subtle" class="my-6"/>
            @endif
        @endforeach
    </div>
</div>
