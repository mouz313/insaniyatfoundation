<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Setting;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DonorCardController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::whereNull('card_printed_at')->with('city', 'area');

        if ($bloodGroup = $request->get('blood_group')) {
            $query->where('blood_group', $bloodGroup);
        }

        if ($cityId = $request->get('city_id')) {
            $query->where('city_id', $cityId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $donors = $query->latest()->paginate(20);

        $bloodGroups = Donor::select('blood_group')->distinct()->pluck('blood_group');
        $cities = \App\Models\City::orderBy('name')->get();

        return view('admin.donor-cards.index', compact('donors', 'bloodGroups', 'cities'));
    }

    public function printPdf(Request $request)
    {
        $request->validate([
            'donor_ids' => 'required',
        ]);

        $ids = json_decode($request->donor_ids, true);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Invalid donor selection.');
        }

        $donors = Donor::with('city')->whereIn('id', $ids)->get();

        $ngoName = Setting::where('key', 'ngo_name')->value('value') ?? 'Blood Donation NGO';
        $ngoLogo = Setting::where('key', 'ngo_logo')->value('value');

        $qrCodes = [];
        foreach ($donors as $donor) {
            $payload = json_encode([
                'id' => $donor->id,
                'name' => $donor->name,
                'blood_group' => $donor->blood_group,
                'reg_no' => $donor->registration_no,
                'verify_url' => route('portal.verify', $donor->id),
            ]);
            $qrCodes[$donor->id] = base64_encode(QrCode::format('svg')->size(150)->generate($payload));
        }

        return view('admin.donor-cards.print', compact('donors', 'ngoName', 'ngoLogo', 'qrCodes'));
    }

    public function markPrinted(Request $request)
    {
        $request->validate([
            'donor_ids' => 'required|array',
            'donor_ids.*' => 'exists:donors,id',
        ]);

        Donor::whereIn('id', $request->donor_ids)
            ->whereNull('card_printed_at')
            ->update(['card_printed_at' => now()]);

        return redirect()->back()->with('success', 'Cards marked as printed successfully.');
    }
}
