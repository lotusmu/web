<?php

namespace App\Filament\Resources;

use App\Enums\Game\BankItem;
use App\Enums\Partner\PartnerLevel;
use App\Filament\Resources\PartnerFarmPackageResource\Pages;
use App\Models\Partner\PartnerFarmPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerFarmPackageResource extends Resource
{
    protected static ?string $model = PartnerFarmPackage::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Farm Packages';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->placeholder('e.g., Level 1 Farm Reward'),

                        Forms\Components\Select::make('partner_level')
                            ->label('Partner Level')
                            ->options([
                                1 => PartnerLevel::LEVEL_ONE->getLabel(),
                                2 => PartnerLevel::LEVEL_TWO->getLabel(),
                                3 => PartnerLevel::LEVEL_THREE->getLabel(),
                                4 => PartnerLevel::LEVEL_FOUR->getLabel(),
                                5 => PartnerLevel::LEVEL_FIVE->getLabel(),
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Farm Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->schema([
                                Forms\Components\Select::make('item_index')
                                    ->label('Item')
                                    ->options(self::getItemOptions())
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set) => $set('item_level', 0)),

                                Forms\Components\Select::make('item_level')
                                    ->label('Level')
                                    ->options(fn ($get) => self::getLevelOptions($get('item_index')))
                                    ->default(0)
                                    ->required(),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->default(1),
                            ])
                            ->columns(3)
                            ->addActionLabel('Add Item')
                            ->minItems(1)
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('partner_level')
                    ->options([
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Level 3',
                        4 => 'Level 4',
                        5 => 'Level 5',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('partner_level');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerFarmPackages::route('/'),
            'create' => Pages\CreatePartnerFarmPackage::route('/create'),
            'edit' => Pages\EditPartnerFarmPackage::route('/{record}/edit'),
        ];
    }

    private static function getItemOptions(): array
    {
        $options = [];
        foreach (BankItem::cases() as $item) {
            $options[$item->value] = $item->getName();
        }

        return $options;
    }

    private static function getLevelOptions(?int $itemIndex): array
    {
        if (! $itemIndex) {
            return [0 => 'Level 0'];
        }

        // Special case for Loch's Feather which has Monarch's Crest at level 1
        if ($itemIndex === BankItem::LOCHS_FEATHER->value) {
            return [
                0 => 'Level 0 (Loch\'s Feather)',
                1 => 'Level 1 (Monarch\'s Crest)',
            ];
        }

        return [0 => 'Level 0'];
    }
}
