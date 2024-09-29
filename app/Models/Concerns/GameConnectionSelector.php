<?php

namespace App\Models\Concerns;

use App\Models\Utility\GameServer;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

trait GameConnectionSelector
{
    public $selectedServerId;

    public $serverOptions;

    public function loadConnectionOptions(): void
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
}
