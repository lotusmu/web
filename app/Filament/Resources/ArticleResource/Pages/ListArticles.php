<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleType;
use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Articles'),
            'news' => Tab::make(ArticleType::NEWS->getLabel())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('type', ArticleType::NEWS);
                }),
            'patch_notes' => Tab::make(ArticleType::PATCH_NOTE->getLabel())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('type', ArticleType::PATCH_NOTE);
                }),
        ];
    }
}
