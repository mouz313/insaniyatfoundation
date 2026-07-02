<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donor;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

class AttendanceController extends Controller
{
    public function print(Campaign $campaign)
    {
        $campaign->load('donations.donor');

        $donors = Donor::whereHas('donations', function ($q) use ($campaign) {
            $q->where('campaign_id', $campaign->id);
        })->orWhere(function ($q) use ($campaign) {
            $q->where('city_id', $campaign->venue);
        })->get();

        $settings = Setting::all()->pluck('value', 'key');
        $ngoName = $settings['ngo_name'] ?? 'Blood Donor Organization';
        $ngoAddress = $settings['ngo_address'] ?? '';
        $ngoLogo = $settings['ngo_logo'] ?? null;
        $logoPath = $ngoLogo ? public_path('storage/' . $ngoLogo) : null;

        $pdf = Pdf::loadView('admin.campaigns.attendance', compact(
            'campaign', 'donors', 'ngoName', 'ngoAddress', 'logoPath'
        ));
        $pdf->setPaper('a4', 'landscape');
        $pdf->getDomPDF()->set_option('defaultFont', 'DejaVu Sans');

        return $pdf->stream("attendance_{$campaign->name}.pdf");
    }
}
