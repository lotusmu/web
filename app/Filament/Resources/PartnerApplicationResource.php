<?php

namespace App\Filament\Resources;

use App\Enums\Partner\ApplicationStatus;
use App\Filament\Resources\PartnerApplicationResource\Pages;
use App\Models\Partner\PartnerApplication;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerApplicationResource extends Resource
{
    protected static ?string $model = PartnerApplication::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('content_type')
                    ->label('Content Type')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'streaming' => 'Live Streaming',
                            'content' => 'Video Content',
                            'both' => 'Both',
                            default => ucfirst($state)
                        };
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y')
                    ->sortable(),

                TextColumn::make('reviewed_at')
                    ->label('Reviewed')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ApplicationStatus::PENDING->value => 'Pending',
                        ApplicationStatus::APPROVED->value => 'Approved',
                        ApplicationStatus::REJECTED->value => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (PartnerApplication $record) => $record->status === ApplicationStatus::PENDING)
                    ->action(function (PartnerApplication $record) {
                        $record->update([
                            'status' => ApplicationStatus::APPROVED->value,
                            'reviewed_at' => now(),
                        ]);

                        // In the future, we'll create a Partner record here
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (PartnerApplication $record) => $record->status === ApplicationStatus::PENDING)
                    ->action(function (PartnerApplication $record) {
                        $record->update([
                            'status' => ApplicationStatus::REJECTED->value,
                            'reviewed_at' => now(),
                        ]);
                    }),

                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (PartnerApplication $record) => static::getUrl('view', ['record' => $record])),

            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerApplications::route('/'),
            'view' => Pages\ViewPartnerApplication::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user');

    }
}
