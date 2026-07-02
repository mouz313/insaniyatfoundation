@extends('adminlte::page')

@section('title', 'Donor Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #dc3545, #e4606d); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(220,53,69,0.3);">
                <i class="fas fa-user text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">{{ $donor->name }}</h1>
                <small class="text-muted"><i class="fas fa-fw fa-id-badge"></i> {{ $donor->registration_no ?? 'N/A' }} &bull; <i class="fas fa-fw fa-calendar"></i> Registered {{ $donor->created_at->format('d M Y') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center mt-2 mt-md-0">
            <a href="{{ route('admin.donors.certificate', $donor) }}" class="btn btn-danger btn-sm mr-1" target="_blank"><i class="fas fa-certificate"></i> Certificate</a>
            <form action="{{ route('admin.donor-cards.print') }}" method="POST" target="_blank" style="display:inline;">@csrf
                <input type="hidden" name="donor_ids" value="{{ json_encode([$donor->id]) }}">
                <button type="submit" class="btn btn-info btn-sm mr-1"><i class="fas fa-id-card"></i> Card</button>
            </form>
            <a href="{{ route('admin.donors.edit', $donor->id) }}" class="btn btn-warning btn-sm mr-1"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('admin.donors.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
@stop

@section('content')
    {{-- Eligibility Banner --}}
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card {{ $elgStatus == 'eligible' ? 'bg-gradient-success' : ($elgStatus == 'temporarily' ? 'bg-gradient-warning' : 'bg-gradient-secondary') }} shadow-sm border-0 rounded-lg">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <i class="fas fa-{{ $elgStatus == 'eligible' ? 'check-circle' : ($elgStatus == 'temporarily' ? 'hourglass-half' : 'ban') }} text-white" style="font-size: 32px;"></i>
                        </div>
                        <div class="col-md-11">
                            <div class="d-flex align-items-center text-white flex-wrap" style="gap: 15px;">
                                <span class="badge badge-light" style="font-size:1rem;padding:6px 20px;">
                                    @if($elgStatus == 'eligible')
                                        <i class="fas fa-check-circle text-success"></i> Eligible to Donate
                                    @elseif($elgStatus == 'temporarily')
                                        <i class="fas fa-clock text-warning"></i> Temporarily Deferred
                                    @else
                                        <i class="fas fa-ban text-danger"></i> Permanently Ineligible
                                    @endif
                                </span>
                                @if($donor->last_donation_date)
                                    <small class="text-white-50">Last donation: {{ $donor->last_donation_date->format('d M Y') }}</small>
                                @endif
                                <span class="badge badge-light"><i class="fas fa-star text-warning"></i> Reliability: {{ $donor->reliability_score }}%</span>
                            </div>
                            @if(!empty($eligibilityReasons))
                                <div class="mt-2">
                                    @foreach($eligibilityReasons as $reason)
                                        <span class="badge badge-light mr-1 mb-1" style="font-size:11px;color:#856404;">
                                            <i class="fas fa-exclamation-circle"></i> {{ $reason }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
                    <div style="width: 110px; height: 110px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 4px solid #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        @if($donor->photo)
                            <img src="{{ asset('storage/' . $donor->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user text-white" style="font-size:40px;"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="mt-3 mb-0" style="font-weight:600;">{{ $donor->name }}</h4>
                    <small class="text-muted"><code>{{ $donor->registration_no ?? 'No Reg No' }}</code></small>
                    <div class="mt-1">
                        <span class="badge badge-danger" style="font-size:1rem;padding:5px 18px;border-radius:20px;"><i class="fas fa-tint"></i> {{ $donor->blood_group }}</span>
                        <span class="badge {{ $donor->status == 'active' ? 'badge-success' : ($donor->status == 'ineligible' ? 'badge-danger' : 'badge-secondary') }}" style="font-size:0.85rem;padding:5px 14px;border-radius:20px;">{{ ucfirst($donor->status) }}</span>
                    </div>
                    @if($donor->is_student)
                        <span class="badge badge-info mt-1" style="border-radius:20px;"><i class="fas fa-graduation-cap"></i> Student</span>
                    @endif
                    @if($donor->badges->count() > 0)
                        <div class="mt-2">
                            @foreach($donor->badges as $badge)
                                <span class="badge" style="background:{{ $badge->color }};color:#fff;border-radius:20px;padding:3px 10px;margin:2px;" title="{{ $badge->description }}">
                                    <i class="fas {{ $badge->icon }} mr-1"></i>{{ $badge->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <small class="text-muted d-block text-center mt-1" style="font-size:11px;">100% if donated at least once, 0% otherwise</small>
                    {{-- Stats Strip --}}
                    <div class="mt-3 d-flex justify-content-center" style="gap:8px;">
                        <div class="text-center px-2">
                            <div style="width:60px;height:60px;border-radius:50%;background:conic-gradient(#dc3545 {{ $donor->reliability_score }}%, #f0f0f0 0);display:flex;align-items:center;justify-content:center;margin:0 auto;" title="100% if donated at least once">
                                <div style="width:48px;height:48px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;color:#dc3545;">{{ $donor->reliability_score }}%</div>
                            </div>
                            <small class="text-muted d-block mt-1">Reliability</small>
                        </div>
                        <div class="text-center px-2">
                            <div style="width:60px;height:60px;border-radius:50%;background:conic-gradient(#28a745 {{ min(100, $donor->total_donations * 10) }}%, #f0f0f0 0);display:flex;align-items:center;justify-content:center;margin:0 auto;" title="Life-time donation count">
                                <div style="width:48px;height:48px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;color:#28a745;">{{ $donor->total_donations }}</div>
                            </div>
                            <small class="text-muted d-block mt-1">Donations</small>
                        </div>
                        <div class="text-center px-2">
                            <div style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                <img src="data:image/svg+xml;base64,{{ $donorQr }}" alt="QR" style="width:60px;height:60px;">
                            </div>
                            <small class="text-muted d-block mt-1">Donor QR</small>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <hr class="mt-0">
                    <dl class="row mb-0" style="font-size:14px;">
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-user"></i> Father</dt>
                        <dd class="col-7">{{ $donor->father_name ?? 'N/A' }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-id-card"></i> CNIC</dt>
                        <dd class="col-7">{{ $donor->cnic ?? 'N/A' }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-phone"></i> Phone</dt>
                        <dd class="col-7">{{ $donor->phone }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-calendar-alt"></i> DOB</dt>
                        <dd class="col-7">{{ $donor->dob ? $donor->dob->format('d M Y') . ' (' . $donor->age . ' yrs)' : 'N/A' }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-venus-mars"></i> Gender</dt>
                        <dd class="col-7">{{ ucfirst($donor->gender ?? 'N/A') }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-weight"></i> Weight</dt>
                        <dd class="col-7">{{ $donor->weight ? $donor->weight . ' kg' : 'N/A' }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-tint"></i> Hemoglobin</dt>
                        <dd class="col-7">{{ $donor->hemoglobin ? $donor->hemoglobin . ' g/dL' : 'N/A' }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-graduation-cap"></i> Education</dt>
                        <dd class="col-7">{{ $donor->education ?? 'N/A' }}</dd>
                        <dt class="col-5 text-muted"><i class="fas fa-fw fa-calendar-check"></i> Last Donation</dt>
                        <dd class="col-7">{{ $donor->last_donation_date ? $donor->last_donation_date->format('d M Y') : 'Never' }}</dd>
                    </dl>
                    @if(!empty($donor->health_flags_list))
                        <hr>
                        <div style="font-size:13px;font-weight:600;color:#495057;margin-bottom:6px;"><i class="fas fa-exclamation-triangle text-warning"></i> Health Flags</div>
                        @foreach($donor->health_flags_list as $flag)
                            <span class="badge badge-warning mr-1">{{ ucfirst(str_replace('_', ' ', $flag)) }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Contact & Location --}}
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#17a2b8,#20c997);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight:600;">Contact & Location</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <dl class="row mb-0" style="font-size:14px;">
                                <dt class="col-5 text-muted">Address</dt><dd class="col-7">{{ $donor->address ?? 'N/A' }}</dd>
                                <dt class="col-5 text-muted">City</dt><dd class="col-7">{{ $donor->city->name ?? 'N/A' }}</dd>
                                <dt class="col-5 text-muted">Area</dt><dd class="col-7">{{ $donor->area->name ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                        <div class="col-sm-6">
                            <dl class="row mb-0" style="font-size:14px;">
                                <dt class="col-5 text-muted">University</dt><dd class="col-7">{{ $donor->university_display ?: 'N/A' }}</dd>
                                <dt class="col-5 text-muted">Student</dt><dd class="col-7">{{ $donor->is_student ? 'Yes' : 'No' }}</dd>
                                <dt class="col-5 text-muted">Referred By</dt>
                                <dd class="col-7">
                                    @if($donor->referrer)
                                        <a href="{{ route('portal.referrer', $donor->referrer->cnic) }}" target="_blank" class="text-danger font-weight-bold">{{ $donor->referrer->name }} <i class="fas fa-external-link-alt" style="font-size:10px;"></i></a>
                                    @else
                                        N/A
                                    @endif
                                </dd>
                                <dt class="col-5 text-muted">Referrals</dt>
                                <dd class="col-7"><span class="badge badge-{{ $donor->referrals_count > 0 ? 'success' : 'secondary' }}" style="border-radius:20px;">{{ $donor->referrals_count }} donor{{ $donor->referrals_count !== 1 ? 's' : '' }}</span></dd>
                                <dt class="col-5 text-muted">Share Profile</dt>
                                <dd class="col-7">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="shareUrl" class="form-control form-control-sm" value="{{ route('portal.referrer', $donor->cnic) }}" readonly style="background:#fff;font-size:12px;cursor:text;">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="copyShareUrl()" title="Copy to Clipboard"><i class="fas fa-copy"></i></button>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Blood Donation History --}}
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#dc3545,#e4606d);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-tint text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight:600;">Blood Donation History</h5>
                    <a href="{{ route('admin.blood-donations.create', ['donor_id' => $donor->id]) }}" class="btn btn-sm btn-danger ml-auto" style="border-radius:20px;"><i class="fas fa-plus mr-1"></i> Add</a>
                    <span class="badge badge-danger ml-2">{{ $donor->donations->count() }} records</span>
                </div>
                <div class="card-body p-0">
                    @if($donor->donations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light"><tr><th>#</th><th>Date</th><th>Patient</th><th>Units</th><th>Campaign</th><th>Status</th></tr></thead>
                                <tbody>
                                    @foreach($donor->donations as $bd)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($bd->donation_date)->format('d M Y') }}</td>
                                            <td>{{ $bd->patient_name ?? 'N/A' }}</td>
                                            <td><strong>{{ $bd->units }}</strong></td>
                                            <td>{{ $bd->campaign->name ?? 'N/A' }}</td>
                                            <td>@switch($bd->status)
                                                    @case('donated') <span class="badge badge-pill badge-success">Donated</span> @break
                                                    @case('pending') <span class="badge badge-pill badge-warning">Pending</span> @break
                                                    @case('deferred') <span class="badge badge-pill badge-danger">Deferred</span> @break
                                                    @default <span class="badge badge-pill badge-secondary">{{ $bd->status }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5"><i class="fas fa-tint text-muted" style="font-size:48px;opacity:0.4;"></i><p class="text-muted mt-2 mb-0">No blood donations recorded.</p></div>
                    @endif
                </div>
            </div>

            {{-- Call Logs / Reliability --}}
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#fd7e14,#f39c12);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-phone-alt text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight:600;">Call History</h5>
                    <span class="badge badge-warning ml-auto">{{ $callLogs->count() }} calls</span>
                </div>
                <div class="card-body p-0">
                    @if($callLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light"><tr><th>Date</th><th>Request</th><th>Staff</th><th>Outcome</th><th>Notes</th></tr></thead>
                                <tbody>
                                    @foreach($callLogs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                            <td><a href="{{ route('admin.blood-requests.show', $log->bloodRequest) }}">{{ $log->bloodRequest->patient_name ?? 'N/A' }}</a></td>
                                            <td>{{ $log->staff->name ?? 'N/A' }}</td>
                                            <td><span class="badge badge-{{ $log->outcome == 'donor_found' ? 'success' : ($log->outcome == 'refused' ? 'danger' : 'info') }}">{{ str_replace('_', ' ', ucfirst($log->outcome)) }}</span></td>
                                            <td><small>{{ $log->notes ?? '—' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4"><p class="text-muted mb-0">No call history.</p></div>
                    @endif
                </div>
            </div>

            {{-- Money Donations + Annual Summary --}}
            ...
        </div>
    </div>
@stop

@section('js')
<script>
function copyShareUrl() {
    var input = document.getElementById('shareUrl');
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand('copy');
    toastr.success('Profile URL copied to clipboard!', 'Copied');
}
</script>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important; }
.bg-gradient-success { background: linear-gradient(135deg, #28a745, #20c997) !important; }
.bg-gradient-warning { background: linear-gradient(135deg, #e67e22, #f39c12) !important; }
.bg-gradient-secondary { background: linear-gradient(135deg, #6c757d, #868e96) !important; }
</style>
@stop