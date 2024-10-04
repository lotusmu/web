<?php

namespace App\Actions;

use App\Models\User\User;
use App\Services\ResourceTypeValidator;
use Flux;
use Illuminate\Support\Str;

readonly class DecrementResource
{
    public function __construct(
        private User $user,
        private string $resourceType,
        private int $amount
    ) {
        ResourceTypeValidator::validate($this->resourceType);
    }

    public function handle(): bool
    {
        if (! $this->validate()) {
            return false;
        }

        $this->decrementResource();
        $this->saveChanges();
        $this->recordActivity();

        return true;
    }

    private function validate(): bool
    {
        $currentValue = $this->getResourceValue();

        if ($currentValue >= $this->amount) {
            return true;
        }

        Flux::toast(
            heading: 'Warning',
            text: "Insufficient {$this->resourceType}. You need {$this->amount} but only have {$currentValue}.",
        );

        return false;
    }

    private function decrementResource(): void
    {
        match ($this->resourceType) {
            'tokens' => $this->user->member->tokens -= $this->amount,
            'credits' => $this->user->member->wallet->credits -= $this->amount,
            'zen' => $this->user->member->wallet->zen -= $this->amount,
        };
    }

    private function saveChanges(): void
    {
        match ($this->resourceType) {
            'tokens' => $this->user->member->save(),
            'credits', 'zen' => $this->user->member->wallet->save(),
        };
    }

    public function recordActivity(): void
    {
        $newValue = $this->getResourceValue();

        activity('resource_change')
            ->performedOn($this->user)
            ->withProperties([
                'resource_type' => Str::title($this->resourceType),
                'amount' => $this->amount,
                'new_value' => $newValue,
            ])
            ->log(':properties.resource_type decreased by :properties.amount. New total: :properties.new_value');
    }

    private function getResourceValue(): int
    {
        return match ($this->resourceType) {
            'tokens' => $this->user->member->tokens,
            'credits' => $this->user->member->wallet->credits,
            'zen' => $this->user->member->wallet->zen,
        };
    }
}
