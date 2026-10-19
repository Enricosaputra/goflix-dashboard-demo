<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => 'Premium', 'price' => 35000, 'duration_months' => 1, 'max_devices' => 2, 'max_quality' => '1080p', 'is_active' => true];
    }
}
