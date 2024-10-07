<?php

namespace App\Actions;

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
            'amount' => $amount,
            'credit_balance' => $user->credits->format(),
            'token_balance' => $user->tokens->format(),
            ...IdentityProperties::capture(),
        ];

        $logMessage = $taxAmount > 0
            ? 'Exchanged: :properties.amount tokens for credits. Tax: :properties.tax_amount tokens. New balances - Credits: :properties.credit_balance, Tokens: :properties.token_balance'
            : 'Exchanged: :properties.amount tokens for credits. New balances - Credits: :properties.credit_balance, Tokens: :properties.token_balance';

        if ($taxAmount > 0) {
            $properties['tax_amount'] = $taxAmount;
        }

        activity('resource_change')
            ->performedOn($user)
            ->withProperties($properties)
            ->log($logMessage);
    }

    public function notifyUser(int $taxAmount, int $amount): void
    {
        $toastText = $taxAmount > 0
            ? "{$amount} tokens exchanged for credits (Tax: {$taxAmount} tokens)"
            : "{$amount} tokens exchanged for credits";

        Flux::toast(
            heading: 'Success',
            text: $toastText,
        );
    }
}
