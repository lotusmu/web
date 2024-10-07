<?php

namespace App\Actions;

use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;
use Illuminate\Support\Str;

class TransferResources
{
    public function handle(User $sender, User $recipient, ResourceType $resourceType, int $amount, int $taxAmount): bool
    {
        $totalAmount = $amount + $taxAmount;

        if (! $sender->resource($resourceType)->decrement($totalAmount)) {
            return false;
        }
        $this->recordSenderActivity($sender, $recipient, $resourceType, $totalAmount);

        $recipient->resource($resourceType)->increment($amount);
        $this->recordRecipientActivity($sender, $recipient, $resourceType, $amount);

        Flux::toast(
            heading: 'Success',
            text: "You've send {$amount} {$resourceType->getLabel()} to {$recipient->name}. Tax paid: {$taxAmount} {$resourceType->getLabel()}.",
        );

        return true;
    }

    public function recordSenderActivity(User $sender, User $recipient, ResourceType $resourceType, int $amount): void
    {
        $newValue = $sender->getResourceValue($resourceType);

        activity('resource_change')
            ->performedOn($sender)
            ->withProperties([
                'recipient' => $recipient->name,
                'resource_type' => Str::title($resourceType->value),
                'amount' => $amount,
                'new_value' => $newValue,
                ...IdentityProperties::capture(),
            ])
            ->log(':properties.resource_type sent to :properties.recipient. Amount: :properties.amount (incl. tax). New balance: :properties.new_value :properties.resource_type');
    }

    public function recordRecipientActivity(User $sender, User $recipient, ResourceType $resourceType, int $amount): void
    {
        $newValue = $recipient->getResourceValue($resourceType);

        activity('resource_change')
            ->performedOn($recipient)
            ->withProperties([
                'sender' => $sender->name,
                'resource_type' => Str::title($resourceType->value),
                'amount' => $amount,
                'new_value' => $newValue,
            ])
            ->log(':properties.resource_type received from :properties.sender. Amount: :properties.amount (after tax). New balance: :properties.new_value :properties.resource_type');
    }
}
