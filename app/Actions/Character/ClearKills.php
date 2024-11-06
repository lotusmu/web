<?php

namespace App\Actions\Character;

use App\Enums\Game\PkLevel;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class ClearKills
{
    use Taxable;

    public function __construct()
    {
        $this->operationType = OperationType::PK_CLEAR;
        $this->initializeTaxable();
    }

    public function handle(User $user, Character $character): bool
    {
        $amount = $this->calculateRate($character->PkCount);

        if (! $this->validate($user, $character, $amount)) {
            return false;
        }

        $resource = ResourceType::from($this->getResourceType());

        if ($resource === ResourceType::ZEN) {
            $this->decrementCharacterZen($amount, $character);
        } else {
            if (! $user->resource($resource)->decrement($amount)) {
                return false;
            }
        }

        $this->recordActivity($user, $character, $amount);
        $this->clearCharacterKills($character);

        Flux::toast(
            text: __('Player kills for ":name" have been cleared successfully.', ['name' => $character->Name]),
            heading: __('Success'),
            variant: 'success',
        );

        return true;
    }

    private function validate(User $user, Character $character, int $amount): bool
    {
        if ($user->isOnline()) {
            return false;
        }

        if ($character->PkCount < 1) {
            Flux::toast(
                text: __('Your character ":name" has no player kills to clear.', ['name' => $character->Name]),
                heading: __('No Kills to Clear'),
                variant: 'warning',
            );

            return false;
        }

        $resource = ResourceType::from($this->getResourceType());

        if ($resource === ResourceType::ZEN) {
            if ($character->Money < $amount) {
                Flux::toast(
                    text: __('Insufficient zen in your character. You need :amount but only have :current', [
                        'amount' => $this->format($amount),
                        'current' => $this->format($character->Money),
                    ]),
                    heading: __('Insufficient Funds'),
                    variant: 'warning',
                );

                return false;
            }
        }

        return true;
    }

    private function recordActivity(User $user, Character $character, int $amount): void
    {
        activity('clear_kills')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::DECREMENT->value,
                'character' => $character->Name,
                'amount' => $this->format($amount),
                'resource' => $this->getResourceType(),
                'kills' => $character->PkCount,
                ...IdentityProperties::capture(),
            ])
            ->log('Cleared :properties.kills player kills on :properties.character for :properties.resource.');
    }

    private function decrementCharacterZen(int $amount, Character $character): void
    {
        $character->Money -= $amount;
        $character->save();
    }

    private function clearCharacterKills(Character $character): void
    {
        $character->PkCount = 0;
        $character->PkTime = 0;
        $character->PkLevel = PkLevel::Commoner;
        $character->save();
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }
}
