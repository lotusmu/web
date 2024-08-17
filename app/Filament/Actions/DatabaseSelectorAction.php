<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class DatabaseSelectorAction
{
    public static function make(): Action
    {
        $currentDatabase = 'Database 2'; // Hardcoded current database

        return Action::make('selectDatabase')
            ->label(fn (): string => "Current: {$currentDatabase}")
            ->icon('heroicon-o-server-stack')
            ->form([
                Select::make('database')
                    ->label('Choose Database')
                    ->options([
                        'database1' => 'Database 1',
                        'database2' => 'Database 2',
                        'database3' => 'Database 3',
                    ])
                    ->default('database2') // Set default to Database 2
                    ->required(),
            ])
            ->action(function (array $data) use ($currentDatabase) {
                // Logic to switch the database
                //                Config::set('database.default', $data['database']);

                // You might need to reconnect here, depending on your setup
                // DB::reconnect();

                Notification::make()
                    ->title('Database Changed')
                    ->body("Switched from {$currentDatabase} to {$data['database']}")
                    ->success()
                    ->send();
            });
    }
}
