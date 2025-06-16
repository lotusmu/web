<?php

namespace App\Filament\Resources;

use App\Enums\Partner\PartnerLevel;
use App\Enums\Partner\PartnerStatus;
use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\Widgets\PartnerPerformanceWidget;
use App\Models\Partner\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Partners';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('level')
                    ->options(PartnerLevel::class)
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options(PartnerStatus::class)
                    ->required(),

                Forms\Components\TextInput::make('promo_code')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('token_percentage')
                    ->numeric()
                    ->step(0.01)
                    ->suffix('%')
                    ->required(),

                Forms\Components\TagsInput::make('platforms'),

                Forms\Components\Repeater::make('channels')
                    ->schema([
                        Forms\Components\TextInput::make('platform')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\DateTimePicker::make('approved_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->badge(),

                Tables\Columns\TextColumn::make('token_percentage')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\IconColumn::make('has_live_streams')
                    ->label('Live')
                    ->boolean()
                    ->getStateUsing(function (Partner $record): bool {
                        return $record->streamSessions()->whereNull('ended_at')->exists();
                    })
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('streams_this_week')
                    ->label('Streams/Week')
                    ->getStateUsing(function (Partner $record): string {
                        $count = $record->streamSessions()
                            ->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->count();

                        return (string) $count;
                    })
                    ->sortable(false),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PartnerStatus::class),

                Tables\Filters\SelectFilter::make('level')
                    ->options(PartnerLevel::class),

                Tables\Filters\Filter::make('live_now')
                    ->query(fn (Builder $query): Builder => $query->whereHas('streamSessions', fn (Builder $q) => $q->whereNull('ended_at')
                    )
                    )
                    ->label('Currently Live'),

                Tables\Filters\Filter::make('streamed_this_week')
                    ->query(fn (Builder $query): Builder => $query->whereHas('streamSessions', fn (Builder $q) => $q->whereBetween('started_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ])
                    )
                    )
                    ->label('Streamed This Week'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('approved_at', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            PartnerPerformanceWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
