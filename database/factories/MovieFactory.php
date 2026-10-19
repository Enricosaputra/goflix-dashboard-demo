<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'language_id' => Language::factory(),
            'release_year' => 2024,
            'duration_minutes' => 100,
            'is_premium' => false,
            'is_active' => true,
        ];
    }

    public function premium(?int $rentPrice = null): static
    {
        return $this->state(['is_premium' => true, 'rent_price' => $rentPrice, 'rent_days' => 2]);
    }
}
