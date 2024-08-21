<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\TicketReply;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManageTicket extends Page
{
    use InteractsWithRecord;

    public string $replyContent = '';

    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.manage-ticket';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getViewData(): array
    {
        return [
            'ticket' => $this->record,
            'replies' => $this->record->replies()->with('user')->orderBy('created_at', 'desc')->get(),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            RichEditor::make('replyContent')
                ->label('Reply')
                ->required(),
        ];
    }

    public function addReply(): void
    {
        $this->validate();

        TicketReply::create([
            'content' => $this->replyContent,
            'user_id' => Auth::id(),
            'ticket_id' => $this->ticket->id,
        ]);

        $this->reset('replyContent');
        $this->notify('success', 'Reply added successfully.');
    }
}
