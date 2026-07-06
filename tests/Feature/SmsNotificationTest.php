<?php

namespace Tests\Feature;

use App\Jobs\SendDonorSmsJob;
use App\Models\BloodRequest;
use App\Models\City;
use App\Models\Donor;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SmsNotificationTest extends TestCase
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

    public function test_registration_dispatches_welcome_sms()
    {
        Queue::fake();

        $city = City::factory()->create(['name' => 'Test City']);

        $response = $this->post(route('portal.registration.store'), [
            'name' => 'Test Donor',
            'father_name' => 'Test Father',
            'cnic' => '12345-1234567-1',
            'phone' => '0300-1111111',
            'blood_group' => 'O+',
            'city_id' => $city->id,
        ]);

        $response->assertSessionHas('success');

        Queue::assertPushed(SendDonorSmsJob::class, function ($job) {
            return str_contains($job->message, 'welcome')
                && str_contains($job->message, 'Test Donor');
        });
    }

    public function test_donor_match_call_log_dispatches_sms()
    {
        Queue::fake();

        $donor = Donor::factory()->create(['phone' => '0300-2222222']);
        $bloodRequest = BloodRequest::factory()->create([
            'blood_group' => 'A+',
            'status' => 'pending',
            'patient_name' => 'Test Patient',
            'hospital' => 'Test Hospital',
            'contact_phone' => '0300-3333333',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.blood-requests.match.call', [$bloodRequest, $donor]),
            ['outcome' => 'donor_found', 'notes' => 'Donor confirmed']
        );

        $response->assertSessionHas('success');

        Queue::assertPushed(SendDonorSmsJob::class, function ($job) use ($donor, $bloodRequest) {
            return $job->phone === $donor->phone
                && str_contains($job->message, $bloodRequest->blood_group)
                && str_contains($job->message, $bloodRequest->hospital);
        });
    }

    public function test_non_match_call_log_does_not_dispatch_sms()
    {
        Queue::fake();

        $donor = Donor::factory()->create();
        $bloodRequest = BloodRequest::factory()->create(['status' => 'pending']);

        $this->actingAs($this->admin)->post(
            route('admin.blood-requests.match.call', [$bloodRequest, $donor]),
            ['outcome' => 'refused', 'notes' => 'Donor refused']
        );

        Queue::assertNotPushed(SendDonorSmsJob::class);
    }
}
