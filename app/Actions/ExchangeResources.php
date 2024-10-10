<?php

namespace App\Actions;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class ExchangeResources
{
    public function handle(User $user, int $amount, int $taxAmount): bool
    {
        $exchangedAmount = $amount - $taxAmount;

        if (! $user->resource(ResourceType::TOKENS)->decrement($amount)) {
            return false;
        }

        $user->resource(ResourceType::CREDITS)->increment($exchangedAmount);
        $this->recordActivity($user, $amount, $taxAmount);
        $this->notifyUser($taxAmount, $amount);

        return true;
    }

    public function recordActivity(User $user, int $amount, int $taxAmount): void
    {
        $properties = [
            'activity_type' => ActivityType::INTERNAL->value,
            'amount' => $this->format($amount),
            'token_balance' => $user->tokens->format(),
            'credit_balance' => $user->credits->format(),
            ...IdentityProperties::capture(),
        ];

        if ($taxAmount > 0) {
            $properties['tax_amount'] = $this->format($taxAmount);
        }

        $description = $taxAmount > 0
            ? 'Exchanged: :properties.amount tokens for credits. Tax: :properties.tax_amount tokens.'
            : 'Exchanged: :properties.amount tokens for credits.';

        activity('resource_exchange')
            ->performedOn($user)
            ->withProperties($properties)
            ->log($description);
    }

    public function notifyUser(int $taxAmount, int $amount): void
    {
        $toastText = $taxAmount > 0
            ? "{$this->format($amount)} tokens exchanged for credits (Tax: {$this->format($taxAmount)} tokens)"
            : "{$this->format($amount)} tokens exchanged for credits";

        Flux::toast(
            variant: 'success',
            heading: 'Exchange completed',
            text: $toastText,
        );
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }
}
