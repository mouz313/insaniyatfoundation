<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Models\Setting;
use App\Models\Donor;
use App\Models\Campaign;
use App\Models\DonorStory;
use App\Models\BloodInventory;
use App\Models\BloodDonation;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = LandingSetting::getAll();
        $ngo = [
            'name' => Setting::where('key', 'ngo_name')->value('value') ?? 'Blood Donor',
            'address' => Setting::where('key', 'ngo_address')->value('value') ?? '',
            'logo' => Setting::where('key', 'ngo_logo')->value('value'),
            'favicon' => Setting::where('key', 'favicon')->value('value'),
            'footer_text' => Setting::where('key', 'footer_text')->value('value') ?? 'Serving humanity since 2018',
            'footer_email' => Setting::where('key', 'footer_email')->value('value') ?? '',
            'footer_phone' => Setting::where('key', 'footer_phone')->value('value') ?? '',
        ];

        $donorsTotal = Donor::count();
        $donorsActive = Donor::where('status', 'active')->count();
        $totalDonations = BloodDonation::where('status', 'donated')->count();
        $livesSaved = $totalDonations * 3;

        $stats = [
            'donors' => $donorsTotal,
            'lives_saved' => $livesSaved,
            'drives' => Campaign::count(),
            'cities' => \App\Models\City::count(),
        ];

        $bloodGroups = collect(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->map(function ($group) {
            $eligibleDonors = Donor::where('blood_group', $group)
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('last_donation_date')
                       ->orWhere('last_donation_date', '<=', now()->subMonths(3));
                })->count();

            $totalDonors = Donor::where('blood_group', $group)->count();

            $inventory = BloodInventory::where('blood_group', $group)->sum('units');

            $availability = 'low';
            if ($inventory >= 20) $availability = 'available';
            elseif ($inventory >= 5) $availability = 'moderate';

            return (object) [
                'group' => $group,
                'eligible_donors' => $eligibleDonors,
                'total_donors' => $totalDonors,
                'inventory' => $inventory,
                'availability' => $availability,
            ];
        });

        $campaigns = Campaign::featured()
            ->where('date', '>=', Carbon::today())
            ->orderBy('date')
            ->take(6)
            ->get()
            ->map(function ($c) {
                $collected = $c->donations()->where('status', 'donated')->sum('units');
                $c->collected_units = $collected;
                $c->progress = $c->target_units > 0 ? min(100, round(($collected / $c->target_units) * 100)) : 0;
                return $c;
            });

        $stories = DonorStory::active()->get();

        return view('portal.landing', compact(
            'settings', 'stats', 'bloodGroups', 'campaigns', 'stories',
            'donorsActive', 'totalDonations', 'ngo'
        ));
    }
}
