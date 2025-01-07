<?php

namespace App\Filament\Resources;

use App\Enums\Game\AccountLevel;
use App\Filament\Resources\VipPackageResource\Pages;
use App\Models\Utility\VipPackage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VipPackageResource extends Resource
{
    protected static ?string $model = VipPackage::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $modelLabel = 'Package';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('VIP Package Configuration')
                    ->description('Configure VIP package details including level, duration, pricing, and promotional status.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Select::make('level')
                            ->label('VIP Level')
                            ->columnSpanFull()
                            ->options(collect(AccountLevel::cases())
                                ->except(AccountLevel::Regular->value)
                                ->pluck('name', 'value'))
                            ->required()
                            ->enum(AccountLevel::class)
                            ->helperText('Select the VIP tier for this package.'),

                        TextInput::make('duration')
                            ->label('Package Duration')
                            ->required()
                            ->numeric()
                            ->suffix('Days')
                            ->helperText('Number of days this VIP package will remain active.'),

                        TextInput::make('cost')
                            ->label('Package Cost')
                            ->required()
                            ->numeric()
                            ->suffix('Tokens')
                            ->helperText('Cost of the package in tokens.'),

                        Toggle::make('is_best_value')
                            ->label('Best Value Package')
                            ->columnSpanFull()
                            ->required()
                            ->inline(false)
                            ->helperText('Toggle to highlight this package as the best value option for players.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('level')
                    ->badge()
                    ->label('Account Level'),
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn (int $state): string => "{$state} days")
                    ->label('Duration'),
                Tables\Columns\TextColumn::make('cost')
                    ->formatStateUsing(fn (int $state): string => "{$state} tokens")
                    ->label('Cost'),
                Tables\Columns\IconColumn::make('is_best_value')
                    ->boolean()
                    ->label('Best Value'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVipPackages::route('/'),
            'create' => Pages\CreateVipPackage::route('/create'),
            'edit' => Pages\EditVipPackage::route('/{record}/edit'),
        ];
    }
}
