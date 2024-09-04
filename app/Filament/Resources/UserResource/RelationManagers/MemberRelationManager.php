<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\Game\AccountLevel;
use App\Models\User\Member;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconPosition;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class MemberRelationManager extends RelationManager
{
    protected static string $relationship = 'member';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Fieldset::make('Resources')
                    ->schema([
                        TextInput::make('tokens')
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                        Group::make()
                            ->relationship('wallet')
                            ->schema([
                                TextInput::make('WCoinC')
                                    ->label('Credits')
                                    ->numeric()
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->default(0)
                                    ->minValue(0)
                                    ->required(),
                            ]),
                        Group::make()
                            ->relationship('wallet')
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('zen')
                                    ->numeric()
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->default(0)
                                    ->minValue(0)
                                    ->required(),
                            ]),

                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('member')
            ->columns([
                Tables\Columns\TextColumn::make('memb___id')
                    ->label('Username')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After)
                    ->url(function (Member $record) {
                        return route('filament.admin.resources.members.edit', ['record' => $record]);
                    }),
                Tables\Columns\TextColumn::make('mail_addr')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('AccountLevel')
                    ->label('Account Level')
                    ->badge(),
                Tables\Columns\TextColumn::make('AccountExpireDate')
                    ->label('VIP Expire Date')
                    ->dateTime()
                    ->formatStateUsing(function ($state, $record) {
                        return $record->AccountLevel === AccountLevel::Regular ? '-' : $state;
                    }),
            ])
            ->filters([
                //
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
