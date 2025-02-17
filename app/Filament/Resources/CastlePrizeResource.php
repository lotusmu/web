<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CastlePrizeResource\Pages;
use App\Filament\Resources\CastlePrizeResource\RelationManagers\DistributionsRelationManager;
use App\Models\Utility\CastlePrize;
use App\Models\Utility\GameServer;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CastlePrizeResource extends Resource
{
    protected static ?string $model = CastlePrize::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Castle Prizes';

    protected static ?string $modelLabel = 'Prize';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Castle Prize Pool Configuration')
                    ->description('Configure game server and activation status for the castle siege prize pool.')
                    ->aside()
                    ->schema([
                        Select::make('game_server_id')
                            ->label('Game Server')
                            ->options(GameServer::where('is_active', true)
                                ->pluck('name', 'id'))
                            ->required()
                            ->helperText('Select the game server for this prize pool.'),

                        Toggle::make('is_active')
                            ->label('Prize Pool Status')
                            ->required()
                            ->columnSpanFull()
                            ->inline(false)
                            ->default(true)
                            ->helperText('Toggle to activate or deactivate the prize pool distribution.'),
                    ]),
                Section::make('Distribution Settings')
                    ->description('Set up the total prize pool amount, distribution period, and schedule for weekly credits distribution to castle siege winning guild members.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('total_prize_pool')
                            ->label('Total Prize Pool')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Total amount of credits to be distributed over the period.')
                            ->suffix('Credits')
                            ->live(),

                        TextInput::make('distribution_weeks')
                            ->label('Distribution Period')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('Weeks')
                            ->helperText('Number of weeks over which the prize pool will be distributed.')
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                if ($state && $get('total_prize_pool')) {
                                    $set('weekly_amount', floor($get('total_prize_pool') / $state));
                                }
                            }),

                        TextInput::make('weekly_amount')
                            ->label('Weekly Distribution')
                            ->disabled()
                            ->suffix('Credits')
                            ->helperText('Amount of credits to be distributed each week.')
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->formatStateUsing(function ($state, $record) {
                                if (! $record?->total_prize_pool || ! $record?->distribution_weeks) {
                                    return 0;
                                }

                                return floor($record->total_prize_pool / $record->distribution_weeks);
                            }),

                        DatePicker::make('period_starts_at')
                            ->label('Distribution Cycle Start Date')
                            ->required()
                            ->native(false)
                            ->helperText('Date of the first distribution. Subsequent distributions will occur weekly at the same time.')
                            ->columnSpan(fn ($record) => $record ? 1 : 2)
                            ->beforeStateDehydrated(fn ($state) => Carbon::parse($state)->startOfWeek()),

                        DatePicker::make('period_ends_at')
                            ->label('Distribution Cycle End Date')
                            ->required()
                            ->native(false)
                            ->helperText('Date marking the end of the distributions. Ensure this is after the start date.')
                            ->visible(fn ($livewire) => $livewire instanceof EditRecord)
                            ->disabled(fn ($record) => Carbon::now()->greaterThan($record->period_ends_at))
                            ->beforeStateDehydrated(fn ($state) => Carbon::parse($state)->endOfWeek())
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $startDate = Carbon::parse($get('period_starts_at'));
                                $endDate = Carbon::parse($state);

                                if ($endDate->lessThan($startDate)) {
                                    $set('period_ends_at', $startDate->copy()->addWeek());
                                }
                            }),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gameServer.name')
                    ->label('Server')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_prize_pool')
                    ->label('Total Prize Pool')
                    ->numeric(),
                Tables\Columns\TextColumn::make('remaining_prize_pool')
                    ->label('Remaining Prize Pool')
                    ->numeric(),
                Tables\Columns\TextColumn::make('distribution_weeks')
                    ->label('Distribution Period'),
                Tables\Columns\TextColumn::make('weekly_amount')
                    ->numeric()
                    ->label('Weekly Amount'),
                Tables\Columns\TextColumn::make('period_starts_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('period_ends_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DistributionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCastlePrizes::route('/'),
            'create' => Pages\CreateCastlePrize::route('/create'),
            'edit' => Pages\EditCastlePrize::route('/{record}/edit'),
        ];
    }
}
