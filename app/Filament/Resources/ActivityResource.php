<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Performed By')
                    ->state(fn ($record) => self::getCauserInfo($record)['name'])
                    ->color(fn ($record) => self::getCauserInfo($record)['color'])
                    ->icon(fn ($record) => self::getCauserInfo($record)['icon'])
                    ->iconPosition(IconPosition::After)
                    ->url(fn ($record) => self::getCauserInfo($record)['url'])
                    ->searchable(),
                Tables\Columns\TextColumn::make('properties.ip_address')
                    ->label('IP Address')
                    ->copyable()
                    ->icon('heroicon-o-clipboard-document-list')
                    ->iconPosition(IconPosition::After)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->options(function () {
                        return Activity::distinct('log_name')
                            ->whereNotNull('log_name')
                            ->pluck('log_name', 'log_name')
                            ->mapWithKeys(function ($item) {
                                $formatted = str_replace('_', ' ', $item);

                                return [$item => ucwords($formatted)];
                            })
                            ->toArray();
                    })
                    ->label('Category')
                    ->multiple()
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->native(false),
                        DatePicker::make('created_until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Activity Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('causer.name')
                            ->label('Performed By')
                            ->state(fn ($record) => self::getCauserInfo($record)['name'])
                            ->icon(fn ($record) => self::getCauserInfo($record)['icon'])
                            ->iconPosition(IconPosition::After)
                            ->url(fn ($record) => self::getCauserInfo($record)['url']),
                        TextEntry::make('created_at')
                            ->label('Timestamp')
                            ->dateTime(),
                        TextEntry::make('log_name')
                            ->label('Category')
                            ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),
                        TextEntry::make('description'),
                    ]),

                Section::make('Identity Information')
                    ->schema([
                        TextEntry::make('properties.ip_address')
                            ->label('IP Address')
                            ->copyable()
                            ->icon('heroicon-o-clipboard-document-list')
                            ->iconPosition(IconPosition::After),
                        TextEntry::make('properties.user_agent')
                            ->label('User Agent')
                            ->columnSpan(2),
                    ])->columns(3),

                Section::make('Technical Details')
                    ->schema([
                        TextEntry::make('subject_type')
                            ->label('Subject Type'),
                        TextEntry::make('subject_id')
                            ->label('Subject ID'),
                        TextEntry::make('causer_type')
                            ->label('Causer Type'),
                        TextEntry::make('causer_id')
                            ->label('Causer ID'),
                    ])->columns(2),

                Section::make('All Properties')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        KeyValueEntry::make('properties')
                            ->label('')
                            ->columnSpan('full'),
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
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }

    protected static function getCauserInfo($record): array
    {
        if ($record->causer) {
            return [
                'name' => $record->causer->name,
                'color' => null,
                'icon' => 'heroicon-o-arrow-top-right-on-square',
                'url' => route('filament.admin.resources.members.edit', ['record' => $record->causer->name]),
            ];
        }

        return [
            'name' => 'System',
            'color' => 'primary',
            'icon' => null,
            'url' => null,
        ];
    }
}
