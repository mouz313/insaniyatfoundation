<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['hero_tagline'] ?? 'Blood Donor' }} | {{ $ngo['name'] }}</title>
    @if($ngo['favicon'] && file_exists(storage_path('app/public/' . $ngo['favicon'])))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $ngo['favicon']) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩸</text></svg>">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @stack('styles')
    <style>
        :root {
            --primary: #1A6B2E;
            --primary-dark: #145A26;
            --primary-light: #28a745;
            --secondary: #1a1a2e;
            --accent: #1565A8;
            --gradient-hero: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            --gradient-primary: linear-gradient(135deg, #1A6B2E, #28a745);
            --gradient-accent: linear-gradient(135deg, #1565A8, #1a7acc);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; color: #333; }
        .landing-wrapper { width: 100%; overflow: hidden; }

        @keyframes drip {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(8px); opacity: 0.7; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(220,53,69,0.3); }
            50% { box-shadow: 0 0 40px rgba(220,53,69,0.6); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes countUp { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        @keyframes reveal {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.8s ease forwards; }
        .float-anim { animation: float 4s ease-in-out infinite; }
        .reveal { opacity: 0; transform: translateY(40px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        .section-padding { padding: 100px 0; }
        .section-tag { display: inline-block; background: rgba(220,53,69,0.1); color: var(--primary); padding: 6px 20px; border-radius: 50px; font-size: 13px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px; }
        .section-heading { font-size: 2.5rem; font-weight: 800; color: var(--secondary); margin-bottom: 16px; line-height: 1.2; }
        .section-sub { font-size: 1.1rem; color: #666; max-width: 600px; margin: 0 auto 50px; line-height: 1.7; }

        .btn-custom-primary { background: var(--gradient-primary); color: #fff; border: none; padding: 14px 36px; border-radius: 50px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-custom-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(220,53,69,0.4); color: #fff; }
        .btn-custom-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.5); padding: 12px 32px; border-radius: 50px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-custom-outline:hover { background: rgba(255,255,255,0.1); border-color: #fff; color: #fff; }

        .navbar-landing { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 15px 0; transition: all 0.3s ease; }
        .navbar-landing.scrolled { background: rgba(26,26,46,0.95); backdrop-filter: blur(10px); padding: 8px 0; box-shadow: 0 2px 20px rgba(0,0,0,0.2); }
        .navbar-landing .navbar-brand { font-weight: 800; color: #fff; font-size: 1.5rem; display: flex; align-items: center; gap: 10px; }
        .navbar-landing .navbar-brand span { color: var(--primary); }
        .navbar-landing .navbar-brand .logo-img { height: 36px; width: auto; border-radius: 6px; }
        .navbar-landing .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; margin: 0 12px; transition: color 0.2s; font-size: 14px; }
        .navbar-landing .nav-link:hover { color: #fff !important; }
        .navbar-landing .btn-nav-donate { background: var(--gradient-primary); color: #fff; padding: 8px 24px; border-radius: 50px; font-weight: 600; font-size: 14px; }

        .hero-section { background: var(--gradient-hero); min-height: 100vh; display: flex; align-items: center; position: relative; overflow: hidden; padding-top: 80px; }
        .hero-overlay { position: absolute; inset: 0; opacity: 0.05; background: radial-gradient(circle at 20% 50%, #1A6B2E 0%, transparent 50%), radial-gradient(circle at 80% 20%, #28a745 0%, transparent 50%), radial-gradient(circle at 50% 80%, #0f3460 0%, transparent 50%); }
        .hero-bg-img { position: absolute; inset: 0; background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0.15; }
        .hero-gradient-bottom { position: absolute; bottom: 0; left: 0; right: 0; height: 100px; background: linear-gradient(to top, #f8f9fa, transparent); }
        .hero-stat-card { background: rgba(255,255,255,0.08); border-radius: 16px; padding: 20px 12px; text-align: center; backdrop-filter: blur(10px); }
        .hero-stat-value { font-size: 2rem; font-weight: 800; color: #fff; }
        .hero-stat-label { font-size: 12px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.5px; }
        .hero-drip-icon { display: inline-block; font-size: 80px; animation: drip 2s ease-in-out infinite; color: var(--primary); }

        .stat-card { padding: 25px 15px; border-radius: 16px; background: #f8f9fa; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .stat-icon-box { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .stat-icon-box i { font-size: 24px; }
        .stat-number { font-size: 2.2rem; font-weight: 800; color: var(--secondary); }
        .stat-label { font-size: 14px; color: #888; font-weight: 500; }

        .about-card { background: #f8f9fa; border-radius: 16px; padding: 25px; text-align: center; border-left: 4px solid var(--primary); }
        .about-card-green { border-left-color: #28a745; }
        .about-img-wrap { border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .about-img-placeholder { width: 100%; height: 400px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; }
        .about-img-overlay { position: absolute; bottom: -30px; right: -20px; width: 200px; height: 150px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 4px solid #fff; }

        .blood-card { background: #fff; border-radius: 20px; padding: 30px 20px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #eee; height: 100%; }
        .blood-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        .blood-icon-circle { width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
        .blood-group-name { font-weight: 800; font-size: 1.8rem; color: var(--secondary); margin-bottom: 5px; }
        .blood-badge { display: inline-block; padding: 4px 16px; border-radius: 50px; font-size: 12px; font-weight: 600; margin-bottom: 15px; }

        .campaign-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; height: 100%; transition: all 0.3s ease; }
        .campaign-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        .campaign-header { height: 180px; background: linear-gradient(135deg, #1A6B2E, #28a745); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
        .campaign-date-day { font-size: 2.5rem; font-weight: 800; color: #fff; }
        .campaign-date-month { font-size: 13px; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 1px; }
        .campaign-body { padding: 24px; }
        .campaign-title { font-weight: 700; color: var(--secondary); margin-bottom: 8px; }
        .campaign-meta { font-size: 13px; color: #888; margin-bottom: 4px; }
        .campaign-progress-bar { height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden; margin-top: 5px; }
        .campaign-progress-fill { height: 100%; background: var(--gradient-primary); border-radius: 10px; transition: width 1s ease; }
        .campaign-join-btn { background: var(--gradient-primary); color: #fff; border-radius: 50px; padding: 6px 20px; font-weight: 600; font-size: 13px; border: none; }

        .step-card { text-align: center; padding: 40px 25px; border-radius: 20px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f0f0f0; transition: all 0.3s ease; height: 100%; }
        .step-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); border-color: rgba(26,107,46,0.15); }
        .step-icon-circle { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, rgba(220,53,69,0.08), rgba(228,96,109,0.15)); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; position: relative; border: 2px solid rgba(220,53,69,0.1); }
        .step-number { position: absolute; top: -6px; right: -6px; width: 30px; height: 30px; border-radius: 50%; background: var(--gradient-primary); color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(220,53,69,0.3); }
        .step-connector { position: absolute; top: 290px; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #1A6B2E, #28a745, #1565A8); opacity: 0.2; border-radius: 10px; z-index: 0; }

        .testimonial-card { background: #f8f9fa; border-radius: 20px; padding: 35px; height: 100%; border-left: 4px solid var(--primary); transition: box-shadow 0.3s ease; }
        .testimonial-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .testimonial-avatar { width: 50px; height: 50px; border-radius: 50%; overflow: hidden; flex-shrink: 0; margin-right: 15px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; }

        .cta-section { background: var(--gradient-hero); position: relative; overflow: hidden; }
        .cta-overlay { position: absolute; inset: 0; opacity: 0.05; background: radial-gradient(circle at 30% 50%, #1A6B2E 0%, transparent 50%), radial-gradient(circle at 70% 50%, #1565A8 0%, transparent 50%); }
        .cta-bg-img { position: absolute; inset: 0; background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0.1; }
        .cta-contact-chip { background: rgba(255,255,255,0.08); border-radius: 12px; padding: 12px 24px; backdrop-filter: blur(10px); color: #fff; font-weight: 500; }

        .footer-section { background: var(--secondary); padding: 60px 0 30px; border-top: 1px solid rgba(255,255,255,0.05); }
        .footer-logo { font-weight: 800; color: #fff; font-size: 1.5rem; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .footer-logo .logo-img { height: 36px; width: auto; border-radius: 6px; }
        .footer-logo span { color: var(--primary); }
        .footer-desc { color: rgba(255,255,255,0.5); font-size: 14px; line-height: 1.7; }
        .footer-heading { color: #fff; font-weight: 600; margin-bottom: 20px; font-size: 1rem; }
        .footer-link { color: rgba(255,255,255,0.5); text-decoration: none; font-size: 14px; transition: color 0.2s; }
        .footer-link:hover { color: #fff; }
        .footer-contact-item { color: rgba(255,255,255,0.5); font-size: 14px; display: flex; align-items: flex-start; gap: 10px; }
        .footer-contact-item i { margin-top: 4px; }
        .footer-social-link { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); transition: all 0.3s ease; text-decoration: none; font-size: 16px; }
        .footer-social-link:hover { background: var(--gradient-primary); color: #fff; transform: translateY(-2px); }
        .footer-divider { border-color: rgba(255,255,255,0.05); margin: 30px 0 20px; }
        .footer-copyright { color: rgba(255,255,255,0.3); font-size: 13px; margin: 0; }

        @media (max-width: 768px) {
            .section-padding { padding: 60px 0; }
            .section-heading { font-size: 1.8rem; }
            .hero-stat-value { font-size: 1.5rem; }
            .about-img-overlay { width: 140px; height: 100px; bottom: -15px; right: -10px; }
        }
    </style>
</head>
<body>
    <div class="landing-wrapper">
        @include('portal.landing.navbar')
        @include('portal.landing.hero')
        @include('portal.landing.stats')
        @include('portal.landing.about')
        @include('portal.landing.blood-groups')
        @include('portal.landing.campaigns')
        @include('portal.landing.how-it-works')
        @include('portal.landing.testimonials')
        @include('portal.landing.cta')
        @include('portal.landing.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        $(function() {
            var navbar = $('.navbar-landing');
            $(window).on('scroll', function() {
                if ($(window).scrollTop() > 50) navbar.addClass('scrolled');
                else navbar.removeClass('scrolled');
            });

            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $(this.hash);
                if (target.length) $('html, body').animate({ scrollTop: target.offset().top - 70 }, 600);
            });

            var animated = false;

            function animateCounters() {
                if (animated) return;
                animated = true;
                $('.stat-number').each(function() {
                    var $this = $(this);
                    var target = parseInt($this.data('target')) || 0;
                    var suffix = $this.data('suffix') || '';
                    $({ count: 0 }).animate({ count: target }, {
                        duration: 2000, easing: 'swing',
                        step: function() {
                            var val = Math.floor(this.count);
                            $this.text(val.toLocaleString() + suffix);
                        },
                        complete: function() { $this.text(target.toLocaleString() + suffix); }
                    });
                });
            }

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        if (entry.target.classList.contains('stats-section')) {
                            animateCounters();
                        }
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.reveal').forEach(function(el) {
                observer.observe(el);
            });

        });
    </script>
</body>
</html>
