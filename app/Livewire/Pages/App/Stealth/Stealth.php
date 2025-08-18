<?php

namespace App\Livewire\Pages\App\Stealth;

use App\Actions\Member\ManageStealthMode;
use App\Enums\Utility\OperationType;
use App\Livewire\BaseComponent;
use App\Models\Concerns\Taxable;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class Stealth extends BaseComponent
{
    use Taxable;

    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->operationType = OperationType::STEALTH;
        $this->initializeTaxable();
    }

    public function enable(ManageStealthMode $action): void
    {
        $action->handle($this->user);

        $this->modal('enable')->close();
    }

    public function extend(ManageStealthMode $action): void
    {
        $action->handle($this->user, 'extend');

        $this->modal('extend')->close();
    }

    protected function getViewName(): string
    {
        return 'pages.app.stealth.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
