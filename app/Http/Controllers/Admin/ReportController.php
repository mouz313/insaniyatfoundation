<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\DonorsExport;
use App\Exports\BloodDonationsExport;
use App\Exports\BloodRequestsExport;
use App\Exports\MoneyDonationsExport;
use App\Models\BloodDonation;
use App\Models\BloodRequest;
use App\Models\CallLog;
use App\Models\Campaign;
use App\Models\City;
use App\Models\Donor;
use App\Models\MoneyDonation;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ReportController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->get();
        return view('admin.reports.index', compact('cities'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'city_id' => 'nullable|exists:cities,id',
            'status' => 'nullable|string',
            'format' => 'required|in:pdf,excel,word',
        ]);

        $method = 'export' . ucfirst($data['format']);
        return $this->$method($request);
    }

    private function ngoProfile(): array
    {
        $settings = Setting::whereIn('key', ['ngo_name', 'ngo_logo', 'ngo_address'])
            ->pluck('value', 'key');

        return [
            'name' => $settings->get('ngo_name') ?? 'Blood Donation NGO',
            'logo' => $settings->get('ngo_logo'),
            'address' => $settings->get('ngo_address'),
        ];
    }

    private function buildQuery(string $reportType, Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $bloodGroup = $request->blood_group;
        $cityId = $request->city_id;
        $status = $request->status;

        return match ($reportType) {
            'daily_calls' => $this->dailyCallsQuery($start, $end, $cityId),
            'monthly_donor' => BloodDonation::with('donor', 'campaign')
                ->when($start, fn($q) => $q->whereDate('donation_date', '>=', $start))
                ->when($end, fn($q) => $q->whereDate('donation_date', '<=', $end))
                ->when($bloodGroup, fn($q) => $q->where('blood_group', $bloodGroup))
                ->when($status, fn($q) => $q->where('status', $status))
                ->latest('donation_date'),
            'yearly_summary' => BloodDonation::with('donor')
                ->when($start, fn($q) => $q->whereYear('donation_date', date('Y', strtotime($start))))
                ->when($status, fn($q) => $q->where('status', $status)),
            'money_collected' => MoneyDonation::with('donor')
                ->when($start, fn($q) => $q->whereDate('donation_date', '>=', $start))
                ->when($end, fn($q) => $q->whereDate('donation_date', '<=', $end))
                ->latest('donation_date'),
            'donor_list' => Donor::with('city', 'area')
                ->when($bloodGroup, fn($q) => $q->where('blood_group', $bloodGroup))
                ->when($cityId, fn($q) => $q->where('city_id', $cityId))
                ->when($status, fn($q) => $q->where('status', $status))
                ->latest(),
            'card_queue' => Donor::whereNull('card_printed_at')
                ->with('city')
                ->when($bloodGroup, fn($q) => $q->where('blood_group', $bloodGroup))
                ->when($cityId, fn($q) => $q->where('city_id', $cityId))
                ->latest(),
            default => BloodDonation::with('donor')->latest(),
        };
    }

    private function dailyCallsQuery($start, $end, $cityId)
    {
        return CallLog::with(['bloodRequest', 'staff'])
            ->when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end))
            ->when($cityId, function ($q) use ($cityId) {
                $q->whereHas('bloodRequest', fn($q) => $q->where('city_id', $cityId));
            })
            ->latest();
    }

    private function progressReport(Request $request): array
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $donorCounts = Donor::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN status = 'ineligible' THEN 1 ELSE 0 END) as ineligible
        ")->first();
        
        $totalDonors = $donorCounts->total;
        $activeDonors = (int) $donorCounts->active;
        $inactiveDonors = (int) $donorCounts->inactive;
        $ineligibleDonors = (int) $donorCounts->ineligible;

        $donationStats = BloodDonation::query()
            ->when($start, fn($q) => $q->whereDate('donation_date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('donation_date', '<=', $end))
            ->selectRaw("
                COUNT(*) as total_count,
                SUM(CASE WHEN status = 'donated' THEN units ELSE 0 END) as donated_units
            ")
            ->first();
        $totalDonations = $donationStats->total_count;
        $donatedUnits = $donationStats->donated_units ?? 0;

        $donationsByGroup = BloodDonation::query()
            ->when($start, fn($q) => $q->whereDate('donation_date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('donation_date', '<=', $end))
            ->selectRaw('blood_group, SUM(units) as units')
            ->groupBy('blood_group')
            ->pluck('units', 'blood_group');

        $moneyStats = MoneyDonation::query()
            ->when($start, fn($q) => $q->whereDate('donation_date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('donation_date', '<=', $end))
            ->selectRaw("
                SUM(amount) as total_amount,
                SUM(CASE WHEN MONTH(donation_date) = ? AND YEAR(donation_date) = ? THEN amount ELSE 0 END) as this_month_amount
            ", [now()->month, now()->year])
            ->first();
        $totalMoney = $moneyStats->total_amount ?? 0;
        $moneyThisMonth = $moneyStats->this_month_amount ?? 0;

        $moneyByMethod = MoneyDonation::query()
            ->when($start, fn($q) => $q->whereDate('donation_date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('donation_date', '<=', $end))
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $campaignStats = Campaign::query()
            ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->selectRaw("
                COUNT(*) as total_count,
                SUM(CASE WHEN date >= ? THEN 1 ELSE 0 END) as upcoming_count
            ", [now()->toDateString()])
            ->first();
        $totalCampaigns = $campaignStats->total_count;
        $upcomingCampaigns = (int) ($campaignStats->upcoming_count ?? 0);

        $requestStats = BloodRequest::query()
            ->when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end))
            ->selectRaw("
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_count,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_count
            ")
            ->first();
        $pendingRequests = (int) ($requestStats->pending_count ?? 0);
        $resolvedRequests = (int) ($requestStats->resolved_count ?? 0);
        $closedRequests = (int) ($requestStats->closed_count ?? 0);

        return compact(
            'totalDonors', 'activeDonors', 'inactiveDonors', 'ineligibleDonors',
            'totalDonations', 'donatedUnits', 'donationsByGroup',
            'totalMoney', 'moneyThisMonth', 'moneyByMethod',
            'totalCampaigns', 'upcomingCampaigns',
            'pendingRequests', 'resolvedRequests', 'closedRequests',
        );
    }

    public function exportExcel(Request $request)
    {
        $type = $request->report_type ?? 'donor_list';
        $ngo = $this->ngoProfile();

        if ($type === 'progress_report') {
            $data = $this->progressReport($request);
            return Excel::download(
                new \App\Exports\ProgressReportExport($data, $ngo),
                "report_{$type}_" . now()->format('Ymd') . '.xlsx'
            );
        }

        $rows = $this->buildQuery($type, $request)->get();

        if ($type === 'daily_calls') {
            $rows = $rows->map(fn($log) => $log->bloodRequest)->filter();
        }

        $export = match ($type) {
            'donor_list', 'card_queue' => new DonorsExport($rows, $ngo),
            'monthly_donor', 'yearly_summary' => new BloodDonationsExport($rows, $ngo),
            'daily_calls' => new BloodRequestsExport($rows, $ngo),
            'money_collected' => new MoneyDonationsExport($rows, $ngo),
            default => new BloodDonationsExport($rows, $ngo),
        };

        return Excel::download($export, "report_{$type}_" . now()->format('Ymd') . '.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $type = $request->report_type ?? 'donor_list';
        $ngo = $this->ngoProfile();

        if ($type === 'progress_report') {
            $data = $this->progressReport($request);
            $pdf = Pdf::loadView('admin.reports.progress-pdf', compact('data', 'ngo'));
            return $pdf->download("report_{$type}_" . now()->format('Ymd') . '.pdf');
        }

        $rows = $this->buildQuery($type, $request)->get();

        if ($type === 'daily_calls') {
            $rows = $rows->map(fn($log) => $log->bloodRequest)->filter();
        }

        $title = match ($type) {
            'daily_calls' => 'Daily Calls Report',
            'monthly_donor' => 'Monthly Donor Report',
            'yearly_summary' => 'Yearly Summary',
            'money_collected' => 'Money Collected Report',
            'donor_list' => 'Donor List',
            'card_queue' => 'Card Printing Queue',
            default => 'Report',
        };

        $pdf = Pdf::loadView('admin.reports.pdf', compact('rows', 'type', 'title', 'ngo'));
        return $pdf->download("report_{$type}_" . now()->format('Ymd') . '.pdf');
    }

    public function exportWord(Request $request)
    {
        $type = $request->report_type ?? 'donor_list';
        $ngo = $this->ngoProfile();

        if ($type === 'progress_report') {
            $data = $this->progressReport($request);
            return $this->wordProgressReport($data, $ngo);
        }

        $rows = $this->buildQuery($type, $request)->get();

        if ($type === 'daily_calls') {
            $rows = $rows->map(fn($log) => $log->bloodRequest)->filter();
        }

        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 800, 'marginBottom' => 800, 'marginLeft' => 800, 'marginRight' => 800,
        ]);

        $section->addText($ngo['name'], ['bold' => true, 'size' => 16, 'color' => 'dc3545']);
        if ($ngo['address']) {
            $section->addText($ngo['address'], ['size' => 10, 'color' => '888888']);
        }
        $section->addTextBreak();

        $title = match ($type) {
            'daily_calls' => 'Daily Calls Report',
            'monthly_donor' => 'Monthly Donor Report',
            'yearly_summary' => 'Yearly Summary',
            'money_collected' => 'Money Collected',
            'donor_list' => 'Donor List',
            'card_queue' => 'Card Queue',
            default => 'Report',
        };

        $section->addTitle($title, 1);
        $section->addText("Generated: " . now()->format('d M Y h:i A'));
        $section->addTextBreak();

        if ($rows->isNotEmpty()) {
            $first = $rows->first();
            $headers = array_keys($first->toArray());
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
            $table->addRow();
            foreach ($headers as $h) {
                $table->addCell(1500)->addText(ucwords(str_replace('_', ' ', $h)));
            }
            foreach ($rows as $row) {
                $table->addRow();
                foreach ($row->toArray() as $val) {
                    $table->addCell(1500)->addText(is_string($val) ? $val : json_encode($val));
                }
            }
        } else {
            $section->addText('No data found.');
        }

        $section->addTextBreak();
        $section->addText($ngo['name'] . ' | ' . now()->format('d M Y'), ['size' => 9, 'color' => 'aaaaaa']);

        $file = storage_path("app/report_{$type}_" . now()->format('Ymd') . '.docx');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($file);

        return response()->download($file)->deleteFileAfterSend(true);
    }

    private function wordProgressReport(array $data, array $ngo)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 800, 'marginBottom' => 800, 'marginLeft' => 800, 'marginRight' => 800,
        ]);

        $section->addText($ngo['name'], ['bold' => true, 'size' => 16, 'color' => 'dc3545']);
        if ($ngo['address']) {
            $section->addText($ngo['address'], ['size' => 10, 'color' => '888888']);
        }
        $section->addTextBreak();
        $section->addTitle('Progress Report', 1);
        $section->addText("Generated: " . now()->format('d M Y h:i A'));
        $section->addTextBreak();

        $section->addTitle('Donors', 2);
        $section->addText("Total Donors: {$data['totalDonors']}");
        $section->addText("Active: {$data['activeDonors']} | Inactive: {$data['inactiveDonors']} | Ineligible: {$data['ineligibleDonors']}");
        $section->addTextBreak();

        $section->addTitle('Blood Donations', 2);
        $section->addText("Total Donations: {$data['totalDonations']}");
        $section->addText("Units Donated: {$data['donatedUnits']}");
        foreach ($data['donationsByGroup'] as $bg => $units) {
            $section->addText("  {$bg}: {$units} units");
        }
        $section->addTextBreak();

        $section->addTitle('Money Collected', 2);
        $section->addText("Total: PKR " . number_format($data['totalMoney'], 2));
        $section->addText("This Month: PKR " . number_format($data['moneyThisMonth'], 2));
        foreach ($data['moneyByMethod'] as $method => $total) {
            $section->addText("  {$method}: PKR " . number_format($total, 2));
        }
        $section->addTextBreak();

        $section->addTitle('Campaigns', 2);
        $section->addText("Total Campaigns: {$data['totalCampaigns']}");
        $section->addText("Upcoming: {$data['upcomingCampaigns']}");
        $section->addTextBreak();

        $section->addTitle('Blood Requests', 2);
        $section->addText("Pending: {$data['pendingRequests']} | Resolved: {$data['resolvedRequests']} | Closed: {$data['closedRequests']}");

        $section->addTextBreak();
        $section->addText($ngo['name'] . ' | ' . now()->format('d M Y'), ['size' => 9, 'color' => 'aaaaaa']);

        $file = storage_path("app/report_progress_" . now()->format('Ymd') . '.docx');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($file);

        return response()->download($file)->deleteFileAfterSend(true);
    }
}
