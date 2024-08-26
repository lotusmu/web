<?php

namespace App\Filament\Resources;

use App\Filament\Infolists\Components\Entry\CharacterClassEntry;
use App\Filament\Resources\CharacterResource\Pages;
use App\Filament\Tables\Columns\CharacterClassColumn;
use App\Models\Game\Character;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationGroup = 'Account & Characters';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['Name', 'Class'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "{$record->Name} ({$record->Class->getLabel()})";
    }

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
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After)
                    ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->AccountID]))
                    ->searchable(),
                Tables\Columns\TextColumn::make('Name')
                    ->label('Character')
                    ->searchable(),
                CharacterClassColumn::make('Class')
                    ->label('Class')
                    ->imageSize(32),
                Tables\Columns\TextColumn::make('cLevel')
                    ->label('Level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ResetCount')
                    ->label('Resets')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('ResetCount', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Character Information')
                    ->description('General information about the character.')
                    ->aside()
                    ->columns(6)
                    ->schema([
                        CharacterClassEntry::make('Class')
                            ->label('Character Class'),
                        TextEntry::make('AccountID')
                            ->label('Username')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->iconPosition(IconPosition::After)
                            ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->AccountID])),
                        TextEntry::make('Name')
                            ->label('Character'),
                        TextEntry::make('Class')
                            ->label('Class'),
                        TextEntry::make('ResetCount')
                            ->label('Resets'),
                        TextEntry::make('cLevel')
                            ->label('Level'),
                    ]),
                Section::make('Other Information')
                    ->description('Detailed information about the character.')
                    ->aside()
                    ->schema([
                        Fieldset::make('Character Stats')
                            ->columns(5)
                            ->schema([
                                TextEntry::make('Strength'),
                                TextEntry::make('Dexterity'),
                                TextEntry::make('Vitality'),
                                TextEntry::make('Energy'),
                                TextEntry::make('Leadership'),
                            ]),
                        Fieldset::make('Location')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('MapNumber')
                                    ->label('Map Name'),
                                TextEntry::make('MapPosX')
                                    ->label('X Position'),
                                TextEntry::make('MapPosY')
                                    ->label('Y Position'),
                            ]),
                        Fieldset::make('Player Status')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('PkLevel')
                                    ->label('PK Level'),
                                TextEntry::make('PkCount')
                                    ->label('Kills Count'),
                                TextEntry::make('PkTime')
                                    ->label('PK Time'),
                            ]),
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
            'edit' => Pages\EditCharacter::route('/{record}/edit'),
            'view' => Pages\ViewCharacter::route('/{record}'),
        ];
    }
}
