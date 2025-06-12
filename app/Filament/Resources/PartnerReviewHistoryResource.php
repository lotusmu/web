<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerReviewHistoryResource\Pages;
use App\Models\Partner\PartnerReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerReviewHistoryResource extends Resource
{
    protected static ?string $model = PartnerReview::class;

    protected static ?string $navigationGroup = 'Partners';

    protected static ?string $navigationLabel = 'Review History';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->schema([
                        Forms\Components\Select::make('partner_id')
                            ->label('Partner')
                            ->relationship('partner.user', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(),

                        Forms\Components\TextInput::make('week_number')
                            ->label('Week Number')
                            ->disabled(),

                        Forms\Components\TextInput::make('year')
                            ->label('Year')
                            ->disabled(),

                        Forms\Components\Select::make('decision')
                            ->label('Decision')
                            ->options([
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->disabled(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->disabled()
                            ->rows(4),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner.user.name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('partner.promo_code')
                    ->label('Promo Code')
                    ->copyable(),

                Tables\Columns\TextColumn::make('week_number')
                    ->label('Week')
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->label('Year')
                    ->sortable(),

                Tables\Columns\TextColumn::make('decision')
                    ->label('Decision')
                    ->formatStateUsing(fn ($state) => ucwords($state))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(function ($state) {
                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reviewed At')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('decision')
                    ->options([
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        $years = [];
                        for ($year = now()->year; $year >= now()->year - 2; $year--) {
                            $years[$year] = $year;
                        }

                        return $years;
                    }),

                Tables\Filters\Filter::make('current_week')
                    ->label('This Week')
                    ->query(fn ($query) => $query->where('week_number', now()->week)->where('year', now()->year)),

                Tables\Filters\Filter::make('last_4_weeks')
                    ->label('Last 4 Weeks')
                    ->query(function ($query) {
                        $fourWeeksAgo = now()->subWeeks(4);

                        return $query->where(function ($q) use ($fourWeeksAgo) {
                            $q->where('year', now()->year)
                                ->where('week_number', '>=', $fourWeeksAgo->week);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Tables\Grouping\Group::make('year')
                    ->label('Year')
                    ->collapsible(),

                Tables\Grouping\Group::make('week_number')
                    ->label('Week')
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerReviewHistories::route('/'),
            'view' => Pages\ViewPartnerReviewHistory::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
