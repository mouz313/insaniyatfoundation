@php
    $settings = \App\Models\Setting::all()->pluck('value', 'key');
    $ngo = [
        'name' => $settings['ngo_name'] ?? config('app.name', 'Insaaniyat Foundation'),
        'logo' => $settings['ngo_logo'] ?? null,
        'favicon' => $settings['favicon'] ?? null,
        'footer_text' => $settings['footer_text'] ?? '',
        'footer_phone' => $settings['footer_phone'] ?? '',
        'footer_email' => $settings['footer_email'] ?? '',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $ngo['name']) | {{ $ngo['name'] }}</title>
    @if($ngo['favicon'] && file_exists(storage_path('app/public/' . $ngo['favicon'])))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $ngo['favicon']) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩸</text></svg>">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @stack('css')
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
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; min-height: 100vh; display: flex; flex-direction: column; color: #333; }
        main { flex: 1; }

        .portal-navbar { background: var(--secondary); padding: 12px 0; box-shadow: 0 2px 20px rgba(0,0,0,0.15); }
        .portal-navbar .navbar-brand { font-weight: 800; color: #fff; font-size: 1.3rem; display: flex; align-items: center; gap: 10px; }
        .portal-navbar .navbar-brand .logo-img { height: 32px; width: auto; border-radius: 6px; }
        .portal-navbar .navbar-brand span { color: var(--primary-light); }
        .portal-navbar .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; margin: 0 10px; transition: color 0.2s; font-size: 14px; }
        .portal-navbar .nav-link:hover, .portal-navbar .nav-link.active { color: #fff !important; }
        .portal-navbar .navbar-toggler { border-color: rgba(255,255,255,0.2); }
        .portal-navbar .navbar-toggler-icon { filter: brightness(0) invert(1); }

        .portal-footer { background: var(--secondary); padding: 40px 0 20px; border-top: 1px solid rgba(255,255,255,0.05); margin-top: auto; }
        .portal-footer .footer-logo-text { font-weight: 800; color: #fff; font-size: 1.2rem; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .portal-footer .footer-logo-text .logo-img { height: 30px; width: auto; border-radius: 6px; }
        .portal-footer .footer-logo-text span { color: var(--primary-light); }
        .portal-footer .footer-desc { color: rgba(255,255,255,0.45); font-size: 13px; line-height: 1.6; }
        .portal-footer .footer-link { color: rgba(255,255,255,0.45); text-decoration: none; font-size: 13px; transition: color 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .portal-footer .footer-link:hover { color: #fff; }
        .portal-footer .footer-heading { color: #fff; font-weight: 600; margin-bottom: 16px; font-size: 14px; }
        .portal-footer .footer-contact { color: rgba(255,255,255,0.45); font-size: 13px; display: flex; align-items: flex-start; gap: 8px; }
        .portal-footer .footer-contact i { margin-top: 3px; color: var(--primary-light); }
        .portal-footer .footer-divider { border-color: rgba(255,255,255,0.05); margin: 24px 0 16px; }
        .portal-footer .footer-copyright { color: rgba(255,255,255,0.25); font-size: 12px; }

        .btn-custom-primary { background: var(--gradient-primary); color: #fff; border: none; padding: 12px 32px; border-radius: 50px; font-weight: 600; font-size: 14px; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-custom-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26,107,46,0.4); color: #fff; }

        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,107,46,0.15); }
    </style>
</head>
<body>
    <nav class="navbar portal-navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                @if($ngo['logo'] && file_exists(storage_path('app/public/' . $ngo['logo'])))
                    <img src="{{ asset('storage/' . $ngo['logo']) }}" alt="Logo" class="logo-img">
                @else
                    <i class="fas fa-tint"></i> <span>{{ $ngo['name'] }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="portalNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.landing') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.registration.*') ? 'active' : '' }}" href="{{ route('portal.registration.create') }}"><i class="fas fa-user-plus me-1"></i> Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.verify') ? 'active' : '' }}" href="{{ route('portal.verify') }}"><i class="fas fa-id-card me-1"></i> Verify</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.availability') ? 'active' : '' }}" href="{{ route('portal.availability') }}"><i class="fas fa-search me-1"></i> Availability</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="portal-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-logo-text">
                        @if($ngo['logo'] && file_exists(storage_path('app/public/' . $ngo['logo'])))
                            <img src="{{ asset('storage/' . $ngo['logo']) }}" alt="Logo" class="logo-img">
                        @else
                            <i class="fas fa-tint" style="color:var(--primary-light);"></i> <span>{{ $ngo['name'] }}</span>
                        @endif
                    </div>
                    <p class="footer-desc">{{ $ngo['footer_text'] }}</p>
                    @if(!empty($settings['address']))
                        <div class="footer-contact mb-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $settings['address'] }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="footer-heading">Quick Links</h6>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="{{ route('portal.registration.create') }}" class="footer-link"><i class="fas fa-user-plus"></i>Register</a>
                        <a href="{{ route('portal.verify') }}" class="footer-link"><i class="fas fa-id-card"></i>Verify Donor</a>
                        <a href="{{ route('portal.availability') }}" class="footer-link"><i class="fas fa-search"></i>Availability</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-heading">Contact</h6>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        @if(!empty($settings['footer_phone']))
                            <div class="footer-contact">
                                <i class="fas fa-phone-alt"></i>
                                <span>{{ $settings['footer_phone'] }}</span>
                            </div>
                        @endif
                        @if(!empty($settings['footer_email']))
                            <div class="footer-contact">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $settings['footer_email'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-heading">Get Involved</h6>
                    <p class="footer-desc" style="margin-bottom:12px;">Every drop counts. Register today and become a hero.</p>
                    <a href="{{ route('portal.registration.create') }}" class="btn-custom-primary" style="padding:10px 24px;font-size:13px;">
                        <i class="fas fa-tint me-1"></i> Donate Now
                    </a>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="text-center">
                <p class="footer-copyright">&copy; {{ date('Y') }} {{ $ngo['name'] }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('js')
</body>
</html>
