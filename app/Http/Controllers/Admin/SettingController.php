<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $commands = $this->availableCommands();
        return view('admin.settings.index', compact('settings', 'commands'));
    }

    public function update(Request $request)
    {
        $keys = [
            'ngo_name', 'ngo_address', 'blood_groups', 'donation_cooldown_days',
            'sms_gateway', 'sms_api_key', 'sms_api_secret', 'sms_sender_id',
            'card_template', 'footer_text', 'footer_email', 'footer_phone',
        ];

        $data = $request->validate([
            'ngo_name' => 'nullable|string|max:255',
            'ngo_address' => 'nullable|string',
            'blood_groups' => 'nullable|string',
            'donation_cooldown_days' => 'nullable|integer|min:1',
            'sms_gateway' => 'nullable|string',
            'sms_api_key' => 'nullable|string',
            'sms_api_secret' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
            'card_template' => 'nullable|string',
            'footer_text' => 'nullable|string|max:500',
            'footer_email' => 'nullable|email|max:255',
            'footer_phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:1024',
        ]);

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $value = $data[$key];
                if (in_array($key, ['sms_api_key', 'sms_api_secret'], true) && !empty($value)) {
                    $value = Crypt::encryptString($value);
                }
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'ngo_logo'], ['value' => $path]);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $path]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function removeLogo()
    {
        $path = Setting::where('key', 'ngo_logo')->value('value');
        if ($path) {
            Storage::disk('public')->delete($path);
            Setting::where('key', 'ngo_logo')->delete();
        }
        return redirect()->route('admin.settings.index')
            ->with('success', 'Logo removed successfully.');
    }

    public function removeFavicon()
    {
        $path = Setting::where('key', 'favicon')->value('value');
        if ($path) {
            Storage::disk('public')->delete($path);
            Setting::where('key', 'favicon')->delete();
        }
        return redirect()->route('admin.settings.index')
            ->with('success', 'Favicon removed successfully.');
    }

    public function runCommand(Request $request)
    {
        $request->validate(['signature' => 'required|string']);

        $allowed = collect($this->availableCommands())->pluck('signature')->toArray();
        $signature = $request->signature;

        if (!in_array($signature, $allowed, true)) {
            return back()->with('error', 'Command not allowed.');
        }

        $exitCode = Artisan::call($signature);
        $output = Artisan::output();

        return back()->with('command_output', [
            'success' => $exitCode === 0,
            'message' => $exitCode === 0 ? 'Command executed successfully.' : 'Command completed with errors.',
            'output' => $output,
            'signature' => $signature,
        ]);
    }

    private function availableCommands(): array
    {
        return [
            [
                'signature' => 'app:sync-donor-badges',
                'label' => 'Sync Donor Badges',
                'description' => 'Seed badge definitions and assign badges to all eligible donors',
                'icon' => 'fa-trophy',
                'color' => '#ffc107',
            ],
            [
                'signature' => 'app:process-donor-follow-ups',
                'label' => 'Process Follow-ups',
                'description' => 'Create follow-up records for donors due for re-engagement, eligible reminders, and call-backs',
                'icon' => 'fa-bell',
                'color' => '#17a2b8',
            ],
            [
                'signature' => 'app:database-backup --keep=10',
                'label' => 'Database Backup',
                'description' => 'Create a database backup and keep the latest 10 backups',
                'icon' => 'fa-database',
                'color' => '#28a745',
            ],
            [
                'signature' => 'permission:cache-reset',
                'label' => 'Reset Permission Cache',
                'description' => 'Reset Spatie permission cache after role/permission changes',
                'icon' => 'fa-sync',
                'color' => '#dc3545',
            ],
            [
                'signature' => 'config:clear',
                'label' => 'Clear Config Cache',
                'description' => 'Clear the application configuration cache',
                'icon' => 'fa-eraser',
                'color' => '#6f42c1',
            ],
            [
                'signature' => 'route:clear',
                'label' => 'Clear Route Cache',
                'description' => 'Clear the route cache',
                'icon' => 'fa-route',
                'color' => '#fd7e14',
            ],
            [
                'signature' => 'cache:clear',
                'label' => 'Clear Application Cache',
                'description' => 'Clear the entire application cache',
                'icon' => 'fa-trash',
                'color' => '#20c997',
            ],
        ];
    }
}
