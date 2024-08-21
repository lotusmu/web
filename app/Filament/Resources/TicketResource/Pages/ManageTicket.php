<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource;
use App\Models\TicketReply;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
        $this->form->fill($this->record->toArray());
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->model($this->record),
            'replyForm' => $this->makeForm()
                ->schema($this->getReplyFormSchema())
                ->statePath('replyData'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Content')
                ->columnSpan(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('description')
                        ->required(),
                ]),
            Section::make('Status')
                ->columnSpan(1)
                ->schema([
                    Select::make('ticket_category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->required(),
                    Select::make('status')
                        ->options(TicketStatus::class)
                        ->enum(TicketStatus::class)
                        ->required(),
                    Select::make('priority')
                        ->options(TicketPriority::class)
                        ->enum(TicketPriority::class)
                        ->required(),
                    TextInput::make('user_id')
                        ->required(),
                ]),
        ];
    }

    protected function getReplyFormSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label('Reply')
                ->required(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $this->record->update($data);

        Notification::make()->success()->title('Success!')
            ->body('Ticket updated successfully.')
            ->send();
    }

    public function addReply(): void
    {
        $data = $this->replyForm->getState();

        TicketReply::create([
            'content' => $data['content'],
            'user_id' => Auth::id(),
            'ticket_id' => $this->record->id,
        ]);

        $this->replyForm->fill();

        Notification::make()->success()->title('Success!')
            ->body('Reply was sent successfully.')
            ->send();
    }

    public function getViewData(): array
    {
        return [
            'replies' => $this->record->replies()->with('user')->orderBy('created_at', 'asc')->get(),
        ];
    }
}
