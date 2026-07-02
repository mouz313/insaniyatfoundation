<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\City;
use App\Models\Area;
use App\Models\University;
use App\Models\BloodDonation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::with('city', 'area');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%")
                  ->orWhere('blood_group', 'like', "%{$search}%");
            });
        }

        if ($bloodGroup = $request->get('blood_group')) {
            $query->where('blood_group', $bloodGroup);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $donors = $query->latest()->get();

        $cities = City::orderBy('name')->get();

        return view('admin.donors.index', compact('donors', 'cities'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        $areas = collect();
        $universities = University::orderBy('name')->get();
        return view('admin.donors.create', compact('cities', 'areas', 'universities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'cnic' => 'required|string|unique:donors,cnic|max:20',
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'required|string|max:5',
            'address' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'weight' => 'nullable|numeric|min:30|max:300',
            'hemoglobin' => 'nullable|numeric|min:5|max:20',
            'health_flags' => 'nullable|array',
            'health_flags.*' => 'string|in:recent_illness,pregnant,recent_tattoo,medication,chronic_disease,low_risk',
            'is_student' => 'boolean',
            'university_id' => 'nullable',
            'university_name' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'referred_by' => 'nullable|exists:donors,id',
            'status' => 'required|in:active,inactive,ineligible',
            'last_donation_date' => 'nullable|date',
            'total_donations' => 'nullable|integer|min:0',
            'no_referral' => 'boolean',
        ]);

        $data['is_student'] = $request->boolean('is_student');
        $data['health_flags'] = $request->has('health_flags') ? $request->health_flags : [];
        if ($request->boolean('no_referral')) {
            $data['referred_by'] = null;
        }

        if (($data['university_id'] ?? null) === 'new' && !empty($data['university_name'])) {
            $uni = University::whereRaw('LOWER(name) = ?', [strtolower($data['university_name'])])->first();
            if (!$uni) {
                $uni = University::create(['name' => trim($data['university_name'])]);
            }
            $data['university_id'] = $uni->id;
        }

        if (empty($data['university_id']) || $data['university_id'] === 'new') {
            $data['university_id'] = null;
        }

        if ($data['is_student'] && !$data['university_id'] && empty($data['university_name'])) {
            return back()->withErrors(['university_name' => 'University is required when the donor is a student.'])->withInput();
        }

        if ($request->hasFile('photo')) {
            $dir = storage_path('app/public/donors');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($request->file('photo'));
            $image->cover(300, 300);
            $path = 'donors/' . uniqid() . '.webp';
            $image->save(storage_path('app/public/' . $path), quality: 80);
            $data['photo'] = $path;
        }

        $donor = Donor::create($data);

        if ($donor->referred_by) {
            Donor::find($donor->referred_by)?->syncBadges();
        }

        $cityCode = strtoupper(substr($donor->city->name ?? 'NGO', 0, 3));
        $initials = strtoupper(($donor->name[0] ?? 'X') . ($donor->father_name[0] ?? 'X'));
        $phoneLast5 = substr(preg_replace('/\D/', '', $donor->phone), -5);
        $regNo = sprintf('%s-%s-%05s', $cityCode, $initials, $phoneLast5);
        $donor->update(['registration_no' => $regNo]);

        activity()->causedBy(auth()->user())->performedOn($donor)
            ->withProperties(['cnic' => $donor->cnic, 'phone' => $donor->phone])
            ->log('created');

        return redirect()->route('admin.donors.index')
            ->with('success', "Donor registered successfully. Reg #: {$regNo}");
    }

    public function show($id)
    {
        $donor = Donor::with(['city', 'area', 'donations', 'moneyDonations', 'badges', 'referrer'])->withCount('referrals')->findOrFail($id);

        $callLogs = $donor->callLogs()->with('bloodRequest', 'staff')->latest()->get();

        $annualSummary = $donor->moneyDonations()
            ->selectRaw('YEAR(donation_date) as year, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $eligibilityReasons = $donor->eligibilityReasons();
        $elgStatus = $donor->eligibility_status;

        $donorQr = base64_encode(QrCode::format('svg')->size(150)->generate(json_encode([
            'id' => $donor->id,
            'name' => $donor->name,
            'blood_group' => $donor->blood_group,
            'verify_url' => route('portal.verify', $donor->id),
        ])));

        return view('admin.donors.show', compact('donor', 'callLogs', 'annualSummary', 'eligibilityReasons', 'elgStatus', 'donorQr'));
    }

    public function edit($id)
    {
        $donor = Donor::findOrFail($id);
        $cities = City::orderBy('name')->get();
        $areas = Area::where('city_id', $donor->city_id)->orderBy('name')->get();
        $universities = University::orderBy('name')->get();

        return view('admin.donors.edit', compact('donor', 'cities', 'areas', 'universities'));
    }

    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'cnic' => 'required|string|max:20|unique:donors,cnic,' . $id,
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'required|string|max:5',
            'address' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'weight' => 'nullable|numeric|min:30|max:300',
            'hemoglobin' => 'nullable|numeric|min:5|max:20',
            'health_flags' => 'nullable|array',
            'health_flags.*' => 'string|in:recent_illness,pregnant,recent_tattoo,medication,chronic_disease,low_risk',
            'is_student' => 'boolean',
            'university_id' => 'nullable',
            'university_name' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'referred_by' => 'nullable|exists:donors,id',
            'status' => 'required|in:active,inactive,ineligible',
            'last_donation_date' => 'nullable|date',
            'total_donations' => 'nullable|integer|min:0',
            'no_referral' => 'boolean',
        ]);

        $data['is_student'] = $request->boolean('is_student');
        $data['health_flags'] = $request->has('health_flags') ? $request->health_flags : [];
        if ($request->boolean('no_referral')) {
            $data['referred_by'] = null;
        }

        if (($data['university_id'] ?? null) === 'new' && !empty($data['university_name'])) {
            $uni = University::whereRaw('LOWER(name) = ?', [strtolower($data['university_name'])])->first();
            if (!$uni) {
                $uni = University::create(['name' => trim($data['university_name'])]);
            }
            $data['university_id'] = $uni->id;
        }

        if (empty($data['university_id']) || $data['university_id'] === 'new') {
            $data['university_id'] = null;
        }

        if ($data['is_student'] && !$data['university_id'] && empty($data['university_name'])) {
            return back()->withErrors(['university_name' => 'University is required when the donor is a student.'])->withInput();
        }

        $oldCityId = $donor->city_id;

        if ($request->hasFile('photo')) {
            $dir = storage_path('app/public/donors');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($request->file('photo'));
            $image->cover(300, 300);
            $path = 'donors/' . uniqid() . '.webp';
            $image->save(storage_path('app/public/' . $path), quality: 80);
            $data['photo'] = $path;
        }

        $donor->update($data);

        if ($donor->referred_by) {
            Donor::find($donor->referred_by)?->syncBadges();
        }

        if ($donor->city_id !== $oldCityId || empty($donor->registration_no)) {
            $cityCode = strtoupper(substr($donor->city->name ?? 'NGO', 0, 3));
            $initials = strtoupper(($donor->name[0] ?? 'X') . ($donor->father_name[0] ?? 'X'));
            $phoneLast5 = substr(preg_replace('/\D/', '', $donor->phone), -5);
            $regNo = sprintf('%s-%s-%05s', $cityCode, $initials, $phoneLast5);
            $donor->update(['registration_no' => $regNo]);
        }

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor updated successfully.');
    }

    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->delete();

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor deleted successfully.');
    }

    public function searchReferrer(Request $request)
    {
        $q = $request->get('q');
        if (!$q || strlen($q) < 3) {
            return response()->json([]);
        }
        $donors = Donor::where('cnic', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'name', 'cnic', 'phone', 'blood_group']);
        return response()->json($donors);
    }

    public function checkDuplicate(Request $request)
    {
        $cnic = $request->get('cnic');
        $phone = $request->get('phone');
        $excludeId = $request->get('exclude_id');

        $query = Donor::query();
        if ($cnic) $query->orWhere('cnic', $cnic);
        if ($phone) $query->orWhere('phone', $phone);
        if ($excludeId) $query->where('id', '!=', $excludeId);
        $match = $query->first();

        if ($match) {
            return response()->json([
                'duplicate' => true,
                'donor' => ['id' => $match->id, 'name' => $match->name, 'cnic' => $match->cnic, 'phone' => $match->phone],
            ]);
        }

        return response()->json(['duplicate' => false]);
    }

    public function certificate($id)
    {
        $donor = Donor::findOrFail($id);
        $lastDonation = $donor->donations()->where('status', 'donated')->latest()->first();

        if (!$lastDonation) {
            return back()->with('error', 'No completed donation found to generate a certificate.');
        }

        $settings = Setting::all()->pluck('value', 'key');
        $ngoName = $settings['ngo_name'] ?? 'Blood Donor Organization';
        $ngoAddress = $settings['ngo_address'] ?? '';
        $ngoLogo = $settings['ngo_logo'] ?? null;
        $logoPath = $ngoLogo ? public_path('storage/' . $ngoLogo) : null;

        $pdf = Pdf::loadView('admin.donors.certificate', compact(
            'donor', 'lastDonation', 'ngoName', 'ngoAddress', 'logoPath'
        ));
        $pdf->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option('defaultFont', 'DejaVu Sans');

        return $pdf->download("certificate_{$donor->registration_no}.pdf");
    }
}
