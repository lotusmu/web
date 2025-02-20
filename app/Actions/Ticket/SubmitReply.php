<?php

namespace App\Actions\Ticket;

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class SubmitReply
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function handle(Ticket $ticket, int $userId, string $content): TicketReply
    {
        $ticket->load(['replies.user']);

        $this->ensureIsNotRateLimited($userId);

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

    private function ensureIsNotRateLimited(int $userId): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS, self::DECAY_SECONDS)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($userId));

        throw ValidationException::withMessages([
            'content' => __('Too many replies submitted. Please wait :seconds seconds.', [
                'seconds' => $seconds,
            ]),
        ]);
    }
}
