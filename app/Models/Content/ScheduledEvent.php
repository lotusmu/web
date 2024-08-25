<?php

namespace App\Models\Content;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class ScheduledEvent extends Model
{
    protected $fillable = [
        'name',
        'recurrence_type',
        'schedule',
        'interval_minutes',
        'is_active',
    ];

    protected $casts = [
        'schedule' => 'array',
        'interval_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getForm()
    {
        return [
            Section::make('Event Details')
                ->columns(2)
                ->schema([
                    Group::make()
                        ->schema([
                            TextInput::make('name')
                                ->label('Event Name')
                                ->required(),
                            Select::make('recurrence_type')
                                ->default('daily')
                                ->options([
                                    'daily' => 'Daily',
                                    'weekly' => 'Weekly',
                                    'interval' => 'Interval',
                                ])
                                ->required()
                                ->reactive(),
                            TextInput::make('interval_minutes')
                                ->label('Event Interval (minutes)')
                                ->type('number')
                                ->visible(fn (callable $get) => $get('recurrence_type') === 'interval')
                                ->required(fn (callable $get) => $get('recurrence_type') === 'interval')
                                ->minValue(fn (callable $get) => $get('recurrence_type') === 'interval' ? 1 : null),
                            Toggle::make('is_active')
                                ->default(true)
                                ->required(),
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
                                        ->visible(fn (callable $get) => $get('../../recurrence_type') === 'weekly'),
                                    TimePicker::make('time')
                                        ->seconds(false)
                                        ->timezone('Europe/Sofia')
                                        ->required(),
                                ])
                                ->minItems(1)
                                ->maxItems(fn (callable $get) => $get('recurrence_type') === 'interval' ? 1 : null)
                                ->label(fn (callable $get) => $get('recurrence_type') === 'weekly' ? 'Weekly Schedule' :
                                    ($get('recurrence_type') === 'interval' ? 'First Occurrence' : 'Daily Schedule')
                                )
                                ->helperText(fn (callable $get) => $get('recurrence_type') === 'weekly' ? 'Specify times for each day of the week' :
                                    ($get('recurrence_type') === 'interval' ? 'Specify the time for the first occurrence' : 'Specify times for each day')
                                ),

                        ]),

                ]),
        ];
    }

    public function activate(): void
    {
        $this->is_active = true;

        $this->save();
    }

    public function deactivate(): void
    {
        $this->is_active = false;

        $this->save();
    }
}
