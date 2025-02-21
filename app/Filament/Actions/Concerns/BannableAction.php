<?php

namespace App\Filament\Actions\Concerns;

use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
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
                    Textarea::make('reason')
                        ->label('Ban Reason')
                        ->placeholder('Enter reason for this ban')
                        ->columnSpanFull(),
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

                $reason = $data['reason'] ?? null;

                if ($data['permanent_ban'] ?? false) {
                    $record->banPermanently($reason);
                    $modelType = class_basename($record);
                    $identifierField = $record->getKeyName();
                    $identifier = $record->{$identifierField};
                    $message = "{$modelType} {$identifier} has been banned permanently";
                    if ($reason) {
                        $message .= " for: {$reason}";
                    }
                } else {
                    $banUntil = Carbon::parse($data['ban_until']);
                    $record->banUntil($banUntil, $reason);
                    $modelType = class_basename($record);
                    $identifierField = $record->getKeyName();
                    $identifier = $record->{$identifierField};
                    $message = "{$modelType} {$identifier} has been banned until ".$banUntil->format('Y-m-d H:i');
                    if ($reason) {
                        $message .= " for: {$reason}";
                    }
                }

                Notification::make()
                    ->title($message)
                    ->success()
                    ->send();
            })
            ->requiresConfirmation();
    }
}
