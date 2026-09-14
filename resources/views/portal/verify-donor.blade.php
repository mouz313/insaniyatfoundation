@extends('portal.layout')

@section('title', 'Verified Donor - ' . $donor->name)

@push('css')
<style>
.verify-card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    background: #ffffff;
}
.verify-header {
    background: linear-gradient(135deg, #1A6B2E 0%, #145A26 100%);
    color: #fff;
    padding: 30px 24px 24px;
    text-align: center;
    position: relative;
}
.verify-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    margin: 0 auto 14px;
    border: 4px solid #fff;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    overflow: hidden;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}
.verify-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.pulse-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
}
.pulse-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    animation: pulse 1.8s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.5; }
    100% { transform: scale(0.95); opacity: 1; }
}
.stat-pill {
    background: #f8f9fa;
    border-radius: 14px;
    padding: 14px 10px;
    text-align: center;
    border: 1px solid #edf2f7;
}
.stat-val {
    font-size: 22px;
    font-weight: 800;
    line-height: 1.2;
}
.stat-lbl {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width: 680px;">
    <div class="verify-card">
        <div class="verify-header">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3" style="background:rgba(255,255,255,0.18);border-radius:30px;font-size:12px;letter-spacing:0.5px;">
                <i class="fas fa-check-circle text-warning"></i>
                <span class="text-uppercase font-weight-bold">Officially Verified Donor</span>
            </div>
            <div class="verify-avatar">
                @if($donor->photo)
                    <img src="{{ asset('storage/' . $donor->photo) }}" alt="{{ $donor->name }}">
                @else
                    <i class="fas fa-user text-success" style="font-size:46px;"></i>
                @endif
            </div>
            <h2 class="font-weight-bold mb-1" style="font-size:24px;">{{ $donor->name }}</h2>
            <div class="text-white-50 small mb-2"><code>{{ $donor->registration_no ?? 'ID: #' . $donor->id }}</code></div>
            <div>
                <span class="badge badge-light px-3 py-2 mr-1" style="font-size:15px;color:#dc3545;font-weight:700;border-radius:20px;">
                    <i class="fas fa-tint"></i> {{ $donor->blood_group }}
                </span>
                @php $tier = $donor->tier; @endphp
                <span class="badge px-3 py-2" style="background:{{ $tier['bg'] }};color:{{ $tier['color'] }};border:1px solid {{ $tier['color'] }};font-size:13px;border-radius:20px;">
                    <i class="fas {{ $tier['icon'] }} mr-1"></i> {{ $tier['name'] }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Live Eligibility Status --}}
            <div class="text-center mb-4">
                @if($donor->is_eligible)
                    <div class="pulse-badge" style="background:rgba(40,167,69,0.15);color:#28a745;border:1.5px solid #28a745;">
                        <span class="pulse-dot" style="background:#28a745;"></span>
                        <span>ELIGIBLE TO DONATE BLOOD TODAY</span>
                    </div>
                @else
                    <div class="pulse-badge" style="background:rgba(255,193,7,0.15);color:#856404;border:1.5px solid #ffc107;">
                        <span class="pulse-dot" style="background:#ffc107;"></span>
                        <span>TEMPORARILY DEFERRED</span>
                    </div>
                    @if($donor->days_until_eligible > 0)
                        <div class="text-muted small mt-2">
                            <i class="fas fa-hourglass-half text-warning mr-1"></i> Next eligible donation in <strong>{{ $donor->days_until_eligible }} days</strong> ({{ $donor->next_eligible_date->format('d M, Y') }})
                        </div>
                    @endif
                @endif
            </div>

            {{-- Stat Pills --}}
            <div class="row g-2 mb-4">
                <div class="col-4">
                    <div class="stat-pill">
                        <div class="stat-val text-success">{{ $donor->total_donations ?? 0 }}</div>
                        <div class="stat-lbl">Donations</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-pill">
                        <div class="stat-val text-info">{{ $donor->reliability_score }}%</div>
                        <div class="stat-lbl">Reliability</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-pill">
                        <div class="stat-val text-primary">{{ $donor->city->name ?? 'Pakistan' }}</div>
                        <div class="stat-lbl">City</div>
                    </div>
                </div>
            </div>

            {{-- Details List --}}
            <div class="p-3 mb-4" style="background:#f8f9fa;border-radius:14px;">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="fas fa-venus-mars mr-1"></i> Gender</span>
                    <span class="font-weight-bold">{{ ucfirst($donor->gender ?? 'Not Specified') }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="fas fa-calendar-check mr-1"></i> Last Donation</span>
                    <span class="font-weight-bold">{{ $donor->last_donation_date ? $donor->last_donation_date->format('d M Y') : 'Never Donated' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="fas fa-award mr-1"></i> Honor Tier</span>
                    <span class="font-weight-bold text-success">{{ $tier['name'] }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted"><i class="fas fa-shield-alt mr-1"></i> Security Status</span>
                    <span class="badge badge-success" style="border-radius:10px;"><i class="fas fa-check mr-1"></i> Authenticated</span>
                </div>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row gap-2">
                @if($donor->whatsapp_url)
                    <a href="{{ $donor->whatsapp_url }}" target="_blank" class="btn btn-success flex-fill py-2" style="background:#25D366;border-color:#25D366;font-weight:600;border-radius:12px;">
                        <i class="fab fa-whatsapp mr-1"></i> Contact via WhatsApp
                    </a>
                @endif
                <a href="tel:{{ $ngoPhone }}" class="btn btn-outline-danger flex-fill py-2" style="font-weight:600;border-radius:12px;">
                    <i class="fas fa-phone-alt mr-1"></i> Call Blood Bank
                </a>
            </div>

            <div class="text-center text-muted small mt-4 pt-3 border-top">
                <i class="fas fa-lock text-success mr-1"></i> Verified on {{ now()->format('l, F j, Y - h:i A') }} &bull; {{ $ngoName }}
            </div>
        </div>
    </div>
</div>
@stop
