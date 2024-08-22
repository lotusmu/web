<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Content\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('File')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->columnSpanFull()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('storage_type')
                            ->options([
                                'local' => 'Local',
                                'external' => 'External',
                            ])
                            ->default('external')
                            ->helperText('Choose between local and external storage.')
                            ->required()
                            ->reactive(),
                        Forms\Components\FileUpload::make('local_file')
                            ->label('File')
                            ->disk('public')
                            ->directory('downloads')
                            ->acceptedFileTypes(['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/x-msdownload', 'application/octet-stream'])
                            ->maxSize(500 * 1024) // 500MB max size
                            ->helperText('Allowed file types: zip, rar, 7z, exe. Maximum size: 500MB')
                            ->visible(fn (callable $get) => $get('storage_type') === 'local')
                            ->required(fn (callable $get) => $get('storage_type') === 'local'),
                        Forms\Components\TextInput::make('external_url')
                            ->label('External URL')
                            ->prefixIcon('heroicon-o-globe-alt')
                            ->helperText('Please provide a valid url address.')
                            ->visible(fn (callable $get) => $get('storage_type') === 'external')
                            ->required(fn (callable $get) => $get('storage_type') === 'external')
                            ->url(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('storage_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::ucfirst($state))
                    ->color(fn (string $state): array => match ($state) {
                        'local' => Color::Blue,
                        'external' => Color::Emerald,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('file_url')
                    ->label('File URL')
                    ->url(fn (Download $record): string => $record->file_url)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }
}
