<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\AccountLevel;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MemberRelationManager extends RelationManager
{
    protected static string $relationship = 'member';

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Fieldset::make('Account Level')
                        ->schema([
                            Select::make('AccountLevel')
                                ->label('VIP')
                                ->options(AccountLevel::class)
                                ->enum(AccountLevel::class),
                            DateTimePicker::make('AccountExpireDate')
                                ->label('Expire Date')
                                ->required(),
                        ]),
                ]
            );
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('member')
            ->columns([
                Tables\Columns\TextColumn::make('memb___id')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mail_addr')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AccountLevel')
                    ->label('Account Level')
                    ->sortable(),
                Tables\Columns\TextColumn::make('AccountExpireDate')
                    ->label('Account Expire Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->paginated(false)
            ->searchable(false)
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
