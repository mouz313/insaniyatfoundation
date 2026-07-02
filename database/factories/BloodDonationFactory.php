<?php

namespace Database\Factories;

use App\Models\BloodDonation;
use App\Models\Donor;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class BloodDonationFactory extends Factory
{
    protected $model = BloodDonation::class;

    public function definition(): array
    {
        return [
            'donor_id' => Donor::factory(),
            'donation_date' => fake()->date(),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'units' => fake()->numberBetween(1, 3),
            'status' => 'donated',
            'patient_name' => fake()->name(),
        ];
    }

    public function donated()
    {
        return $this->state(fn() => ['status' => 'donated']);
    }
}
