<section class="hero-section">
    <div class="hero-overlay"></div>
    @if(!empty($settings['hero_bg_image']) && file_exists(storage_path('app/public/' . $settings['hero_bg_image'])))
        <div class="hero-bg-img" style="background-image:url('{{ asset('storage/' . $settings['hero_bg_image']) }}')"></div>
    @endif
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                @if(!empty($settings['hero_tagline']))
                    <div class="section-tag mb-3" style="background:rgba(255,255,255,0.1);color:#fff;">
                        <i class="fas fa-heartbeat me-2"></i>{{ $settings['hero_tagline'] }}
                    </div>
                @endif
                <h1 style="font-size:3.8rem;font-weight:900;color:#fff;line-height:1.1;margin-bottom:20px;">
                    {{ $settings['hero_sub_tagline'] ?? 'Every Drop Counts' }}
                </h1>
                <p style="font-size:1.2rem;color:rgba(255,255,255,0.7);max-width:560px;line-height:1.8;margin-bottom:35px;">
                    {{ $settings['hero_description'] ?? '' }}
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-custom-primary">
                        <i class="fas fa-tint me-2"></i>{{ $settings['hero_cta_text'] ?? 'Donate Blood' }}
                    </a>
                    <a href="#about" class="btn-custom-outline">
                        {{ $settings['hero_secondary_cta'] ?? 'Learn More' }} <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="row mt-5 g-3">
                    @php
                        $heroStats = [
                            ['value' => $settings['hero_stat_1_value'] ?? '5000+', 'label' => $settings['hero_stat_1_label'] ?? 'Lives Saved'],
                            ['value' => $settings['hero_stat_2_value'] ?? '2000+', 'label' => $settings['hero_stat_2_label'] ?? 'Donors Registered'],
                            ['value' => $settings['hero_stat_3_value'] ?? '150+', 'label' => $settings['hero_stat_3_label'] ?? 'Drives Organized'],
                        ];
                    @endphp
                    @foreach($heroStats as $s)
                        <div class="col-4">
                            <div class="hero-stat-card">
                                <div class="hero-stat-value">{{ $s['value'] }}</div>
                                <div class="hero-stat-label">{{ $s['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div class="float-anim" style="width:320px;height:320px;margin:0 auto;background:rgba(220,53,69,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid rgba(220,53,69,0.3);">
                    <div style="width:200px;height:200px;background:rgba(220,53,69,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid rgba(220,53,69,0.2);">
                        <div style="text-align:center;color:#fff;">
                            <i class="fas fa-tint hero-drip-icon"></i>
                            <div style="margin-top:10px;font-weight:700;font-size:14px;color:rgba(255,255,255,0.7);">DONATE BLOOD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-gradient-bottom"></div>
</section>
