<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MoneyDonation;
use App\Models\Donor;
use App\Models\Campaign;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MoneyDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = MoneyDonation::with('donor', 'campaign');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('donor', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhere('anonymous_name', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%");
            });
        }

        if ($paymentMethod = $request->get('payment_method')) {
            $query->where('payment_method', $paymentMethod);
        }

        if ($campaignId = $request->get('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        $moneyDonations = $query->latest()->paginate(20);

        $stats = Cache::remember('money-donations.stats', 300, function () {
            return MoneyDonation::selectRaw('
                COALESCE(SUM(amount), 0) as total_amount,
                COALESCE(AVG(amount), 0) as avg_amount,
                COALESCE(SUM(CASE WHEN YEAR(donation_date) = ? AND MONTH(donation_date) = ? THEN amount ELSE 0 END), 0) as this_month,
                COALESCE(SUM(CASE WHEN YEAR(donation_date) = ? THEN amount ELSE 0 END), 0) as this_year
            ', [now()->year, now()->month, now()->year])->first();
        });
        $totalAmount = $stats->total_amount;
        $thisMonth = $stats->this_month;
        $thisYear = $stats->this_year;
        $avgAmount = $stats->avg_amount;
        $campaigns = Cache::remember('campaigns.all', 3600, function () {
            return Campaign::orderBy('name')->get();
        });

        return view('admin.money-donations.index', compact(
            'moneyDonations', 'totalAmount', 'thisMonth', 'thisYear', 'avgAmount', 'campaigns'
        ));
    }

    public function create()
    {
        $donors = Donor::orderBy('name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        return view('admin.money-donations.create', compact('donors', 'campaigns'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'donor_id' => 'nullable|exists:donors,id',
            'anonymous_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,JazzCash,Easypaisa',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);

        $donation = MoneyDonation::create($data);

        $donation->update([
            'receipt_number' => 'REC-' . date('Y') . '-' . $donation->id,
        ]);

        return redirect()->route('admin.money-donations.index')
            ->with('success', 'Money donation recorded successfully.');
    }

    public function edit($id)
    {
        $moneyDonation = MoneyDonation::findOrFail($id);
        $donors = Donor::orderBy('name')->get();
        $campaigns = Campaign::orderBy('name')->get();
        return view('admin.money-donations.edit', compact('moneyDonation', 'donors', 'campaigns'));
    }

    public function show($id)
    {
        $moneyDonation = MoneyDonation::with('donor', 'campaign')->findOrFail($id);
        return view('admin.money-donations.show', compact('moneyDonation'));
    }

    public function update(Request $request, $id)
    {
        $moneyDonation = MoneyDonation::findOrFail($id);

        $data = $request->validate([
            'donor_id' => 'nullable|exists:donors,id',
            'anonymous_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,JazzCash,Easypaisa',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);

        $moneyDonation->update($data);

        return redirect()->route('admin.money-donations.index')
            ->with('success', 'Money donation updated successfully.');
    }

    public function printReceipt($id)
    {
        $donation = MoneyDonation::with('donor', 'campaign')->findOrFail($id);
        $ngoName = Setting::where('key', 'ngo_name')->value('value') ?? 'Blood Donation NGO';
        $ngoLogo = Setting::where('key', 'ngo_logo')->value('value');
        $ngoAddress = Setting::where('key', 'ngo_address')->value('value');

        $pdf = Pdf::loadView('admin.money-donations.receipt-pdf', compact('donation', 'ngoName', 'ngoLogo', 'ngoAddress'));

        $pdf->setPaper('A5', 'portrait');

        return $pdf->download("receipt_{$donation->receipt_number}.pdf");
    }

    public function destroy($id)
    {
        $moneyDonation = MoneyDonation::findOrFail($id);
        $moneyDonation->delete();

        return redirect()->route('admin.money-donations.index')
            ->with('success', 'Money donation deleted successfully.');
    }
}
