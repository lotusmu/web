<?php

namespace Database\Factories\Content;

use App\Models\Content\Download;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Download>
 */
class DownloadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storageType = $this->faker->randomElement(['local', 'external']);

        return [
            'id' => Str::uuid(),
            'name' => $this->faker->words(3, true),
            'storage_type' => $storageType,
            'local_file' => $storageType === 'local' ? 'downloads/'.$this->faker->word.'.pdf' : null,
            'external_url' => $storageType === 'external' ? $this->faker->url : null,
        ];
    }

    public function local(): DownloadFactory|Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'storage_type' => 'local',
                'local_file' => 'downloads/'.$this->faker->word.'.pdf',
                'external_url' => null,
            ];
        });
    }

    public function external(): DownloadFactory|Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'storage_type' => 'external',
                'local_file' => null,
                'external_url' => $this->faker->url,
            ];
        });
    }
}
