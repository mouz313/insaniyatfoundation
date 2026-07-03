<section id="blood-groups" class="section-padding reveal" style="background:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="fas fa-tint me-2"></i>Availability</div>
            <h2 class="section-heading">{{ $settings['blood_section_heading'] ?? 'Blood Groups Availability' }}</h2>
            <p class="section-sub">Real-time availability of blood groups from our inventory and registered donors.</p>
        </div>
        <div class="row g-4">
            @foreach($bloodGroups as $bg)
                @php
                    $availColors = ['available' => ['bg' => '#d4edda', 'text' => '#155724', 'badge' => 'Available', 'icon' => 'fa-check-circle'],
                                    'moderate' => ['bg' => '#fff3cd', 'text' => '#856404', 'badge' => 'Limited', 'icon' => 'fa-exclamation-circle'],
                                    'low' => ['bg' => '#f8d7da', 'text' => '#721c24', 'badge' => 'Critical', 'icon' => 'fa-times-circle']];
                    $ac = $availColors[$bg->availability] ?? $availColors['low'];
                @endphp
                <div class="col-6 col-md-3 col-lg-3">
                    <div class="blood-card">
                        <div class="blood-icon-circle" style="background:{{ $ac['bg'] }};">
                            <i class="fas fa-tint" style="font-size:28px;color:{{ $ac['text'] }};"></i>
                        </div>
                        <div class="blood-group-name">{{ $bg->group }}</div>
                        <span class="blood-badge" style="background:{{ $ac['bg'] }};color:{{ $ac['text'] }};">
                            <i class="fas {{ $ac['icon'] }} me-1"></i>{{ $ac['badge'] }}
                        </span>
                        <div style="font-size:13px;color:#888;">
                            <div><strong style="color:var(--secondary);">{{ $bg->eligible_donors }}</strong> Eligible Donors</div>
                            <div><strong style="color:var(--secondary);">{{ $bg->inventory }}</strong> Units in Stock</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('portal.registration.create') }}" class="btn-custom-primary">
                <i class="fas fa-user-plus me-2"></i>Register as Donor
            </a>
        </div>
    </div>
</section>
