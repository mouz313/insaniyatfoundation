@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3 d-none d-sm-block" style="width:48px;height:48px;background:linear-gradient(135deg,#dc3545,#e4606d);border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(220,53,69,0.35);">
                <i class="fas fa-tint text-white" style="font-size:22px;line-height:48px;width:48px;text-align:center;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight:600;">
                    @php
                        $hour = now()->format('H');
                        if ($hour < 12)      $greet = __('app.good_morning');
                        elseif ($hour < 17)  $greet = __('app.good_afternoon');
                        else                 $greet = __('app.good_evening');
                    @endphp
                    {{ $greet }}, {{ auth()->user()->name ?? 'Admin' }}
                </h1>
                <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>{{ now()->format('l, F j, Y') }}</small>
            </div>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-danger mr-1"><i class="fas fa-file-pdf mr-1"></i>{{ __('app.reports') }}</a>
            <a href="{{ route('admin.donors.create') }}" class="btn btn-sm btn-danger"><i class="fas fa-plus mr-1"></i>{{ __('app.new_donor') }}</a>
        </div>
    </div>
@stop

@section('content')
    {{-- PRIMARY KPI CARDS --}}
    <div class="row">
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-card db-card-green">
                <div class="db-card-inner">
                    <div class="db-card-icon"><i class="fas fa-users"></i></div>
                    <div class="db-card-body">
                        <div class="db-card-number">{{ $totalDonors ?? 0 }}</div>
                        <div class="db-card-label">{{ __('app.total_donors') }}</div>
                    </div>
                    <div class="db-card-footer">
                        <span class="db-card-stat"><i class="fas fa-circle mr-1" style="color:#2ecc71;font-size:8px;"></i>{{ $activeDonors ?? 0 }} {{ __('app.active') }}</span>
                        <a href="{{ route('admin.donors.index') }}" class="db-card-link">View <i class="fas fa-arrow-right ml-1" style="font-size:10px;"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-card db-card-blue">
                <div class="db-card-inner">
                    <div class="db-card-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="db-card-body">
                        <div class="db-card-number">{{ $todayCalls ?? 0 }}</div>
                        <div class="db-card-label">{{ __('app.todays_calls') }}</div>
                    </div>
                    <div class="db-card-footer">
                        <span class="db-card-stat"><i class="fas fa-circle mr-1" style="color:#2ecc71;font-size:8px;"></i>{{ $donorFoundCalls ?? 0 }} {{ __('app.found') }}</span>
                        <a href="{{ route('admin.call-logs.index') }}" class="db-card-link">View <i class="fas fa-arrow-right ml-1" style="font-size:10px;"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-card db-card-orange">
                <div class="db-card-inner">
                    <div class="db-card-icon"><i class="fas fa-hand-holding-usd"></i></div>
                    <div class="db-card-body">
                        <div class="db-card-number">PKR {{ number_format($moneyThisMonth ?? 0) }}</div>
                        <div class="db-card-label">{{ __('app.money_this_month') }}</div>
                    </div>
                    <div class="db-card-footer">
                        <span class="db-card-stat"><i class="fas fa-circle mr-1" style="color:#f39c12;font-size:8px;"></i>PKR {{ number_format($totalMoney ?? 0) }} {{ __('app.total') }}</span>
                        <a href="{{ route('admin.money-donations.index') }}" class="db-card-link">View <i class="fas fa-arrow-right ml-1" style="font-size:10px;"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-card db-card-red">
                <div class="db-card-inner">
                    <div class="db-card-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="db-card-body">
                        <div class="db-card-number">{{ $upcomingCampaignsCount ?? 0 }}</div>
                        <div class="db-card-label">{{ __('app.upcoming_campaigns') }}</div>
                    </div>
                    <div class="db-card-footer">
                        <span class="db-card-stat"><i class="fas fa-circle mr-1" style="color:#e74c3c;font-size:8px;"></i>{{ collect($recentCampaigns)->where('date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))->count() }} Planned</span>
                        <a href="{{ route('admin.campaigns.index') }}" class="db-card-link">View <i class="fas fa-arrow-right ml-1" style="font-size:10px;"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECONDARY STAT ROW --}}
    <div class="row">
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-mini-card" style="border-left:4px solid #28a745;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="db-mini-label">{{ __('app.active_donors') }}</div>
                        <div class="db-mini-value text-success">{{ $activeDonors ?? 0 }}</div>
                        <small class="text-muted">{{ $activeDonors > 0 ? round(($activeDonors / max($totalDonors, 1)) * 100) : 0 }}% of total</small>
                    </div>
                    <div class="db-mini-icon" style="background:rgba(40,167,69,0.12);color:#28a745;"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="progress db-mini-progress">
                    <div class="progress-bar bg-success" style="width: {{ $activeDonors > 0 ? round(($activeDonors / max($totalDonors, 1)) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-mini-card" style="border-left:4px solid #6c757d;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="db-mini-label">{{ __('app.inactive_donors') }}</div>
                        <div class="db-mini-value text-secondary">{{ $inactiveDonors ?? 0 }}</div>
                        <small class="text-muted">{{ $inactiveDonors > 0 ? round(($inactiveDonors / max($totalDonors, 1)) * 100) : 0 }}% of total</small>
                    </div>
                    <div class="db-mini-icon" style="background:rgba(108,117,125,0.12);color:#6c757d;"><i class="fas fa-user-clock"></i></div>
                </div>
                <div class="progress db-mini-progress">
                    <div class="progress-bar bg-secondary" style="width: {{ $inactiveDonors > 0 ? round(($inactiveDonors / max($totalDonors, 1)) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-mini-card" style="border-left:4px solid #17a2b8;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="db-mini-label">{{ __('app.this_month_donations') }}</div>
                        <div class="db-mini-value text-info">{{ $donationsThisMonth ?? 0 }}</div>
                        <small class="text-muted">{{ __('app.all_time') }}: {{ $totalDonations ?? 0 }}</small>
                    </div>
                    <div class="db-mini-icon" style="background:rgba(23,162,184,0.12);color:#17a2b8;"><i class="fas fa-tint"></i></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="db-mini-card" style="border-left:4px solid #ffc107;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="db-mini-label">{{ __('app.deferred_this_month') }}</div>
                        <div class="db-mini-value text-warning">{{ $deferredDonations ?? 0 }}</div>
                        <small class="text-muted">{{ __('app.total_requests') }}: {{ $totalBloodRequests ?? 0 }}</small>
                    </div>
                    <div class="db-mini-icon" style="background:rgba(255,193,7,0.12);color:#ffc107;"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- BLOOD GROUP CHART + INVENTORY OVERVIEW --}}
    <div class="row">
        <div class="col-md-7 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-chart-pie text-danger mr-2"></i> Blood Group Distribution</div>
                    <small class="text-muted">Donor breakdown by blood type</small>
                </div>
                <div class="db-card-flat-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <canvas id="bloodGroupChart" style="height:260px;width:100%;"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="db-blood-legend">
                                @php
                                    $bgColors = ['#dc3545','#28a745','#ffc107','#17a2b8','#6610f2','#e83e8c','#20c997','#fd7e14'];
                                    $bgIndex = 0;
                                @endphp
                                @foreach($chartLabels ?? [] as $label)
                                    @php
                                        $total = max(array_sum($chartData ?? [1]), 1);
                                        $pct = round(($chartData[$bgIndex] / $total) * 100);
                                    @endphp
                                    <div class="db-blood-legend-item">
                                        <span class="db-blood-dot" style="background:{{ $bgColors[$bgIndex % 8] }};"></span>
                                        <span class="db-blood-label">{{ $label }}</span>
                                        <span class="db-blood-count">{{ $chartData[$bgIndex] ?? 0 }}</span>
                                        <span class="db-blood-pct">({{ $pct }}%)</span>
                                    </div>
                                    @php $bgIndex++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-3">
            <div class="db-card-flat h-100">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-warehouse text-info mr-2"></i> Blood Inventory Overview</div>
                    <a href="{{ route('admin.blood-inventory.index') }}" class="btn btn-xs btn-outline-info">Manage <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="db-card-flat-body">
                    <div class="text-center mb-3">
                        <span class="db-inventory-total">{{ $totalAvailableUnits ?? 0 }}</span>
                        <div class="text-muted" style="font-size:13px;">{{ __('app.total_available_units') }}</div>
                    </div>
                    @php
                        $thresholds = ['A+' => 10, 'A-' => 5, 'B+' => 10, 'B-' => 5, 'AB+' => 5, 'AB-' => 5, 'O+' => 15, 'O-' => 8];
                        $allGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                    @endphp
                    <div class="db-inventory-grid">
                        @foreach($allGroups as $bg)
                            @php
                                $units = $stockByGroup[$bg] ?? 0;
                                $threshold = $thresholds[$bg];
                                if ($units >= $threshold) {
                                    $cls = 'sufficient'; $icon = 'fa-check-circle';
                                } elseif ($units > 0) {
                                    $cls = 'low'; $icon = 'fa-exclamation-circle';
                                } else {
                                    $cls = 'critical'; $icon = 'fa-times-circle';
                                }
                            @endphp
                            <div class="db-inventory-item {{ $cls }}" title="{{ $bg }}: {{ $units }} units (threshold: {{ $threshold }})">
                                <span class="db-inv-badge">{{ $bg }}</span>
                                <span class="db-inv-units">{{ $units }}</span>
                                <i class="fas {{ $icon }} db-inv-icon"></i>
                            </div>
                        @endforeach
                    </div>
                    @if(count($lowStockAlerts) > 0)
                        <div class="db-low-stock-warning mt-3">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>{{ count($lowStockAlerts) }} group(s)</strong> below threshold
                            <span class="float-right">
                                @foreach($lowStockAlerts as $alert)
                                    <span class="badge badge-danger ml-1">{{ $alert['blood_group'] }}:{{ $alert['total_units'] }}</span>
                                @endforeach
                            </span>
                        </div>
                    @else
                        <div class="db-low-stock-ok mt-3">
                            <i class="fas fa-check-circle mr-1"></i> All blood groups have sufficient stock
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- TREND CHARTS --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-chart-line text-danger mr-2"></i> Donation Trends (12 Mo)</div>
                </div>
                <div class="db-card-flat-body">
                    <canvas id="donationTrendChart" style="height:200px;width:100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-chart-line text-warning mr-2"></i> Money Trends (12 Mo)</div>
                </div>
                <div class="db-card-flat-body">
                    <canvas id="moneyTrendChart" style="height:200px;width:100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-chart-area text-success mr-2"></i> Donor Growth (12 Mo)</div>
                </div>
                <div class="db-card-flat-body">
                    <canvas id="donorGrowthChart" style="height:200px;width:100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT DONATIONS + MONEY OVERVIEW --}}
    <div class="row">
        <div class="col-md-7 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-tint text-success mr-2"></i> Recent Donations</div>
                    <a href="{{ route('admin.blood-donations.index') }}" class="btn btn-xs btn-outline-success">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="db-card-flat-body p-0">
                    @if(isset($recentDonations) && count($recentDonations) > 0)
                        <div class="table-responsive">
                            <table class="table db-table mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('app.donor') }}</th>
                                        <th>{{ __('app.blood') }}</th>
                                        <th>{{ __('app.date') }}</th>
                                        <th>{{ __('app.units') }}</th>
                                        <th>{{ __('app.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDonations as $donation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="db-avatar" style="background:{{ ['#dc3545','#28a745','#ffc107','#17a2b8','#6610f2','#e83e8c'][ord(substr($donation['donor_name'] ?? 'A',0,1)) % 6] }};">
                                                        {{ strtoupper(substr($donation['donor_name'] ?? '?', 0, 1)) }}
                                                    </div>
                                                    <span class="font-weight-bold" style="font-size:14px;">{{ $donation['donor_name'] ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td><span class="db-blood-badge">{{ $donation['blood_group'] }}</span></td>
                                            <td><span class="text-muted" style="font-size:13px;">{{ \Carbon\Carbon::parse($donation['donation_date'])->format('d M') }}</span></td>
                                            <td><strong>{{ $donation['units'] }}</strong></td>
                                            <td>
                                                @switch($donation['status'])
                                                    @case('donated') <span class="db-status db-status-success">{{ __('app.donated') }}</span> @break
                                                    @case('deferred') <span class="db-status db-status-warning">{{ __('app.deferred') }}</span> @break
                                                    @case('pending') <span class="db-status db-status-info">{{ __('app.pending') }}</span> @break
                                                    @default <span class="db-status db-status-default">{{ $donation['status'] }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted"><i class="fas fa-inbox mr-2"></i>No recent donations.</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-3">
            <div class="db-card-flat h-100">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-chart-line text-warning mr-2"></i> Money Overview</div>
                    <a href="{{ route('admin.money-donations.index') }}" class="btn btn-xs btn-outline-warning">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="db-card-flat-body">
                    <div class="row text-center mb-3">
                        <div class="col-4 db-money-box">
                            <div class="db-money-amount text-warning">PKR {{ number_format($moneyThisMonth ?? 0) }}</div>
                            <div class="db-money-label">This Month</div>
                        </div>
                        <div class="col-4 db-money-box">
                            <div class="db-money-amount text-success">PKR {{ number_format($moneyThisYear ?? 0) }}</div>
                            <div class="db-money-label">This Year</div>
                        </div>
                        <div class="col-4 db-money-box">
                            <div class="db-money-amount text-info">PKR {{ number_format($totalMoney ?? 0) }}</div>
                            <div class="db-money-label">All Time</div>
                        </div>
                    </div>
                    @if(isset($moneyByMethod) && count($moneyByMethod) > 0)
                        <hr class="my-2">
                        <div style="font-size:13px;font-weight:600;color:#495057;margin-bottom:8px;"><i class="fas fa-credit-card mr-1"></i> By Payment Method</div>
                        @foreach($moneyByMethod as $method => $amount)
                            @php
                                $methodTotal = max(array_sum($moneyByMethod), 1);
                                $methodPct = round(($amount / $methodTotal) * 100);
                                $methodColors = ['cash' => '#28a745', 'bank_transfer' => '#17a2b8', 'credit_card' => '#6610f2', 'online' => '#fd7e14', 'cheque' => '#e83e8c', 'other' => '#6c757d'];
                                $barColor = $methodColors[$method] ?? '#6c757d';
                            @endphp
                            <div class="mb-2">
                                <div class="d-flex justify-content-between" style="font-size:13px;">
                                    <span>{{ ucfirst(str_replace('_', ' ', $method)) }}</span>
                                    <span><strong>PKR {{ number_format($amount) }}</strong> ({{ $methodPct }}%)</span>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar" style="width:{{ $methodPct }}%;background:{{ $barColor }};"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- CALL STATUS + UPCOMING CAMPAIGNS --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-phone-volume text-info mr-2"></i> Today's Call Status</div>
                    <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-xs btn-outline-info">Manage <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="db-card-flat-body">
                    <div class="row text-center mb-3">
                        <div class="col-3">
                            <div class="db-call-stat" style="background:rgba(23,162,184,0.1);">
                                <div class="db-call-num text-info">{{ $pendingCalls ?? 0 }}</div>
                                <div class="db-call-lbl">{{ __('app.pending') }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="db-call-stat" style="background:rgba(40,167,69,0.1);">
                                <div class="db-call-num text-success">{{ $successCalls ?? 0 }}</div>
                                <div class="db-call-lbl">{{ __('app.resolved') }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="db-call-stat" style="background:rgba(220,53,69,0.1);">
                                <div class="db-call-num text-danger">{{ $failedCalls ?? 0 }}</div>
                                <div class="db-call-lbl">{{ __('app.closed') }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="db-call-stat" style="background:rgba(255,193,7,0.1);">
                                <div class="db-call-num text-warning">{{ $donorFoundCalls ?? 0 }}</div>
                                <div class="db-call-lbl">{{ __('app.donor_found') }}</div>
                            </div>
                        </div>
                    </div>
                    @php $totalCalls = max(($pendingCalls + $successCalls + $failedCalls + $donorFoundCalls), 1); @endphp
                    <div class="progress" style="height:10px;border-radius:6px;">
                        <div class="progress-bar bg-info" style="width: {{ ($pendingCalls / $totalCalls) * 100 }}%" title="Pending: {{ $pendingCalls }}"></div>
                        <div class="progress-bar bg-success" style="width: {{ ($successCalls / $totalCalls) * 100 }}%" title="Resolved: {{ $successCalls }}"></div>
                        <div class="progress-bar bg-danger" style="width: {{ ($failedCalls / $totalCalls) * 100 }}%" title="Closed: {{ $failedCalls }}"></div>
                        <div class="progress-bar bg-warning" style="width: {{ ($donorFoundCalls / $totalCalls) * 100 }}%" title="Donor Found: {{ $donorFoundCalls }}"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2" style="font-size:12px;">
                        <span><i class="fas fa-circle text-info mr-1" style="font-size:8px;"></i>Pending</span>
                        <span><i class="fas fa-circle text-success mr-1" style="font-size:8px;"></i>Resolved</span>
                        <span><i class="fas fa-circle text-danger mr-1" style="font-size:8px;"></i>Closed</span>
                        <span><i class="fas fa-circle text-warning mr-1" style="font-size:8px;"></i>Donor Found</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="db-card-flat h-100">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-calendar-alt text-danger mr-2"></i> Upcoming Campaigns</div>
                    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-xs btn-outline-danger"><i class="fas fa-plus mr-1"></i>Create</a>
                </div>
                <div class="db-card-flat-body p-0">
                    @if(isset($upcomingCampaigns) && count($upcomingCampaigns) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingCampaigns as $campaign)
                                <a href="{{ route('admin.campaigns.show', $campaign['id']) }}" class="list-group-item list-group-item-action db-campaign-item">
                                    <div class="d-flex align-items-center">
                                        <div class="db-campaign-date-box">
                                            <div class="db-campaign-day">{{ \Carbon\Carbon::parse($campaign['date'])->format('d') }}</div>
                                            <div class="db-campaign-month">{{ \Carbon\Carbon::parse($campaign['date'])->format('M') }}</div>
                                        </div>
                                        <div class="ml-3 flex-grow-1">
                                            <div class="font-weight-bold" style="font-size:14px;">{{ $campaign['name'] }}</div>
                                            <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>{{ $campaign['venue'] }}</small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-danger">{{ $campaign['target_units'] ?? '—' }} units</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted"><i class="fas fa-calendar-times mr-2"></i>No upcoming campaigns.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- BLOOD REQUEST STATUS --}}
    <div class="row">
        <div class="col-12 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-tasks text-info mr-2"></i> Blood Request Status</div>
                    <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-xs btn-outline-info">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="db-card-flat-body">
                    @php $reqTotal = max($pendingRequests + $resolvedRequests + $closedRequests, 1); @endphp
                    <div class="row text-center mb-3">
                        <div class="col-3">
                            <div class="db-req-stat">
                                <div class="db-req-num">{{ $totalBloodRequests ?? 0 }}</div>
                                <div class="db-req-lbl">Total Requests</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="db-req-stat">
                                <div class="db-req-num text-info">{{ $pendingRequests ?? 0 }}</div>
                                <div class="db-req-lbl">Pending</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="db-req-stat">
                                <div class="db-req-num text-success">{{ $resolvedRequests ?? 0 }}</div>
                                <div class="db-req-lbl">Resolved</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="db-req-stat">
                                <div class="db-req-num text-danger">{{ $closedRequests ?? 0 }}</div>
                                <div class="db-req-lbl">Closed</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress" style="height:12px;border-radius:8px;">
                        <div class="progress-bar bg-info" style="width: {{ ($pendingRequests / $reqTotal) * 100 }}%" title="Pending: {{ $pendingRequests }}">
                            {{ $pendingRequests > 0 ? $pendingRequests : '' }}
                        </div>
                        <div class="progress-bar bg-success" style="width: {{ ($resolvedRequests / $reqTotal) * 100 }}%" title="Resolved: {{ $resolvedRequests }}">
                            {{ $resolvedRequests > 0 ? $resolvedRequests : '' }}
                        </div>
                        <div class="progress-bar bg-danger" style="width: {{ ($closedRequests / $reqTotal) * 100 }}%" title="Closed: {{ $closedRequests }}">
                            {{ $closedRequests > 0 ? $closedRequests : '' }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-2" style="font-size:13px;gap:20px;">
                        <span><i class="fas fa-circle text-info mr-1" style="font-size:8px;"></i>{{ round(($pendingRequests / $reqTotal) * 100) }}% Pending</span>
                        <span><i class="fas fa-circle text-success mr-1" style="font-size:8px;"></i>{{ round(($resolvedRequests / $reqTotal) * 100) }}% Resolved</span>
                        <span><i class="fas fa-circle text-danger mr-1" style="font-size:8px;"></i>{{ round(($closedRequests / $reqTotal) * 100) }}% Closed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="row">
        <div class="col-12 mb-3">
            <div class="db-card-flat">
                <div class="db-card-flat-header">
                    <div><i class="fas fa-bolt text-warning mr-2"></i> Quick Actions</div>
                </div>
                <div class="db-card-flat-body">
                    <div class="row">
                        <div class="col-lg-2 col-4 mb-2">
                            <a href="{{ route('admin.donors.create') }}" class="db-action-btn" style="border-color:#28a745;color:#28a745;">
                                <i class="fas fa-user-plus" style="color:#28a745;"></i>
                                <span>{{ __('app.new_donor') }}</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <a href="{{ route('admin.blood-donations.create') }}" class="db-action-btn" style="border-color:#dc3545;color:#dc3545;">
                                <i class="fas fa-tint" style="color:#dc3545;"></i>
                                <span>{{ __('app.record_donation') }}</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <a href="{{ route('admin.blood-requests.create') }}" class="db-action-btn" style="border-color:#17a2b8;color:#17a2b8;">
                                <i class="fas fa-phone-alt" style="color:#17a2b8;"></i>
                                <span>{{ __('app.blood_request') }}</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <a href="{{ route('admin.money-donations.create') }}" class="db-action-btn" style="border-color:#ffc107;color:#ffc107;">
                                <i class="fas fa-hand-holding-usd" style="color:#ffc107;"></i>
                                <span>{{ __('app.money_donation') }}</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <a href="{{ route('admin.blood-inventory.create') }}" class="db-action-btn" style="border-color:#17a2b8;color:#17a2b8;">
                                <i class="fas fa-warehouse" style="color:#17a2b8;"></i>
                                <span>{{ __('app.add_inventory') }}</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <a href="{{ route('admin.reports.index') }}" class="db-action-btn" style="border-color:#6c757d;color:#6c757d;">
                                <i class="fas fa-chart-bar" style="color:#6c757d;"></i>
                                <span>{{ __('app.reports') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
:root {
    --db-shadow: 0 2px 12px rgba(0,0,0,0.06);
    --db-shadow-hover: 0 6px 24px rgba(0,0,0,0.1);
    --db-radius: 14px;
    --db-radius-sm: 10px;
}

/* Primary KPI Cards */
.db-card {
    border-radius: var(--db-radius);
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    cursor: default;
    height: 100%;
}
.db-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--db-shadow-hover);
}
.db-card-inner {
    padding: 20px 22px 16px;
    position: relative;
}
.db-card-green { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); box-shadow: 0 4px 20px rgba(40,167,69,0.3); }
.db-card-blue { background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%); box-shadow: 0 4px 20px rgba(23,162,184,0.3); }
.db-card-orange { background: linear-gradient(135deg, #fd7e14 0%, #f39c12 100%); box-shadow: 0 4px 20px rgba(253,126,20,0.3); }
.db-card-red { background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%); box-shadow: 0 4px 20px rgba(220,53,69,0.3); }
.db-card-icon {
    position: absolute;
    right: 18px;
    top: 16px;
    font-size: 36px;
    opacity: 0.2;
    color: #fff;
}
.db-card-body { position: relative; z-index: 1; }
.db-card-number {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
    margin-bottom: 2px;
}
.db-card-label {
    font-size: 14px;
    color: rgba(255,255,255,0.85);
    font-weight: 400;
}
.db-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,0.2);
    position: relative;
    z-index: 1;
}
.db-card-stat { font-size: 12px; color: rgba(255,255,255,0.8); }
.db-card-link {
    font-size: 12px;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
.db-card-link:hover { color: #fff; text-decoration: underline; }

/* Mini Stat Cards */
.db-mini-card {
    background: #fff;
    border-radius: var(--db-radius-sm);
    padding: 16px 18px;
    box-shadow: var(--db-shadow);
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}
.db-mini-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--db-shadow-hover);
}
.db-mini-label { font-size: 12px; color: #6c757d; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px; }
.db-mini-value { font-size: 24px; font-weight: 700; line-height: 1.2; }
.db-mini-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.db-mini-progress { height: 4px; margin-top: 10px; border-radius: 4px; }

/* Flat Cards */
.db-card-flat {
    background: #fff;
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
    transition: box-shadow 0.2s;
    height: 100%;
}
.db-card-flat:hover { box-shadow: var(--db-shadow-hover); }
.db-card-flat-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 15px;
    color: #343a40;
}
.db-card-flat-body { padding: 18px 20px; }

/* Blood Group Legend */
.db-blood-legend { display: flex; flex-direction: column; gap: 8px; }
.db-blood-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    padding: 4px 0;
}
.db-blood-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}
.db-blood-label { font-weight: 500; min-width: 40px; }
.db-blood-count { font-weight: 700; margin-left: auto; color: #343a40; }
.db-blood-pct { font-size: 12px; color: #6c757d; min-width: 40px; text-align: right; }

/* Inventory Grid */
.db-inventory-total {
    font-size: 42px;
    font-weight: 800;
    color: #343a40;
    line-height: 1;
}
.db-inventory-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}
.db-inventory-item {
    border-radius: 10px;
    padding: 10px 8px;
    text-align: center;
    transition: transform 0.2s;
    position: relative;
}
.db-inventory-item:hover { transform: scale(1.06); }
.db-inventory-item.sufficient { background: rgba(40,167,69,0.12); }
.db-inventory-item.low { background: rgba(255,193,7,0.15); }
.db-inventory-item.critical { background: rgba(220,53,69,0.12); }
.db-inv-badge {
    display: block;
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 2px;
}
.sufficient .db-inv-badge { color: #28a745; }
.low .db-inv-badge { color: #e67e22; }
.critical .db-inv-badge { color: #dc3545; }
.db-inv-units {
    display: block;
    font-size: 18px;
    font-weight: 800;
    color: #343a40;
}
.db-inv-icon {
    position: absolute;
    top: 4px;
    right: 6px;
    font-size: 10px;
}
.sufficient .db-inv-icon { color: #28a745; }
.low .db-inv-icon { color: #e67e22; }
.critical .db-inv-icon { color: #dc3545; }

.db-low-stock-warning {
    background: rgba(255,193,7,0.12);
    border: 1px solid rgba(255,193,7,0.3);
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    color: #856404;
}
.db-low-stock-ok {
    background: rgba(40,167,69,0.1);
    border: 1px solid rgba(40,167,69,0.2);
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    color: #155724;
}

/* Table */
.db-table { margin: 0; }
.db-table thead th {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: #6c757d;
    font-weight: 600;
    padding: 10px 14px;
}
.db-table td { padding: 10px 14px; vertical-align: middle; font-size: 13px; }
.db-table tbody tr:hover { background: #f8f9fa; }

.db-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    margin-right: 10px;
    flex-shrink: 0;
}

.db-blood-badge {
    background: #dc3545;
    color: #fff;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.db-status {
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.db-status-success { background: rgba(40,167,69,0.12); color: #28a745; }
.db-status-warning { background: rgba(255,193,7,0.15); color: #856404; }
.db-status-info { background: rgba(23,162,184,0.12); color: #17a2b8; }
.db-status-default { background: rgba(108,117,125,0.12); color: #6c757d; }

/* Money Box */
.db-money-box { padding: 6px 4px; }
.db-money-amount { font-size: 16px; font-weight: 700; line-height: 1.2; }
.db-money-label { font-size: 11px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }

/* Call Stat */
.db-call-stat {
    border-radius: 12px;
    padding: 12px 6px;
}
.db-call-num { font-size: 26px; font-weight: 800; line-height: 1.1; }
.db-call-lbl { font-size: 12px; color: #6c757d; font-weight: 500; }

/* Request Stat */
.db-req-stat { padding: 4px 0; }
.db-req-num { font-size: 28px; font-weight: 800; color: #343a40; line-height: 1.1; }
.db-req-lbl { font-size: 12px; color: #6c757d; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px; }

/* Campaign List */
.db-campaign-item {
    border-left: none;
    border-right: none;
    padding: 12px 18px;
    transition: background 0.2s;
}
.db-campaign-item:first-child { border-top: none; }
.db-campaign-date-box {
    width: 50px;
    text-align: center;
    background: #dc3545;
    border-radius: 10px;
    padding: 6px 0;
    flex-shrink: 0;
}
.db-campaign-day {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
}
.db-campaign-month {
    font-size: 10px;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Quick Action Buttons */
.db-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 18px 8px;
    border: 1.5px dashed;
    border-radius: var(--db-radius-sm);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.25s ease;
    background: #fff;
    text-align: center;
    height: 100%;
}
.db-action-btn:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: var(--db-shadow-hover);
    text-decoration: none;
}
.db-action-btn i {
    font-size: 24px;
    margin-bottom: 8px;
}

/* Responsive tweaks */
@media (max-width: 767px) {
    .db-inventory-grid { grid-template-columns: repeat(4, 1fr); gap: 5px; }
    .db-inventory-item { padding: 8px 4px; }
    .db-inv-units { font-size: 16px; }
}
</style>
@stop

@section('js')
<script>
    var ctx = document.getElementById('bloodGroupChart').getContext('2d');
    var colors = ['#dc3545', '#28a745', '#ffc107', '#17a2b8', '#6610f2', '#e83e8c', '#20c997', '#fd7e14'];
    var labels = {!! json_encode($chartLabels ?? []) !!};
    var data = {!! json_encode($chartData ?? []) !!};
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors.slice(0, Math.max(labels.length, 1)),
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                            var pct = ((ctx.parsed / total) * 100).toFixed(1);
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    var trendLabels = {!! json_encode($trendLabels ?? []) !!};

    new Chart(document.getElementById('donationTrendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Donations',
                data: {!! json_encode($donationTrendData ?? []) !!},
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220,53,69,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: '#dc3545',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { maxTicksLimit: 6, font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } }
            }
        }
    });

    new Chart(document.getElementById('moneyTrendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Amount (PKR)',
                data: {!! json_encode($moneyTrendData ?? []) !!},
                borderColor: '#f39c12',
                backgroundColor: 'rgba(243,156,18,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: '#f39c12',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { maxTicksLimit: 6, font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { font: { size: 10 } } }
            }
        }
    });

    new Chart(document.getElementById('donorGrowthChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Total Donors',
                data: {!! json_encode($donorGrowthData ?? []) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: '#28a745',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { maxTicksLimit: 6, font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } }
            }
        }
    });
</script>
@stop