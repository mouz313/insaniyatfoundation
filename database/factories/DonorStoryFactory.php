<?php

namespace Database\Factories;

use App\Models\DonorStory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonorStoryFactory extends Factory
{
    protected $model = DonorStory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'quote' => fake()->realText(150),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'city' => fake()->city(),
            'donations_count' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
