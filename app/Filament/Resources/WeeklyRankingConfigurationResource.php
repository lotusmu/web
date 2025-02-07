<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WeeklyRankingConfigurationResource\Pages;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use App\Models\Utility\GameServer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WeeklyRankingConfigurationResource extends Resource
{
    protected static ?string $model = WeeklyRankingConfiguration::class;

    protected static ?string $navigationGroup = 'Rankings';

    protected static ?string $modelLabel = 'Weekly Ranking Configuration';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Weekly Reset Configuration')
                ->description('Configure when the weekly rankings should start and reset.')
                ->aside()
                ->columns(2)
                ->schema([
                    Select::make('game_server_id')
                        ->label('Server')
                        ->columnSpanFull()
                        ->options(GameServer::query()
                            ->where('is_active', true)
                            ->pluck('name', 'id'))
                        ->required()
                        ->helperText('Select which server this configuration applies to'),

                    DatePicker::make('first_cycle_start')
                        ->label('First Cycle Start')
                        ->columnSpanFull()
                        ->required()
                        ->native(false)
                        ->firstDayOfWeek(1)
                        ->helperText('When should the weekly ranking system start processing?'),

                    Select::make('reset_day_of_week')
                        ->label('Reset Day')
                        ->options([
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                            0 => 'Sunday',
                        ])
                        ->native(false)
                        ->required()
                        ->helperText('On which day should the rankings reset?'),

                    TimePicker::make('reset_time')
                        ->label('Reset Time')
                        ->required()
                        ->seconds(false)
                        ->native(false)
                        ->format('H:i')
                        ->displayFormat('H:i')
                        ->helperText('At what time should the rankings reset?'),

                    Toggle::make('is_enabled')
                        ->label('Enable Weekly Rankings')
                        ->inline(false)
                        ->helperText('Toggle the weekly ranking system on/off.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('server.name')
                    ->label('Server')
                    ->sortable(),

                TextColumn::make('first_cycle_start')
                    ->label('First Cycle Start')
                    ->date()
                    ->sortable(),

                TextColumn::make('reset_day_of_week')
                    ->label('Reset Day')
                    ->formatStateUsing(fn (int $state) => [
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        0 => 'Sunday',
                    ][$state])
                    ->sortable(),

                TextColumn::make('reset_time')
                    ->label('Reset Time')
                    ->sortable(),

                IconColumn::make('is_enabled')
                    ->label('Enabled'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeeklyRankingConfigurations::route('/'),
            'create' => Pages\CreateWeeklyRankingConfiguration::route('/create'),
            'edit' => Pages\EditWeeklyRankingConfiguration::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
