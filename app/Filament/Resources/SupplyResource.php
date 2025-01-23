<?php

namespace App\Filament\Resources;

use App\Enums\Content\Catalog\SupplyCategory;
use App\Enums\Utility\ResourceType;
use App\Filament\Resources\SupplyResource\Pages;
use App\Models\Content\Catalog\Supply;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?string $modelLabel = 'Supply';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->description('Configure the basic information for this supply item.')
                ->aside()
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->helperText('Enter the name of the supply item.'),

                    Select::make('image_path')
                        ->options(function () {
                            return collect(File::files(public_path('images/catalog/supplies')))
                                ->mapWithKeys(fn ($file) => [
                                    'images/catalog/supplies/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ]);
                        })
                        ->allowHtml()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(File::files(public_path('images/catalog/supplies')))
                                ->filter(fn ($file) => str_contains(strtolower($file->getFilename()), strtolower($search)))
                                ->mapWithKeys(fn ($file) => [
                                    'images/catalog/supplies/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ])
                                ->toArray();
                        })
                        ->label('Image')
                        ->required()
                        ->helperText('Select an image from the catalog.'),

                    Textarea::make('description')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Enter the description of the supply item. (Max 255 characters)'),

                    Select::make('category')
                        ->options(SupplyCategory::class)
                        ->default(SupplyCategory::CONSUMABLES)
                        ->required()
                        ->native(false),
                ]),

            Section::make('Pricing')
                ->description('Configure the price and resource type.')
                ->columns(2)
                ->aside()
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
            ->columns(components: [
                ImageColumn::make('image_path')
                    ->label('')
                    ->square()
                    ->width(50)
                    ->getStateUsing(fn ($record) => asset($record->image_path)),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('price')
                    ->badge()
                    ->formatStateUsing(fn ($state, $record) => "{$state} {$record->resource->getLabel()}"),
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
            'index' => Pages\ListSupplies::route('/'),
            'create' => Pages\CreateSupply::route('/create'),
            'edit' => Pages\EditSupply::route('/{record}/edit'),
        ];
    }

    public static function getImageOptionString(string $filename): string
    {
        return view('filament.components.select-image')  // Should we create a new view or reuse this?
            ->with('filename', $filename)
            ->with('path', 'images/catalog/supplies/'.$filename)
            ->render();
    }
}
