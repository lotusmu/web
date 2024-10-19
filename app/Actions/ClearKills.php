<?php

namespace App\Actions;

use App\Enums\Game\PkLevel;
use App\Enums\Utility\ActivityType;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class ClearKills
{
    public function handle(User $user, Character $character, int $amount): bool
    {
        if (! $this->validate($user, $character, $amount)) {
            return false;
        }

        $this->decrementResource($amount, $character);
        $this->clearCharacterKills($character);

        $this->recordActivity($user, $character, $amount);

        Flux::toast(
            variant: 'success',
            heading: 'Success',
            text: __('Player kills for :name have been cleared successfully.', ['name' => $character->Name]),
        );

        return true;
    }

    private function validate(User $user, $character, int $amount): bool
    {
        if ($user->isOnline()) {
            return false;
        }

        if ($character->PkCount < 1) {
            Flux::toast(
                variant: 'warning',
                heading: __('No Kills to Clear'),
                text: __('Your character ":name" has no player kills to clear.', ['name' => $character->Name]),
            );

            return false;
        }

        if ($character->Money <= $amount) {
            Flux::toast(
                variant: 'warning',
                heading: __('Insufficient Funds'),
                text: __('Insufficient zen in your character. You need ').$this->format($amount).' '.__('but only have').' '.$this->format($character->Money),
            );

            return false;
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
                ...IdentityProperties::capture(),
            ])
            ->log('Cleared player kills for :properties.character.');
    }

    private function decrementResource(int $amount, Character $character): void
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
