<?php

namespace App\Livewire\Pages\App\Castle;

use App\Actions\Castle\WithdrawFromCastle;
use App\Livewire\BaseComponent;
use App\Models\Game\CastleData;

class Withdraw extends BaseComponent
{
    public int $treasury = 0;

    public ?string $withdrawType = 'custom';

    public ?int $amount = null;

    public CastleData $castle;

    public function mount(int $treasury, CastleData $castle)
    {
        $this->treasury = $treasury;
        $this->castle = $castle;
    }

    public function withdraw(): void
    {
        if ($this->withdrawType !== 'custom') {
            $this->amount = floor($this->treasury * (intval($this->withdrawType) / 100));
        }

        $this->validate([
            'withdrawType' => ['required', 'in:25,50,75,100,custom'],
            'amount' => ['required', 'numeric', 'min:1', "max:{$this->treasury}"],
        ]);

        $result = (new WithdrawFromCastle(
            auth()->user(),
            $this->castle,
            $this->amount
        ))->handle();

        if ($result) {
            $this->dispatch('treasury-updated', treasury: $this->castle->fresh()->MONEY);
            $this->treasury = $this->castle->fresh()->MONEY;
            $this->reset(['amount', 'withdrawType']);
        }
    }

    protected function getViewName(): string
    {
        return 'pages.app.castle.withdraw';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
