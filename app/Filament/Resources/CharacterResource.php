<?php

namespace App\Filament\Resources;

use App\Enums\CharacterClass;
use App\Filament\Resources\CharacterResource\Pages;
use App\Models\Character;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationGroup = 'Account & Characters';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Character::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('AccountID')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Name')
                    ->label('Character')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Class')
                    ->formatStateUsing(fn (CharacterClass $state): string => $state->getLabel()),
                Tables\Columns\TextColumn::make('cLevel')
                    ->label('Level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ResetCount')
                    ->label('Resets')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit' => Pages\EditCharacter::route('/{record}/edit'),
        ];
    }
}
