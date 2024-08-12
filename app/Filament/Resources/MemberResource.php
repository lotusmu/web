<?php

namespace App\Filament\Resources;

use App\Enums\AccountLevel;
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationGroup = 'Account & Characters';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Member::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->columns([
                Tables\Columns\TextColumn::make('memb___id')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mail_addr')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AccountLevel')
                    ->label('Account Level')
                    ->icon(function ($state, $record) {
                        return $record->AccountLevel === AccountLevel::Regular ? '' : 'heroicon-o-fire';
                    })
                    ->iconColor(function ($state, $record) {
                        return match ($record->AccountLevel) {
                            AccountLevel::Bronze => Color::Orange,
                            AccountLevel::Silver => Color::Gray,
                            AccountLevel::Gold => Color::Yellow,
                            default => '',
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('AccountExpireDate')
                    ->label('VIP Expire Date')
                    ->dateTime()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        return $record->AccountLevel === AccountLevel::Regular ? '-' : $state;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('AccountLevel')
                    ->options(AccountLevel::class)
                    ->label('Account Level')
                    ->placeholder('All Levels')
                    ->multiple(),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
