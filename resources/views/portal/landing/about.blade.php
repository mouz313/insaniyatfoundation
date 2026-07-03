<section id="about" class="section-padding reveal" style="background:#fff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="section-tag">{{ $settings['about_eyebrow'] ?? 'Who We Are' }}</div>
                <h2 class="section-heading">{{ $settings['about_heading'] ?? 'Making Blood Donation Accessible for Everyone' }}</h2>
                <p style="color:#666;line-height:1.8;font-size:1.05rem;margin-bottom:30px;">
                    {{ $settings['about_body'] ?? '' }}
                </p>
                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="about-card">
                            <div style="font-size:2.5rem;font-weight:800;color:var(--primary);">{{ $settings['about_founded_year'] ?? '2018' }}</div>
                            <div style="font-size:13px;color:#888;font-weight:500;">Founded</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-card about-card-green">
                            <div style="font-size:1rem;font-weight:600;color:var(--secondary);line-height:1.5;">{{ $settings['about_mission'] ?? '' }}</div>
                        </div>
                    </div>
                </div>
                <a href="#campaigns" class="btn-custom-primary">
                    <i class="fas fa-arrow-right me-2"></i>{{ $settings['about_cta_text'] ?? 'Our Story' }}
                </a>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <div class="about-img-wrap">
                        @if(!empty($settings['about_image_1']) && file_exists(storage_path('app/public/' . $settings['about_image_1'])))
                            <img src="{{ asset('storage/' . $settings['about_image_1']) }}" alt="About" style="width:100%;height:400px;object-fit:cover;">
                        @else
                            <div class="about-img-placeholder">
                                <i class="fas fa-users" style="font-size:80px;color:rgba(255,255,255,0.3);"></i>
                            </div>
                        @endif
                    </div>
                    @if(!empty($settings['about_image_2']) && file_exists(storage_path('app/public/' . $settings['about_image_2'])))
                        <div class="about-img-overlay">
                            <img src="{{ asset('storage/' . $settings['about_image_2']) }}" alt="About 2" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
