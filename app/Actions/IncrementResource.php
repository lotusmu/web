<?php

namespace App\Actions;

use App\Enums\Utility\ResourceType;
use App\Models\User\User;

readonly class IncrementResource
{
    public function __construct(
        private User $user,
        private ResourceType $resourceType,
        private int $amount
    ) {}

    public function handle(): bool
    {
        $this->incrementResource();
        $this->saveChanges();

        return true;
    }

    private function incrementResource(): void
    {
        match ($this->resourceType) {
            ResourceType::TOKENS => $this->user->member->tokens += $this->amount,
            ResourceType::CREDITS => $this->user->member->wallet->credits += $this->amount,
            ResourceType::ZEN => $this->user->member->wallet->zen += $this->amount,
        };
    }

    private function saveChanges(): void
    {
        match ($this->resourceType) {
            ResourceType::TOKENS => $this->user->member->save(),
            ResourceType::CREDITS, ResourceType::ZEN => $this->user->member->wallet->save(),
        };
    }
}
