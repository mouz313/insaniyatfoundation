@extends('adminlte::page')

@section('title', 'Matching Donors - ' . $bloodRequest->patient_name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width:52px;height:52px;background:linear-gradient(135deg,#dc3545,#e4606d);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(220,53,69,0.3);">
                <i class="fas fa-handshake text-white" style="font-size:22px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight:600;">Donor Matching</h1>
                <small class="text-muted">{{ $bloodRequest->patient_name }} &bull; {{ $bloodRequest->blood_group }} &bull; {{ $bloodRequest->city->name ?? 'N/A' }}</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.call-logs.create', ['blood_request_id' => $bloodRequest->id]) }}" class="btn btn-outline-info btn-sm mr-1"><i class="fas fa-plus mr-1"></i>Quick Log</a>
            <a href="{{ route('admin.blood-requests.show', $bloodRequest) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a>
        </div>
    </div>
@stop

@section('content')
    {{-- Request Summary Banner --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center p-3" style="background:#fff;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,0.06);gap:24px;">
                <div class="d-flex align-items-center">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#dc3545,#e4606d);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:10px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold" style="font-size:15px;">{{ $bloodRequest->patient_name }}</div>
                        <small class="text-muted">Patient</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#28a745,#20c997);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:10px;">
                        <i class="fas fa-tint text-white"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold" style="font-size:18px;">{{ $bloodRequest->blood_group }}</div>
                        <small class="text-muted">Blood Group</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#17a2b8,#0dcaf0);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:10px;">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold" style="font-size:15px;">{{ $bloodRequest->city->name ?? 'N/A' }}</div>
                        <small class="text-muted">Location</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#6f42c1,#6610f2);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:10px;">
                        <i class="fas fa-flask text-white"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold" style="font-size:15px;">{{ $bloodRequest->units_required }}</div>
                        <small class="text-muted">Units Needed</small>
                    </div>
                </div>
                <div class="ml-auto">
                    <span class="badge badge-{{ $bloodRequest->status === 'resolved' ? 'success' : ($bloodRequest->status === 'pending' ? 'warning' : 'secondary') }}" style="font-size:14px;padding:6px 18px;border-radius:20px;">
                        {{ ucfirst($bloodRequest->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Strip --}}
    <div class="row mb-3">
        <div class="col-md-3 col-6 mb-2">
            <div class="text-center p-3" style="background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                <div class="font-weight-bold" style="font-size:28px;color:#dc3545;">{{ $donors->count() }}</div>
                <small class="text-muted">Eligible Donors</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="text-center p-3" style="background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                <div class="font-weight-bold" style="font-size:28px;color:#17a2b8;">{{ $donors->where('same_city', true)->count() }}</div>
                <small class="text-muted">Same City</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="text-center p-3" style="background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                <div class="font-weight-bold" style="font-size:28px;color:#28a745;">{{ $donors->where('match_score', '>=', 70)->count() }}</div>
                <small class="text-muted">High Match (&ge;70)</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="text-center p-3" style="background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                <div class="font-weight-bold" style="font-size:28px;color:#ffc107;">{{ $donors->where('match_score', '>=', 40)->where('match_score', '<', 70)->count() }}</div>
                <small class="text-muted">Medium Match</small>
            </div>
        </div>
    </div>

    {{-- Donor Cards Grid --}}
    @if($donors->count() > 0)
        <div class="row">
            @foreach($donors as $donor)
                <div class="col-lg-6 mb-3">
                    <div class="match-card" data-score="{{ $donor->match_score }}">
                        <div class="match-card-header">
                            <div class="match-card-left">
                                <div class="match-avatar" style="background:{{ ['#dc3545','#28a745','#17a2b8','#6610f2','#e83e8c','#fd7e14','#20c997','#6f42c1'][crc32($donor->id) % 8] }};">
                                    {{ strtoupper(substr($donor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.donors.show', $donor) }}" class="match-name">{{ $donor->name }}</a>
                                    <div class="match-meta">
                                        <span><i class="fas fa-phone mr-1"></i>{{ $donor->phone }}</span>
                                        @if($donor->city)
                                            <span class="ml-2"><i class="fas fa-map-marker-alt mr-1"></i>{{ $donor->city->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="match-score-ring">
                                <svg width="56" height="56" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="#f0f0f0" stroke-width="2.5"/>
                                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="{{ $donor->match_score >= 70 ? '#28a745' : ($donor->match_score >= 40 ? '#ffc107' : '#6c757d') }}" stroke-width="2.5" stroke-dasharray="{{ $donor->match_score }},100" stroke-linecap="round" transform="rotate(-90 18 18)"/>
                                    <text x="18" y="20" text-anchor="middle" font-size="8" font-weight="700" fill="#343a40">{{ $donor->match_score }}</text>
                                </svg>
                                <small class="text-muted">Score</small>
                            </div>
                        </div>

                        <div class="match-card-body">
                            <div class="match-badges">
                                <span class="match-badge match-badge-blood">{{ $donor->blood_group }}</span>
                                @if($donor->same_city)
                                    <span class="match-badge match-badge-city"><i class="fas fa-check-circle mr-1"></i>Same City</span>
                                @endif
                                @if($donor->last_contacted)
                                    <span class="match-badge match-badge-contacted"><i class="fas fa-phone mr-1"></i>Contacted {{ $donor->last_contacted->diffForHumans() }}</span>
                                @endif
                            </div>

                            <div class="match-breakdown">
                                <div class="breakdown-item">
                                    <div class="breakdown-label">
                                        <span>Blood Group</span>
                                        <strong>{{ $donor->match_breakdown['blood_group'] ?? 0 }}/30</strong>
                                    </div>
                                    <div class="breakdown-bar"><div class="breakdown-fill" style="width:{{ (($donor->match_breakdown['blood_group'] ?? 0) / 30) * 100 }}%;background:#dc3545;"></div></div>
                                </div>
                                <div class="breakdown-item">
                                    <div class="breakdown-label">
                                        <span>Location</span>
                                        <strong>{{ $donor->match_breakdown['same_city'] ?? 0 }}/20</strong>
                                    </div>
                                    <div class="breakdown-bar"><div class="breakdown-fill" style="width:{{ (($donor->match_breakdown['same_city'] ?? 0) / 20) * 100 }}%;background:#17a2b8;"></div></div>
                                </div>
                                <div class="breakdown-item">
                                    <div class="breakdown-label">
                                        <span>Reliability</span>
                                        <strong>{{ $donor->match_breakdown['reliability'] ?? 0 }}/20</strong>
                                    </div>
                                    <div class="breakdown-bar"><div class="breakdown-fill" style="width:{{ (($donor->match_breakdown['reliability'] ?? 0) / 20) * 100 }}%;background:#6610f2;"></div></div>
                                </div>
                                <div class="breakdown-item">
                                    <div class="breakdown-label">
                                        <span>Recency</span>
                                        <strong>{{ $donor->match_breakdown['days_since_donation'] ?? 0 }}/15</strong>
                                    </div>
                                    <div class="breakdown-bar"><div class="breakdown-fill" style="width:{{ (($donor->match_breakdown['days_since_donation'] ?? 0) / 15) * 100 }}%;background:#ffc107;"></div></div>
                                </div>
                                <div class="breakdown-item">
                                    <div class="breakdown-label">
                                        <span>History</span>
                                        <strong>{{ $donor->match_breakdown['donation_history'] ?? 0 }}/15</strong>
                                    </div>
                                    <div class="breakdown-bar"><div class="breakdown-fill" style="width:{{ (($donor->match_breakdown['donation_history'] ?? 0) / 15) * 100 }}%;background:#20c997;"></div></div>
                                </div>
                            </div>

                            <div class="match-footer">
                                <div class="match-last-donation">
                                    @if($donor->last_donation_date)
                                        <i class="fas fa-calendar-alt mr-1"></i>Last: {{ $donor->last_donation_date->format('d M Y') }}
                                        <small class="text-muted">({{ $donor->days_since_last }} days ago)</small>
                                    @else
                                        <span class="text-success"><i class="fas fa-check-circle mr-1"></i>Never donated</span>
                                    @endif
                                </div>
                                <div class="match-actions">
                                    <button class="btn btn-sm btn-success btn-call" data-donor="{{ $donor->id }}" data-name="{{ $donor->name }}" data-request="{{ $bloodRequest->id }}">
                                        <i class="fas fa-phone mr-1"></i>Call
                                    </button>
                                    <a href="{{ route('admin.donors.show', $donor) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5" style="background:#fff;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <div style="width:80px;height:80px;border-radius:50%;background:rgba(108,117,125,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="fas fa-users text-muted" style="font-size:36px;opacity:0.4;"></i>
            </div>
            <p class="text-muted h5 mb-1">No eligible matching donors found</p>
            <p class="text-muted mb-0">Try expanding the search or checking other blood groups.</p>
        </div>
    @endif

    {{-- Call Log Modal --}}
    <div class="modal fade" id="callModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header" style="background:linear-gradient(135deg,#28a745,#20c997);border:none;padding:18px 24px;">
                    <h5 class="modal-title text-white"><i class="fas fa-phone mr-1"></i> Call <span id="callDonorName" class="font-weight-bold"></span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" id="callForm">
                    @csrf
                    <div class="modal-body" style="padding:24px;">
                        <input type="hidden" name="outcome" id="callOutcome">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase">Outcome</label>
                            <div class="row g-2">
                                <div class="col-6 mb-2">
                                    <button type="button" class="btn btn-block outcome-btn outcome-donor" data-value="donor_found">
                                        <i class="fas fa-check-circle mr-1"></i>Found
                                    </button>
                                </div>
                                <div class="col-6 mb-2">
                                    <button type="button" class="btn btn-block outcome-btn outcome-refused" data-value="refused">
                                        <i class="fas fa-times-circle mr-1"></i>Refused
                                    </button>
                                </div>
                                <div class="col-6 mb-2">
                                    <button type="button" class="btn btn-block outcome-btn outcome-noanswer" data-value="no_answer">
                                        <i class="fas fa-phone-slash mr-1"></i>No Answer
                                    </button>
                                </div>
                                <div class="col-6 mb-2">
                                    <button type="button" class="btn btn-block outcome-btn outcome-callback" data-value="call_back">
                                        <i class="fas fa-clock mr-1"></i>Call Back
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted" id="outcomeHelp">Select an outcome above</small>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-muted small text-uppercase">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any notes about this call..." style="border-radius:10px;border:1.5px solid #e9ecef;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:14px 24px;">
                        <button type="submit" class="btn btn-success px-4" id="submitCallBtn" disabled><i class="fas fa-save mr-1"></i> Save Call Log</button>
                        <button type="button" class="btn btn-light px-3" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.match-card {
    background:#fff;
    border-radius:14px;
    box-shadow:0 2px 12px rgba(0,0,0,0.06);
    overflow:hidden;
    transition:all 0.25s ease;
    height:100%;
    border-left:4px solid #dee2e6;
}
.match-card[data-score="100"],
.match-card[data-score^="9"],
.match-card[data-score^="8"],
.match-card[data-score^="7"] { border-left-color:#28a745; }
.match-card[data-score^="6"],
.match-card[data-score^="5"],
.match-card[data-score^="4"] { border-left-color:#ffc107; }
.match-card[data-score^="3"],
.match-card[data-score^="2"],
.match-card[data-score^="1"],
.match-card[data-score="0"] { border-left-color:#6c757d; }
.match-card:hover {
    transform:translateY(-3px);
    box-shadow:0 8px 30px rgba(0,0,0,0.1);
}
.match-card-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 18px 12px;
    border-bottom:1px solid #f0f0f0;
}
.match-card-left { display:flex;align-items:center;gap:12px; }
.match-avatar {
    width:44px;height:44px;border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-weight:700;font-size:18px;
    flex-shrink:0;
}
.match-name {
    font-weight:600;font-size:15px;color:#343a40;
    text-decoration:none;
}
.match-name:hover { color:#dc3545; }
.match-meta { font-size:12px;color:#6c757d; }
.match-score-ring { text-align:center; }
.match-score-ring small { display:block;font-size:10px;margin-top:-2px; }
.match-card-body { padding:12px 18px 16px; }
.match-badges { display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px; }
.match-badge {
    display:inline-flex;align-items:center;
    padding:3px 12px;border-radius:20px;
    font-size:12px;font-weight:600;
}
.match-badge-blood { background:#dc3545;color:#fff; }
.match-badge-city { background:rgba(23,162,184,0.12);color:#17a2b8; }
.match-badge-contacted { background:rgba(108,117,125,0.1);color:#6c757d; }
.match-breakdown { display:flex;flex-direction:column;gap:6px;margin-bottom:12px; }
.breakdown-item { }
.breakdown-label {
    display:flex;justify-content:space-between;
    font-size:12px;color:#6c757d;margin-bottom:2px;
}
.breakdown-label strong { font-size:11px;color:#343a40; }
.breakdown-bar {
    height:4px;background:#f0f0f0;border-radius:4px;overflow:hidden;
}
.breakdown-fill { height:100%;border-radius:4px;transition:width 0.6s ease; }
.match-footer {
    display:flex;justify-content:space-between;align-items:center;
    padding-top:10px;border-top:1px solid #f0f0f0;
}
.match-last-donation { font-size:12px;color:#6c757d; }
.match-actions { display:flex;gap:6px; }

/* Outcome buttons */
.outcome-btn {
    border-radius:12px !important;
    padding:12px 8px !important;
    font-weight:600;font-size:13px;
    border:2px solid #e9ecef !important;
    background:#fff !important;
    transition:all 0.2s ease;
}
.outcome-btn:hover { transform:translateY(-2px); }
.outcome-btn.active {
    border-width:2px !important;
    transform:translateY(-2px);
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.outcome-donor.active { border-color:#28a745 !important;background:rgba(40,167,69,0.08) !important;color:#28a745 !important; }
.outcome-refused.active { border-color:#dc3545 !important;background:rgba(220,53,69,0.08) !important;color:#dc3545 !important; }
.outcome-noanswer.active { border-color:#ffc107 !important;background:rgba(255,193,7,0.08) !important;color:#856404 !important; }
.outcome-callback.active { border-color:#17a2b8 !important;background:rgba(23,162,184,0.08) !important;color:#17a2b8 !important; }
</style>
@stop

@section('js')
<script>
$(function() {
    $('.btn-call').on('click', function() {
        var donorId = $(this).data('donor');
        var donorName = $(this).data('name');
        var requestId = $(this).data('request');
        $('#callDonorName').text(donorName);
        $('#callForm').attr('action', '{{ url("admin/blood-requests") }}/' + requestId + '/match/' + donorId + '/call');
        $('#callOutcome').val('');
        $('#outcomeHelp').text('Select an outcome above').removeClass('text-success text-danger');
        $('.outcome-btn').removeClass('active');
        $('#submitCallBtn').prop('disabled', true);
        $('#callModal').modal('show');
    });

    $('.outcome-btn').on('click', function() {
        $('.outcome-btn').removeClass('active');
        $(this).addClass('active');
        $('#callOutcome').val($(this).data('value'));
        var labels = { donor_found: 'Donor confirmed and willing to donate!', refused: 'Donor refused to donate.', no_answer: 'No answer, try again later.', call_back: 'Asked to call back later.' };
        var colors = { donor_found: 'text-success', refused: 'text-danger', no_answer: 'text-warning', call_back: 'text-info' };
        var val = $(this).data('value');
        $('#outcomeHelp').text(labels[val] || '').removeClass('text-success text-danger text-warning text-info').addClass(colors[val] || '');
        $('#submitCallBtn').prop('disabled', false);
    });

    $('#callForm').on('submit', function(e) {
        if (!$('#callOutcome').val()) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Select Outcome', text: 'Please select a call outcome before saving.', confirmButtonColor: '#dc3545' });
        }
    });
});
</script>
@stop
