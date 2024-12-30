<?php

namespace App\Filament\Resources;

use App\Enums\Utility\UpdateBannerType;
use App\Filament\Resources\UpdateBannerResource\Pages;
use App\Models\Utility\UpdateBanner;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UpdateBannerResource extends Resource
{
    protected static ?string $model = UpdateBanner::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Update Banner';

    protected static ?string $modelLabel = 'Update Banner';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Banner')
                ->columns(2)
                ->schema([
                    Select::make('type')
                        ->options(UpdateBannerType::class)
                        ->required(),

                    TextInput::make('content')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('url')
                        ->label('URL')
                        ->url()
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-globe-alt')
                        ->helperText('Optional: Add URL to make banner clickable')
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label('Banner Status')
                        ->default(true)
                        ->required()
                        ->inline(false)
                        ->columnSpanFull()
                        ->helperText('Toggle to activate or deactivate the banner.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make('content'),

                TextColumn::make('type')
                    ->badge(),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUpdateBanners::route('/'),
            'create' => Pages\CreateUpdateBanner::route('/create'),
            'edit' => Pages\EditUpdateBanner::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
