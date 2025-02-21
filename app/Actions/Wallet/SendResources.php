<?php

namespace App\Actions\Wallet;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\User\User;
use App\Models\Utility\GameServer;
use App\Support\ActivityLog\IdentityProperties;
use Flux\Flux;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class SendResources
{
    use Taxable;

    private const MAX_ATTEMPTS = 10;

    private const DECAY_SECONDS = 60;

    public function __construct()
    {
        $this->operationType = OperationType::TRANSFER;
        $this->initializeTaxable();
    }

    public function handle(User $sender, User $recipient, ResourceType $resourceType, int $amount): bool
    {
        if (! $this->canSendResource($sender, $resourceType)) {
            return false;
        }

        if (! $this->ensureIsNotRateLimited($sender->id)) {
            return false;
        }

        $taxAmount = $this->calculateRate($amount);
        $totalAmount = $amount + $taxAmount;

        $serverName = GameServer::where('connection_name', session('game_db_connection', 'gamedb_main'))
            ->first()
            ->getServerName();

        if (! $sender->resource($resourceType)->decrement($totalAmount)) {
            return false;
        }
        $this->recordSenderActivity($sender, $recipient, $resourceType, $totalAmount, $taxAmount, $serverName);

        $recipient->resource($resourceType)->increment($amount);
        $this->recordRecipientActivity($sender, $recipient, $resourceType, $amount, $serverName);

        RateLimiter::hit($this->throttleKey($sender->id));

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

    private function throttleKey(int $userId): string
    {
        return 'send-resources:'.$userId;
    }

    private function ensureIsNotRateLimited(int $userId): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($userId));

        Flux::toast(
            text: __('Too many transfers. Please wait :seconds seconds.', ['seconds' => $seconds]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }

    public function recordSenderActivity(User $sender, User $recipient, ResourceType $resourceType, int $amount, int $taxAmount, string $serverName): void
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

        if ($resourceType !== ResourceType::TOKENS) {
            $properties['connection'] = session('game_db_connection', 'gamedb_main');
        }

        if ($resourceType !== ResourceType::TOKENS) {
            $properties['connection'] = $serverName;

            $description = ':properties.resource_type sent to :properties.recipient. Amount: :properties.amount (incl. tax) in :properties.connection.';
        } else {
            $description = ':properties.resource_type sent to :properties.recipient. Amount: :properties.amount (incl. tax).';
        }

        activity('resource_transfer')
            ->performedOn($sender)
            ->withProperties($properties)
            ->log($description);
    }

    public function recordRecipientActivity(User $sender, User $recipient, ResourceType $resourceType, int $amount, string $serverName): void
    {
        $balance = $recipient->getResourceValue($resourceType);

        $properties = [
            'activity_type' => ActivityType::INCREMENT->value,
            'sender' => $sender->name,
            'amount' => $this->format($amount),
            'resource_type' => Str::title($resourceType->value),
            'balance' => $this->format($balance),
        ];

        if ($resourceType !== ResourceType::TOKENS) {
            $properties['connection'] = $serverName;

            $description = ':properties.resource_type received from :properties.sender. Amount: :properties.amount (:properties.connection).';
        } else {
            $description = ':properties.resource_type received from :properties.sender. Amount: :properties.amount.';
        }

        activity('resource_transfer')
            ->performedOn($recipient)
            ->withProperties($properties)
            ->log($description);
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }

    private function canSendResource(User $sender, ResourceType $resourceType): bool
    {
        if ($resourceType === ResourceType::TOKENS) {
            return true;
        }

        if ($sender->member->isBanned()) {
            Flux::toast(
                text: __('You cannot send :resource while your account is banned.', [
                    'resource' => $resourceType->getLabel(),
                ]),
                heading: __('Transfer Blocked'),
                variant: 'danger',
            );

            return false;
        }

        return true;
    }
}
