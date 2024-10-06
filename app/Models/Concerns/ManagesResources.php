<?php

namespace App\Models\Concerns;

use App\Actions\DecrementResource;
use App\Actions\IncrementResource;
use App\Enums\Utility\ResourceType;

trait ManagesResources
{
    public function resource(ResourceType $type): object
    {
        return new class($this, $type)
        {
            public function __construct(private $user, private readonly ResourceType $type) {}

            public function increment(int $amount): bool
            {
                return (new IncrementResource($this->user, $this->type, $amount))->handle();
            }

            public function decrement(int $amount): bool
            {
                return (new DecrementResource($this->user, $this->type, $amount))->handle();
            }
        };
    }
}
