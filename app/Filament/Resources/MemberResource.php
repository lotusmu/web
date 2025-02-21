<?php

namespace App\Filament\Resources;

use App\Enums\Game\AccountLevel;
use App\Enums\Game\BanStatus;
use App\Filament\Actions\BanTableAction;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers\CharactersRelationManager;
use App\Models\User\Member;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationGroup = 'Account & Characters';

    protected static ?int $navigationSort = 2;

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
        return ['memb___id', 'mail_addr'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "{$record->name} ({$record->email})";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')
                    ->description('User Details can be changed from User Logins.')
                    ->aside()
                    ->columns(3)
                    ->schema([
                        Placeholder::make('memb___id')
                            ->label('Username')
                            ->content(fn ($record) => $record->memb___id),
                        Placeholder::make('mail_addr')
                            ->label('Email')
                            ->content(fn ($record) => $record->mail_addr),
                        Placeholder::make('memb__pwd')
                            ->label('Password')
                            ->content(fn ($record) => $record->memb__pwd),
                    ]),
                Section::make('Account Level')
                    ->description('Change the account level and its expiration date.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Select::make('AccountLevel')
                            ->label('VIP Package')
                            ->options(AccountLevel::class)
                            ->enum(AccountLevel::class)
                            ->required(),
                        DateTimePicker::make('AccountExpireDate')
                            ->label('Expiration Date')
                            ->required(),
                    ]),
                Section::make('Resources')
                    ->description('Adjust member\'s balances.')
                    ->aside()
                    ->columns(2)
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

                Section::make('Account Status')
                    ->description('Manage ban status for this account.')
                    ->aside()
                    ->schema([
                        Select::make('bloc_code')
                            ->label('Status')
                            ->enum(BanStatus::class)
                            ->options(BanStatus::class)
                            ->default(BanStatus::Active)
                            ->reactive()
                            ->required(),

                        DateTimePicker::make('bloc_expire')
                            ->label('Ban Expires')
                            ->native(false)
                            ->helperText('Leave empty for permanent ban.')
                            ->minDate(now()->addDay())
                            ->nullable()
                            ->visible(fn (Get $get) => $get('bloc_code') == 1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('memb___id')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mail_addr')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AccountLevel')
                    ->label('Account Level')
                    ->badge(),
                Tables\Columns\TextColumn::make('AccountExpireDate')
                    ->label('VIP Expire Date')
                    ->dateTime()
                    ->formatStateUsing(function ($state, $record) {
                        return $record->AccountLevel === AccountLevel::Regular ? '-' : $state;
                    }),
                Tables\Columns\TextColumn::make('tokens')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallet.credits')
                    ->label('Credits')
                    ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('AccountLevel')
                    ->options(AccountLevel::class)
                    ->label('Account Level')
                    ->placeholder('All Levels')
                    ->multiple(),
            ])
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->actions([
                Tables\Actions\EditAction::make(),
                BanTableAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CharactersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
