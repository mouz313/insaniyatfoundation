<?php

namespace App\Services;

use App\Models\Setting;

class SmsService
{
    protected ?string $gateway;
    protected ?string $apiKey;
    protected ?string $apiSecret;
    protected ?string $senderId;

    public function __construct()
    {
        $this->gateway = Setting::where('key', 'sms_gateway')->value('value');
        $this->apiKey = Setting::where('key', 'sms_api_key')->value('value');
        $this->apiSecret = Setting::where('key', 'sms_api_secret')->value('value');
        $this->senderId = Setting::where('key', 'sms_sender_id')->value('value');
    }

    public function isConfigured(): bool
    {
        return !empty($this->gateway) && !empty($this->apiKey);
    }

    public function send(string $phone, string $message): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        // Placeholder for SMS gateway integration
        // Supported gateways: Twilio, BulkSMS.pk, Zong/Telenor API
        switch ($this->gateway) {
            case 'twilio':
                return $this->sendViaTwilio($phone, $message);
            case 'bulksms':
                return $this->sendViaBulkSms($phone, $message);
            default:
                // Log the message for now
                logger()->info("SMS to {$phone}: {$message}");
                return true;
        }
    }

    public function sendDonorNotification(string $phone, string $donorName, string $bloodGroup, ?string $patientName = null): bool
    {
        $message = "Dear {$donorName}, you are requested for blood donation ({$bloodGroup}).";
        if ($patientName) {
            $message .= " Patient: {$patientName}.";
        }
        $message .= " Please contact the nearest blood bank. - " . (Setting::where('key', 'ngo_name')->value('value') ?? 'Blood Donation NGO');

        return $this->send($phone, $message);
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        // TODO: Implement Twilio SMS integration
        // $twilio = new \Twilio\Rest\Client($this->apiKey, $this->apiSecret);
        // $twilio->messages->create($phone, ['from' => $this->senderId, 'body' => $message]);
        logger()->info("Twilio SMS to {$phone}: {$message}");
        return true;
    }

    protected function sendViaBulkSms(string $phone, string $message): bool
    {
        // TODO: Implement BulkSMS.pk integration
        // $url = "https://bulksms.pk/api/send?api_key={$this->apiKey}&mobile={$phone}&message=" . urlencode($message) . "&sender={$this->senderId}";
        // Http::get($url);
        logger()->info("BulkSMS to {$phone}: {$message}");
        return true;
    }
}
