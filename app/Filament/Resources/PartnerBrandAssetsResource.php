<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerBrandAssetsResource\Pages\CreatePartnerBrandAssets;
use App\Filament\Resources\PartnerBrandAssetsResource\Pages\EditPartnerBrandAssets;
use App\Filament\Resources\PartnerBrandAssetsResource\Pages\ListPartnerBrandAssets;
use App\Models\Partner\PartnerBrandAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PartnerBrandAssetsResource extends Resource
{
    protected static ?string $model = PartnerBrandAsset::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Brand Assets';

    protected static ?string $pluralModelLabel = 'Brand Assets';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Asset Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Asset Name')
                            ->required()
                            ->placeholder('e.g., Stream Banners Pack'),

                        Forms\Components\FileUpload::make('path')
                            ->label('File')
                            ->disk('public')
                            ->directory('downloads')
                            ->acceptedFileTypes(['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/x-msdownload', 'application/octet-stream'])
                            ->maxSize(500 * 1024) // 500MB max size
                            ->helperText('Allowed file types: zip, rar, 7z, exe. Maximum size: 500MB')
                            ->storeFileNamesIn('filename'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active assets can be downloaded by partners'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('filename')
                    ->label('File')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (PartnerBrandAsset $record) {
                        if (! $record->exists()) {
                            Notification::make()
                                ->title('File not found')
                                ->danger()
                                ->send();

                            return;
                        }

                        return Storage::disk('public')->download($record->path, $record->filename);
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartnerBrandAssets::route('/'),
            'create' => CreatePartnerBrandAssets::route('/create'),
            'edit' => EditPartnerBrandAssets::route('/{record}/edit'),
        ];
    }
}
