<?php

namespace App\Livewire\Pages\App\Support;

use App\Livewire\BaseComponent;
use App\Models\Ticket\Ticket;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class Support extends BaseComponent
{
    use WithPagination;

    #[Computed]
    public function tickets()
    {
        return Ticket::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(5);
    }

    protected function getViewName(): string
    {
        return 'pages.app.support.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
