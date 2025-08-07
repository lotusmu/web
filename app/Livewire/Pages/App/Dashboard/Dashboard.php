<?php

namespace App\Livewire\Pages\App\Dashboard;

use App\Livewire\BaseComponent;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;

class Dashboard extends BaseComponent
{
    public $sortBy = 'ResetCount';

    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function characters()
    {
        return Character::query()
            ->with('guildMember')
            ->select('Name', 'cLevel', 'ResetCount', 'Class', 'PkCount')
            ->where('AccountID', auth()->user()->name)
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->get();
    }

    protected function getViewName(): string
    {
        return 'pages.app.dashboard.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
