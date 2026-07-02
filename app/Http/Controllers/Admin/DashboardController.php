<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\BloodDonation;
use App\Models\BloodInventory;
use App\Models\MoneyDonation;
use App\Models\Campaign;
use App\Models\BloodRequest;
use App\Models\CallLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard.stats', 300, function () {
            $donorStats = Donor::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive
            ")->first();
            $totalDonors = $donorStats->total;
            $activeDonors = (int) $donorStats->active;
            $inactiveDonors = (int) $donorStats->inactive;

            $bloodGroups = Donor::selectRaw('blood_group, count(*) as total')
                ->groupBy('blood_group')
                ->pluck('total', 'blood_group')
                ->toArray();

            $today = Carbon::today();
            $todayStr = $today->toDateString();
            $requestStats = BloodRequest::selectRaw("
                SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_calls,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_calls,
                SUM(CASE WHEN status = 'resolved' AND DATE(updated_at) = ? THEN 1 ELSE 0 END) as success_calls,
                SUM(CASE WHEN status = 'closed' AND DATE(updated_at) = ? THEN 1 ELSE 0 END) as failed_calls
            ", [$todayStr, $todayStr, $todayStr])->first();
            
            $todayCalls = (int) ($requestStats->today_calls ?? 0);
            $pendingCalls = (int) ($requestStats->pending_calls ?? 0);
            $successCalls = (int) ($requestStats->success_calls ?? 0);
            $failedCalls = (int) ($requestStats->failed_calls ?? 0);

            $donorFoundCalls = CallLog::where('outcome', 'donor_found')->whereDate('created_at', $today)->count();

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $moneyStats = MoneyDonation::selectRaw("
                SUM(amount) as total,
                SUM(CASE WHEN MONTH(donation_date) = ? AND YEAR(donation_date) = ? THEN amount ELSE 0 END) as this_month,
                SUM(CASE WHEN YEAR(donation_date) = ? THEN amount ELSE 0 END) as this_year
            ", [$currentMonth, $currentYear, $currentYear])->first();
            
            $totalMoney = $moneyStats->total ?? 0;
            $moneyThisMonth = $moneyStats->this_month ?? 0;
            $moneyThisYear = $moneyStats->this_year ?? 0;

            $moneyByMethod = MoneyDonation::selectRaw('payment_method, sum(amount) as total')
                ->whereYear('donation_date', $currentYear)
                ->groupBy('payment_method')
                ->pluck('total', 'payment_method')
                ->toArray();

            $donationStats = BloodDonation::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN MONTH(donation_date) = ? AND YEAR(donation_date) = ? THEN 1 ELSE 0 END) as this_month,
                SUM(CASE WHEN status = 'deferred' AND MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as deferred_this_month
            ", [$currentMonth, $currentYear, $currentMonth, $currentYear])->first();
            
            $totalDonations = $donationStats->total;
            $donationsThisMonth = (int) ($donationStats->this_month ?? 0);
            $deferredDonations = (int) ($donationStats->deferred_this_month ?? 0);

            $requestLifetimeStats = BloodRequest::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed
            ")->first();
            
            $totalBloodRequests = $requestLifetimeStats->total;
            $pendingRequests = (int) ($requestLifetimeStats->pending ?? 0);
            $resolvedRequests = (int) ($requestLifetimeStats->resolved ?? 0);
            $closedRequests = (int) ($requestLifetimeStats->closed ?? 0);

            $thresholds = ['A+' => 10, 'A-' => 5, 'B+' => 10, 'B-' => 5, 'AB+' => 5, 'AB-' => 5, 'O+' => 15, 'O-' => 8];
            $stockByGroup = BloodInventory::stockByGroup();
            $lowStockAlerts = [];
            foreach ($thresholds as $bg => $threshold) {
                $units = $stockByGroup[$bg] ?? 0;
                if ($units < $threshold) {
                    $lowStockAlerts[] = [
                        'blood_group' => $bg,
                        'total_units' => $units,
                        'threshold' => $threshold,
                        'status' => 'low',
                    ];
                }
            }
            $totalAvailableUnits = array_sum($stockByGroup);

            $donationTrends = BloodDonation::selectRaw("DATE_FORMAT(donation_date, '%Y-%m') as month, COUNT(*) as total")
                ->where('donation_date', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $moneyTrends = MoneyDonation::selectRaw("DATE_FORMAT(donation_date, '%Y-%m') as month, SUM(amount) as total")
                ->where('donation_date', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $donorGrowth = Donor::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $upcomingCampaignsCount = Campaign::where('date', '>=', $today)->count();
            $recentCampaigns = Campaign::latest()->take(5)->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'date' => $c->date?->format('Y-m-d'),
                ])->values()->toArray();

            return compact(
                'totalDonors', 'activeDonors', 'inactiveDonors',
                'bloodGroups', 'stockByGroup', 'totalAvailableUnits',
                'todayCalls', 'pendingCalls', 'successCalls', 'failedCalls', 'donorFoundCalls',
                'moneyThisMonth', 'totalMoney', 'moneyThisYear', 'moneyByMethod',
                'totalDonations', 'donationsThisMonth', 'deferredDonations',
                'totalBloodRequests', 'pendingRequests', 'resolvedRequests', 'closedRequests',
                'lowStockAlerts',
                'donationTrends', 'moneyTrends', 'donorGrowth',
                'upcomingCampaignsCount', 'recentCampaigns'
            );
        });

        $stats['chartLabels'] = array_keys($stats['bloodGroups']);
        $stats['chartData'] = array_values($stats['bloodGroups']);

        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $stats['trendLabels'] = $months->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y'))->toArray();
        $stats['donationTrendData'] = $months->map(fn($m) => $stats['donationTrends'][$m] ?? 0)->toArray();
        $stats['moneyTrendData'] = $months->map(fn($m) => $stats['moneyTrends'][$m] ?? 0)->toArray();
        $cumulative = 0;
        $stats['donorGrowthData'] = $months->map(function ($m) use ($stats, &$cumulative) {
            $cumulative += $stats['donorGrowth'][$m] ?? 0;
            return $cumulative;
        })->toArray();

        $stats['recentDonations'] = Cache::remember('dashboard.recent_donations', 300, function () {
            return BloodDonation::with('donor')->latest()->take(10)->get()
                ->map(fn($d) => [
                    'donor_name' => $d->donor?->name ?? 'N/A',
                    'blood_group' => $d->blood_group,
                    'donation_date' => $d->donation_date?->format('Y-m-d'),
                    'units' => $d->units,
                    'status' => $d->status,
                ])->values()->toArray();
        });

        $stats['upcomingCampaigns'] = Cache::remember('dashboard.upcoming_campaigns', 300, function () {
            return Campaign::where('date', '>=', Carbon::today())
                ->orderBy('date')->take(5)->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'venue' => $c->venue,
                    'date' => $c->date?->format('Y-m-d'),
                    'target_units' => $c->target_units,
                ])->values()->toArray();
        });

        return view('admin.dashboard.index', $stats);
    }
}
