<?php

namespace App\Actions\Wallet;

use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use Flux;

readonly class DecrementResource
{
    public function __construct(
        private User $user,
        private ResourceType $resourceType,
        private int $amount
    ) {}

    public function handle(): bool
    {
        if (! $this->validate()) {
            return false;
        }

        $this->decrementResource();
        $this->saveChanges();

        return true;
    }

    private function validate(): bool
    {
        $currentValue = $this->user->getResourceValue($this->resourceType);

        if ($currentValue >= $this->amount) {
            return true;
        }

        Flux::toast(
            text: __('Insufficient :resource. You need :amount but only have :current', [
                'resource' => $this->resourceType->getLabel(),
                'amount' => $this->format($this->amount),
                'current' => $this->format($currentValue),
            ]),
            heading: __('Insufficient Funds'),
            variant: 'warning',
        );

        return false;
    }

    private function decrementResource(): void
    {
        match ($this->resourceType) {
            ResourceType::TOKENS => $this->user->member->tokens -= $this->amount,
            ResourceType::CREDITS => $this->user->member->wallet->credits -= $this->amount,
            ResourceType::ZEN => $this->user->member->wallet->zen -= $this->amount,
        };
    }

    private function saveChanges(): void
    {
        match ($this->resourceType) {
            ResourceType::TOKENS => $this->user->member->save(),
            ResourceType::CREDITS, ResourceType::ZEN => $this->user->member->wallet->save(),
        };
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }
}
