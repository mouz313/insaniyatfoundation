@extends('portal.layout')

@section('title', 'Check Availability')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
main > .container { max-width: 100%; padding-left: 40px; padding-right: 40px; }
.swiper-button-next, .swiper-button-prev { color: #fff; background: rgba(26,107,46,0.85); width: 40px; height: 40px; border-radius: 50%; }
.swiper-button-next::after, .swiper-button-prev::after { font-size: 16px; }
.swiper-button-next { right: 10px; }
.swiper-button-prev { left: 10px; }
.swiper-pagination-bullet { background: rgba(255,255,255,0.4); opacity: 1; }
.swiper-pagination-bullet-active { background: var(--primary-light); width: 24px; border-radius: 4px; }
.donor-card { transition: transform 0.25s ease, box-shadow 0.25s ease; border-left: 4px solid var(--primary) !important; }
.donor-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important; }
.stat-card { transition: transform 0.2s; }
.stat-card:hover { transform: translateY(-3px); }
</style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <div style="width:80px;height:80px;border-radius:20px;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 10px 30px rgba(26,107,46,0.3);">
                <i class="fas fa-tint text-white" style="font-size:36px;"></i>
            </div>
            <h1 style="font-weight:800;color:var(--secondary);font-size:2.2rem;">Blood Availability</h1>
            <p class="text-muted" style="font-size:1.05rem;">Find active donors by blood group and city</p>
        </div>

        {{-- Carousel --}}
        @if($carouselDonors->count() > 0)
            <div class="mb-5" style="border-radius:24px;background:var(--gradient-hero);padding:3px;">
                <div style="border-radius:22px;background:linear-gradient(135deg,rgba(255,255,255,0.08),rgba(255,255,255,0.02));padding:30px 50px 50px;">
                    <div class="d-flex align-items-center mb-4 px-3">
                        <span style="background:rgba(26,107,46,0.2);color:var(--primary-light);padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;letter-spacing:1px;"><i class="fas fa-crown me-1"></i> FEATURED DONORS</span>
                    </div>
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            @foreach($carouselDonors as $d)
                                <div class="swiper-slide">
                                    <div class="row align-items-center">
                                        <div class="col-md-5 text-center mb-4 mb-md-0">
                                            <div style="position:relative;display:inline-block;">
                                                <div style="width:140px;height:140px;border-radius:50%;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:52px;color:#fff;border:4px solid rgba(255,255,255,0.2);box-shadow:0 15px 40px rgba(26,107,46,0.4);margin:0 auto;">
                                                    {{ strtoupper(substr($d->name, 0, 1)) }}
                                                </div>
                                                <div style="position:absolute;bottom:5px;right:5px;background:#ffc107;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;border:3px solid #1a1a2e;box-shadow:0 4px 10px rgba(0,0,0,0.3);">
                                                    <i class="fas fa-tint" style="color:var(--primary);font-size:14px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 text-center text-md-start">
                                            <h3 style="font-weight:800;color:#fff;font-size:1.6rem;margin-bottom:4px;">{{ $d->name }}</h3>
                                            <span style="background:var(--gradient-primary);color:#fff;padding:4px 18px;border-radius:20px;font-size:15px;font-weight:700;display:inline-block;margin-bottom:14px;"><i class="fas fa-tint me-1"></i> {{ $d->blood_group }}</span>
                                            <div class="d-flex flex-wrap justify-content-center justify-content-md-start" style="gap:12px;">
                                                <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:10px 18px;text-align:center;min-width:90px;">
                                                    <div style="font-size:20px;font-weight:800;color:#ffc107;">{{ $d->total_donations }}</div>
                                                    <small style="color:rgba(255,255,255,0.6);font-size:11px;">Donations</small>
                                                </div>
                                                <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:10px 18px;text-align:center;min-width:90px;">
                                                    <div style="font-size:20px;font-weight:800;color:#fff;">{{ $d->city->name ?? 'N/A' }}</div>
                                                    <small style="color:rgba(255,255,255,0.6);font-size:11px;">City</small>
                                                </div>
                                                <div style="background:rgba(255,255,255,0.08);border-radius:12px;padding:10px 18px;text-align:center;min-width:90px;">
                                                    <div style="font-size:20px;font-weight:800;color:#fff;">{{ $d->last_donation_date ? \Carbon\Carbon::parse($d->last_donation_date)->format('d M') : '--' }}</div>
                                                    <small style="color:rgba(255,255,255,0.6);font-size:11px;">Last Donation</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination" style="bottom:5px;"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Stat Cards --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 rounded-lg h-100 stat-card" style="border-left:4px solid #ffc107;">
                    <div class="card-body d-flex align-items-center">
                        <div style="width:56px;height:56px;border-radius:14px;background:#fff3cd;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-trophy text-warning" style="font-size:24px;"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted d-block font-weight-bold">TOP DONOR THIS MONTH</small>
                            @if($topDonors->count() > 0)
                                <strong style="font-size:16px;">{{ $topDonors->first()->donor->name }}</strong>
                                <small class="d-block text-muted">{{ $topDonors->first()->donation_count }} donation{{ $topDonors->first()->donation_count !== 1 ? 's' : '' }}</small>
                            @else
                                <strong style="font-size:16px;">No donations yet</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 rounded-lg h-100 stat-card" style="border-left:4px solid #17a2b8;">
                    <div class="card-body d-flex align-items-center">
                        <div style="width:56px;height:56px;border-radius:14px;background:#d1ecf1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-user-plus text-info" style="font-size:24px;"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted d-block font-weight-bold">TOP REFERRER</small>
                            @if($topReferrer->count() > 0)
                                <strong style="font-size:16px;">{{ $topReferrer->first()->name }}</strong>
                                <small class="d-block text-muted">{{ $topReferrer->first()->referrals_count }} referral{{ $topReferrer->first()->referrals_count !== 1 ? 's' : '' }}</small>
                            @else
                                <strong style="font-size:16px;">No referrals yet</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-lg h-100 stat-card" style="border-left:4px solid var(--primary);">
                    <div class="card-body d-flex align-items-center">
                        <div style="width:56px;height:56px;border-radius:14px;background:#d4edda;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-user" style="color:var(--primary);font-size:24px;"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted d-block font-weight-bold">NEWEST DONOR</small>
                            @if($newDonors->count() > 0)
                                <strong style="font-size:16px;">{{ $newDonors->first()->name }}</strong>
                                <small class="d-block text-muted">{{ $newDonors->first()->created_at->format('d M Y') }}</small>
                            @else
                                <strong style="font-size:16px;">No donors yet</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Form --}}
        <div class="card shadow-sm border-0 rounded-lg mb-4" style="border-top:4px solid var(--primary);">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('portal.availability') }}" id="availabilityForm">
                    <input type="hidden" name="search" value="1">
                    <div class="row align-items-end">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label class="font-weight-bold text-muted small">Blood Group</label>
                            <select name="blood_group" class="form-select rounded-pill" style="border-color:#dee2e6;">
                                <option value="">All Groups</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label class="font-weight-bold text-muted small">City</label>
                            <select name="city_id" class="form-select rounded-pill" style="border-color:#dee2e6;">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-custom-primary rounded-pill py-2" style="flex:1;justify-content:center;">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                            @if(request()->has('search'))
                                <a href="{{ route('portal.availability') }}" class="btn btn-outline-secondary rounded-pill py-2" style="font-weight:600;flex-shrink:0;display:flex;align-items:center;justify-content:center;width:44px;" title="Clear Search">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($donors))
            <div class="d-flex align-items-center mb-3">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                    <i class="fas fa-users text-white" style="font-size:16px;"></i>
                </div>
                <h5 class="mb-0" style="font-weight:700;">{{ $donors->count() }} Donor{{ $donors->count() !== 1 ? 's' : '' }} Found</h5>
            </div>

            @if($donors->count() > 0)
                <div class="row">
                    @foreach($donors as $donor)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm border-0 rounded-lg h-100 donor-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width:48px;height:48px;border-radius:50%;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;font-size:18px;color:#fff;">
                                            {{ strtoupper(substr($donor->name, 0, 1)) }}
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0" style="font-weight:700;">{{ $donor->name }}</h6>
                                            <small class="text-muted"><i class="fas fa-phone-alt me-1" style="width:14px;"></i>{{ $donor->phone }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap" style="gap:8px;">
                                        <span class="badge rounded-pill px-3 py-2" style="background:var(--gradient-primary);color:#fff;font-size:13px;font-weight:600;">
                                            <i class="fas fa-tint me-1"></i>{{ $donor->blood_group }}
                                        </span>
                                        <span class="badge rounded-pill px-3 py-2" style="background:#f8f9fa;color:#495057;font-size:13px;font-weight:500;border:1px solid #dee2e6;">
                                            <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>{{ $donor->city->name ?? 'N/A' }}
                                        </span>
                                        @if($donor->area)
                                            <span class="badge rounded-pill px-3 py-2" style="background:#f8f9fa;color:#495057;font-size:13px;font-weight:500;border:1px solid #dee2e6;">
                                                <i class="fas fa-map-pin me-1" style="color:var(--primary);"></i>{{ $donor->area->name }}
                                            </span>
                                        @endif
                                        <span class="badge rounded-pill px-3 py-2" style="background:#f8f9fa;color:#495057;font-size:13px;font-weight:500;border:1px solid #dee2e6;">
                                            <i class="fas fa-calendar me-1" style="color:var(--primary);"></i>{{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y') : 'Never' }}
                                        </span>
                                        @if($donor->total_donations > 0)
                                            <span class="badge rounded-pill px-3 py-2" style="background:var(--gradient-primary);color:#fff;font-size:13px;font-weight:600;">
                                                <i class="fas fa-heart me-1"></i>{{ $donor->total_donations }} donation{{ $donor->total_donations !== 1 ? 's' : '' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body text-center py-5">
                        <div style="width:80px;height:80px;border-radius:50%;background:#f8f9fa;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <i class="fas fa-search text-muted" style="font-size:32px;"></i>
                        </div>
                        <h5 style="font-weight:600;color:#495057;">No Donors Found</h5>
                        <p class="text-muted mb-0">Try a different blood group or city.</p>
                    </div>
                </div>
            @endif
        @else
            <div class="card shadow-sm border-0 rounded-lg" style="border:2px dashed #dee2e6;">
                <div class="card-body text-center py-5">
                    <div style="width:80px;height:80px;border-radius:50%;background:#f8f9fa;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-hand-pointer text-muted" style="font-size:32px;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#495057;">Select a Blood Group</h5>
                    <p class="text-muted mb-0">Choose a blood group and city above, then click Search.</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
new Swiper('.swiper', {
    loop: true,
    autoplay: { delay: 4500, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
});
</script>
@endpush
