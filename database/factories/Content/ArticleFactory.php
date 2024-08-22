<?php

namespace Database\Factories\Content;

use App\Enums\ArticleType;
use App\Models\Content\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        $locales = config('locales.available');

        return [
            'title' => $this->generateTranslations($locales, fn () => $this->faker->sentence()),
            'content' => $this->generateTranslations($locales, fn () => $this->faker->paragraphs(3, true)),
            'excerpt' => $this->generateTranslations($locales, fn () => Str::limit($this->faker->paragraph(), 95, '...')),
            'type' => $this->faker->randomElement(ArticleType::cases()),
            'is_published' => $this->faker->boolean(),
        ];
    }

    protected function generateTranslations(array $locales, callable $generator): array
    {
        return array_combine($locales, array_map($generator, $locales));
    }

    public function published(): ArticleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
            ];
        });
    }

    public function archived(): ArticleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => false,
            ];
        });
    }
}
