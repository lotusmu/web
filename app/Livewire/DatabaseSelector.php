<?php

namespace App\Livewire;

use App\Models\GameServer;
use Filament\Notifications\Notification;
use Livewire\Component;

class DatabaseSelector extends Component
{
    public $selectedDatabase;

    public $databaseOptions;

    public function mount()
    {
        $this->selectedDatabase = session('selected_server_connection', 'gamedb_main');
        $this->databaseOptions = $this->getDatabaseOptions();
    }

    public function render()
    {
        return view('livewire.database-selector');
    }

    public function updateDatabase($newDatabase): void
    {
        $this->selectedDatabase = $newDatabase;
        session(['selected_server_connection' => $newDatabase]);

        $this->dispatch('database-changed', $newDatabase);

        Notification::make()
            ->title('Server Changed')
            ->body("Switched to {$this->databaseOptions[$newDatabase]['name']} - x{$this->databaseOptions[$newDatabase]['experience_rate']}")
            ->success()
            ->send();

        $this->redirect(request()->header('Referer'));
    }

    private function getDatabaseOptions(): array
    {
        return GameServer::where('is_active', true)
            ->get(['name', 'connection_name', 'experience_rate'])
            ->mapWithKeys(function ($server) {
                return [
                    $server->connection_name => [
                        'name' => $server->name,
                        'experience_rate' => $server->experience_rate,
                    ],
                ];
            })
            ->toArray();
    }
}
