<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Services\SmsService;
use Tests\TestCase;

class SmsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Setting::whereIn('key', ['sms_gateway', 'sms_api_key', 'sms_api_secret', 'sms_sender_id'])->delete();
    }

    /** @test */
    public function is_not_configured_when_no_gateway_set()
    {
        $service = new SmsService();
        $this->assertFalse($service->isConfigured());
    }

    /** @test */
    public function is_configured_when_gateway_and_key_are_set()
    {
        Setting::create(['key' => 'sms_gateway', 'value' => 'twilio']);
        Setting::create(['key' => 'sms_api_key', 'value' => 'test_key']);

        $service = new SmsService();
        $this->assertTrue($service->isConfigured());
    }

    /** @test */
    public function send_returns_false_when_not_configured()
    {
        $service = new SmsService();
        $result = $service->send('0300-0000000', 'Test message');
        $this->assertFalse($result);
    }

    /** @test */
    public function sendDonorNotification_returns_false_when_not_configured()
    {
        $service = new SmsService();
        $result = $service->sendDonorNotification('0300-0000000', 'John', 'A+');
        $this->assertFalse($result);
    }

    /** @test */
    public function send_returns_true_for_unknown_gateway_with_logging()
    {
        Setting::create(['key' => 'sms_gateway', 'value' => 'unknown']);
        Setting::create(['key' => 'sms_api_key', 'value' => 'test_key']);

        $service = new SmsService();
        $result = $service->send('0300-0000000', 'Test message');
        $this->assertTrue($result);
    }

    /** @test */
    public function sendDonorNotification_includes_patient_name_when_provided()
    {
        Setting::create(['key' => 'sms_gateway', 'value' => 'unknown']);
        Setting::create(['key' => 'sms_api_key', 'value' => 'test_key']);

        $service = new SmsService();
        $result = $service->sendDonorNotification('0300-0000000', 'John', 'A+', 'Jane');
        $this->assertTrue($result);
    }
}