<?php

namespace App\Filament\Resources;

use App\Enums\Content\Catalog\BuffDuration;
use App\Enums\Utility\ResourceType;
use App\Filament\Resources\BuffResource\Pages;
use App\Models\Content\Catalog\Buff;
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

class BuffResource extends Resource
{
    protected static ?string $model = Buff::class;

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?string $modelLabel = 'Buff';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->description('Configure the basic information for this buff item.')
                ->aside()
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->helperText('Enter the name of the buff item.'),

                    Select::make('image_path')
                        ->options(function () {
                            return collect(File::files(public_path('images/catalog/buffs')))
                                ->mapWithKeys(fn ($file) => [
                                    'images/catalog/buffs/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ]);
                        })
                        ->allowHtml()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(File::files(public_path('images/catalog/buffs')))
                                ->filter(fn ($file) => str_contains(strtolower($file->getFilename()), strtolower($search)))
                                ->mapWithKeys(fn ($file) => [
                                    'images/catalog/buffs/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ])
                                ->toArray();
                        })
                        ->label('Image')
                        ->required()
                        ->helperText('Select an image from the catalog.'),

                    Toggle::make('is_bundle')
                        ->label('Bundle Item')
                        ->inline(false)
                        ->live()
                        ->helperText('Toggle if this is a bundle of multiple buffs.'),
                ]),

            Section::make('Item Details')
                ->description('Configure the stats or bundle contents.')
                ->aside()
                ->schema([
                    Repeater::make('stats')
                        ->schema([
                            TextInput::make('value')
                                ->required()
                                ->helperText('Enter stat bonus value (e.g. "+200 defense")'),
                        ])
                        ->defaultItems(1)
                        ->hidden(fn (Get $get): bool => $get('is_bundle')),

                    Select::make('bundle_items')
                        ->multiple()
                        ->options(function () {
                            return Buff::where('is_bundle', false)
                                ->pluck('name', 'name');
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('is_bundle')),
                ]),

            Section::make('Pricing')
                ->description('Configure duration-based pricing and resource.')
                ->aside()
                ->schema([
                    Select::make('resource')
                        ->options(ResourceType::class)
                        ->default(ResourceType::CREDITS)
                        ->native(false)
                        ->required(),

                    Repeater::make('duration_prices')
                        ->schema([
                            Select::make('duration')
                                ->native(false)
                                ->options(BuffDuration::class)
                                ->required(),
                            TextInput::make('price')
                                ->numeric()
                                ->required(),
                        ])
                        ->defaultItems(1)
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('')
                    ->square()
                    ->width(50)
                    ->getStateUsing(fn ($record) => asset($record->image_path)),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stats')
                    ->listWithLineBreaks()
                    ->formatStateUsing(fn ($state) => collect($state)->implode(', ')),
                TextColumn::make('bundle_items')
                    ->listWithLineBreaks()
                    ->formatStateUsing(fn ($state) => collect($state)->implode(', ')),
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
            'index' => Pages\ListBuffs::route('/'),
            'create' => Pages\CreateBuff::route('/create'),
            'edit' => Pages\EditBuff::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getImageOptionString(string $filename): string
    {
        return view('filament.components.select-image')
            ->with('filename', $filename)
            ->with('path', 'images/catalog/buffs/'.$filename)
            ->render();
    }
}
