<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\City;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicBloodRequestController extends Controller
{
    public function create()
    {
        $cities = City::orderBy('name')->get();
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $ngoName = Setting::where('key', 'ngo_name')->value('value') ?? 'Insaniyat Foundation Blood Bank';
        $ngoPhone = Setting::where('key', 'contact_phone')->value('value') ?? '+92 300 1234567';

        return view('portal.blood-request.create', compact('cities', 'bloodGroups', 'ngoName', 'ngoPhone'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name'   => 'required|string|max:150',
            'hospital'       => 'required|string|max:200',
            'blood_group'    => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1|max:20',
            'contact_name'   => 'required|string|max:150',
            'contact_phone'  => 'required|string|max:30',
            'city_id'        => 'required|exists:cities,id',
        ]);

        $validated['status'] = 'pending';

        $bloodRequest = BloodRequest::create($validated);

        return redirect()->route('portal.blood-request.confirmed', $bloodRequest->id)
            ->with('success', 'Your emergency blood request has been received.');
    }

    public function confirmation(BloodRequest $bloodRequest)
    {
        $ngoName = Setting::where('key', 'ngo_name')->value('value') ?? 'Insaniyat Foundation Blood Bank';
        $ngoPhone = Setting::where('key', 'contact_phone')->value('value') ?? '+92 300 1234567';

        return view('portal.blood-request.confirmation', compact('bloodRequest', 'ngoName', 'ngoPhone'));
    }
}
