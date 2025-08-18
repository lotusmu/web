<?php

namespace App\Livewire\Pages\App\Dashboard;

use App\Enums\Game\AccountLevel;
use App\Livewire\BaseComponent;
use App\Models\User\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class Card extends BaseComponent
{
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    #[Computed]
    public function resources(): object
    {
        return (object) [
            'tokens' => $this->user->tokens->format(),
            'credits' => $this->user->credits->format(),
            'zen' => $this->user->zen->format(),
        ];
    }

    #[Computed]
    public function accountLevel(): ?array
    {
        $level = $this->user->member->AccountLevel;
        if ($level === AccountLevel::Regular) {
            return null;
        }

        return [
            'label' => $this->user->member->AccountLevel->getLabel(),
            'color' => $this->user->member->AccountLevel->badgeColor(),
        ];
    }

    #[On('resourcesUpdated')]
    public function onResourcesUpdated(): void
    {
        $this->user->refresh();
    }

    protected function getViewName(): string
    {
        return 'pages.app.dashboard.card';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
