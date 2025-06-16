<?php

namespace App\Filament\Resources;

use App\Enums\Stream\StreamProvider;
use App\Filament\Resources\StreamSessionResource\Pages;
use App\Models\Stream\StreamSession;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StreamSessionResource extends Resource
{
    protected static ?string $model = StreamSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Stream Sessions';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner.user.name')
                    ->label('Partner')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('provider')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('channel_name')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (StreamSession $record): ?string {
                        return $record->title;
                    }),

                Tables\Columns\TextColumn::make('game_category')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\IconColumn::make('isLive')
                    ->label('Live')
                    ->boolean()
                    ->getStateUsing(fn (StreamSession $record): bool => $record->isLive())
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('M j, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ended_at')
                    ->dateTime('M j, H:i')
                    ->sortable()
                    ->placeholder('Live'),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->getStateUsing(function (StreamSession $record): string {
                        $hours = $record->getDurationInHours();

                        return number_format($hours, 1).'h';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw('
                            CASE
                                WHEN ended_at IS NULL THEN TIMESTAMPDIFF(SECOND, started_at, NOW())
                                ELSE TIMESTAMPDIFF(SECOND, started_at, ended_at)
                            END '.$direction
                        );
                    }),

                Tables\Columns\TextColumn::make('peak_viewers')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('average_viewers')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')
                    ->options(StreamProvider::class),

                Tables\Filters\Filter::make('live_only')
                    ->query(fn (Builder $query): Builder => $query->live())
                    ->label('Live Streams Only'),

                Tables\Filters\Filter::make('today')
                    ->query(fn (Builder $query): Builder => $query->today())
                    ->label('Today'),

                Tables\Filters\Filter::make('this_week')
                    ->query(fn (Builder $query): Builder => $query->thisWeek())
                    ->label('This Week'),

                Tables\Filters\SelectFilter::make('partner')
                    ->relationship('partner.user', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStreamSessions::route('/'),
            'create' => Pages\CreateStreamSession::route('/create'),
            'view' => Pages\ViewStreamSession::route('/{record}'),
        ];
    }
}
