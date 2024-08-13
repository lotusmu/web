<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use App\Filament\Tables\Columns\CharacterClassColumn;
use App\Models\Character;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class CharactersRelationManager extends RelationManager
{
    protected static string $relationship = 'characters';

    public function form(Form $form): Form
    {
        return $form
            ->schema(Character::getForm());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Name')
            ->columns([
                Tables\Columns\TextColumn::make('Name')
                    ->label('Character'),
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
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn (Character $record): string => route('filament.admin.resources.characters.view', $record))
                    ->icon('heroicon-s-eye'),
            ])
            ->bulkActions([
                //
            ]);
    }
}
