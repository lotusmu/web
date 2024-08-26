<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource;
use App\Models\Ticket\TicketReply;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
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

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->form->fill();
    }

    public function ticketInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Section::make($this->record->title)
                    ->collapsible()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Author')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->iconPosition(IconPosition::After)
                            ->url(fn ($record) => route('filament.admin.resources.members.edit', ['record' => $record->user->name]))
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
                RichEditor::make('content')
                    ->label('')
                    ->disableToolbarButtons([
                        'h2',
                        'h3',
                    ])
                    ->required(),
            ])
            ->statePath('replyData');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('changeStatus')
                ->label('Change Status')
                ->form([
                    Select::make('status')
                        ->options(TicketStatus::class)
                        ->enum(TicketStatus::class)
                        ->afterStateUpdated(function ($state, Set $set) {
                            $this->record->status = $state;
                            $this->record->save();

                            Notification::make()
                                ->success()
                                ->title('Success!')
                                ->body('Ticket status updated successfully.')
                                ->send();
                        }),
                ]),
        ];
    }

    public function addReply(): void
    {
        $data = $this->form->getState();

        $this->form->validate();

        TicketReply::create([
            'content' => $data['content'],
            'user_id' => Auth::id(),
            'ticket_id' => $this->record->id,
        ]);

        $this->form->fill();

        Notification::make()->success()->title('Success!')
            ->body('Message sent successfully.')
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
