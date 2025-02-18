<?php

namespace App\Livewire\Components;

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<flux:navmenu.item icon="arrow-right-start-on-rectangle" wire:click="logout">
    {{ __('Logout') }}
</flux:navmenu.item>
