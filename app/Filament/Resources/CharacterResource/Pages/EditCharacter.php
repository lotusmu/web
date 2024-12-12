<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Enums\Game\GuildMemberStatus;
use App\Filament\Resources\CharacterResource;
use App\Models\Game\Character;
use App\Models\Game\GuildMember;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCharacter extends EditRecord
{
    protected static string $resource = CharacterResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return array_intersect_key($data, array_flip(Character::getFillableFields()));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Character::findUserByCharacterName($this->record->Name);

        if ($user?->isOnline()) {
            Notification::make()
                ->warning()
                ->title('Character is online')
                ->body('Please logout from the game to make changes.')
                ->send();

            $this->halt();
        }

        unset($data['guild_name']);
        unset($data['guild_status']);

        return $data;
    }

    protected function afterSave(): void
    {
        if (filled($this->data['guild_name'])) {
            if ($this->record->guildMember?->G_Status === GuildMemberStatus::GuildMaster) {
                Notification::make()
                    ->warning()
                    ->title('Cannot change guild')
                    ->body('Guild Master cannot be moved to another guild.')
                    ->send();

                return;
            }

            $newStatus = $this->data['guild_name'] !== $this->record->guildMember?->G_Name
                ? GuildMemberStatus::GuildMember
                : $this->record->guildMember?->G_Status ?? GuildMemberStatus::GuildMember;

            GuildMember::updateOrCreate(
                ['Name' => $this->record->Name],
                [
                    'G_Name' => $this->data['guild_name'],
                    'G_Status' => $newStatus,
                ]
            );
        }
    }
}
