<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => fake()->unique()->languageCode(), 'is_active' => true];
    }
}
