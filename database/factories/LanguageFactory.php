<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Language::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->languageCode,
            'icon' => $this->faker->imageUrl(50, 50, 'flags'),
        ];
    }
}
