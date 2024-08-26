<?php

namespace App\Filament\Resources;

use App\Enums\ScheduledEventType;
use App\Filament\Resources\ScheduledEventResource\Pages;
use App\Models\Content\ScheduledEvent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduledEventResource extends Resource
{
    protected static ?string $model = ScheduledEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Event Name')
                                    ->required(),
                                Select::make('type')
                                    ->options(ScheduledEventType::class)
                                    ->enum(ScheduledEventType::class)
                                    ->required(),
                                Select::make('recurrence_type')
                                    ->options([
                                        'daily' => 'Daily',
                                        'weekly' => 'Weekly',
                                        'interval' => 'Interval',
                                    ])
                                    ->default('daily')
                                    ->helperText('Event occurs every day at the specified time(s).')
                                    ->afterStateUpdated(function (Select $component, $state) {
                                        $helperText = match ($state) {
                                            'daily' => 'Event occurs every day at the specified time(s).',
                                            'weekly' => 'Event occurs on specific days of the week at the specified time(s).',
                                            'interval' => 'Event occurs at regular intervals (e.g., every 2 hours).',
                                        };
                                        $component->helperText($helperText);
                                    })
                                    ->required()
                                    ->reactive(),
                                TextInput::make('interval_minutes')
                                    ->label('Event Interval (minutes)')
                                    ->type('number')
                                    ->helperText('Enter the number of minutes between each occurrence of the event.')
                                    ->visible(fn (callable $get) => $get('recurrence_type') === 'interval')
                                    ->required(fn (callable $get) => $get('recurrence_type') === 'interval')
                                    ->minValue(fn (callable $get) => $get('recurrence_type') === 'interval' ? 1 : null),
                                Toggle::make('is_active')
                                    ->label('Event Status')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-s-x-mark')
                                    ->default(true)
                                    ->required()
                                    ->inline(false)
                                    ->helperText('Toggle to activate or deactivate the event in the schedule.'),
                            ]),
                        Group::make()
                            ->schema([
                                Repeater::make('schedule')
                                    ->schema([
                                        Select::make('day')
                                            ->options([
                                                'monday' => 'Monday',
                                                'tuesday' => 'Tuesday',
                                                'wednesday' => 'Wednesday',
                                                'thursday' => 'Thursday',
                                                'friday' => 'Friday',
                                                'saturday' => 'Saturday',
                                                'sunday' => 'Sunday',
                                            ])
                                            ->required()
                                            ->visible(function (callable $get) {
                                                return $get('../../recurrence_type') === 'weekly';
                                            }),
                                        TimePicker::make('time')
                                            ->seconds(false)
                                            ->timezone('Europe/Sofia')
                                            ->required(),
                                    ])
                                    ->minItems(1)
                                    ->maxItems(function (callable $get) {
                                        return $get('recurrence_type') === 'interval' ? 1 : PHP_INT_MAX;
                                    })
                                    ->label(function (callable $get) {
                                        return match ($get('recurrence_type')) {
                                            'weekly' => 'Weekly Schedule',
                                            'interval' => 'Interval Start Time',
                                            default => 'Daily Schedule',
                                        };
                                    })
                                    ->helperText(function (callable $get) {
                                        return match ($get('recurrence_type')) {
                                            'weekly' => 'Set the time for each day the event occurs',
                                            'interval' => 'Set the start time for the recurring interval',
                                            default => 'Set the time(s) the event occurs each day',
                                        };
                                    }),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('recurrence_type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->visible(function ($record) {
                        return $record->is_active === false;
                    })
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->activate();
                    })
                    ->after(function () {
                        Notification::make()->success()->title('Success!')
                            ->body('Activated successfully.')
                            ->duration(2000)
                            ->send();
                    }),
                Tables\Actions\Action::make('deactivate')
                    ->visible(function ($record) {
                        return $record->is_active === true;
                    })
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->deactivate();
                    })
                    ->after(function () {
                        Notification::make()->success()->title('Success!')
                            ->body('Deactivated successfully.')
                            ->duration(2000)
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduledEvents::route('/'),
            'create' => Pages\CreateScheduledEvent::route('/create'),
            'edit' => Pages\EditScheduledEvent::route('/{record}/edit'),
        ];
    }
}
