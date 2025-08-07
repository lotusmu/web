<?php

namespace App\Livewire\Pages\App\Wallet;

use App\Actions\Wallet\ExchangeResources;
use App\Enums\Utility\OperationType;
use App\Livewire\BaseComponent;
use App\Models\Concerns\Taxable;
use App\Models\User\User;
use Livewire\Attributes\Validate;

class ExchangeTokens extends BaseComponent
{
    use Taxable;

    public User $user;

    #[Validate('required|integer|min:1')]
    public int $amount;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->operationType = OperationType::EXCHANGE;
        $this->initializeTaxable();
    }

    public function exchange(ExchangeResources $action): void
    {
        $this->validate();

        $success = $action->handle(
            $this->user,
            $this->amount
        );

        if ($success) {
            $this->reset('amount');
            $this->dispatch('resourcesUpdated');
        }
    }

    protected function getViewName(): string
    {
        return 'pages.app.wallet.exchange-tokens';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
