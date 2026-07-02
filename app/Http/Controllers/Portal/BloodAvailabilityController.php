<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BloodDonation;
use App\Models\City;
use App\Models\Donor;
use Illuminate\Http\Request;

class BloodAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::orderBy('name')->get(['id', 'name']);
        $donors = null;

        if ($request->filled('search') || $request->filled('blood_group') || $request->filled('city_id')) {
            $query = Donor::where('status', 'active')->with('city', 'area');

            if ($request->filled('blood_group')) {
                $query->where('blood_group', $request->blood_group);
            }

            if ($request->filled('city_id')) {
                $query->where('city_id', $request->city_id);
            }

            $donors = $query->get();
        }

        $carouselDonors = Donor::with('city')
            ->where('status', 'active')
            ->where('total_donations', '>', 0)
            ->orderByDesc('total_donations')
            ->take(5)
            ->get();

        $topDonors = BloodDonation::where('status', 'donated')
            ->whereMonth('donation_date', now()->month)
            ->whereYear('donation_date', now()->year)
            ->with('donor.city')
            ->selectRaw('donor_id, COUNT(*) as donation_count')
            ->groupBy('donor_id')
            ->orderByDesc('donation_count')
            ->take(5)
            ->get();

        $topReferrer = Donor::with('city')
            ->withCount('referrals')
            ->orderByDesc('referrals_count')
            ->limit(5)
            ->get()
            ->filter(fn($d) => $d->referrals_count > 0)
            ->values();

        $newDonors = Donor::with('city')
            ->latest()
            ->take(5)
            ->get();

        return view('portal.availability', compact(
            'cities', 'donors', 'carouselDonors', 'topDonors', 'topReferrer', 'newDonors'
        ));
    }
}
