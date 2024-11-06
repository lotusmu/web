<?php

namespace App\Actions\Wallet;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;
use Illuminate\Support\Str;

class SendResources
{
    use Taxable;

    public function __construct()
    {
        $this->operationType = OperationType::TRANSFER;
        $this->initializeTaxable();
    }

    public function handle(User $sender, User $recipient, ResourceType $resourceType, int $amount): bool
    {
        $taxAmount = $this->calculateRate($amount);
        $totalAmount = $amount + $taxAmount;

        if (! $sender->resource($resourceType)->decrement($totalAmount)) {
            return false;
        }
        $this->recordSenderActivity($sender, $recipient, $resourceType, $totalAmount, $taxAmount);

        $recipient->resource($resourceType)->increment($amount);
        $this->recordRecipientActivity($sender, $recipient, $resourceType, $amount);

        Flux::toast(
            text: __('You\'ve sent :amount :resource to :recipient. Tax paid: :tax :resource', [
                'amount' => $this->format($amount),
                'resource' => $resourceType->getLabel(),
                'recipient' => $recipient->name,
                'tax' => $this->format($taxAmount),
            ]),
            heading: __('Transfer Completed'),
            variant: 'success',
        );

        return true;
    }

    public function recordSenderActivity(User $sender, User $recipient, ResourceType $resourceType, int $amount, int $taxAmount): void
    {
        $balance = $sender->getResourceValue($resourceType);

        $properties = [
            'activity_type' => ActivityType::DECREMENT->value,
            'recipient' => $recipient->name,
            'amount' => $this->format($amount),
            'tax_amount' => $this->format($taxAmount),
            'tax_rate' => $this->getRate().'%',
            'resource_type' => Str::title($resourceType->value),
            'balance' => $this->format($balance),
            ...IdentityProperties::capture(),
        ];

        $description = ':properties.resource_type sent to :properties.recipient. Amount: :properties.amount (incl. tax).';

        activity('resource_transfer')
            ->performedOn($sender)
            ->withProperties($properties)
            ->log($description);
    }

    public function recordRecipientActivity(User $sender, User $recipient, ResourceType $resourceType, int $amount): void
    {
        $balance = $recipient->getResourceValue($resourceType);

        $properties = [
            'activity_type' => ActivityType::INCREMENT->value,
            'sender' => $sender->name,
            'amount' => $this->format($amount),
            'resource_type' => Str::title($resourceType->value),
            'balance' => $this->format($balance),
        ];

        $description = ':properties.resource_type received from :properties.sender. Amount: :properties.amount.';

        activity('resource_transfer')
            ->performedOn($recipient)
            ->withProperties($properties)
            ->log($description);
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }
}
