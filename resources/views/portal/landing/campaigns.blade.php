<section id="campaigns" class="section-padding reveal" style="background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="fas fa-calendar-alt me-2"></i>Events</div>
            <h2 class="section-heading">{{ $settings['campaigns_section_heading'] ?? 'Upcoming Blood Drives' }}</h2>
            <p class="section-sub">Join our upcoming blood donation camps and make a difference in your community.</p>
        </div>
        @if($campaigns->count() > 0)
            <div class="row g-4">
                @foreach($campaigns as $campaign)
                    <div class="col-md-6 col-lg-4">
                        <div class="campaign-card">
                            <div class="campaign-header">
                                <div style="text-align:center;position:relative;z-index:1;">
                                    <div class="campaign-date-day">{{ $campaign->date->format('d') }}</div>
                                    <div class="campaign-date-month">{{ $campaign->date->format('M Y') }}</div>
                                </div>
                            </div>
                            <div class="campaign-body">
                                <h5 class="campaign-title">{{ $campaign->name }}</h5>
                                <div class="campaign-meta"><i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i> {{ $campaign->venue }}</div>
                                <div class="campaign-meta" style="margin-bottom:15px;"><i class="fas fa-clock me-1" style="color:var(--primary);"></i> {{ $campaign->date->format('l, d M Y') }}</div>
                                <div style="margin-bottom:12px;">
                                    <div class="d-flex justify-content-between" style="font-size:13px;font-weight:600;color:var(--secondary);">
                                        <span>{{ $campaign->collected_units }} / {{ $campaign->target_units }} Units</span>
                                        <span>{{ $campaign->progress }}%</span>
                                    </div>
                                    <div class="campaign-progress-bar">
                                        <div class="campaign-progress-fill" style="width:{{ $campaign->progress }}%;"></div>
                                    </div>
                                </div>
                                <a href="{{ route('portal.registration.create') }}" class="campaign-join-btn btn btn-sm">
                                    <i class="fas fa-hand-holding-heart me-1"></i> Join Drive
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt" style="font-size:60px;color:#ddd;"></i>
                <p style="color:#999;margin-top:15px;font-size:1.1rem;">No upcoming drives scheduled. Check back soon!</p>
            </div>
        @endif
    </div>
</section>
