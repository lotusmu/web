<?php

namespace Database\Factories;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketReply;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketReply>
 */
class TicketReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'ticket_id' => Ticket::factory(),
        ];
    }
}
