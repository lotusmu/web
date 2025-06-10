<?php

namespace App\Filament\Resources;

use App\Enums\Partner\PartnerLevel;
use App\Enums\Partner\PartnerStatus;
use App\Enums\Partner\Platform;
use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Partners';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Partner Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),

                        Forms\Components\TextInput::make('promo_code')
                            ->label('Promo Code')
                            ->required()
                            ->unique(Partner::class, 'promo_code', ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\Select::make('level')
                            ->label('Partner Level')
                            ->options(PartnerLevel::getOptionsWithPercentages())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set) => $set('token_percentage', PartnerLevel::from($state)->getTokenPercentage())),

                        Forms\Components\TextInput::make('token_percentage')
                            ->label('Token Percentage')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(1)
                            ->maxValue(50)
                            ->step(0.01)
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                PartnerStatus::ACTIVE->value => PartnerStatus::ACTIVE->getLabel(),
                                PartnerStatus::INACTIVE->value => PartnerStatus::INACTIVE->getLabel(),
                                PartnerStatus::SUSPENDED->value => PartnerStatus::SUSPENDED->getLabel(),
                            ])
                            ->required(),

                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->default(now()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Platforms & Channels')
                    ->schema([
                        Forms\Components\CheckboxList::make('platforms')
                            ->label('Platforms')
                            ->options([
                                Platform::YOUTUBE->value => Platform::YOUTUBE->getLabel(),
                                Platform::TWITCH->value => Platform::TWITCH->getLabel(),
                                Platform::TIKTOK->value => Platform::TIKTOK->getLabel(),
                                Platform::FACEBOOK->value => Platform::FACEBOOK->getLabel(),
                            ])
                            ->required()
                            ->columns(2),

                        Forms\Components\Repeater::make('channels')
                            ->label('Channels')
                            ->schema([
                                Forms\Components\Select::make('platform')
                                    ->label('Platform')
                                    ->options([
                                        Platform::YOUTUBE->value => Platform::YOUTUBE->getLabel(),
                                        Platform::TWITCH->value => Platform::TWITCH->getLabel(),
                                        Platform::TIKTOK->value => Platform::TIKTOK->getLabel(),
                                        Platform::FACEBOOK->value => Platform::FACEBOOK->getLabel(),
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Channel Name/URL')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->required()
                            ->minItems(1)
                            ->addActionLabel('Add Channel'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('promo_code')
                    ->label('Promo Code')
                    ->searchable()
                    ->icon('heroicon-o-clipboard-document-list')
                    ->iconPosition(IconPosition::After)
                    ->copyable(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->badge(),

                Tables\Columns\TextColumn::make('token_percentage')
                    ->label('Token %')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                Tables\Columns\TextColumn::make('total_referrals')
                    ->label('Referrals')
                    ->getStateUsing(fn ($record) => $record->promoCodeUsages()->count())
                    ->sortable(false),

                Tables\Columns\TextColumn::make('total_tokens')
                    ->label('Total Tokens')
                    ->getStateUsing(fn ($record) => number_format($record->getTotalTokensEarned()))
                    ->sortable(false),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('approved_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'promoCodeUsages']);
    }
}
