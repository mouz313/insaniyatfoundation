<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $defaults = [
            'hero_tagline' => 'Save Lives',
            'hero_sub_tagline' => 'Every Drop Counts',
            'hero_description' => 'Join us in our mission to ensure safe blood is available to everyone who needs it. Your donation can save up to three lives.',
            'hero_cta_text' => 'Donate Blood',
            'hero_cta_url' => '/portal/register',
            'hero_secondary_cta' => 'Learn More',
            'hero_stat_1_value' => '5000+',
            'hero_stat_1_label' => 'Lives Saved',
            'hero_stat_2_value' => '2000+',
            'hero_stat_2_label' => 'Donors Registered',
            'hero_stat_3_value' => '150+',
            'hero_stat_3_label' => 'Drives Organized',
            'stat_donors_count' => '2500',
            'stat_lives_saved' => '7500',
            'stat_drives_held' => '150',
            'stat_cities_covered' => '25',
            'about_eyebrow' => 'Who We Are',
            'about_heading' => 'Making Blood Donation Accessible for Everyone',
            'about_body' => 'Founded with a vision to bridge the gap between blood donors and those in need, our organization has been at the forefront of voluntary blood donation. We work tirelessly to ensure every hospital and patient has access to safe, tested blood when they need it most.',
            'about_founded_year' => '2018',
            'about_mission' => 'To eliminate blood shortages by building a network of committed voluntary donors and creating awareness about the importance of regular blood donation.',
            'about_cta_text' => 'Our Story',
            'blood_section_heading' => 'Blood Groups Availability',
            'campaigns_section_heading' => 'Upcoming Blood Drives',
            'how_section_heading' => 'How It Works',
            'how_step_1_icon' => 'fas fa-user-plus',
            'how_step_1_title' => 'Register',
            'how_step_1_desc' => 'Sign up as a donor with your basic information and medical details.',
            'how_step_2_icon' => 'fas fa-calendar-check',
            'how_step_2_title' => 'Get Notified',
            'how_step_2_desc' => 'Receive alerts when blood of your type is needed near you.',
            'how_step_3_icon' => 'fas fa-tint',
            'how_step_3_title' => 'Donate',
            'how_step_3_desc' => 'Visit a nearby drive or center. The process takes just 30 minutes.',
            'how_step_4_icon' => 'fas fa-heart',
            'how_step_4_title' => 'Save Lives',
            'how_step_4_desc' => 'Your donation is tested, processed, and delivered to patients in need.',
            'testimonials_section_heading' => 'Hear From Our Heroes',
            'cta_heading' => 'Ready to Make a Difference?',
            'cta_subheading' => 'Join thousands of donors saving lives every day',
            'cta_donate_btn_text' => 'Donate Blood Now',
            'cta_register_btn_text' => 'Register as Donor',
            'cta_phone' => '+92-300-1234567',
            'cta_whatsapp' => '+92-300-1234567',
            'cta_email' => 'info@blooddonor.org',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('landing_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_settings');
    }
};
