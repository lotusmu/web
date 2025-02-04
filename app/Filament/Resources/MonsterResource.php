<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonsterResource\Pages\CreateMonster;
use App\Filament\Resources\MonsterResource\Pages\EditMonster;
use App\Filament\Resources\MonsterResource\Pages\ListMonsters;
use App\Models\Game\Ranking\MonsterSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

class MonsterResource extends Resource
{
    protected static ?string $model = MonsterSetting::class;

    protected static ?string $navigationGroup = 'Rankings';

    protected static ?string $modelLabel = 'Monster';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->description('Configure the basic information for this monster.')
                ->aside()
                ->schema([
                    TextInput::make('MonsterClass')
                        ->label('Monster Class')
                        ->required()
                        ->numeric()
                        ->unique(ignoreRecord: true)
                        ->helperText('Enter the monster class ID (server config).'),

                    TextInput::make('MonsterName')
                        ->label('Name')
                        ->required()
                        ->maxLength(50)
                        ->helperText('Enter the name of the monster.'),

                    Select::make('image_path')
                        ->options(function () {
                            return collect(File::files(public_path('images/game/monsters')))
                                ->mapWithKeys(fn ($file) => [
                                    'images/game/monsters/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ]);
                        })
                        ->allowHtml()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(File::files(public_path('images/game/monsters')))
                                ->filter(fn ($file) => str_contains(strtolower($file->getFilename()), strtolower($search)))
                                ->mapWithKeys(fn ($file) => [
                                    'images/game/monsters/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ])
                                ->toArray();
                        })
                        ->label('Image')
                        ->helperText('Select an image from the catalog.'),
                ]),

            Section::make('Game Statistics')
                ->description('Configure the monster statistics.')
                ->aside()
                ->schema([
                    TextInput::make('PointsPerKill')
                        ->label('Points Per Kill')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->helperText('Points awarded when this monster is killed.'),
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
                TextColumn::make('MonsterClass')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('MonsterName')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('PointsPerKill')
                    ->badge()
                    ->sortable(),
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
            'index' => ListMonsters::route('/'),
            'create' => CreateMonster::route('/create'),
            'edit' => EditMonster::route('/{record}/edit'),
        ];
    }

    public static function getImageOptionString(string $filename): string
    {
        return view('filament.components.select-image')
            ->with('filename', $filename)
            ->with('path', 'images/game/monsters/'.$filename)
            ->render();
    }
}
