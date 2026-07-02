<footer class="footer-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-logo">
                    @if($ngo['logo'] && file_exists(storage_path('app/public/' . $ngo['logo'])))
                        <img src="{{ asset('storage/' . $ngo['logo']) }}" alt="Logo" class="logo-img">
                    @else
                        <i class="fas fa-tint" style="color:var(--primary);"></i> <span>{{ $ngo['name'] }}</span>
                    @endif
                </div>
                <p class="footer-desc">{{ $ngo['footer_text'] }}</p>
                @if(!empty($ngo['address']))
                    <div class="footer-contact-item mb-2">
                        <i class="fas fa-map-marker-alt" style="color:var(--primary);"></i>
                        <span>{{ $ngo['address'] }}</span>
                    </div>
                @endif
                <div class="d-flex gap-2 mt-3">
                    @if(!empty($settings['cta_whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['cta_whatsapp']) }}" target="_blank" class="footer-social-link" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                    @if(!empty($settings['cta_email']))
                        <a href="mailto:{{ $settings['cta_email'] }}" class="footer-social-link" title="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    @endif
                    @if(!empty($settings['cta_phone']))
                        <a href="tel:{{ $settings['cta_phone'] }}" class="footer-social-link" title="Phone">
                            <i class="fas fa-phone-alt"></i>
                        </a>
                    @endif
                    <a href="#" class="footer-social-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="footer-social-link" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6 class="footer-heading">Quick Links</h6>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="#about" class="footer-link">About Us</a>
                    <a href="#blood-groups" class="footer-link">Blood Groups</a>
                    <a href="#campaigns" class="footer-link">Campaigns</a>
                    <a href="#how-it-works" class="footer-link">How It Works</a>
                    <a href="#testimonials" class="footer-link">Stories</a>
                    <a href="{{ route('portal.verify') }}" class="footer-link"><i class="fas fa-id-card me-1"></i> Verify Donor</a>
                    <a href="{{ route('portal.availability') }}" class="footer-link"><i class="fas fa-search me-1"></i> Blood Availability</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6 class="footer-heading">Contact Info</h6>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @if(!empty($ngo['footer_phone']) || !empty($settings['cta_phone']))
                        <div class="footer-contact-item">
                            <i class="fas fa-phone-alt" style="color:var(--primary);"></i>
                            <span>{{ $ngo['footer_phone'] ?: ($settings['cta_phone'] ?? '') }}</span>
                        </div>
                    @endif
                    @if(!empty($ngo['footer_email']) || !empty($settings['cta_email']))
                        <div class="footer-contact-item">
                            <i class="fas fa-envelope" style="color:var(--primary);"></i>
                            <span>{{ $ngo['footer_email'] ?: ($settings['cta_email'] ?? '') }}</span>
                        </div>
                    @endif
                    @if(!empty($settings['cta_whatsapp']))
                        <div class="footer-contact-item">
                            <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                            <span>{{ $settings['cta_whatsapp'] }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6 class="footer-heading">Get Involved</h6>
                <p class="footer-desc" style="margin-bottom:15px;">Every drop counts. Register today and become a hero in your community.</p>
                <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn" style="background:var(--gradient-primary);color:#fff;border-radius:50px;padding:10px 28px;font-weight:600;font-size:14px;border:none;">
                    <i class="fas fa-tint me-1"></i> Donate Now
                </a>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="text-center">
            <p class="footer-copyright">&copy; {{ date('Y') }} {{ $ngo['name'] }}. All rights reserved. Made with <i class="fas fa-heart" style="color:var(--primary);"></i> for humanity.</p>
        </div>
    </div>
</footer>
