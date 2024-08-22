<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(TicketStatus::cases())->value,
            'priority' => $this->faker->randomElement(TicketPriority::cases())->value,
            'ticket_category_id' => TicketCategory::factory(),
            'user_id' => User::factory(),
        ];
    }
}
