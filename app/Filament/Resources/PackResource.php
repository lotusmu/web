<?php

namespace App\Filament\Resources;

use App\Enums\Content\Catalog\EquipmentType;
use App\Enums\Content\Catalog\PackTier;
use App\Enums\Game\CharacterClass;
use App\Enums\Utility\ResourceType;
use App\Filament\Resources\PackResource\Pages;
use App\Filament\Tables\Columns\CharacterClassColumn;
use App\Models\Content\Catalog\Pack;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;

class PackResource extends Resource
{
    protected static ?string $model = Pack::class;

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?string $modelLabel = 'Starter Pack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->description('Configure the character class and tier.')
                ->aside()
                ->schema([
                    Select::make('character_class')
                        ->options(CharacterClass::class)
                        ->searchable()
                        ->required()
                        ->native(false)
                        ->helperText('Select the character class this starter pack is designed for.'),

                    Select::make('tier')
                        ->options(PackTier::class)
                        ->required()
                        ->native(false)
                        ->helperText('Higher tier packs contain better equipment.'),

                    Select::make('image_path')
                        ->options(function () {
                            return collect(File::files(public_path('images/catalog/packs')))
                                ->mapWithKeys(fn ($file) => [
                                    'images/catalog/packs/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ]);
                        })
                        ->allowHtml()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(File::files(public_path('images/catalog/packs')))
                                ->filter(fn ($file) => str_contains(strtolower($file->getFilename()), strtolower($search)))
                                ->mapWithKeys(fn ($file) => [
                                    'images/catalog/packs/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ])
                                ->toArray();
                        })
                        ->required()
                        ->label('Image')
                        ->helperText('Select an image from the catalog.'),
                ]),

            Section::make('Starter Pack Contents')
                ->description('Configure pack content - the items within it.')
                ->aside()
                ->schema([
                    Repeater::make('contents')
                        ->schema([
                            Select::make('type')
                                ->options(EquipmentType::class)
                                ->native(false)
                                ->required(),
                            TextInput::make('name')
                                ->required(),
                        ]),
                ]),

            Section::make('Equipment Options')
                ->description('Configure the equipment bonuses.')
                ->aside()
                ->schema([

                    Fieldset::make('Item Level')
                        ->schema([
                            Toggle::make('has_level')
                                ->label('Item Level Badge')
                                ->inline(false)
                                ->default(true)
                                ->helperText('Toggle item level badge.')
                                ->live(),

                            Select::make('level')
                                ->label('Item Level')
                                ->default(0)
                                ->native(false)
                                ->options(array_combine(
                                    range(0, 15),
                                    array_map(fn ($n) => "+$n", range(0, 15))
                                ))
                                ->hidden(fn (Get $get): bool => ! $get('has_level'))
                                ->required(fn (Get $get): bool => $get('has_level'))
                                ->helperText('Select the item level value.'),
                        ])->columns(2),

                    Fieldset::make('Additional Enhancement')
                        ->schema([
                            Toggle::make('has_additional')
                                ->label('Additional Bonus Badge')
                                ->inline(false)
                                ->helperText('Toggle additional bonus badge.')
                                ->live(),

                            Select::make('additional')
                                ->label('Additional Bonus')
                                ->default(0)
                                ->native(false)
                                ->options(array_combine(
                                    range(0, 28, 4),
                                    array_map(fn ($n) => "+$n", range(0, 28, 4))
                                ))
                                ->hidden(fn (Get $get): bool => ! $get('has_additional'))
                                ->required(fn (Get $get): bool => $get('has_additional'))
                                ->helperText('Select the additional bonus value'),
                        ])->columns(2),

                    Fieldset::make('Special Options')
                        ->schema([
                            Toggle::make('has_luck')
                                ->label('Luck Badge')
                                ->inline(false)
                                ->helperText('Add luck badge to the starter pack.'),

                            Toggle::make('has_skill')
                                ->label('Weapon Skill Badge')
                                ->inline(false)
                                ->helperText('Add weapon skill badge to the starter pack.'),
                        ])->columns(2),
                ]),

            Section::make('Pricing')
                ->description('Configure price and resource type.')
                ->aside()
                ->columns(2)
                ->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->required(),
                    Select::make('resource')
                        ->options(ResourceType::class)
                        ->default(ResourceType::CREDITS)
                        ->native(false)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CharacterClassColumn::make('character_class')
                    ->label('Class')
                    ->imageSize(32),
                ImageColumn::make('image_path')
                    ->label('Image Preview')
                    ->square()
                    ->width(32)
                    ->getStateUsing(fn ($record) => asset($record->image_path)),
                TextColumn::make('tier')
                    ->badge(),
                TextColumn::make('price'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPacks::route('/'),
            'create' => Pages\CreatePack::route('/create'),
            'edit' => Pages\EditPack::route('/{record}/edit'),
        ];
    }

    public static function getImageOptionString(string $filename): string
    {
        return view('filament.components.select-image')
            ->with('filename', $filename)
            ->with('path', 'images/catalog/packs/'.$filename)
            ->render();
    }
}
