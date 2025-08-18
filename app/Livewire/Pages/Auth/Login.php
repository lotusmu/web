<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\BaseComponent;
use App\Livewire\Forms\LoginForm;
use App\Models\Game\Wallet;
use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Session;

class Login extends BaseComponent
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        // Ensure user has wallets for all active servers
        $this->ensureUserWallets();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    private function ensureUserWallets(): void
    {
        $user = auth()->user();
        $activeServers = GameServer::where('is_active', true)->get();

        foreach ($activeServers as $server) {
            session(['game_db_connection' => $server->connection_name]);

            $walletExists = Wallet::where('AccountID', $user->name)->exists();

            if (! $walletExists) {
                Wallet::create([
                    'AccountID' => $user->name,
                    'WCoinC' => 0,
                    'zen' => 0,
                ]);
            }
        }
    }

    protected function getViewName(): string
    {
        return 'pages.auth.login';
    }

    protected function getLayoutType(): string
    {
        return 'auth';
    }
}
