<section id="how-it-works" class="section-padding position-relative reveal" style="background:#f8f9fa;">
    <div class="step-connector d-none d-lg-block"></div>
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="fas fa-arrow-right me-2"></i>Process</div>
            <h2 class="section-heading">{{ $settings['how_section_heading'] ?? 'How It Works' }}</h2>
            <p class="section-sub">Donating blood is simple and takes less than an hour of your time.</p>
        </div>
        @php
            $steps = [
                ['icon' => $settings['how_step_1_icon'] ?? 'fas fa-user-plus', 'title' => $settings['how_step_1_title'] ?? 'Register', 'desc' => $settings['how_step_1_desc'] ?? 'Sign up as a donor with your basic information and medical details.'],
                ['icon' => $settings['how_step_2_icon'] ?? 'fas fa-calendar-check', 'title' => $settings['how_step_2_title'] ?? 'Get Notified', 'desc' => $settings['how_step_2_desc'] ?? 'Receive alerts when blood of your type is needed near you.'],
                ['icon' => $settings['how_step_3_icon'] ?? 'fas fa-tint', 'title' => $settings['how_step_3_title'] ?? 'Donate', 'desc' => $settings['how_step_3_desc'] ?? 'Visit a nearby drive or center. The process takes just 30 minutes.'],
                ['icon' => $settings['how_step_4_icon'] ?? 'fas fa-heart', 'title' => $settings['how_step_4_title'] ?? 'Save Lives', 'desc' => $settings['how_step_4_desc'] ?? 'Your donation is tested, processed, and delivered to patients in need.'],
            ];
        @endphp
        <div class="row g-4">
            @foreach($steps as $i => $step)
                <div class="col-lg-3">
                    <div class="step-card">
                        <div class="step-icon-circle">
                            <i class="{{ $step['icon'] }}" style="font-size:28px;color:var(--primary);"></i>
                            <div class="step-number">{{ $i + 1 }}</div>
                        </div>
                        <h5 style="font-weight:700;color:var(--secondary);margin-bottom:10px;">{{ $step['title'] }}</h5>
                        <p style="font-size:14px;color:#888;line-height:1.7;margin-bottom:0;">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
