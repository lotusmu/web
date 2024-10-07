<?php

namespace App\Actions;

use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\User\User;
use Flux;

class TransferZen
{
    public function handle(User $user, string $source, string $destination, string $sourceCharacter, string $destinationCharacter, int $amount): bool
    {
        return match ([$source, $destination]) {
            ['wallet', 'character'] => $this->transferFromWalletToCharacter($user, $destinationCharacter, $amount),
            ['character', 'wallet'] => $this->transferFromCharacterToWallet($user, $sourceCharacter, $amount),
            ['character', 'character'] => $this->transferBetweenCharacters($user, $sourceCharacter, $destinationCharacter, $amount),
            default => false,
        };
    }

    private function transferFromWalletToCharacter(User $user, string $characterName, int $amount): bool
    {
        $character = $this->getCharacter($user, $characterName);

        if (! $character) {
            Flux::toast(
                heading: 'Error',
                text: 'Character not found.'
            );

            return false;
        }

        if (! $user->resource(ResourceType::ZEN)->decrement($amount)) {
            return false;
        }

        $character->increment('Money', $amount);

        Flux::toast(
            heading: 'Success',
            text: "Transferred {$amount} Zen from wallet to {$characterName}."
        );

        return true;
    }

    private function transferFromCharacterToWallet(User $user, string $characterName, int $amount): bool
    {
        $character = $this->getCharacter($user, $characterName);

        if (! $character) {
            Flux::toast(heading: 'Error',
                text: 'Character not found.'
            );

            return false;
        }

        if ($character->Money < $amount) {
            Flux::toast(
                heading: 'Error',
                text: 'Insufficient Zen on character.'
            );

            return false;
        }

        $character->decrement('Money', $amount);
        $user->resource(ResourceType::ZEN)->increment($amount);

        Flux::toast(
            heading: 'Success',
            text: "Transferred {$amount} Zen from {$characterName} to wallet."
        );

        return true;
    }

    private function transferBetweenCharacters(User $user, string $sourceCharacter, string $destinationCharacter, int $amount): bool
    {
        $source = $this->getCharacter($user, $sourceCharacter);
        $destination = $this->getCharacter($user, $destinationCharacter);

        if (! $source || ! $destination) {
            Flux::toast(
                heading: 'Error',
                text: 'One or both characters not found.'
            );

            return false;
        }

        if ($source->Money < $amount) {
            Flux::toast(
                heading: 'Error',
                text: 'Insufficient Zen on source character.'
            );

            return false;
        }

        $source->decrement('Money', $amount);
        $destination->increment('Money', $amount);

        Flux::toast(
            heading: 'Success',
            text: "Transferred {$amount} Zen from {$sourceCharacter} to {$destinationCharacter}."
        );

        return true;
    }

    private function getCharacter(User $user, string $characterName): ?Character
    {
        return $user->member->characters()->where('Name', $characterName)->first();
    }
}
