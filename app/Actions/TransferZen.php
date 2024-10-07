<?php

namespace App\Actions;

use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class TransferZen
{
    private const MAX_CHARACTER_ZEN = 2000000000; // 2 billion

    public function handle(User $user, string $source, string $destination, string $sourceCharacter, string $destinationCharacter, int $amount): bool
    {
        return match ([$source, $destination]) {
            ['wallet', 'character'] => $this->transferFromWalletToCharacter($user, $destinationCharacter, $amount),
            ['character', 'wallet'] => $this->transferFromCharacterToWallet($user, $sourceCharacter, $amount),
            ['character', 'character'] => $this->transferBetweenCharacters($user, $sourceCharacter, $destinationCharacter, $amount),
            default => $this->handleInvalidTransfer(),
        };
    }

    private function transferFromWalletToCharacter(User $user, string $characterName, int $amount): bool
    {
        $character = $this->getCharacter($user, $characterName);

        if (! $this->validateCharacter($character, $characterName)) {
            return false;
        }

        if (! $this->validateCharacterZenLimit($character, $amount)) {
            return false;
        }

        if (! $user->resource(ResourceType::ZEN)->decrement($amount)) {
            return false;
        }

        $character->increment('Money', $amount);
        $this->recordActivity($user, 'wallet', $characterName, $amount);
        $this->notifySuccess("Transferred {$amount} Zen from wallet to {$characterName}.");

        return true;
    }

    private function transferFromCharacterToWallet(User $user, string $characterName, int $amount): bool
    {
        $character = $this->getCharacter($user, $characterName);

        if (! $this->validateCharacter($character, $characterName)) {
            return false;
        }

        if (! $this->validateCharacterZenAmount($character, $amount)) {
            return false;
        }

        $character->decrement('Money', $amount);
        $user->resource(ResourceType::ZEN)->increment($amount);
        $this->recordActivity($user, $characterName, 'wallet', $amount);
        $this->notifySuccess("Transferred {$amount} Zen from {$characterName} to Zen Wallet.");

        return true;
    }

    private function transferBetweenCharacters(User $user, string $sourceCharacter, string $destinationCharacter, int $amount): bool
    {
        $source = $this->getCharacter($user, $sourceCharacter);
        $destination = $this->getCharacter($user, $destinationCharacter);

        if (! $this->validateCharacters($source, $destination, $sourceCharacter, $destinationCharacter)) {
            return false;
        }

        if (! $this->validateCharacterZenAmount($source, $amount)) {
            return false;
        }

        if (! $this->validateCharacterZenLimit($destination, $amount)) {
            return false;
        }

        $source->decrement('Money', $amount);
        $destination->increment('Money', $amount);
        $this->recordActivity($user, $sourceCharacter, $destinationCharacter, $amount);
        $this->notifySuccess("Transferred {$amount} Zen from {$sourceCharacter} to {$destinationCharacter}.");

        return true;
    }

    private function recordActivity(User $user, string $source, string $destination, int $amount): void
    {
        $properties = [
            'amount' => $amount,
            'source' => $source,
            'destination' => $destination,
            'wallet_balance' => $user->zen->format(),
            ...IdentityProperties::capture(),
        ];

        $logMessage = 'Transferred :properties.amount Zen from :properties.source to :properties.destination. Wallet balance: :properties.wallet_balance';

        if ($source !== 'wallet' && $destination !== 'wallet') {
            $sourceCharacter = $this->getCharacter($user, $source);
            $destinationCharacter = $this->getCharacter($user, $destination);
            $properties['source_balance'] = $sourceCharacter->Money;
            $properties['destination_balance'] = $destinationCharacter->Money;
            $logMessage .= ". {$source} balance: :properties.source_balance, {$destination} balance: :properties.destination_balance";
        } elseif ($source !== 'wallet') {
            $sourceCharacter = $this->getCharacter($user, $source);
            $properties['source_balance'] = $sourceCharacter->Money;
            $logMessage .= ". {$source} balance: :properties.source_balance";
        } elseif ($destination !== 'wallet') {
            $destinationCharacter = $this->getCharacter($user, $destination);
            $properties['destination_balance'] = $destinationCharacter->Money;
            $logMessage .= ". {$destination} balance: :properties.destination_balance";
        }

        activity('zen_transfer')
            ->performedOn($user)
            ->withProperties($properties)
            ->log($logMessage);
    }

    private function getCharacter(User $user, string $characterName): ?Character
    {
        return $user->member->characters()->where('Name', $characterName)->first();
    }

    private function validateCharacter(?Character $character, string $characterName): bool
    {
        if (! $character) {
            $this->notifyError("Character {$characterName} not found.");

            return false;
        }

        return true;
    }

    private function validateCharacters(?Character $source, ?Character $destination, string $sourceCharacter, string $destinationCharacter): bool
    {
        if (! $source || ! $destination) {
            $this->notifyError('One or both characters not found.');

            return false;
        }

        return true;
    }

    private function validateCharacterZenAmount(Character $character, int $amount): bool
    {
        if ($character->Money < $amount) {
            $this->notifyError('Insufficient Zen on character.');

            return false;
        }

        return true;
    }

    private function validateCharacterZenLimit(Character $character, int $amount): bool
    {
        if ($character->Money + $amount > self::MAX_CHARACTER_ZEN) {
            $this->notifyError("Transfer would exceed the maximum Zen limit for {$character->Name}.");

            return false;
        }

        return true;
    }

    private function handleInvalidTransfer(): bool
    {
        $this->notifyError('Invalid transfer type.');

        return false;
    }

    private function notifySuccess(string $message): void
    {
        Flux::toast(heading: 'Success', text: $message);
    }

    private function notifyError(string $message): void
    {
        Flux::toast(heading: 'Error', text: $message);
    }
}
