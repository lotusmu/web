<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Actions\User\SendArticleNotificationAction;
use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function afterCreate(): void
    {
        if ($this->record->is_published) {
            app(SendArticleNotificationAction::class)->handle($this->record);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
