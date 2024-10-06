<?php

namespace App\Actions;

use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use Flux;

class TransferResources
{
    public function handle(User $sender, User $recipient, ResourceType $type, int $amount, int $taxAmount): bool
    {
        $totalAmount = $amount + $taxAmount;

        if (! $sender->resource($type)->decrement($totalAmount)) {
            return false;
        }

        $recipient->resource($type)->increment($amount);

        Flux::toast(
            heading: 'Success',
            text: "You've send {$amount} {$type->getLabel()} to {$recipient->name}. Tax paid: {$taxAmount} {$type->getLabel()}.",
        );

        return true;
    }
}
