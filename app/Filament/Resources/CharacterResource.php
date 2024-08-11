<?php

namespace App\Filament\Resources;

use App\Enums\CharacterClass;
use App\Filament\Resources\CharacterResource\Pages;
use App\Filament\Resources\CharacterResource\RelationManagers;
use App\Models\Character;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('AccountID')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('cLevel')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('LevelUpPoint')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('Class')
                    ->label('Class')
                    ->options(CharacterClass::class)
                    ->enum(CharacterClass::class)
                    ->required(),
                Forms\Components\TextInput::make('Experience')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('Strength')
                    ->numeric(),
                Forms\Components\TextInput::make('Dexterity')
                    ->numeric(),
                Forms\Components\TextInput::make('Vitality')
                    ->numeric(),
                Forms\Components\TextInput::make('Energy')
                    ->numeric(),
                Forms\Components\TextInput::make('Leadership')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('Inventory'),
                Forms\Components\TextInput::make('MagicList'),
                Forms\Components\TextInput::make('Money')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('Life')
                    ->numeric(),
                Forms\Components\TextInput::make('MaxLife')
                    ->numeric(),
                Forms\Components\TextInput::make('Mana')
                    ->numeric(),
                Forms\Components\TextInput::make('MaxMana')
                    ->numeric(),
                Forms\Components\TextInput::make('BP')
                    ->numeric(),
                Forms\Components\TextInput::make('MaxBP')
                    ->numeric(),
                Forms\Components\TextInput::make('Shield')
                    ->numeric(),
                Forms\Components\TextInput::make('MaxShield')
                    ->numeric(),
                Forms\Components\TextInput::make('MapNumber')
                    ->numeric(),
                Forms\Components\TextInput::make('MapPosX')
                    ->numeric(),
                Forms\Components\TextInput::make('MapPosY')
                    ->numeric(),
                Forms\Components\TextInput::make('MapDir')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('PkCount')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('PkLevel')
                    ->numeric()
                    ->default(3),
                Forms\Components\TextInput::make('PkTime')
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('MDate'),
                Forms\Components\DateTimePicker::make('LDate'),
                Forms\Components\TextInput::make('CtlCode')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('DbVersion')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('Quest'),
                Forms\Components\TextInput::make('ChatLimitTime')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('FruitPoint')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('EffectList'),
                Forms\Components\TextInput::make('FruitAddPoint')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('FruitSubPoint')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('ResetCount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('MasterResetCount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('ExtInventory')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('Kills')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('Deads')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('bloc_expire'),
                Forms\Components\TextInput::make('ItemStart')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('ResetDay')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('ResetWek')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('ResetMon')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('CustomFlag')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('CustomSkin')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('LevelUpType')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('last_reset_time')
                    ->numeric(),
                Forms\Components\TextInput::make('last_greset_time')
                    ->numeric(),
                Forms\Components\TextInput::make('resets')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('grand_resets')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dmn_pk_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('dmn_last_server_pk_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('monster_kill_points')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
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
