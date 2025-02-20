<?php

namespace App\Actions\Wallet;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux\Flux;
use Illuminate\Support\Facades\RateLimiter;

class ExchangeResources
{
    use Taxable;

    private const MAX_ATTEMPTS = 10;

    private const DECAY_SECONDS = 60;

    public function __construct()
    {
        $this->operationType = OperationType::EXCHANGE;
        $this->initializeTaxable();
    }

    public function handle(User $user, int $amount): bool
    {
        if (! $this->ensureIsNotRateLimited($user->id)) {
            return false;
        }

        $tax = $this->calculateRate($amount);
        $exchangedAmount = $amount - $tax;

        if (! $user->resource(ResourceType::TOKENS)->decrement($amount)) {
            return false;
        }

        $user->resource(ResourceType::CREDITS)->increment($exchangedAmount);

        RateLimiter::hit($this->throttleKey($user->id));

        $this->recordActivity($user, $amount, $tax);
        $this->notifyUser($tax, $amount);

        return true;
    }

    private function throttleKey(int $userId): string
    {
        return 'exchange-tokens:'.$userId;
    }

    private function ensureIsNotRateLimited(int $userId): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($userId));

        Flux::toast(
            text: __('Too many exchanges. Please wait :seconds seconds.', ['seconds' => $seconds]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }

    public function recordActivity(User $user, int $amount, int $tax): void
    {
        $properties = [
            'activity_type' => ActivityType::INTERNAL->value,
            'amount' => $this->format($amount),
            'token_balance' => $user->tokens->format(),
            'credit_balance' => $user->credits->format(),
            'rate' => $this->getRate().'%',
            ...IdentityProperties::capture(),
        ];

        if ($tax > 0) {
            $properties['tax'] = $this->format($tax);
        }

        $description = $tax > 0
            ? 'Exchanged: :properties.amount tokens for credits. Tax: :properties.tax tokens at :properties.rate.'
            : 'Exchanged: :properties.amount tokens for credits.';

        activity('resource_exchange')
            ->performedOn($user)
            ->withProperties($properties)
            ->log($description);
    }

    public function notifyUser(int $tax, int $amount): void
    {
        $toastText = $tax > 0
            ? __(':amount tokens exchanged for credits (Tax: :tax tokens)', [
                'amount' => $this->format($amount),
                'tax' => $this->format($tax),
            ])
            : __(':amount tokens exchanged for credits', [
                'amount' => $this->format($amount),
            ]);

        Flux::toast(
            text: $toastText,
            heading: __('Exchange completed'),
            variant: 'success',
        );
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }
}
