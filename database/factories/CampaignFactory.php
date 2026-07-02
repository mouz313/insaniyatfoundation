<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'venue' => fake()->address(),
            'target_units' => fake()->numberBetween(10, 100),
            'description' => fake()->paragraph(),
        ];
    }
}
