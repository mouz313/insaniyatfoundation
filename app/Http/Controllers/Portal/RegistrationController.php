<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\City;
use App\Models\Area;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RegistrationController extends Controller
{
    public function searchReferrer(Request $request)
    {
        $q = $request->get('q');
        if (!$q || strlen($q) < 3) {
            return response()->json([]);
        }
        $donors = Donor::where('cnic', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'name', 'cnic', 'phone', 'blood_group'])
            ->map(function ($donor) {
                $donor->cnic = $this->maskCnic($donor->cnic);
                $donor->phone = $this->maskPhone($donor->phone);
                return $donor;
            });
        return response()->json($donors);
    }

    private function maskCnic(?string $cnic): ?string
    {
        if (!$cnic) return null;
        return preg_replace('/^(\d{5})-(\d{4})(\d{3})-(\d)$/', '$1-****-$$4', $cnic)
            ?? substr($cnic, 0, 6) . '***';
    }

    private function maskPhone(?string $phone): ?string
    {
        if (!$phone) return null;
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) >= 7) {
            return substr($phone, 0, 4) . '***' . substr($phone, -4);
        }
        return substr($phone, 0, 3) . '***';
    }

    public function create(Request $request)
    {
        $cities = City::orderBy('name')->get(['id', 'name']);
        $universities = \App\Models\University::orderBy('name')->get();
        $refCnic = $request->query('ref_cnic');
        return view('portal.register', compact('cities', 'universities', 'refCnic'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'cnic' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'required|string|max:5',
            'address' => 'nullable|string',
            'city_id' => 'nullable',
            'city_name' => 'nullable|string|max:255',
            'area_id' => 'nullable',
            'area_name' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:30|max:300',
            'hemoglobin' => 'nullable|numeric|min:5|max:20',
            'health_flags' => 'nullable|array',
            'health_flags.*' => 'string|in:recent_illness,pregnant,recent_tattoo,medication,chronic_disease,low_risk',
            'is_student' => 'boolean',
            'university_id' => 'nullable',
            'university_name' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'referred_by' => 'nullable|exists:donors,id',
            'no_referral' => 'boolean',
            'last_donation_date' => 'nullable|date',
            'total_donations' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $existingCnic = Donor::where('cnic', $data['cnic'])->first();
        if ($existingCnic) {
            return back()->with('error', 'A donor with this CNIC is already registered. Please contact our staff.')
                ->withInput();
        }

        $data['is_student'] = $request->boolean('is_student');
        $data['health_flags'] = $request->has('health_flags') ? $request->health_flags : [];

        if ($data['is_student'] && empty($data['university_name'])) {
            return back()->withErrors(['university_name' => 'University name is required when the donor is a student.'])->withInput();
        }

        if ($data['city_id'] === 'new' && !empty($data['city_name'])) {
            $city = City::whereRaw('LOWER(name) = ?', [strtolower($data['city_name'])])->first();
            if (!$city) {
                $city = City::create(['name' => trim($data['city_name'])]);
            }
            $data['city_id'] = $city->id;
        }

        if (!empty($data['city_id']) && $data['city_id'] !== 'new') {
            if (($data['area_id'] ?? null) === 'new' && !empty($data['area_name'])) {
                $area = Area::where('city_id', $data['city_id'])
                    ->whereRaw('LOWER(name) = ?', [strtolower($data['area_name'])])
                    ->first();
                if (!$area) {
                    $area = Area::create(['city_id' => $data['city_id'], 'name' => trim($data['area_name'])]);
                }
                $data['area_id'] = $area->id;
            }
        }

        if (($data['university_id'] ?? null) === 'new' && !empty($data['university_name'])) {
            $uni = \App\Models\University::whereRaw('LOWER(name) = ?', [strtolower($data['university_name'])])->first();
            if (!$uni) {
                $uni = \App\Models\University::create(['name' => trim($data['university_name'])]);
            }
            $data['university_id'] = $uni->id;
        }

        if (empty($data['university_id']) || $data['university_id'] === 'new') {
            $data['university_id'] = null;
        }

        unset($data['city_name'], $data['area_name'], $data['no_referral']);

        if ($request->boolean('no_referral')) {
            $data['referred_by'] = null;
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

        $data['status'] = 'active';
        $donor = Donor::create($data);

        if ($donor->referred_by) {
            Donor::find($donor->referred_by)?->syncBadges();
        }

        $cityCode = strtoupper(substr(($donor->city->name ?? 'PUB'), 0, 3));
        $initials = strtoupper(($donor->name[0] ?? 'X') . ($donor->father_name[0] ?? 'X'));
        $phoneLast5 = substr(preg_replace('/\D/', '', $donor->phone), -5);
        $regNo = sprintf('%s-%s-%05s', $cityCode, $initials, $phoneLast5);
        $donor->update(['registration_no' => $regNo]);

        activity()->causedBy($donor)->performedOn($donor)
            ->log('self_registered');

        return redirect()->route('portal.registration.confirm', $donor)
            ->with('success', 'Registration submitted successfully!');
    }

    public function confirm(Donor $donor)
    {
        return view('portal.confirm', compact('donor'));
    }
}
