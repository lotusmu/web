<?php

namespace App\Livewire\Pages\App\Support;

use App\Actions\Ticket\CreateTicket;
use App\Actions\User\SendNotification;
use App\Enums\Ticket\TicketPriority;
use App\Livewire\BaseComponent;
use App\Models\Ticket\TicketCategory;
use Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class CreateSupportTicket extends BaseComponent
{
    public ?string $title = '';

    public ?int $ticket_category_id = null;

    public ?string $priority = null;

    public ?string $description = '';

    public ?string $contact_discord = null;

    #[Computed]
    public function categories()
    {
        return Cache::remember('ticket_categories', now()->addDay(), function () {
            return TicketCategory::select('id', 'name')->orderBy('name')->get();
        });
    }

    public function create(CreateTicket $action)
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'priority' => 'required|in:'.implode(',', array_column(TicketPriority::cases(), 'value')),
            'description' => 'required|string|max:16777215',
            'contact_discord' => 'nullable|string|max:255',
        ]);

        if ($ticket = $action->handle([
            'title' => $this->title,
            'ticket_category_id' => $this->ticket_category_id,
            'priority' => $this->priority,
            'description' => $this->description,
            'contact_discord' => $this->contact_discord,
        ], Auth::id())
        ) {

            SendNotification::make('New Support Ticket')
                ->body('A new ticket has been created: :title', [
                    'title' => $this->title,
                ])
                ->action('View Ticket', '/admin/tickets/'.$ticket->id.'/manage')
                ->sendToAdmins();

            Flux::toast(
                text: __('Ticket created successfully.'),
                heading: __('Success'),
                variant: 'success'
            );

            return $this->redirect(route('support'), navigate: true);
        }
    }

    protected function getViewName(): string
    {
        return 'pages.app.support.create-ticket';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
