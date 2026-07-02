<?php

namespace App\Services;

use App\Models\Setting;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Encryption\DecryptException;

class SmsService
{
    protected ?string $gateway;
    protected ?string $apiKey;
    protected ?string $apiSecret;
    protected ?string $senderId;

    public function __construct()
    {
        $this->gateway = Setting::where('key', 'sms_gateway')->value('value');

        $key = Setting::where('key', 'sms_api_key')->value('value');
        $this->apiKey = $this->decryptIfNeeded($key);

        $secret = Setting::where('key', 'sms_api_secret')->value('value');
        $this->apiSecret = $this->decryptIfNeeded($secret);

        $this->senderId = Setting::where('key', 'sms_sender_id')->value('value');
    }

    protected function decryptIfNeeded(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'eyJ')) {
            try {
                return Crypt::decryptString($value);
            } catch (DecryptException) {
                return $value;
            }
        }
        return $value;
    }

    public function isConfigured(): bool
    {
        return !empty($this->gateway) && !empty($this->apiKey);
    }

    public function send(string $phone, string $message): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not sent: gateway not configured', ['phone' => $phone]);
            return false;
        }

        switch ($this->gateway) {
            case 'twilio':
                return $this->sendViaTwilio($phone, $message);
            case 'bulksms':
                return $this->sendViaBulkSms($phone, $message);
            default:
                Log::info("SMS logged (no gateway): to {$phone}: {$message}");
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
        try {
            $client = new TwilioClient($this->apiKey, $this->apiSecret);
            $from = $this->senderId ?? config('services.twilio.from');

            $client->messages->create($phone, [
                'from' => $from,
                'body' => $message,
            ]);

            Log::info("Twilio SMS sent to {$phone}");
            return true;
        } catch (\Exception $e) {
            Log::error("Twilio SMS failed to {$phone}: " . $e->getMessage());
            return false;
        }
    }

    protected function sendViaBulkSms(string $phone, string $message): bool
    {
        try {
            $url = "https://bulksms.pk/api/send";
            $response = Http::get($url, [
                'api_key' => $this->apiKey,
                'mobile' => $phone,
                'message' => $message,
                'sender' => $this->senderId,
            ]);

            if ($response->successful()) {
                Log::info("BulkSMS sent to {$phone}");
                return true;
            }

            Log::error("BulkSMS failed to {$phone}: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("BulkSMS exception to {$phone}: " . $e->getMessage());
            return false;
        }
    }
}