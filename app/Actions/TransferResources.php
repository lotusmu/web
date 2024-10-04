<?php

namespace App\Actions;

use App\Models\User\User;
use Flux;

class TransferResources
{
    public function handle(User $sender, User $recipient, string $type, int $amount): bool
    {
        if (! $sender->resource($type)->decrement($amount)) {
            return false;
        }

        $recipient->resource($type)->increment($amount);

        Flux::toast(
            heading: 'Success',
            text: "You've send {$amount} {$type} to {$recipient->name}.",
        );

        return true;
    }
}
