<?php

namespace Tests\Unit;

use App\Models\BloodRequest;
use App\Models\City;
use App\Models\Donor;
use App\Services\MatchingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MatchingServiceTest extends TestCase
{
    use DatabaseTransactions;

    private MatchingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MatchingService();
    }

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

    private function makeBloodRequest(array $attrs = []): BloodRequest
    {
        $request = new BloodRequest();
        foreach ($attrs as $key => $value) {
            $request->$key = $value;
        }
        return $request;
    }

    /** @test */
    public function exact_blood_group_match_scores_30()
    {
        $score = $this->service->bloodGroupScore('A+', 'A+');
        $this->assertEquals(30, $score);
    }

    /** @test */
    public function compatible_blood_group_scores_21()
    {
        $score = $this->service->bloodGroupScore('A-', 'A+');
        $this->assertEquals(21, $score);
    }

    /** @test */
    public function incompatible_blood_group_scores_0()
    {
        $score = $this->service->bloodGroupScore('B+', 'A+');
        $this->assertEquals(0, $score);
    }

    /** @test */
    public function o_negative_is_universal_donor()
    {
        foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group) {
            $score = $this->service->bloodGroupScore('O-', $group);
            $this->assertEquals(21, $score, "O- should be compatible with {$group}");
        }
    }

    /** @test */
    public function ab_positive_is_universal_recipient()
    {
        foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group) {
            $score = $this->service->bloodGroupScore($group, 'AB+');
            $this->assertEquals(21, $score, "{$group} should be compatible with AB+");
        }
    }

    /** @test */
    public function same_city_scores_20()
    {
        $score = $this->service->cityScore(1, 1);
        $this->assertEquals(20, $score);
    }

    /** @test */
    public function different_city_scores_0()
    {
        $score = $this->service->cityScore(1, 2);
        $this->assertEquals(0, $score);
    }

    /** @test */
    public function null_city_scores_0()
    {
        $this->assertEquals(0, $this->service->cityScore(null, 1));
        $this->assertEquals(0, $this->service->cityScore(1, null));
        $this->assertEquals(0, $this->service->cityScore(null, null));
    }

    /** @test */
    public function reliability_scores_20_when_donor_has_donations()
    {
        $donor = $this->makeDonor(['total_donations' => 5]);
        $this->assertEquals(20, $this->service->reliabilityScore($donor));
    }

    /** @test */
    public function reliability_scores_0_when_no_donations()
    {
        $donor = $this->makeDonor(['total_donations' => 0]);
        $this->assertEquals(0, $this->service->reliabilityScore($donor));
    }

    /** @test */
    public function days_since_donation_scores_15_when_no_prior_donation()
    {
        $donor = $this->makeDonor(['last_donation_date' => null]);
        $this->assertEquals(15, $this->service->daysSinceDonationScore($donor));
    }

    /** @test */
    public function days_since_donation_scores_15_when_over_730_days()
    {
        $donor = $this->makeDonor([
            'last_donation_date' => Carbon::now()->subYears(3),
        ]);
        $this->assertEquals(15, $this->service->daysSinceDonationScore($donor));
    }

    /** @test */
    public function days_since_donation_scores_0_when_within_90_days()
    {
        $donor = $this->makeDonor([
            'last_donation_date' => Carbon::now()->subDays(30),
        ]);
        $this->assertEquals(0, $this->service->daysSinceDonationScore($donor));
    }

    /** @test */
    public function days_since_donation_scales_between_90_and_730_days()
    {
        $donor = $this->makeDonor([
            'last_donation_date' => Carbon::now()->subDays(365),
        ]);
        $score = $this->service->daysSinceDonationScore($donor);
        $this->assertGreaterThan(0, $score);
        $this->assertLessThan(15, $score);
        $this->assertEquals(7, $score);
    }

    /** @test */
    public function donation_history_caps_at_15()
    {
        $donor = $this->makeDonor(['total_donations' => 100]);
        $this->assertEquals(15, $this->service->donationHistoryScore($donor));
    }

    /** @test */
    public function donation_history_scales_with_donations()
    {
        $donor = $this->makeDonor(['total_donations' => 3]);
        $this->assertEquals(9, $this->service->donationHistoryScore($donor));
    }

    /** @test */
    public function donation_history_scores_0_when_no_donations()
    {
        $donor = $this->makeDonor(['total_donations' => 0]);
        $this->assertEquals(0, $this->service->donationHistoryScore($donor));
    }

    /** @test */
    public function calculate_scores_returns_all_dimensions()
    {
        $donor = $this->makeDonor([
            'blood_group' => 'A+',
            'city_id' => 1,
            'total_donations' => 5,
            'last_donation_date' => Carbon::now()->subYear(),
            'status' => 'active',
        ]);
        $request = $this->makeBloodRequest([
            'blood_group' => 'A+',
            'city_id' => 1,
        ]);

        $scores = $this->service->calculateScores($donor, $request);

        $this->assertArrayHasKey('blood_group', $scores);
        $this->assertArrayHasKey('same_city', $scores);
        $this->assertArrayHasKey('reliability', $scores);
        $this->assertArrayHasKey('days_since_donation', $scores);
        $this->assertArrayHasKey('donation_history', $scores);
        $this->assertArrayHasKey('total', $scores);
        $this->assertEquals(30 + 20 + 20 + 7 + 15, $scores['total']);
    }

    /** @test】
    public function findMatches_returns_only_eligible_donors()
    {
        $city = City::factory()->create();
        $eligible = Donor::factory()->create([
            'blood_group' => 'A+',
            'city_id' => $city->id,
            'status' => 'active',
            'last_donation_date' => null,
        ]);
        $inactive = Donor::factory()->create([
            'blood_group' => 'A+',
            'city_id' => $city->id,
            'status' => 'inactive',
        ]);
        $recentDonation = Donor::factory()->create([
            'blood_group' => 'A+',
            'city_id' => $city->id,
            'status' => 'active',
            'last_donation_date' => Carbon::now()->subDays(30),
        ]);

        $request = BloodRequest::create([
            'patient_name' => 'Test Patient',
            'hospital' => 'Test Hospital',
            'blood_group' => 'A+',
            'city_id' => $city->id,
            'units_required' => 2,
            'contact_name' => 'Contact',
            'contact_phone' => '0300-0000000',
            'status' => 'pending',
        ]);

        $matches = $this->service->findMatches($request);

        $this->assertTrue($matches->contains('id', $eligible->id), 'Eligible donor should be in matches');
        $this->assertFalse($matches->contains('id', $inactive->id), 'Inactive donor should not match');
        $this->assertFalse($matches->contains('id', $recentDonation->id), 'Recent donation donor should not match');
    }

    /** @test */
    public function findMatches_sorts_by_score_descending()
    {
        $city = City::factory()->create();
        $best = Donor::factory()->create([
            'blood_group' => 'A+',
            'city_id' => $city->id,
            'status' => 'active',
            'total_donations' => 10,
        ]);
        $worst = Donor::factory()->create([
            'blood_group' => 'A-',
            'city_id' => $city->id,
            'status' => 'active',
            'total_donations' => 0,
        ]);

        $request = BloodRequest::create([
            'patient_name' => 'Test Patient',
            'hospital' => 'Test Hospital',
            'blood_group' => 'A+',
            'city_id' => $city->id,
            'units_required' => 2,
            'contact_name' => 'Contact',
            'contact_phone' => '0300-0000000',
            'status' => 'pending',
        ]);

        $matches = $this->service->findMatches($request);

        $this->assertGreaterThan($matches->firstWhere('id', $worst->id)->match_score, $matches->firstWhere('id', $best->id)->match_score);
    }
}