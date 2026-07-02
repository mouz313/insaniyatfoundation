<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodDonation;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Campaign;
use Illuminate\Http\Request;

class BloodDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodDonation::with('donor', 'campaign');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($donorId = $request->get('donor_id')) {
            $query->where('donor_id', $donorId);
        }

        if ($bloodGroup = $request->get('blood_group')) {
            $query->where('blood_group', $bloodGroup);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('donor', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('patient_name', 'like', "%{$search}%")
                  ->orWhereHas('campaign', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bloodDonations = $query->latest()->paginate(20);

        return view('admin.blood-donations.index', compact('bloodDonations'));
    }

    public function create()
    {
        $donors = Donor::withCount(['donations as donated_count' => function ($q) {
            $q->where('status', 'donated');
        }])->where('status', 'active')->orderBy('name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        return view('admin.blood-donations.create', compact('donors', 'campaigns'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'patient_name' => 'nullable|string|max:255',
            'donation_date' => 'required|date',
            'blood_group' => 'required|string|max:5',
            'units' => 'required|numeric|min:0.01',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:donated,deferred,pending',
        ]);

        if ($data['blood_request_id'] && empty($data['patient_name'])) {
            $request = BloodRequest::find($data['blood_request_id']);
            $data['patient_name'] = $request?->patient_name;
        }

        $donation = BloodDonation::create($data);

        if ($donation->status === 'donated') {
            $donor = Donor::find($donation->donor_id);
            if (!$donor->last_donation_date || $donation->donation_date->gt($donor->last_donation_date)) {
                $donor->update(['last_donation_date' => $donation->donation_date]);
            }
            $donor->increment('total_donations');
            $donor->syncBadges();
        }

        return redirect()->route('admin.blood-donations.index')
            ->with('success', 'Blood donation recorded successfully.');
    }

    public function edit($id)
    {
        $bloodDonation = BloodDonation::findOrFail($id);
        $donors = Donor::withCount(['donations as donated_count' => function ($q) {
            $q->where('status', 'donated');
        }])->where('status', 'active')->orderBy('name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        return view('admin.blood-donations.edit', compact('bloodDonation', 'donors', 'campaigns'));
    }

    public function update(Request $request, $id)
    {
        $bloodDonation = BloodDonation::findOrFail($id);

        $data = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'blood_request_id' => 'nullable|exists:blood_requests,id',
            'patient_name' => 'nullable|string|max:255',
            'donation_date' => 'required|date',
            'blood_group' => 'required|string|max:5',
            'units' => 'required|numeric|min:0.01',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:donated,deferred,pending',
        ]);

        if ($data['blood_request_id'] && empty($data['patient_name'])) {
            $req = BloodRequest::find($data['blood_request_id']);
            $data['patient_name'] = $req?->patient_name;
        }

        $oldStatus = $bloodDonation->status;
        $bloodDonation->update($data);

        $donor = Donor::find($bloodDonation->donor_id);

        if ($oldStatus === 'donated' && $data['status'] !== 'donated') {
            $donor->decrement('total_donations');
        } elseif ($oldStatus !== 'donated' && $data['status'] === 'donated') {
            $donor->increment('total_donations');
        }

        $donor->syncBadges();

        $latest = BloodDonation::where('donor_id', $bloodDonation->donor_id)
            ->where('status', 'donated')
            ->latest('donation_date')
            ->first();

        $donor->update([
            'last_donation_date' => $latest ? $latest->donation_date : null,
        ]);

        return redirect()->route('admin.blood-donations.index')
            ->with('success', 'Blood donation updated successfully.');
    }

    public function destroy($id)
    {
        $bloodDonation = BloodDonation::findOrFail($id);
        $donorId = $bloodDonation->donor_id;

        if ($bloodDonation->status === 'donated') {
            Donor::where('id', $donorId)->decrement('total_donations');
        }

        $bloodDonation->delete();

        $latest = BloodDonation::where('donor_id', $donorId)
            ->where('status', 'donated')
            ->latest('donation_date')
            ->first();

        Donor::where('id', $donorId)->update([
            'last_donation_date' => $latest ? $latest->donation_date : null,
        ]);

        return redirect()->route('admin.blood-donations.index')
            ->with('success', 'Blood donation deleted successfully.');
    }
}
