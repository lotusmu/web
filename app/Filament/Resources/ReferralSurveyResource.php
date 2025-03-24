<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralSurveyResource\Pages;
use App\Filament\Resources\ReferralSurveyResource\Widgets\ReferralSourcesChart;
use App\Filament\Resources\ReferralSurveyResource\Widgets\SurveyStatsWidget;
use App\Models\User\ReferralSurvey;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ReferralSurveyResource extends Resource
{
    protected static ?string $model = ReferralSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Referral Survey';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('referral_source')
                    ->label('Source')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mmo_top_site')
                    ->label('MMO Site')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('mu_online_forum')
                    ->label('Forum')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('custom_source')
                    ->label('Custom Source')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('completed')
                    ->label('Completed')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('dismissed')
                    ->label('Dismissed')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shown_at')
                    ->label('Shown At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            });
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            SurveyStatsWidget::class,
            ReferralSourcesChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferralSurveys::route('/'),
        ];
    }
}
