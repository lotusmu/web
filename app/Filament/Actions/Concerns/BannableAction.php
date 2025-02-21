<?php

namespace App\Filament\Actions\Concerns;

use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

trait BannableAction
{
    public function configureAction(): static
    {
        return $this
            ->label(fn (Model $record): string => $record->isBanned() ? 'Unban' : 'Ban')
            ->icon(fn (Model $record): string => $record->isBanned() ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
            ->color(fn (Model $record): string => $record->isBanned() ? 'success' : 'danger')
            ->form(function (Model $record) {
                if ($record->isBanned()) {
                    return [];
                }

                return [
                    Toggle::make('permanent_ban')
                        ->label('Ban Permanently')
                        ->inline(false)
                        ->onColor('success')
                        ->offColor('danger')
                        ->onIcon('heroicon-s-check')
                        ->offIcon('heroicon-s-x-mark')
                        ->default(true)
                        ->reactive(),
                    DateTimePicker::make('ban_until')
                        ->label('Ban Until')
                        ->required()
                        ->native(false)
                        ->minDate(now()->addDay())
                        ->hidden(fn (Get $get) => $get('permanent_ban')),
                ];
            })
            ->action(function (Model $record, array $data) {
                if ($record->isBanned()) {
                    $record->unban();

                    $modelType = class_basename($record);
                    $identifierField = $record->getKeyName();
                    $identifier = $record->{$identifierField};

                    Notification::make()
                        ->title("{$modelType} {$identifier} has been unbanned")
                        ->success()
                        ->send();

                    return;
                }

                if ($data['permanent_ban'] ?? false) {
                    $record->banPermanently();
                    $modelType = class_basename($record);
                    $identifierField = $record->getKeyName();
                    $identifier = $record->{$identifierField};
                    $message = "{$modelType} {$identifier} has been banned permanently";
                } else {
                    $banUntil = Carbon::parse($data['ban_until']);
                    $record->banUntil($banUntil);
                    $modelType = class_basename($record);
                    $identifierField = $record->getKeyName();
                    $identifier = $record->{$identifierField};
                    $message = "{$modelType} {$identifier} has been banned until ".$banUntil->format('Y-m-d H:i');
                }

                Notification::make()
                    ->title($message)
                    ->success()
                    ->send();
            })
            ->requiresConfirmation();
    }
}
