<?php

namespace App\Filament\Resources;

use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Filament\Resources\SettingResource\Pages;
use App\Models\Utility\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $modelLabel = 'Tax';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('group')
                    ->options(OperationType::class)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('settings', []))
                    ->columnSpanFull(),

                Forms\Components\Group::make()
                    ->schema(fn (callable $get) => self::getFieldsForGroup($get('group')))
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    private static function getFieldsForGroup(?string $group): array
    {
        if (! $group) {
            return [];
        }

        $operationType = OperationType::from($group);

        return match ($operationType) {
            OperationType::PK_CLEAR => [
                Forms\Components\TextInput::make('settings.pk_clear.cost')
                    ->label('Cost Value')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(','),

                Forms\Components\Select::make('settings.pk_clear.resource')
                    ->label('Resource')
                    ->options(ResourceType::class)
                    ->required(),
            ],

            OperationType::QUEST_SKIP => [
                Forms\Components\TextInput::make('settings.quest_skip.cost')
                    ->label('Cost Value')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(','),

                Forms\Components\Select::make('settings.quest_skip.resource')
                    ->label('Resource')
                    ->options(ResourceType::class)
                    ->required(),
            ],

            OperationType::STEALTH => [
                Forms\Components\TextInput::make('settings.stealth.cost')
                    ->label('Cost Value')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(','),

                Forms\Components\Select::make('settings.stealth.resource')
                    ->label('Resource')
                    ->options(ResourceType::class)
                    ->required(),

                Forms\Components\TextInput::make('settings.stealth.duration')
                    ->label('Duration (Days)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->integer(),
            ],

            OperationType::TRANSFER => [
                Forms\Components\TextInput::make('settings.transfer.rate')
                    ->label('Rate (%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%'),
            ],

            OperationType::EXCHANGE => [
                Forms\Components\TextInput::make('settings.exchange.rate')
                    ->label('Rate (%)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%'),
            ],
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->formatStateUsing(fn (string $state) => OperationType::from($state)->getLabel())
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
        ];
    }
}
