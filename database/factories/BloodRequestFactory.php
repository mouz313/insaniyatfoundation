<?php

namespace Database\Factories;

use App\Models\BloodRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class BloodRequestFactory extends Factory
{
    protected $model = BloodRequest::class;

    public function definition(): array
    {
        return [
            'patient_name' => fake()->name(),
            'hospital' => fake()->company() . ' Hospital',
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'units_required' => fake()->numberBetween(1, 5),
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'status' => 'pending',
        ];
    }
}
