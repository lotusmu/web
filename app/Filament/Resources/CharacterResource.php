<?php

namespace App\Filament\Resources;

use App\Enums\Game\BanStatus;
use App\Enums\Game\CharacterClass;
use App\Enums\Game\GuildMemberStatus;
use App\Enums\Game\Map;
use App\Enums\Game\PkLevel;
use App\Filament\Infolists\Components\Entry\CharacterClassEntry;
use App\Filament\Resources\CharacterResource\Pages;
use App\Filament\Tables\Columns\CharacterClassColumn;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\GlobalSearch\Actions\Action;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->url(static::getUrl('view', ['record' => $record])),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Character Information')
                    ->description('General information about the character.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Placeholder::make('Name')
                            ->label('Character Name')
                            ->content(fn ($record) => $record->Name),
                        Placeholder::make('AccountID')
                            ->label('Username')
                            ->content(fn ($record) => $record->AccountID),
                        Select::make('Class')
                            ->label('Class')
                            ->columnSpanFull()
                            ->options(CharacterClass::class)
                            ->enum(CharacterClass::class)
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('ResetCount')
                            ->label('Resets')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('cLevel')
                            ->label('Level')
                            ->required()
                            ->numeric()
                            ->default(1),
                    ]),
                \Filament\Forms\Components\Section::make('Other Information')
                    ->description('Detailed information about the character.')
                    ->aside()
                    ->schema([
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make('Character Stats')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('Strength')
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(32767)
                                            ->numeric(),
                                        TextInput::make('Dexterity')
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(32767)
                                            ->numeric(),
                                        TextInput::make('Vitality')
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(32767)
                                            ->numeric(),
                                        TextInput::make('Energy')
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(32767)
                                            ->numeric(),
                                        TextInput::make('Leadership')
                                            ->columnSpanFull()
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(32767)
                                            ->numeric()
                                            ->default(0),
                                    ]),
                                Tabs\Tab::make('Location')
                                    ->schema([
                                        Select::make('MapNumber')
                                            ->label('Map Name')
                                            ->options(Map::class)
                                            ->enum(Map::class)
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        TextInput::make('MapPosX')
                                            ->label('X Position')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->numeric(),
                                        TextInput::make('MapPosY')
                                            ->label('Y Position')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->numeric(),
                                    ]),
                                Tabs\Tab::make('Player Status')
                                    ->schema([
                                        Select::make('PkLevel')
                                            ->label('PK Level')
                                            ->options(PkLevel::class)
                                            ->enum(PkLevel::class)
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('PkCount')
                                            ->label('Kills Count')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        TextInput::make('PkTime')
                                            ->label('PK Time')
                                            ->numeric()
                                            ->minValue(0)
                                            ->required()
                                            ->default(0),
                                    ]),
                                Tabs\Tab::make('Guild')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('guild_name')
                                            ->options(Guild::query()->pluck('G_Name', 'G_Name')->toArray())
                                            ->label('Guild')
                                            ->searchable()
                                            ->preload()
                                            ->nullable()
                                            ->live()
                                            ->disabled(fn ($record) => $record->guildMember?->G_Status === GuildMemberStatus::GuildMaster)
                                            ->afterStateHydrated(function (Set $set, $state, $record) {
                                                $set('guild_name', $record->guildMember?->G_Name);
                                            }),

                                        Select::make('guild_status')
                                            ->options(GuildMemberStatus::class)
                                            ->label('Guild Position')
                                            ->disabled()
                                            ->afterStateHydrated(function (Set $set, $state, $record) {
                                                $set('guild_status', $record->guildMember?->G_Status);
                                            }),
                                    ]),
                                Tabs\Tab::make('Character Status')
                                    ->schema([
                                        Select::make('CtlCode')
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
                                            ->visible(fn (Get $get) => $get('CtlCode') == 1),
                                    ]),
                            ]),
                    ]),
            ]);
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
                Tables\Columns\TextColumn::make('guildMember.G_Name')
                    ->label('Guild')
                    ->placeholder('No Guild')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guildMember.G_Status')
                    ->label('Guild Position')
                    ->placeholder('Not a member')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => $state?->getColor()),
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
                Tables\Actions\Action::make('banCharacter')
                    ->label(fn (Character $record): string => $record->isBanned() ? 'Unban' : 'Ban')
                    ->icon(fn (Character $record): string => $record->isBanned() ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->color(fn (Character $record): string => $record->isBanned() ? 'success' : 'danger')
                    ->form(function (Character $record) {
                        if ($record->isBanned()) {
                            return [];
                        }

                        return [
                            Toggle::make('permanent_ban')
                                ->label('Ban Permanently')
                                ->inline(false)
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon('heroicon-s-check')
                                ->offIcon('heroicon-s-x-mark')
                                ->default(true)
                                ->reactive(),
                            DateTimePicker::make('ban_until')
                                ->label('Ban Until')
                                ->required()
                                ->native(false)
                                ->minDate(now()->addDay())
                                ->hidden(fn (Get $get) => $get('permanent_ban')),
                        ];
                    })
                    ->action(function (Character $record, array $data) {
                        if ($record->isBanned()) {
                            $record->unban();

                            Notification::make()
                                ->title("Character {$record->Name} has been unbanned")
                                ->success()
                                ->send();

                            return;
                        }

                        if ($data['permanent_ban'] ?? false) {
                            $record->banPermanently();
                            $message = "Character {$record->Name} has been banned permanently";
                        } else {
                            $banUntil = Carbon::parse($data['ban_until']);
                            $record->banUntil($banUntil);
                            $message = "Character {$record->Name} has been banned until ".$banUntil->format('Y-m-d H:i');
                        }

                        Notification::make()
                            ->title($message)
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                //
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
                        TextEntry::make('Name')
                            ->label('Character'),
                        TextEntry::make('Class')
                            ->label('Class'),
                        TextEntry::make('ResetCount')
                            ->label('Resets'),
                        TextEntry::make('cLevel')
                            ->label('Level'),
                        TextEntry::make('AccountID')
                            ->label('Username')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->iconPosition(IconPosition::After)
                            ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->AccountID])),
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
                        Fieldset::make('Guild Information')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('guildMember.G_Name')
                                    ->label('Guild Name')
                                    ->placeholder('No Guild'),
                                TextEntry::make('guildMember.G_Status')
                                    ->label('Guild Position')
                                    ->placeholder('Not a member')
                                    ->badge(),
                            ]),

                        Fieldset::make('Character Status')
                            ->columns(1)
                            ->schema([
                                TextEntry::make('CtlCode')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($state): string => $state->getLabel())
                                    ->color(fn ($state): string => $state->getColor())
                                    ->badge(),

                                TextEntry::make('ban_until')
                                    ->label('Ban Expires')
                                    ->state(function (Character $record) {
                                        return $record->bloc_expire === null ? 'Permanent' : $record->bloc_expire->format('Y-m-d H:i');
                                    })
                                    ->visible(fn (Character $record): bool => $record->isBanned()),

                                Actions::make([
                                    Actions\Action::make('banCharacter')
                                        ->label(fn (Character $record): string => $record->isBanned() ? 'Unban Character' : 'Ban Character')
                                        ->icon(fn (Character $record): string => $record->isBanned() ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                                        ->color(fn (Character $record): string => $record->isBanned() ? 'success' : 'danger')
                                        ->form(function (Character $record) {
                                            if ($record->isBanned()) {
                                                return [];
                                            }

                                            return [
                                                Toggle::make('permanent_ban')
                                                    ->label('Ban Permanently')
                                                    ->inline(false)
                                                    ->onColor('success')
                                                    ->offColor('danger')
                                                    ->onIcon('heroicon-s-check')
                                                    ->offIcon('heroicon-s-x-mark')
                                                    ->default(true)
                                                    ->reactive(),
                                                DateTimePicker::make('ban_until')
                                                    ->label('Ban Until')
                                                    ->required()
                                                    ->native(false)
                                                    ->minDate(now()->addDay())
                                                    ->hidden(fn (Get $get) => $get('permanent_ban')),
                                            ];
                                        })
                                        ->action(function (Character $record, array $data) {
                                            if ($record->isBanned()) {
                                                $record->unban();

                                                Notification::make()
                                                    ->title("Character {$record->Name} has been unbanned")
                                                    ->success()
                                                    ->send();

                                                return;
                                            }

                                            if ($data['permanent_ban'] ?? false) {
                                                $record->banPermanently();
                                                $message = "Character {$record->Name} has been banned permanently";
                                            } else {
                                                $banUntil = Carbon::parse($data['ban_until']);
                                                $record->banUntil($banUntil);
                                                $message = "Character {$record->Name} has been banned until ".$banUntil->format('Y-m-d H:i');
                                            }

                                            Notification::make()
                                                ->title($message)
                                                ->success()
                                                ->send();
                                        })
                                        ->requiresConfirmation(),
                                ]),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->select(Character::getFillableFields());
    }
}
