<?php

namespace App\Livewire\Pages\Guest\Server;

use App\Livewire\BaseComponent;
use App\Models\Utility\GameServer;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Livewire\Attributes\Url;

class Overview extends BaseComponent
{
    #[Url]
    public string $tab = '';

    public function getServersProperty(): Collection
    {
        return GameServer::where('is_active', true)
            ->get()
            ->map(function ($server) {
                $server->reset_zen    = Number::abbreviate($server->reset_zen);
                $server->clear_pk_zen = Number::abbreviate($server->clear_pk_zen);

                return $server;
            });
    }

    public function mount(): void
    {
        // Set first server as default tab
        if ($this->servers->isNotEmpty()) {
            $this->tab = $this->servers->first()->name;
        }
    }

    protected function getViewName(): string
    {
        return 'pages.guest.server.overview';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
