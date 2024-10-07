<?php

namespace App\Filament\Resources;

use App\Enums\Utility\OperationType;
use App\Filament\Resources\TaxResource\Pages;
use App\Models\Utility\Tax;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('operation')
                    ->required()
                    ->columnSpanFull()
                    ->options(OperationType::class),
                Forms\Components\TextInput::make('rate')
                    ->required()
                    ->columnSpanFull()
                    ->numeric()
                    ->step(0.01)
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('operation'),
                Tables\Columns\TextColumn::make('rate')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTaxes::route('/'),
        ];
    }
}
