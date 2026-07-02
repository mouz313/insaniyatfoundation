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

    public function show($id, Request $request)
    {
        if ($id == 0) {
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

        $donor = Donor::with('city', 'area')->findOrFail($id);
        return view('portal.verify', compact('donor'));
    }
}
