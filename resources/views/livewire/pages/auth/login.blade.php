<?php

use App\Livewire\Forms\LoginForm;
use App\Models\Game\Wallet;
use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
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
        $user          = auth()->user();
        $activeServers = GameServer::where('is_active', true)->get();

        foreach ($activeServers as $server) {
            session(['game_db_connection' => $server->connection_name]);

            $walletExists = Wallet::where('AccountID', $user->name)->exists();

            if ( ! $walletExists) {
                Wallet::create([
                    'AccountID' => $user->name,
                    'WCoinC'    => 0,
                    'zen'       => 0,
                ]);
            }
        }
    }
}; ?>

<div class="space-y-6">
    <flux:heading size="xl" class="text-center">
        {{__('Welcome Back')}}
    </flux:heading>

    <form wire:submit="login" class="flex flex-col gap-6">
        <flux:input wire:model="form.name" label="{{ __('Username') }}" placeholder="{{ __('Your username') }}"/>

        <flux:field>
            <flux:label class="flex items-center justify-between">
                {{ __('Password') }}
                <flux:link :href="route('password.request')" variant="subtle">
                    {{  __('Forgot password?') }}
                </flux:link>
            </flux:label>
            <flux:input viewable wire:model="form.password" type="password" placeholder="{{__('Your password')}}"/>

            <flux:error name="form.password"/>
        </flux:field>

        <flux:field>
            <flux:checkbox wire:model="form.remember" label="{{ __('Remember me') }}"/>
        </flux:field>

        <flux:button variant="primary" type="submit">
            {{ __('Log in') }}
        </flux:button>
    </form>

    <flux:subheading class="text-center">
        {{__('First time around here?')}}
        <flux:link :href="route('register')" wire:navigate>{{__('Sign up now!')}}</flux:link>
    </flux:subheading>
</div>
