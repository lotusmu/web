<?php

namespace App\Actions\Character;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Models\Utility\GameServer;
use App\Models\Utility\Setting;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class SkipQuest
{
    use Taxable;

    public function __construct()
    {
        $this->operationType = OperationType::QUEST_SKIP;
        $this->initializeTaxable();
    }

    public static function isAvailable(): bool
    {
        $settings = Setting::getGroup(OperationType::QUEST_SKIP->value);

        return isset($settings['quest_skip']['cost']) && isset($settings['quest_skip']['resource']);
    }

    public function handle(User $user, Character $character): bool
    {
        $amount = $this->getCost();

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
        $this->skipCharacterQuest($character);

        Flux::toast(
            text: __('Quest for ":name" has been skipped successfully.', ['name' => $character->Name]),
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

        if (! $this->hasActiveQuest($character)) {
            Flux::toast(
                text: __('Your character ":name" has no active quest to skip.', ['name' => $character->Name]),
                heading: __('No Active Quest'),
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

    private function hasActiveQuest(Character $character): bool
    {
        $quest = $character->quest;

        if (! $quest || $quest->MonsterCount === 99999) {
            return false;
        }

        return $quest->MonsterCount > 0 ||
            $quest->MonsterCount2 > 0 ||
            $quest->MonsterCount3 > 0 ||
            $quest->MonsterCount4 > 0 ||
            $quest->MonsterCount5 > 0;
    }

    private function recordActivity(User $user, Character $character, int $amount): void
    {
        $serverName = GameServer::where('connection_name', session('game_db_connection', 'gamedb_main'))
            ->first()
            ->getServerName();

        activity('skip_quest')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::DECREMENT->value,
                'character' => $character->Name,
                'amount' => $this->format($amount),
                'resource' => $this->getResourceType(),
                'quest_number' => ($character->quest?->Quest ?? 0) + 1,
                'connection' => $serverName,
                ...IdentityProperties::capture(),
            ])
            ->log('Skipped quest No.:properties.quest_number on :properties.character for :properties.amount :properties.resource (:properties.connection).');
    }

    private function decrementCharacterZen(int $amount, Character $character): void
    {
        $character->Money -= $amount;
        $character->save();
    }

    private function skipCharacterQuest(Character $character): void
    {
        $quest = $character->quest;

        if ($quest) {
            $quest->MonsterCount = 0;
            $quest->MonsterCount2 = 0;
            $quest->MonsterCount3 = 0;
            $quest->MonsterCount4 = 0;
            $quest->MonsterCount5 = 0;
            $quest->save();
        }
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }
}
