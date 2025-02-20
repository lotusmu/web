<?php

namespace App\Actions\Ticket;

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CreateTicket
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function handle(array $data, int $userId): Ticket
    {
        $this->ensureIsNotRateLimited($userId);

        $ticket = Ticket::create([
            'user_id' => $userId,
            'title' => $data['title'],
            'ticket_category_id' => $data['ticket_category_id'],
            'priority' => $data['priority'],
            'description' => $data['description'],
            'contact_discord' => $data['contact_discord'] ?? null,
            'status' => TicketStatus::NEW,
        ]);

        RateLimiter::hit($this->throttleKey($userId));

        return $ticket;
    }

    private function throttleKey(int $userId): string
    {
        return 'ticket-create:'.$userId;
    }

    private function ensureIsNotRateLimited(int $userId): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($userId), self::MAX_ATTEMPTS)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($userId));

        throw ValidationException::withMessages([
            'title' => __('Too many tickets created. Please wait :minutes minutes.', [
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
