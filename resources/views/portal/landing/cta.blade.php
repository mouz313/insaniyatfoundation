<section id="contact" class="section-padding cta-section">
    <div class="cta-overlay"></div>
    @if(!empty($settings['cta_bg_image']) && file_exists(storage_path('app/public/' . $settings['cta_bg_image'])))
        <div class="cta-bg-img" style="background-image:url('{{ asset('storage/' . $settings['cta_bg_image']) }}')"></div>
    @endif
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 style="font-size:2.8rem;font-weight:800;color:#fff;margin-bottom:16px;">{{ $settings['cta_heading'] ?? 'Ready to Make a Difference?' }}</h2>
                <p style="font-size:1.2rem;color:rgba(255,255,255,0.7);margin-bottom:35px;">{{ $settings['cta_subheading'] ?? 'Join thousands of donors saving lives every day' }}</p>
                <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
                    <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-custom-primary" style="padding:16px 40px;font-size:16px;">
                        <i class="fas fa-tint me-2"></i>{{ $settings['cta_donate_btn_text'] ?? 'Donate Blood Now' }}
                    </a>
                    <a href="{{ route('portal.registration.create') }}" class="btn-custom-outline" style="padding:16px 40px;font-size:16px;">
                        <i class="fas fa-user-plus me-2"></i>{{ $settings['cta_register_btn_text'] ?? 'Register as Donor' }}
                    </a>
                </div>
                <div class="row g-3 justify-content-center">
                    @if(!empty($settings['cta_phone']))
                        <div class="col-auto">
                            <div class="cta-contact-chip"><i class="fas fa-phone-alt" style="color:var(--primary);margin-right:8px;"></i> {{ $settings['cta_phone'] }}</div>
                        </div>
                    @endif
                    @if(!empty($settings['cta_whatsapp']))
                        <div class="col-auto">
                            <div class="cta-contact-chip"><i class="fab fa-whatsapp" style="color:#25d366;margin-right:8px;"></i> {{ $settings['cta_whatsapp'] }}</div>
                        </div>
                    @endif
                    @if(!empty($settings['cta_email']))
                        <div class="col-auto">
                            <div class="cta-contact-chip"><i class="fas fa-envelope" style="color:var(--accent);margin-right:8px;"></i> {{ $settings['cta_email'] }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
