<?php

namespace App\Filament\Resources;

use App\Enums\Game\AccountLevel;
use App\Filament\Resources\VipPackageResource\Pages;
use App\Models\Utility\VipPackage;
use Filament\Forms\Components\Group;
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

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('VIP Package')
                    ->schema([
                        Select::make('level')
                            ->options(collect(AccountLevel::cases())
                                ->except(AccountLevel::Regular->value)
                                ->pluck('name', 'value'))
                            ->required()
                            ->enum(AccountLevel::class)
                            ->label('Account Level'),
                        Group::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('duration')
                                    ->required()
                                    ->numeric()
                                    ->helperText('Value in days')
                                    ->label('Duration'),
                                TextInput::make('cost')
                                    ->required()
                                    ->numeric()
                                    ->helperText('Value in tokens')
                                    ->label('Cost'),
                            ]),
                        Toggle::make('is_best_value')
                            ->required()
                            ->inline(false)
                            ->helperText('Toggle to set the package as Best Valued one.')
                            ->label('Best Value'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('level')
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
