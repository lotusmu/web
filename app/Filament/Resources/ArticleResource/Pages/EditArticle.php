<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Actions\User\SendArticleNotificationAction;
use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->is_published && $this->record->wasChanged('is_published')) {
            app(SendArticleNotificationAction::class)->handle($this->record);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
