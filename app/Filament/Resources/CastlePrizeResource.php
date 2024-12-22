<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CastlePrizeResource\Pages;
use App\Filament\Resources\CastlePrizeResource\RelationManagers\DistributionsRelationManager;
use App\Models\Utility\CastlePrize;
use App\Models\Utility\GameServer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CastlePrizeResource extends Resource
{
    protected static ?string $model = CastlePrize::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Castle Siege';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('total_prize_pool')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Total Prize Pool (Credits)'),

                        TextInput::make('distribution_weeks')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Distribution Period (Weeks)')
                            ->live(),

                        DateTimePicker::make('period_starts_at')
                            ->required()
                            ->label('First Distribution At'),

                        Select::make('game_server_id')
                            ->label('Game Server')
                            ->options(GameServer::where('is_active', true)
                                ->pluck('name', 'id'))
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Prize Pool Status')
                            ->required()
                            ->columnSpanFull()
                            ->inline(false)
                            ->helperText('Toggle to activate or deactivate the prize pool distribution.'),
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
                Tables\Actions\EditAction::make(),
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
