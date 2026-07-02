<?php

namespace Tests\Feature;

use App\Models\Donor;
use App\Models\City;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DonorManagementTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_donor_registration_generates_registration_no()
    {
        $city = City::factory()->create(['name' => 'Lahore']);

        $response = $this->actingAs($this->admin)->post(route('admin.donors.store'), [
            'name' => 'Usman Shahid',
            'father_name' => 'Shahid Mahmood',
            'cnic' => '35201-1234567-1',
            'phone' => '0300-1234567',
            'blood_group' => 'B+',
            'city_id' => $city->id,
            'status' => 'active',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('donors', [
            'name' => 'Usman Shahid',
            'cnic' => '35201-1234567-1',
        ]);

        $donor = Donor::where('cnic', '35201-1234567-1')->first();
        $this->assertNotNull($donor->registration_no);
    }

    public function test_duplicate_detection_by_cnic()
    {
        Donor::factory()->create(['cnic' => '35201-1234567-1']);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.donors.check-duplicate', ['cnic' => '35201-1234567-1']));

        $response->assertOk()
            ->assertJson(['duplicate' => true]);
    }

    public function test_duplicate_detection_returns_false_when_no_match()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.donors.check-duplicate', ['cnic' => '00000-0000000-0']));

        $response->assertOk()
            ->assertJson(['duplicate' => false]);
    }

    public function test_donor_requires_name_and_blood_group()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.donors.store'), [
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['name', 'blood_group']);
    }

    public function test_university_required_when_is_student()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.donors.store'), [
            'name' => 'Student Donor',
            'cnic' => '35201-7654321-1',
            'phone' => '0300-7654321',
            'blood_group' => 'O+',
            'status' => 'active',
            'is_student' => true,
        ]);

        $response->assertSessionHasErrors(['university_name']);
    }

    public function test_donor_index_page_loads()
    {
        Donor::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.donors.index'));

        $response->assertOk();
    }

    public function test_donor_show_page_shows_eligibility()
    {
        $donor = Donor::factory()->create([
            'dob' => now()->subYears(25),
            'weight' => 70,
            'hemoglobin' => 14.0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.donors.show', $donor));

        $response->assertOk();
        $response->assertSee('Eligible');
    }

    public function test_dashboard_page_loads()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_donor_certificate_requires_completed_donation()
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.donors.certificate', $donor));

        $response->assertSessionHas('error');
    }
}
