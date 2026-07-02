@extends('portal.layout')

@section('title', $donor->name . ' — Referrer Profile')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-lg overflow-hidden" style="border-left:4px solid #dc3545;">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div style="width:160px;height:160px;border-radius:50%;background:linear-gradient(135deg,#dc3545,#e4606d);margin:0 auto;display:flex;align-items:center;justify-content:center;overflow:hidden;border:4px solid #fff;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
                            @if($donor->photo)
                                <img src="{{ asset('storage/' . $donor->photo) }}" alt="Photo" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:48px;font-weight:700;color:#fff;">{{ strtoupper(substr($donor->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <span class="badge rounded-pill px-4 py-2" style="background:#dc3545;color:#fff;font-size:15px;font-weight:600;">
                                <i class="fas fa-tint mr-1"></i> {{ $donor->blood_group }}
                            </span>
                            <span class="badge rounded-pill px-3 py-2 ml-1" style="background:{{ $donor->status == 'active' ? '#28a745' : '#6c757d' }};color:#fff;font-size:13px;">
                                {{ ucfirst($donor->status) }}
                            </span>
                        </div>
                        @if($donor->badges->count() > 0)
                            <div class="mt-2">
                                @foreach($donor->badges as $badge)
                                    <span class="badge" style="background:{{ $badge->color }};color:#fff;border-radius:20px;padding:3px 10px;margin:2px;" title="{{ $badge->description }}">
                                        <i class="fas {{ $badge->icon }} mr-1"></i>{{ $badge->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3" style="font-weight:700;">{{ $donor->name }}</h4>
                        <div class="row" style="font-size:14px;">
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block"><i class="fas fa-fw fa-id-card mr-1"></i> Registration No</small>
                                <strong>{{ $donor->registration_no ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block"><i class="fas fa-fw fa-calendar mr-1"></i> Registered Since</small>
                                <strong>{{ $donor->created_at->format('d M Y') }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block"><i class="fas fa-fw fa-heart mr-1"></i> Total Donations</small>
                                <strong>{{ $donor->total_donations }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block"><i class="fas fa-fw fa-calendar-check mr-1"></i> Last Donation</small>
                                <strong>{{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y') : 'Never' }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block"><i class="fas fa-fw fa-map-marker-alt mr-1"></i> City</small>
                                <strong>{{ $donor->city->name ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block"><i class="fas fa-fw fa-user-friends mr-1"></i> People Referred</small>
                                <strong>{{ $donor->referrals_count }} donor{{ $donor->referrals_count !== 1 ? 's' : '' }}</strong>
                            </div>
                        </div>
                        @if($donor->referrals_count > 0)
                            <div class="mt-3 p-3 rounded-lg" style="background:#fff3cd;border:1px solid #ffc107;">
                                <i class="fas fa-info-circle text-warning mr-1"></i>
                                This donor has referred <strong>{{ $donor->referrals_count }}</strong> other donor{{ $donor->referrals_count !== 1 ? 's' : '' }} to the blood donation program.
                            </div>
                        @else
                            <div class="mt-3 p-3 rounded-lg" style="background:#e9ecef;border:1px solid #dee2e6;">
                                <i class="fas fa-info-circle text-muted mr-1"></i>
                                This donor has not referred anyone yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('portal.registration.create', ['ref_cnic' => $donor->cnic]) }}" class="btn btn-danger rounded-pill px-4 py-2" style="font-weight:600;">
                <i class="fas fa-user-plus mr-1"></i> Register as Donor — Referred by {{ $donor->name }}
            </a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-weight:600;">
                <i class="fas fa-home mr-1"></i> Back to Home
            </a>
        </div>
    </div>
@endsection
