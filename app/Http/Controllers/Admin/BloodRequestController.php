<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\City;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with('city');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($bloodGroup = $request->get('blood_group')) {
            $query->where('blood_group', $bloodGroup);
        }

        if ($cityId = $request->get('city_id')) {
            $query->where('city_id', $cityId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('hospital', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }

        $bloodRequests = $query->latest()->paginate(20);
        $cities = Cache::remember('cities.all', 3600, function () {
            return City::orderBy('name')->get();
        });

        return view('admin.blood-requests.index', compact('bloodRequests', 'cities'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('admin.blood-requests.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_name' => 'required|string|max:255',
            'hospital' => 'required|string|max:255',
            'blood_group' => 'required|string|max:5',
            'city_id' => 'nullable|exists:cities,id',
            'units_required' => 'required|numeric|min:1',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'status' => 'required|in:pending,resolved,closed',
        ]);

        BloodRequest::create($data);

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Blood request created successfully.');
    }

    public function show($id)
    {
        $bloodRequest = BloodRequest::with(['callLogs.staff', 'city'])->findOrFail($id);
        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    public function edit($id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $cities = City::orderBy('name')->get();
        return view('admin.blood-requests.edit', compact('bloodRequest', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

        $data = $request->validate([
            'patient_name' => 'required|string|max:255',
            'hospital' => 'required|string|max:255',
            'blood_group' => 'required|string|max:5',
            'city_id' => 'nullable|exists:cities,id',
            'units_required' => 'required|numeric|min:1',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'status' => 'required|in:pending,resolved,closed',
        ]);

        $bloodRequest->update($data);

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Blood request updated successfully.');
    }

    public function destroy($id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        $bloodRequest->delete();

        return redirect()->route('admin.blood-requests.index')
            ->with('success', 'Blood request deleted successfully.');
    }

    public function getByBloodGroup($bloodGroup)
    {
        $patients = BloodRequest::with('city')
            ->where('blood_group', $bloodGroup)
            ->select('id', 'patient_name', 'hospital', 'blood_group', 'city_id', 'units_required')
            ->orderBy('patient_name')
            ->get()
            ->map(function ($p) {
                $p->city_name = $p->city->name ?? null;
                unset($p->city);
                return $p;
            });

        return response()->json($patients);
    }

    public function getDonorsByBloodGroup($bloodGroup)
    {
        $donors = Donor::with('city')
            ->where('blood_group', $bloodGroup)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('last_donation_date')
                  ->orWhere('last_donation_date', '<=', now()->subMonths(3));
            })
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'blood_group', 'city_id', 'last_donation_date', 'weight', 'gender', 'total_donations'])
            ->map(function ($d) {
                $d->city_name = $d->city->name ?? null;
                $d->reliability_score = $d->reliability_score;
                $d->days_since_last = $d->last_donation_date ? $d->last_donation_date->diffInDays(now()) : null;
                unset($d->city);
                return $d;
            });

        return response()->json($donors);
    }
}
