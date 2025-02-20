<?php

namespace App\Actions\Ticket;

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;

class SubmitReply
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function handle(Ticket $ticket, int $userId, string $content): ?TicketReply
    {
        if (! Gate::allows('reply', $ticket)) {
            Flux::toast(
                text: __('You do not have permission to reply to this ticket.'),
                heading: __('Permission Denied'),
                variant: 'danger'
            );

            return null;
        }

        $ticket->load(['replies.user']);

        if (! $this->ensureIsNotRateLimited($userId)) {
            return null;
        }

        $reply = $ticket->replies()->create([
            'user_id' => $userId,
            'content' => $content,
        ]);

        $ticket->update(['status' => TicketStatus::IN_PROGRESS]);

        RateLimiter::hit($this->throttleKey($userId));

        return $reply;
    }

    private function throttleKey(int $userId): string
    {
        return 'ticket-reply:'.$userId;
    }

    private function ensureIsNotRateLimited(int $userId): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($userId));

        Flux::toast(
            text: __('Too many replies submitted. Please wait :seconds seconds.', ['seconds' => $seconds]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }
}
