<?php

namespace App\Filament\Resources;

use App\Enums\Utility\ResourceType;
use App\Filament\Resources\WeeklyRankingRewardResource\Pages;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use App\Models\Game\Ranking\WeeklyRankingReward;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WeeklyRankingRewardResource extends Resource
{
    protected static ?string $model = WeeklyRankingReward::class;

    protected static ?string $navigationGroup = 'Rankings';

    protected static ?string $modelLabel = 'Ranking Rewards';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Reward Configuration')
                ->description('Configure position range and rewards.')
                ->aside()
                ->columns(2)
                ->schema([
                    Select::make('weekly_ranking_configuration_id')
                        ->label('Server Configuration')
                        ->columnSpanFull()
                        ->options(function () {
                            return WeeklyRankingConfiguration::query()
                                ->with('server')
                                ->get()
                                ->mapWithKeys(fn ($config) => [
                                    $config->id => $config->server->name,
                                ]);
                        })
                        ->required()
                        ->helperText('Select which server configuration this reward belongs to'),

                    TextInput::make('position_from')
                        ->label('Position From')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->helperText('Starting position for this reward tier'),

                    TextInput::make('position_to')
                        ->label('Position To')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->helperText('Ending position for this reward tier'),

                    Repeater::make('rewards')
                        ->label('Rewards')
                        ->columnSpanFull()
                        ->schema([
                            Select::make('type')
                                ->label('Resource Type')
                                ->options(ResourceType::class)
                                ->required(),

                            TextInput::make('amount')
                                ->label('Amount')
                                ->required()
                                ->numeric()
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->default(0)
                                ->minValue(0),
                        ])
                        ->addActionLabel('Add Resource')
                        ->minItems(1)
                        ->defaultItems(1)
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('configuration.server.name')
                    ->label('Server')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('position_from')
                    ->label('From')
                    ->sortable(),

                TextColumn::make('position_to')
                    ->label('To')
                    ->sortable(),

                TextColumn::make('rewards')
                    ->label('Rewards')
                    ->formatStateUsing(function ($record) {
                        return collect($record->rewards)->map(function ($reward) {
                            return number_format($reward['amount']).' '.ResourceType::from($reward['type'])->getLabel();
                        })->join('; ');
                    })
                    ->wrap(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeeklyRankingRewards::route('/'),
            'create' => Pages\CreateWeeklyRankingReward::route('/create'),
            'edit' => Pages\EditWeeklyRankingReward::route('/{record}/edit'),
        ];
    }
}
