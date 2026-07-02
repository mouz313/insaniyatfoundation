<?php

namespace Database\Factories;

use App\Models\Donor;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonorFactory extends Factory
{
    protected $model = Donor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'father_name' => fake()->name('male'),
            'cnic' => fake()->numerify('#####-#######-#'),
            'phone' => fake()->numerify('03##-#######'),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'status' => 'active',
            'city_id' => City::factory(),
            'registration_no' => strtoupper(fake()->lexify('???-??-?????')),
        ];
    }
}
