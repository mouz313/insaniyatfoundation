@extends('adminlte::page')

@section('title', 'Blood Request Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1A6B2E, #145A26); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(26,107,46,0.3);">
                <i class="fas fa-hand-holding-heart text-white" style="font-size: 22px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Blood Request #{{ $bloodRequest->id }}</h1>
                <small class="text-muted">{{ $bloodRequest->patient_name }} &bull; {{ $bloodRequest->blood_group }}</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.blood-requests.match', $bloodRequest->id) }}" class="btn btn-success btn-sm"><i class="fas fa-handshake"></i> Match Donors</a>
            <a href="{{ route('admin.call-logs.create', ['blood_request_id' => $bloodRequest->id]) }}" class="btn btn-info btn-sm"><i class="fas fa-phone"></i> Add Call Log</a>
            <a href="{{ route('admin.blood-requests.edit', $bloodRequest->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #1565A8, #0F4F8A); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight: 600;">Request Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th style="width: 140px;">Patient Name</th><td>{{ $bloodRequest->patient_name }}</td></tr>
                        <tr><th>Hospital</th><td>{{ $bloodRequest->hospital }}</td></tr>
                        <tr><th>Blood Group</th><td><span class="badge badge-danger" style="border-radius: 20px; padding: 4px 14px;"><i class="fas fa-tint"></i> {{ $bloodRequest->blood_group }}</span></td></tr>
                        <tr><th>City</th><td>{{ $bloodRequest->city->name ?? 'N/A' }}</td></tr>
                        <tr><th>Units Required</th><td><strong>{{ $bloodRequest->units_required }}</strong></td></tr>
                        <tr><th>Status</th>
                            <td>
                                @if($bloodRequest->status == 'resolved')
                                    <span class="badge badge-success" style="border-radius: 20px;">Resolved</span>
                                @elseif($bloodRequest->status == 'pending')
                                    <span class="badge badge-warning" style="border-radius: 20px;">Pending</span>
                                @elseif($bloodRequest->status == 'closed')
                                    <span class="badge badge-danger" style="border-radius: 20px;">Closed</span>
                                @else
                                    <span class="badge badge-secondary" style="border-radius: 20px;">{{ ucfirst($bloodRequest->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Contact Person</th><td>{{ $bloodRequest->contact_name ?? 'N/A' }}</td></tr>
                        <tr><th>Contact Phone</th><td>{{ $bloodRequest->contact_phone }}</td></tr>
                        <tr><th>Created</th><td>{{ $bloodRequest->created_at->format('d M Y, h:i A') }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                        <i class="fas fa-phone text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight: 600;">Call Logs</h5>
                    <span class="badge badge-success ml-auto">{{ $bloodRequest->callLogs->count() }} calls</span>
                </div>
                <div class="card-body p-0">
                    @if($bloodRequest->callLogs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($bloodRequest->callLogs as $log)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $log->staff->name ?? 'N/A' }}</strong>
                                            <span class="ml-2">
                                                @php
                                                    $outcomeLabels = [
                                                        'success' => ['Success', 'success'],
                                                        'donor_found' => ['Donor Found', 'success'],
                                                        'pending' => ['Pending', 'warning'],
                                                        'call_back' => ['Call Back', 'info'],
                                                        'failed' => ['Failed', 'danger'],
                                                        'no_answer' => ['No Answer', 'secondary'],
                                                        'not_answered' => ['Not Answered', 'secondary'],
                                                        'refused' => ['Refused', 'danger'],
                                                    ];
                                                    $label = $outcomeLabels[$log->outcome] ?? [ucfirst($log->outcome), 'secondary'];
                                                @endphp
                                                <span class="badge badge-{{ $label[1] }}" style="border-radius: 20px;">{{ $label[0] }}</span>
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $log->created_at->format('d M Y') }}</small>
                                    </div>
                                    @if($log->notes)
                                        <small class="text-muted d-block mt-1">{{ $log->notes }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-phone text-muted" style="font-size: 40px; opacity: 0.4;"></i>
                            <p class="text-muted mt-2 mb-2">No call logs yet.</p>
                            <a href="{{ route('admin.call-logs.create', ['blood_request_id' => $bloodRequest->id]) }}" class="btn btn-sm btn-success"><i class="fas fa-phone"></i> Add Call Log</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #1A6B2E, #28a745); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight: 600;">Eligible Donors</h5>
                    <span class="badge badge-success ml-auto" id="donorCount">Loading...</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Last Donation</th>
                                    <th>Reliability</th>
                                </tr>
                            </thead>
                            <tbody id="donorsTableBody">
                                <tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-1"></i> Loading donors...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('admin.blood-requests.match', $bloodRequest->id) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-handshake mr-1"></i> View Full Matching
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important; }
</style>
@stop

@section('js')
<script>
$(function() {
    $.get('{{ route("admin.donors.by-blood-group", $bloodRequest->blood_group) }}', function(data) {
        var $tbody = $('#donorsTableBody');
        var $count = $('#donorCount');

        if (data.length === 0) {
            $tbody.html('<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-info-circle mr-1"></i> No eligible donors found</td></tr>');
            $count.text('0');
            return;
        }

        var html = '';
        $.each(data, function(i, d) {
            var days = d.days_since_last ? d.days_since_last + ' days ago' : '<span class="badge badge-success">Never</span>';
            var reliabilityColor = d.reliability_score >= 70 ? 'success' : (d.reliability_score >= 40 ? 'warning' : 'danger');
            html += '<tr>' +
                '<td class="font-weight-bold">' + (d.name || '') + '</td>' +
                '<td>' + (d.phone || '') + '</td>' +
                '<td>' + (d.city_name || 'N/A') + '</td>' +
                '<td>' + days + '</td>' +
                '<td><div class="progress" style="height:6px;max-width:60px;display:inline-block;vertical-align:middle;"><div class="progress-bar bg-' + reliabilityColor + '" style="width:' + d.reliability_score + '%"></div></div> <small class="font-weight-bold">' + d.reliability_score + '%</small></td>' +
                '</tr>';
        });
        $tbody.html(html);
        $count.text(data.length);
    }).fail(function() {
        $('#donorsTableBody').html('<tr><td colspan="5" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle mr-1"></i> Failed to load donors</td></tr>');
        $('#donorCount').text('0');
    });
});
</script>
@stop
