<?php

namespace App\Livewire\Pages\App\Castle;

use App\Livewire\BaseComponent;
use App\Models\Game\CastleData;
use App\Models\Utility\CastlePrize;

class PrizePool extends BaseComponent
{
    private const DEFAULT_GAME_SERVER_ID = 1;

    public ?CastleData $castle = null;

    public function mount(CastleData $castle)
    {
        $this->castle = $castle;
    }

    public function getPrizePool(): ?CastlePrize
    {
        $connection = session('selected_server_id', self::DEFAULT_GAME_SERVER_ID);

        return CastlePrize::where('game_server_id', $connection)
            ->where('is_active', true)
            ->where('remaining_prize_pool', '>', 0)
            ->first();
    }

    public function getTimeUntilNextDistribution(): string
    {
        $now = now();
        $nextSunday = $now->copy()->next('Sunday')->setHour(23)->setMinute(1)->setSecond(0);

        if ($now->isSunday() && $now->hour < 23) {
            $nextSunday = $now->copy()->setHour(23)->setMinute(1)->setSecond(0);
        }

        if ($now->isSunday() && $now->hour >= 23) {
            $nextSunday = $now->copy()->next('Sunday')->setHour(23)->setMinute(1)->setSecond(0);
        }

        $diff = $now->diff($nextSunday);

        return "{$diff->d}d {$diff->h}h {$diff->i}m";
    }

    protected function getViewName(): string
    {
        return 'pages.app.castle.prize-pool';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
