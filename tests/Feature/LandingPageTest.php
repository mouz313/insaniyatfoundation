<?php

namespace Tests\Feature;

use App\Models\DonorStory;
use App\Models\Campaign;
use App\Models\Donor;
use App\Models\City;
use App\Models\BloodInventory;
use App\Models\BloodDonation;
use App\Models\LandingSetting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LandingPageTest extends TestCase
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

    public function test_landing_page_loads()
    {
        $response = $this->get(route('portal.landing'));
        $response->assertOk();
    }

    public function test_landing_page_shows_hero_section()
    {
        LandingSetting::set('hero_tagline', 'Test Tagline');
        LandingSetting::set('hero_sub_tagline', 'Test Heading');

        $response = $this->get(route('portal.landing'));
        $response->assertSee('Test Tagline');
        $response->assertSee('Test Heading');
    }

    public function test_landing_page_shows_active_stories()
    {
        $story = DonorStory::factory()->create([
            'name' => 'Test Donor',
            'quote' => 'This is a test quote',
            'is_active' => true,
        ]);

        $response = $this->get(route('portal.landing'));
        $response->assertSee('Test Donor');
        $response->assertSee('This is a test quote');
    }

    public function test_landing_page_hides_inactive_stories()
    {
        $story = DonorStory::factory()->create([
            'name' => 'Hidden Donor',
            'quote' => 'Should not appear',
            'is_active' => false,
        ]);

        $response = $this->get(route('portal.landing'));
        $response->assertDontSee('Hidden Donor');
    }

    public function test_landing_page_shows_featured_campaigns()
    {
        $city = City::factory()->create();
        $campaign = Campaign::factory()->create([
            'name' => 'Featured Drive',
            'date' => now()->addDays(10),
            'is_featured' => true,
        ]);

        $response = $this->get(route('portal.landing'));
        $response->assertSee('Featured Drive');
    }

    public function test_landing_page_hides_non_featured_campaigns()
    {
        $city = City::factory()->create();
        $campaign = Campaign::factory()->create([
            'name' => 'Hidden Drive',
            'date' => now()->addDays(10),
            'is_featured' => false,
        ]);

        $response = $this->get(route('portal.landing'));
        $response->assertDontSee('Hidden Drive');
    }

    public function test_landing_page_settings_tab_form_loads()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.landing-page.index'));
        $response->assertOk();
    }

    public function test_landing_page_settings_can_be_saved()
    {
        $response = $this->actingAs($this->admin)->put(route('admin.landing-page.update'), [
            'hero_tagline' => 'Updated Tagline',
            'hero_sub_tagline' => 'Updated Heading',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('Updated Tagline', LandingSetting::get('hero_tagline'));
    }

    public function test_donor_stories_index_loads()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.donor-stories.index'));
        $response->assertOk();
    }

    public function test_donor_story_can_be_created()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.donor-stories.store'), [
            'name' => 'John Doe',
            'quote' => 'I love donating blood!',
            'blood_group' => 'O+',
            'city' => 'Lahore',
            'donations_count' => 5,
            'is_active' => true,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('donor_stories', ['name' => 'John Doe']);
    }

    public function test_donor_story_requires_name_and_quote()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.donor-stories.store'), []);

        $response->assertSessionHasErrors(['name', 'quote']);
    }
}
