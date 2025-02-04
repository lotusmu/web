<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Game\Ranking\EventSetting;
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

class EventSettingResource extends Resource
{
    protected static ?string $model = EventSetting::class;

    protected static ?string $navigationGroup = 'Rankings';

    protected static ?string $modelLabel = 'Event';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->description('Configure the basic information for this event.')
                ->aside()
                ->schema([
                    TextInput::make('EventID')
                        ->label('Event ID')
                        ->required()
                        ->numeric()
                        ->unique(ignoreRecord: true)
                        ->helperText('Enter the event ID (used in the stored procedures).'),

                    TextInput::make('EventName')
                        ->label('Name')
                        ->required()
                        ->maxLength(50)
                        ->helperText('Enter the name of the event.'),

                    Select::make('image_path')
                        ->options(function () {
                            return collect(File::files(public_path('images/game/events')))
                                ->mapWithKeys(fn ($file) => [
                                    'images/game/events/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ]);
                        })
                        ->allowHtml()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(File::files(public_path('images/game/events')))
                                ->filter(fn ($file) => str_contains(strtolower($file->getFilename()), strtolower($search)))
                                ->mapWithKeys(fn ($file) => [
                                    'images/game/events/'.$file->getFilename() => static::getImageOptionString($file->getFilename()),
                                ])
                                ->toArray();
                        })
                        ->label('Image')
                        ->helperText('Select an image from the catalog.'),
                ]),

            Section::make('Game Statistics')
                ->description('Configure the event statistics.')
                ->aside()
                ->schema([
                    TextInput::make('PointsPerWin')
                        ->label('Points Per Win')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->helperText('Points awarded for this event.'),
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
                TextColumn::make('EventID')
                    ->label('Event ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('EventName')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('PointsPerWin')
                    ->label('Points Per Win')
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
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
            ->with('path', 'images/game/events/'.$filename)
            ->render();
    }
}
