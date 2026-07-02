<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create(['key' => 'ngo_name', 'value' => 'Blood Donation NGO']);
        Setting::create(['key' => 'ngo_address', 'value' => 'Main Street, City']);
        Setting::create(['key' => 'ngo_logo', 'value' => null]);
        Setting::create(['key' => 'blood_groups', 'value' => 'A+,A-,B+,B-,AB+,AB-,O+,O-']);
        Setting::create(['key' => 'donation_cooldown_days', 'value' => '90']);
        Setting::create(['key' => 'sms_gateway', 'value' => 'twilio']);
        Setting::create(['key' => 'sms_api_key', 'value' => null]);
        Setting::create(['key' => 'card_template', 'value' => 'default']);
    }
}
