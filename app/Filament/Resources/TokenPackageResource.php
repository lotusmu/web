<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokenPackageResource\Pages;
use App\Models\Payment\TokenPackage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokenPackageResource extends Resource
{
    protected static ?string $model = TokenPackage::class;

    protected static ?string $navigationGroup = 'Payments';

    protected static ?string $modelLabel = 'Package';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('stripe_product_id')
                            ->label('Stripe Product ID')
                            ->required(),
                        TextInput::make('stripe_price_id')
                            ->label('Stripe Price ID')
                            ->required(),
                        TextInput::make('tokens_amount')
                            ->numeric()
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('€'),
                        Toggle::make('is_popular')
                            ->inline(false)
                            ->label('Most Popular')
                            ->helperText('Toggle to set the package as most popular.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tokens_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
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
            'index' => Pages\ListTokenPackages::route('/'),
            'create' => Pages\CreateTokenPackage::route('/create'),
            'edit' => Pages\EditTokenPackage::route('/{record}/edit'),
        ];
    }
}
