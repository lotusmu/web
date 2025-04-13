<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\Ticket\TicketStatus;
use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Active')
                ->modifyQueryUsing(function ($query) {
                    return $query->whereNotIn('status', [TicketStatus::CLOSED, TicketStatus::RESOLVED]);
                }),
            'resolved' => Tab::make('Resolved')
                ->modifyQueryUsing(function ($query) {
                    return $query->whereIn('status', [TicketStatus::CLOSED, TicketStatus::RESOLVED]);
                }),
            'all' => Tab::make('All Tickets'),
        ];
    }
}
