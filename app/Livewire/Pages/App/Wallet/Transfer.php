<?php

namespace App\Livewire\Pages\App\Wallet;

use App\Actions\Wallet\TransferZen;
use App\Enums\Utility\ResourceType;
use App\Livewire\BaseComponent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class Transfer extends BaseComponent
{
    public $source = '';

    public $destination = '';

    public $sourceCharacter = '';

    public $destinationCharacter = '';

    public $amount = 0;

    public function rules(): array
    {
        return [
            'source' => 'required|in:wallet,character',
            'destination' => 'required|in:wallet,character',
            'sourceCharacter' => 'required_if:source,character',
            'destinationCharacter' => 'required_if:destination,character',
            'amount' => 'required|integer|min:1',
        ];
    }

    #[Computed]
    public function characters()
    {
        return Auth::user()->member->characters->map(function ($character) {
            return [
                'name' => $character->Name,
                'zen' => $character->Money,
            ];
        });
    }

    #[Computed]
    public function zenWallet()
    {
        return Auth::user()->getResourceValue(ResourceType::ZEN);
    }

    public function transfer(TransferZen $action): void
    {
        $this->validate();

        $user = Auth::user();

        $success = $action->handle(
            $user,
            $this->source,
            $this->destination,
            $this->sourceCharacter,
            $this->destinationCharacter,
            $this->amount
        );

        if ($success) {
            $this->reset(['source', 'destination', 'sourceCharacter', 'destinationCharacter', 'amount']);
            $this->dispatch('resourcesUpdated');
        }
    }

    protected function getViewName(): string
    {
        return 'pages.app.wallet.transfer-zen';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
