<?php

namespace App\Livewire\Pages\Guest\Files;

use App\Livewire\BaseComponent;
use App\Models\Content\Download;
use Illuminate\Support\Collection;

class Files extends BaseComponent
{
    public function downloads(): Collection
    {
        return Download::query()
            ->latest()
            ->get()
            ->map(function ($download) {
                return [
                    'name' => $download->name,
                    'url' => $download->file_url,
                    'icon' => $download->provider->getIcon(),
                ];
            });
    }

    protected function getViewName(): string
    {
        return 'pages.guest.files.index';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
