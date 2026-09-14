<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function referrer($cnic)
    {
        $donor = Donor::with('city', 'area', 'badges')
            ->withCount('referrals')
            ->where('cnic', $cnic)
            ->firstOrFail();

        return view('portal.referrer', compact('donor'));
    }

    public function show(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $donor = Donor::with('city', 'area')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('cnic', 'like', "%{$query}%");
                })
                ->first();

            if (!$donor) {
                return back()->with('error', 'No donor found matching your search.')->withInput();
            }

            return view('portal.verify', compact('donor'));
        }

        return view('portal.verify', ['donor' => null]);
    }

    public function verifyDonor($identifier)
    {
        $donor = Donor::with(['city', 'area', 'badges', 'donations'])
            ->where('registration_no', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        $ngoName = \App\Models\Setting::where('key', 'ngo_name')->value('value') ?? 'Insaniyat Foundation Blood Bank';
        $ngoLogo = \App\Models\Setting::where('key', 'ngo_logo')->value('value');
        $ngoPhone = \App\Models\Setting::where('key', 'contact_phone')->value('value') ?? '+92 300 1234567';

        return view('portal.verify-donor', compact('donor', 'ngoName', 'ngoLogo', 'ngoPhone'));
    }
}
