<?php

namespace App\Livewire\Pages\App\Dashboard;

use App\Actions\Character\ClearKills;
use App\Actions\Character\SkipQuest;
use App\Enums\Game\Map;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Livewire\BaseComponent;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Models\Utility\Setting;
use Livewire\Attributes\Computed;

class CharacterRow extends BaseComponent
{
    use Taxable;

    public Character $character;

    public User $user;

    public function mount(Character $character): void
    {
        $this->character = $character;
        $this->user = auth()->user();
        $this->operationType = OperationType::PK_CLEAR;
        $this->initializeTaxable($this->getCurrentServerId());
    }

    public function pollQuestStatus(): void
    {
        $this->character->refresh();
        $this->character->load('quest');
    }

    #[Computed]
    public function shouldPoll(): bool
    {
        return ! $this->canSkipQuest;
    }

    #[Computed]
    public function pkClearCost(): int
    {
        return $this->calculateRate($this->character->PkCount);
    }

    #[Computed]
    public function pkClearResource(): string
    {
        return ResourceType::from($this->getResourceType())->getLabel();
    }

    #[Computed]
    public function canClearPk(): bool
    {
        $settings = Setting::getGroup(OperationType::PK_CLEAR->value);
        $hasSettings = isset($settings['pk_clear']['cost']) && isset($settings['pk_clear']['resource']);

        return $hasSettings && $this->character->PkCount > 0;
    }

    #[Computed]
    public function questSkipCost(): int
    {
        return Setting::getValue(OperationType::QUEST_SKIP->value, 'quest_skip.cost', 0);
    }

    #[Computed]
    public function questSkipResource(): string
    {
        $resourceType = Setting::getValue(OperationType::QUEST_SKIP->value, 'quest_skip.resource', 'tokens');

        return ResourceType::from($resourceType)->getLabel();
    }

    #[Computed]
    public function canSkipQuest(): bool
    {
        return SkipQuest::isAvailable() && $this->character->hasActiveQuest();
    }

    public function unstuck(): void
    {
        if ($this->user->isOnline()) {
            return;
        }

        $this->character->MapNumber = Map::Lorencia;
        $this->character->MapPosX = 125;
        $this->character->MapPosY = 125;

        $this->character->save();

        Flux::toast(
            text: __('Character moved successfully to Lorencia'),
            heading: __('Success'),
            variant: 'success'
        );
    }

    public function clearKills(ClearKills $action): void
    {
        $action->handle($this->user, $this->character);

        $this->modal('pk_clear_'.$this->character->Name)->close();
    }

    public function skipQuest(SkipQuest $action): void
    {
        $action->handle($this->user, $this->character);

        $this->modal('skip_quest_'.$this->character->Name)->close();
    }

    protected function getViewName(): string
    {
        return 'pages.app.dashboard.character-row';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
