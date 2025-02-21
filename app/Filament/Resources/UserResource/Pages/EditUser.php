<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Actions\User\BanUser;
use App\Actions\User\UnbanUser;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('ban')
                ->visible(fn () => ! $this->record->is_banned)
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->form([
                    TextInput::make('ban_reason')
                        ->label('Reason for ban')
                        ->placeholder('Optional reason'),
                ])
                ->requiresConfirmation()
                ->modalHeading('Ban User')
                ->modalDescription('Are you sure you want to ban this user? They will be unable to log in.')
                ->action(function (array $data) {
                    app(BanUser::class)->handle($this->record, $data['ban_reason'] ?? null);

                    Notification::make()
                        ->title('User banned successfully')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('unban')
                ->visible(fn () => $this->record->is_banned)
                ->icon('heroicon-o-lock-open')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Unban User')
                ->modalDescription('Are you sure you want to unban this user?')
                ->action(function () {
                    app(UnbanUser::class)->handle($this->record);

                    Notification::make()
                        ->title('User unbanned successfully')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['change_password']);

        return $data;
    }
}
