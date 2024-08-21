<?php

namespace App\Filament\Resources;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationGroup = 'Support';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return Ticket::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make('Content')
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
                            ->required(),
                    ]),
                Forms\Components\Section::make('Status')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('ticket_category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(TicketStatus::class)
                            ->enum(TicketStatus::class)
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->options(TicketPriority::class)
                            ->enum(TicketPriority::class)
                            ->required(),
                        Forms\Components\TextInput::make('user_id')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'manage' => Pages\ManageTicket::route('/{record}/manage'),
        ];
    }
}
