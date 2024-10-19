<?php

namespace App\Filament\Resources;

use App\Enums\Utility\OperationType;
use App\Filament\Resources\TaxResource\Pages;
use App\Models\Utility\Tax;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('operation')
                    ->required()
                    ->columnSpanFull()
                    ->options(OperationType::class),
                Forms\Components\Toggle::make('is_flat_rate')
                    ->columnSpanFull()
                    ->required()
                    ->label('Flat Rate')
                    ->inline(false)
                    ->helperText('Toggle to set the rate as flat rate.')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('rate', null)),
                Forms\Components\TextInput::make('rate')
                    ->required()
                    ->columnSpanFull()
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->label(fn (callable $get) => $get('is_flat_rate') ? 'Flat Rate' : 'Rate (%)')
                    ->suffix(fn (callable $get) => $get('is_flat_rate') ? '' : '%')
                    ->step(fn (callable $get) => $get('is_flat_rate') ? 1 : 0.01)
                    ->minValue(0)
                    ->maxValue(fn (callable $get) => $get('is_flat_rate') ? null : 100)
                    ->rules([
                        function (callable $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                if ($get('is_flat_rate') && $value <= 0) {
                                    $fail("The {$attribute} must be greater than 0 for flat rate taxes.");
                                }
                            };
                        },
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('operation'),
                Tables\Columns\IconColumn::make('is_flat_rate')
                    ->label('Flat Rate')
                    ->boolean(),
                Tables\Columns\TextColumn::make('rate')
                    ->formatStateUsing(function ($state, Tax $record): string {
                        return $record->is_flat_rate
                            ? number_format($state)
                            : number_format($state, 2).'%';
                    }),
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
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTaxes::route('/'),
        ];
    }
}
