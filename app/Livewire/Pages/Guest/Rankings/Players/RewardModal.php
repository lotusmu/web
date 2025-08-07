<?php

namespace App\Livewire\Pages\Guest\Rankings\Players;

use App\Livewire\BaseComponent;
use App\Models\Game\Ranking\WeeklyRankingReward;
use Livewire\Attributes\Computed;

class RewardModal extends BaseComponent
{
    #[Computed]
    public function rewards()
    {
        $currentServerId = session('selected_server_id');

        return WeeklyRankingReward::query()
            ->whereHas('configuration', function ($query) use ($currentServerId) {
                $query->where('game_server_id', $currentServerId);
            })
            ->orderBy('position_from')
            ->get()
            ->map(fn ($reward) => [
                'position' => $this->formatPosition($reward->position_from, $reward->position_to),
                'rewards' => $reward->rewards,
            ]);
    }

    protected function formatPosition(int $from, int $to): string
    {
        return $from === $to
            ? "{$from}."
            : "{$from} - {$to}.";
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.reward-modal');
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.players.reward-modal';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
