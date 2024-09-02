<?php

namespace App\Livewire;

use App\Models\Utility\GameServer;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class DatabaseSelector extends Component
{
    public $selectedServerId;

    public $serverOptions;

    public function mount(): void
    {
        $this->serverOptions = $this->getServerOptions();
        $this->selectedServerId = session('selected_server_id', $this->serverOptions->keys()->first());
    }

    public function updateServer($newServerId, $referer = null): void
    {
        $this->selectedServerId = $newServerId;
        $server = GameServer::findOrFail($newServerId);

        try {
            session([
                'selected_server_id' => $newServerId,
                'game_db_connection' => $server->connection_name,
            ]);

            Notification::make()
                ->title('Success!')
                ->body("Switched to {$server->name} - x{$server->experience_rate}")
                ->success()
                ->send();

            $this->redirect($referer ?? request()->header('Referer'));
        } catch (Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to switch server: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function getServerOptions(): Collection
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
