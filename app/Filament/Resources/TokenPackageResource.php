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
                Section::make('Basic Information')
                    ->description('Configure the basic package details and display settings.')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Package Name')
                            ->required()
                            ->helperText('Enter a descriptive name for this token package.'),

                        Toggle::make('is_popular')
                            ->label('Most Popular Package')
                            ->inline(false)
                            ->helperText('Toggle to highlight this as the most popular package option.'),
                    ]),

                Section::make('Stripe Integration')
                    ->description('Configure the Stripe payment integration details.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('stripe_product_id')
                            ->label('Stripe Product ID')
                            ->required()
                            ->helperText('Enter the Stripe product ID for payment integration.'),

                        TextInput::make('stripe_price_id')
                            ->label('Stripe Price ID')
                            ->required()
                            ->helperText('Enter the Stripe price ID associated with this package.'),
                    ]),

                Section::make('Pricing Configuration')
                    ->description('Set the token amount and price for this package.')
                    ->aside()
                    ->columns(3)
                    ->schema([
                        TextInput::make('tokens_amount')
                            ->label('Token Amount')
                            ->numeric()
                            ->required()
                            ->suffix('Tokens')
                            ->helperText('Number of tokens included in this package.'),

                        TextInput::make('bonus_rate')
                            ->label('Bonus Rate')
                            ->numeric()
                            ->required()
                            ->prefix('%')
                            ->helperText('Set the bonus percentage for this token package.'),

                        TextInput::make('price')
                            ->label('Package Price')
                            ->numeric()
                            ->required()
                            ->prefix('€')
                            ->helperText('Set the price for this token package.'),
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
                Tables\Columns\TextColumn::make('bonus_rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Most Popular Package')
                    ->boolean(),
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
