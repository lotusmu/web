<?php

use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Ranking\EventSetting;
use App\Models\Game\Ranking\MonsterSetting;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public RankingScoreType $type;

    #[Computed]
    public function rules(): Collection
    {
        return match ($this->type) {
            RankingScoreType::EVENTS => EventSetting::query()
                ->orderBy('PointsPerWin', 'desc')
                ->get()
                ->map(fn($event) => [
                    'name'   => $event->EventName,
                    'points' => number_format($event->PointsPerWin),
                    'image'  => $event->image_path ? asset($event->image_path) : null,
                ]),

            RankingScoreType::HUNTERS => MonsterSetting::query()
                ->where('PointsPerKill', '>', 0)
                ->orderBy('PointsPerKill', 'desc')
                ->get()
                ->map(fn($monster) => [
                    'name'   => $monster->MonsterName,
                    'points' => number_format($monster->PointsPerKill),
                    'image'  => $monster->image_path ? asset($monster->image_path) : null,
                ]),
        };
    }

    public function placeholder()
    {
        $rows = match ($this->type) {
            RankingScoreType::EVENTS => 4,
            RankingScoreType::HUNTERS => 10,
        };

        return view("livewire.pages.guest.rankings.placeholders.modal", [
            'rows' => $rows
        ]);
    }
} ?>

<div class="space-y-12">
    <header>
        <flux:heading size="lg">
            {{ $type->rulesHeading() }}
        </flux:heading>

        <flux:subheading>
            {{ $type->rulesDescription() }}
        </flux:subheading>
    </header>

    <div>
        @foreach($this->rules as $rule)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if($rule['image'])
                        <img src="{{ $rule['image'] }}"
                             alt="{{ $rule['name'] }}"
                             class="w-12 h-12 object-cover">
                    @endif

                    <flux:text>
                        {{ $rule['name'] }}
                    </flux:text>
                </div>

                <flux:badge size="sm" variant="solid">
                    {{ $rule['points'] }} {{ __('points') }}
                </flux:badge>
            </div>

            @if(!$loop->last)
                <flux:separator variant="subtle" class="my-6"/>
            @endif
        @endforeach
    </div>
</div>
