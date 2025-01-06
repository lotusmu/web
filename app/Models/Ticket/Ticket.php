<?php

namespace App\Models\Ticket;

use App\Enums\Ticket\TicketPriority;
use App\Enums\Ticket\TicketStatus;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Flux;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'description',
        'contact_discord',
        'status',
        'priority',
        'ticket_category_id',
        'user_id',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
    ];

    public function truncatedTitle($limit = 45): string
    {
        return Str::limit($this->title, $limit, '...');
    }

    public function markAsResolved(): void
    {
        $this->update(['status' => TicketStatus::RESOLVED]);

        $this->logStatusChange('resolved');

        Flux::toast(
            text: __('The ticket has been marked as resolved. Thank you for your patience.'),
            heading: __('Ticket Resolved'),
            variant: 'success'
        );
    }

    public function reopenTicket(): void
    {
        $this->update(['status' => TicketStatus::IN_PROGRESS]);

        $this->logStatusChange('reopened');

        Flux::toast(
            text: __('We\'re on it! We\'ll reach out about this ticket as soon as possible.'),
            heading: __('Ticket Reopened'),
            variant: 'success'
        );
    }

    private function logStatusChange(string $action): void
    {
        activity('ticket_status')
            ->causedBy(Auth::user())
            ->withProperties([
                'ticket_id' => $this->id,
                'ticket_title' => $this->title,
                'new_status' => $this->status->value,
                'action' => $action,
                ...IdentityProperties::capture(),
            ])
            ->log('":properties.ticket_title" '.$action.' by user.');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}
