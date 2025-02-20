<?php

namespace App\Actions\Castle;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\CastleData;
use App\Models\User\User;
use App\Models\Utility\GameServer;
use App\Support\ActivityLog\IdentityProperties;
use Flux\Flux;
use Illuminate\Support\Facades\RateLimiter;

readonly class WithdrawFromCastle
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function __construct(
        private User $user,
        private CastleData $castle,
        private int $amount
    ) {}

    public function handle(): bool
    {
        if (! $this->ensureIsNotRateLimited($this->user->id)) {
            return false;
        }

        if (! $this->validate()) {
            return false;
        }

        if (! $this->withdrawFunds()) {
            return false;
        }

        RateLimiter::hit($this->throttleKey($this->user->id));

        $this->recordActivity();
        $this->notifySuccess();

        return true;
    }

    private function throttleKey(int $userId): string
    {
        return 'castle-withdraw:'.$userId;
    }

    private function ensureIsNotRateLimited(int $userId): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($userId));

        Flux::toast(
            text: __('Too many withdrawals. Please wait :seconds seconds.', ['seconds' => $seconds]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }

    private function validate(): bool
    {
        if (! $this->user->isCastleLord($this->castle)) {
            $this->notifyError(__('Only the Castle Lord can withdraw treasury funds.'));

            return false;
        }

        if ($this->castle->MONEY < $this->amount) {
            $this->notifyError(__('Insufficient treasury funds.'));

            return false;
        }

        return true;
    }

    private function withdrawFunds(): bool
    {
        if (! $this->castle->decrement('MONEY', $this->amount)) {
            return false;
        }

        cache()->forget('castle_data');

        return $this->user->resource(ResourceType::ZEN)->increment($this->amount);
    }

    private function recordActivity(): void
    {
        $serverName = GameServer::where('connection_name', session('game_db_connection', 'gamedb_main'))
            ->first()
            ->getServerName();

        $properties = [
            'activity_type' => ActivityType::INCREMENT->value,
            'source' => 'Castle Treasury',
            'castle' => $this->castle->OWNER_GUILD,
            'amount' => $this->format($this->amount),
            'treasury_balance' => $this->format($this->castle->MONEY),
            'wallet_balance' => $this->format($this->user->getResourceValue(ResourceType::ZEN)),
            'connection' => $serverName,
            ...IdentityProperties::capture(),
        ];

        $description = 'Withdrew :properties.amount Zen from :properties.source (:properties.connection).';

        activity('castle_siege')
            ->performedOn($this->user)
            ->withProperties($properties)
            ->log($description);
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }

    private function notifySuccess(): void
    {
        Flux::toast(
            text: __('Successfully withdrew :amount Zen from castle treasury', [
                'amount' => $this->format($this->amount),
            ]),
            heading: __('Success'),
            variant: 'success'
        );
    }

    private function notifyError(string $message): void
    {
        Flux::toast(
            text: $message,
            heading: __('Error'),
            variant: 'danger'
        );
    }
}
