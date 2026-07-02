@extends('adminlte::page')

@section('title', $campaign->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #dc3545, #e4606d); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(220,53,69,0.3);">
                <i class="fas fa-calendar-alt text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">{{ $campaign->name }}</h1>
                <small class="text-muted">{{ $campaign->date->format('d M Y') }} &middot; {{ $campaign->venue }}</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.campaigns.attendance', $campaign) }}" class="btn btn-info btn-sm mr-1" target="_blank"><i class="fas fa-clipboard-list"></i> Attendance</a>
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Campaign Details" icon="fas fa-calendar-alt" class="shadow-sm">
                <table class="table table-bordered mb-0">
                    <tr><th>Name</th><td>{{ $campaign->name }}</td></tr>
                    <tr><th>Date</th><td>{{ $campaign->date->format('d M Y') }}</td></tr>
                    <tr><th>Venue</th><td>{{ $campaign->venue }}</td></tr>
                    <tr><th>Target Units</th><td>{{ $campaign->target_units ?? 0 }}</td></tr>
                    <tr><th>Description</th><td>{{ $campaign->description ?? 'N/A' }}</td></tr>
                    <tr><th>Created</th><td>{{ $campaign->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Goal Progress" icon="fas fa-chart-bar" class="shadow-sm">
                @php
                    $totalUnits = $campaign->donations->sum('units');
                    $target = $campaign->target_units ?: 1;
                    $percent = min(100, round(($totalUnits / $target) * 100));
                @endphp
                <h3 class="text-center">{{ $totalUnits }} / {{ $campaign->target_units ?? 0 }} Units</h3>
                <div class="progress mb-2" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">{{ $percent }}%</div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-4">
                        <h5>{{ $campaign->donations->count() }}</h5>
                        <small>Blood Donations</small>
                    </div>
                    <div class="col-4">
                        <h5>{{ $campaign->donations->groupBy('donor_id')->count() }}</h5>
                        <small>Unique Donors</small>
                    </div>
                    <div class="col-4">
                        <h5>{{ number_format($campaign->moneyDonations->sum('amount'), 2) }}</h5>
                        <small>Money Collected</small>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    @php
        $isPast = $campaign->date->isPast();
        $bloodGroupBreakdown = $campaign->donations->groupBy('blood_group')->map(function ($group) {
            return ['units' => $group->sum('units'), 'count' => $group->count()];
        });
    @endphp

    @if($isPast)
        <div class="row">
            <div class="col-12">
                <x-adminlte-card title="Post-Campaign Report" icon="fas fa-file-alt" theme="success" class="shadow-sm">
                    <div class="row text-center mb-3">
                        <div class="col-3">
                            <h4 class="text-success mb-0">{{ $campaign->donations->count() }}</h4>
                            <small class="text-muted">Total Donations</small>
                        </div>
                        <div class="col-3">
                            <h4 class="text-info mb-0">{{ $totalUnits }}</h4>
                            <small class="text-muted">Total Units Collected</small>
                        </div>
                        <div class="col-3">
                            <h4 class="text-warning mb-0">{{ number_format($campaign->moneyDonations->sum('amount'), 0) }}</h4>
                            <small class="text-muted">Money Raised (PKR)</small>
                        </div>
                        <div class="col-3">
                            <h4 class="{{ $percent >= 100 ? 'text-success' : 'text-danger' }} mb-0">{{ $percent }}%</h4>
                            <small class="text-muted">Target Achievement</small>
                        </div>
                    </div>
                    @if($bloodGroupBreakdown->count() > 0)
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr><th>Blood Group</th><th>Donations</th><th>Units</th></tr>
                            </thead>
                            <tbody>
                                @foreach($bloodGroupBreakdown as $bg => $data)
                                    <tr>
                                        <td><span class="badge badge-danger">{{ $bg }}</span></td>
                                        <td>{{ $data['count'] }}</td>
                                        <td>{{ $data['units'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </x-adminlte-card>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Blood Donations" icon="fas fa-tint" class="shadow-sm">
                @if($campaign->donations->count() > 0)
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th>Donor</th><th>Date</th><th>Blood Group</th><th>Units</th></tr>
                        </thead>
                        <tbody>
                            @foreach($campaign->donations as $donation)
                                <tr>
                                    <td>{{ $donation->donor->name ?? 'N/A' }}</td>
                                    <td>{{ $donation->donation_date }}</td>
                                    <td><span class="badge badge-danger">{{ $donation->blood_group }}</span></td>
                                    <td>{{ $donation->units }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">No blood donations recorded.</p>
                @endif
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Money Donations" icon="fas fa-money-bill-wave" class="shadow-sm">
                @if($campaign->moneyDonations->count() > 0)
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th>Donor</th><th>Amount</th><th>Date</th><th>Method</th></tr>
                        </thead>
                        <tbody>
                            @foreach($campaign->moneyDonations as $donation)
                                <tr>
                                    <td>{{ $donation->donor->name ?? $donation->anonymous_name ?? 'Anonymous' }}</td>
                                    <td>{{ number_format($donation->amount, 2) }}</td>
                                    <td>{{ $donation->donation_date }}</td>
                                    <td>{{ ucfirst($donation->payment_method) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">No money donations recorded.</p>
                @endif
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table td, .table th { vertical-align: middle; }
.btn-outline-warning, .btn-outline-danger { border-width: 1.5px; }
.btn-group-sm .btn { padding: 3px 8px; }
</style>
@stop
