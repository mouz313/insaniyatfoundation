<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\BloodDonation;
use App\Models\BloodRequest;
use App\Models\Campaign;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $term = "%{$query}%";

        $donors = Donor::where('name', 'like', $term)
            ->orWhere('phone', 'like', $term)
            ->orWhere('registration_no', 'like', $term)
            ->orWhere('cnic', 'like', $term)
            ->take(5)
            ->get()
            ->map(fn($d) => [
                'type' => 'Donor',
                'label' => $d->name . ' — ' . ($d->phone ?? ''),
                'url' => route('admin.donors.show', $d),
                'icon' => 'fas fa-users',
                'color' => '#28a745',
            ]);

        $donations = BloodDonation::with('donor')
            ->whereHas('donor', fn($q) => $q->where('name', 'like', $term))
            ->orWhere('id', 'like', $term)
            ->take(5)
            ->get()
            ->map(fn($d) => [
                'type' => 'Donation',
                'label' => ($d->donor->name ?? 'N/A') . ' — ' . ($d->blood_group ?? '') . ' ' . $d->units . 'u',
                'url' => route('admin.blood-donations.show', $d),
                'icon' => 'fas fa-tint',
                'color' => '#dc3545',
            ]);

        $requests = BloodRequest::where('patient_name', 'like', $term)
            ->orWhere('hospital', 'like', $term)
            ->orWhere('id', 'like', $term)
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'type' => 'Request',
                'label' => $r->patient_name . ' — ' . ($r->hospital ?? ''),
                'url' => route('admin.blood-requests.show', $r),
                'icon' => 'fas fa-phone-alt',
                'color' => '#17a2b8',
            ]);

        $campaigns = Campaign::where('name', 'like', $term)
            ->orWhere('venue', 'like', $term)
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'type' => 'Campaign',
                'label' => $c->name,
                'url' => route('admin.campaigns.show', $c),
                'icon' => 'fas fa-calendar-alt',
                'color' => '#dc3545',
            ]);

        $results = collect()
            ->merge($donors)
            ->merge($donations)
            ->merge($requests)
            ->merge($campaigns)
            ->take(12)
            ->values();

        return response()->json(['results' => $results]);
    }
}
