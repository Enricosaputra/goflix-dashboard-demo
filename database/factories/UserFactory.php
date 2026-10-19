<?php

namespace Database\Factories;

use App\Enums\LoginType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => fake()->name(), 'email' => fake()->unique()->safeEmail(), 'login_type' => LoginType::Google, 'is_active' => true];
    }

    public function subscribedUntil(string $date): static
    {
        return $this->state(['subscription_ends_on' => $date]);
    }
}
