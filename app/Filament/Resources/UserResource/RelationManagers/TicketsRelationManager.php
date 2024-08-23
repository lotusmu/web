<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->recordUrl(fn ($record) => TicketResource::getUrl('manage', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('priority')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }

    public function getPageClass(): string
    {
        return ManageRelatedRecords::class;
    }
}
