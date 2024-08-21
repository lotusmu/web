<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource;
use App\Models\TicketReply;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\Auth;

class ManageTicket extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists, InteractsWithRecord;

    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.manage-ticket';

    public ?array $data = [];

    public ?array $replyData = [];

    public function __construct()
    {
        $this->initializeReplyForm();
    }

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->form->fill($this->record->attributesToArray());
    }

    public function ticketInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Section::make($this->record->title)
                    ->columns(4)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Author')
                            ->copyable()
                            ->icon('heroicon-o-clipboard-document-list')
                            ->iconPosition(IconPosition::After),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('priority')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('description')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'prose dark:prose-invert'])
                            ->html(),
                    ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                    ->options(TicketStatus::class)
                    ->enum(TicketStatus::class)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->updateStatus($state);
                    }),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    protected function initializeReplyForm(): void
    {
        $this->replyForm = $this->makeForm()
            ->schema([
                RichEditor::make('content')
                    ->label('')
                    ->required(),
            ])
            ->statePath('replyData');
    }

    public function updateStatus($newStatus): void
    {
        $this->record->update(['status' => $newStatus]);

        Notification::make()->success()->title('Success!')
            ->body('Ticket status updated successfully.')
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

        Notification::make()->success()->title('Success!')
            ->body('Reply was sent successfully.')
            ->send();
    }

    public function getViewData(): array
    {
        return [
            'ticket' => $this->record,
            'replies' => $this->record->replies()->with('user')->orderBy('created_at', 'asc')->get(),
        ];
    }
}
