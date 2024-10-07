<?php

namespace App\Actions;

use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class TransferZen
{
    private const MAX_ZEN = 2000000000; // 2 billion

    private const WALLET_TO_CHAR = ['wallet', 'character'];

    private const CHAR_TO_WALLET = ['character', 'wallet'];

    private const CHAR_TO_CHAR = ['character', 'character'];

    public function handle(User $user, string $from, string $to, string $fromChar, string $toChar, int $amount): bool
    {
        return match ([$from, $to]) {
            self::WALLET_TO_CHAR => $this->fromWalletToChar($user, $toChar, $amount),
            self::CHAR_TO_WALLET => $this->fromCharToWallet($user, $fromChar, $amount),
            self::CHAR_TO_CHAR => $this->betweenChars($user, $fromChar, $toChar, $amount),
            default => $this->invalidTransfer(),
        };
    }

    private function fromWalletToChar(User $user, string $charName, int $amount): bool
    {
        $char = $this->getChar($user, $charName);

        if (! $this->validate($user, null, $char, $amount, 'wallet_to_char')) {
            return false;
        }

        if (! $user->resource(ResourceType::ZEN)->decrement($amount)) {
            return false;
        }

        $char->increment('Money', $amount);
        $this->recordActivity($user, 'wallet', $charName, $amount);
        $this->success('Transferred '.$this->format($amount)." Zen from wallet to {$charName}.");

        return true;
    }

    private function fromCharToWallet(User $user, string $charName, int $amount): bool
    {
        $char = $this->getChar($user, $charName);

        if (! $this->validate($user, $char, null, $amount, 'char_to_wallet')) {
            return false;
        }

        $char->decrement('Money', $amount);
        $user->resource(ResourceType::ZEN)->increment($amount);
        $this->recordActivity($user, $charName, 'wallet', $amount);
        $this->success('Transferred '.$this->format($amount)." Zen from {$charName} to Zen Wallet.");

        return true;
    }

    private function betweenChars(User $user, string $fromChar, string $toChar, int $amount): bool
    {
        $from = $this->getChar($user, $fromChar);
        $to = $this->getChar($user, $toChar);

        if (! $this->validate($user, $from, $to, $amount, 'char_to_char')) {
            return false;
        }

        $from->decrement('Money', $amount);
        $to->increment('Money', $amount);
        $this->recordActivity($user, $fromChar, $toChar, $amount);
        $this->success('Transferred '.$this->format($amount)." Zen from {$fromChar} to {$toChar}.");

        return true;
    }

    private function validate(User $user, ?Character $from, ?Character $to, int $amount, string $type): bool
    {
        if ($from && ! $this->validateChar($from, $from->Name)) {
            return false;
        }

        if ($to && ! $this->validateChar($to, $to->Name)) {
            return false;
        }

        if ($type === 'char_to_wallet' || $type === 'char_to_char') {
            if (! $this->validateAmount($from, $amount)) {
                return false;
            }
        }

        if ($type === 'wallet_to_char' || $type === 'char_to_char') {
            if (! $this->validateLimit($to, $amount)) {
                return false;
            }
        }

        return true;
    }

    private function recordActivity(User $user, string $from, string $to, int $amount): void
    {
        $properties = $this->activityProps($user, $from, $to, $amount);
        $logMessage = $this->generateLogMessage($properties);

        activity('zen_transfer')
            ->performedOn($user)
            ->withProperties($properties)
            ->log($logMessage);
    }

    private function activityProps(User $user, string $from, string $to, int $amount): array
    {
        $properties = [
            'amount' => $this->format($amount),
            'from' => $from,
            'to' => $to,
            'wallet_balance' => $this->format($user->getResourceValue(ResourceType::ZEN)),
            ...IdentityProperties::capture(),
        ];

        if ($from !== 'wallet') {
            $fromChar = $this->getChar($user, $from);
            $properties['from_balance'] = $this->format($fromChar->Money);
        }

        if ($to !== 'wallet') {
            $toChar = $this->getChar($user, $to);
            $properties['to_balance'] = $this->format($toChar->Money);
        }

        return $properties;
    }

    private function generateLogMessage(array $props): string
    {
        $log = "Transferred {$props['amount']} Zen from {$props['from']} to {$props['to']}. Wallet balance: {$props['wallet_balance']}";

        if (isset($props['from_balance'])) {
            $log .= ". {$props['from']} balance: {$props['from_balance']}";
        }

        if (isset($props['to_balance'])) {
            $log .= ". {$props['to']} balance: {$props['to_balance']}";
        }

        return $log;
    }

    private function getChar(User $user, string $name): ?Character
    {
        return $user->member->characters()
            ->where('Name', $name)
            ->where('member_id', $user->member->id)
            ->first();
    }

    private function validateChar(?Character $char, string $name): bool
    {
        if (! $char) {
            $this->error("Character {$name} not found.");

            return false;
        }

        return true;
    }

    private function validateAmount(Character $char, int $amount): bool
    {
        if ($char->Money < $amount) {
            $this->error('Insufficient Zen on character.');

            return false;
        }

        return true;
    }

    private function validateLimit(Character $char, int $amount): bool
    {
        if ($char->Money + $amount > self::MAX_ZEN) {
            $this->error("Transfer would exceed the maximum Zen limit for {$char->Name}.");

            return false;
        }

        return true;
    }

    private function invalidTransfer(): bool
    {
        $this->error('Invalid transfer type.');

        return false;
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }

    private function notify(string $heading, string $message): void
    {
        Flux::toast(heading: $heading, text: $message);
    }

    private function success(string $message): void
    {
        $this->notify('Success', $message);
    }

    private function error(string $message): void
    {
        $this->notify('Error', $message);
    }
}
