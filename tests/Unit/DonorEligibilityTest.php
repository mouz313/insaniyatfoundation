<?php

namespace Tests\Unit;

use App\Models\Donor;
use Carbon\Carbon;
use Tests\TestCase;

class DonorEligibilityTest extends TestCase
{
    private function makeDonor(array $attrs = []): Donor
    {
        $donor = new Donor();
        foreach ($attrs as $key => $value) {
            $donor->$key = $value;
        }
        if (!isset($attrs['status'])) {
            $donor->status = 'active';
        }
        return $donor;
    }

    public function test_eligible_donor_has_no_reasons()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(25),
            'weight' => 70,
            'hemoglobin' => 14.0,
            'health_flags' => [],
            'status' => 'active',
        ]);

        $this->assertTrue($donor->is_eligible);
        $this->assertEquals('eligible', $donor->eligibility_status);
        $this->assertEmpty($donor->eligibilityReasons());
    }

    public function test_ineligible_when_under_18()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(16),
            'weight' => 70,
            'hemoglobin' => 14.0,
        ]);

        $this->assertFalse($donor->is_eligible);
        $this->assertContains('Under 18 years old', $donor->eligibilityReasons());
    }

    public function test_ineligible_when_over_60()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(65),
            'weight' => 70,
            'hemoglobin' => 14.0,
        ]);

        $this->assertFalse($donor->is_eligible);
        $this->assertContains('Over 60 years old', $donor->eligibilityReasons());
    }

    public function test_ineligible_when_weight_below_45()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(25),
            'weight' => 40,
            'hemoglobin' => 14.0,
        ]);

        $this->assertFalse($donor->is_eligible);
        $this->assertContains('Weight below 45 kg', $donor->eligibilityReasons());
    }

    public function test_ineligible_when_hemoglobin_below_12_5()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(25),
            'weight' => 70,
            'hemoglobin' => 11.0,
        ]);

        $this->assertFalse($donor->is_eligible);
        $this->assertContains('Hemoglobin below 12.5 g/dL', $donor->eligibilityReasons());
    }

    public function test_ineligible_when_health_flag_present()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(25),
            'weight' => 70,
            'hemoglobin' => 14.0,
            'health_flags' => ['pregnant'],
        ]);

        $this->assertFalse($donor->is_eligible);
        $this->assertContains('Currently pregnant', $donor->eligibilityReasons());
    }

    public function test_ineligible_when_status_is_ineligible()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(25),
            'weight' => 70,
            'hemoglobin' => 14.0,
            'status' => 'ineligible',
        ]);

        $this->assertFalse($donor->is_eligible);
        $this->assertEquals('ineligible', $donor->eligibility_status);
        $this->assertContains('Marked as permanently ineligible', $donor->eligibilityReasons());
    }

    public function test_multiple_eligibility_reasons()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(16),
            'weight' => 40,
            'hemoglobin' => 10.0,
        ]);

        $reasons = $donor->eligibilityReasons();
        $this->assertContains('Under 18 years old', $reasons);
        $this->assertContains('Weight below 45 kg', $reasons);
        $this->assertContains('Hemoglobin below 12.5 g/dL', $reasons);
    }

    public function test_reliability_score_zero_with_no_calls()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(25),
            'weight' => 70,
            'hemoglobin' => 14.0,
        ]);

        $this->assertEquals(0, $donor->reliability_score);
    }

    public function test_age_computed_correctly()
    {
        $donor = $this->makeDonor([
            'dob' => Carbon::now()->subYears(30),
        ]);

        $this->assertEquals(30, $donor->age);
    }

    public function test_age_null_when_no_dob()
    {
        $donor = $this->makeDonor([]);
        $this->assertNull($donor->age);
    }

    public function test_next_eligible_date_null_when_no_last_donation()
    {
        $donor = $this->makeDonor([]);
        $this->assertNull($donor->next_eligible_date);
    }

    public function test_next_eligible_date_is_3_months_after_last_donation()
    {
        $donor = $this->makeDonor([
            'last_donation_date' => Carbon::parse('2026-01-15'),
        ]);

        $this->assertNotNull($donor->next_eligible_date);
        $this->assertEquals('2026-04-15', $donor->next_eligible_date->format('Y-m-d'));
    }

    public function test_health_flags_list_returns_array()
    {
        $donor = $this->makeDonor([
            'health_flags' => ['recent_illness', 'medication'],
        ]);

        $this->assertIsArray($donor->health_flags_list);
        $this->assertCount(2, $donor->health_flags_list);
        $this->assertContains('recent_illness', $donor->health_flags_list);
    }
}
