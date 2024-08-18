<?php

namespace App\Livewire;

use App\Models\GameServer;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;

class DatabaseSelector extends Component
{
    public $selectedServerId;

    public $serverOptions;

    public function mount()
    {
        $this->serverOptions = $this->getServerOptions();
        $this->selectedServerId = session('selected_server_id', $this->serverOptions->keys()->first());
    }

    public function updateServer($newServerId): void
    {
        $this->selectedServerId = $newServerId;
        $server = GameServer::findOrFail($newServerId);

        session(['selected_server_id' => $newServerId]);
        session(['selected_server_connection' => $server->connection_name]);

        $this->dispatch('database-changed', $server->connection_name);

        Notification::make()
            ->title('Server Changed')
            ->body("Switched to {$server->name} - x{$server->experience_rate}")
            ->success()
            ->send();

        $this->redirect(request()->header('Referer'));
    }

    private function getServerOptions()
    {
        return GameServer::where('is_active', true)
            ->get(['id', 'name', 'experience_rate'])
            ->mapWithKeys(function ($server) {
                return [$server->id => [
                    'name' => $server->name,
                    'experience_rate' => $server->experience_rate,
                ]];
            });
    }

    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.database-selector');
    }
}
