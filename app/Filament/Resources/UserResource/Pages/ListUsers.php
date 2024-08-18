<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users'),
            'verified' => Tab::make('Verified')
                ->modifyQueryUsing(function ($query) {
                    return $query->whereNot('email_verified_at', null);
                }),
            'not_verified' => Tab::make('Not Verified')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('email_verified_at', null);
                }),
        ];
    }
}
