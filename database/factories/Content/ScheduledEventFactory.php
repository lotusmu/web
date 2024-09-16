<?php

namespace Database\Factories\Content;

use App\Enums\Game\ScheduledEventType;
use App\Models\Content\ScheduledEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledEventFactory extends Factory
{
    protected $model = ScheduledEvent::class;

    public function definition(): array
    {
        $recurrenceType = $this->faker->randomElement(['daily', 'weekly', 'interval']);

        return [
            'name' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(ScheduledEventType::cases()),
            'recurrence_type' => $recurrenceType,
            'schedule' => $this->generateSchedule($recurrenceType),
            'interval_minutes' => $recurrenceType === 'interval' ? $this->faker->numberBetween(1, 1440) : null,
            'is_active' => $this->faker->boolean(),
            'sort_order' => $this->faker->unique()->numberBetween(1, 100),
        ];
    }

    private function generateSchedule($recurrenceType): array
    {
        return match ($recurrenceType) {
            'interval', 'daily' => [['time' => $this->faker->time('H:i')]],
            'weekly' => [
                [
                    'day' => $this->faker->randomElement([
                        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
                    ]),
                    'time' => $this->faker->time('H:i'),
                ],
            ],
            default => [],
        };
    }

    public function daily(): ScheduledEventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'recurrence_type' => 'daily',
                'schedule' => $this->generateSchedule('daily'),
                'interval_minutes' => null,
            ];
        });
    }

    public function weekly(): ScheduledEventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'recurrence_type' => 'weekly',
                'schedule' => $this->generateSchedule('weekly'),
                'interval_minutes' => null,
            ];
        });
    }

    public function interval(): ScheduledEventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'recurrence_type' => 'interval',
                'schedule' => $this->generateSchedule('interval'),
                'interval_minutes' => $this->faker->numberBetween(1, 1440),
            ];
        });
    }

    public function active(): ScheduledEventFactory
    {
        return $this->state(function (array $attributes) {
            return ['is_active' => true];
        });
    }

    public function inactive(): ScheduledEventFactory
    {
        return $this->state(function (array $attributes) {
            return ['is_active' => false];
        });
    }
}
