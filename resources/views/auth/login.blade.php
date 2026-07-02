@php
    $ngoSettings = \App\Models\Setting::all()->pluck('value', 'key');
    $ngoName = $ngoSettings['ngo_name'] ?? 'Blood Donor';
    $ngoLogo = $ngoSettings['ngo_logo'] ?? null;
    $favicon = $ngoSettings['favicon'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ $ngoName }}</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #1A6B2E;
            --primary-dark: #145A26;
            --secondary: #1a1a2e;
            --gradient-hero: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            --gradient-primary: linear-gradient(135deg, #1A6B2E, #28a745);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }

        .login-container { display: flex; min-height: 100vh; }

        .login-left { flex: 1; background: var(--gradient-hero); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 40px; position: relative; overflow: hidden; }
        .login-left::before { content: ''; position: absolute; inset: 0; opacity: 0.05; background: radial-gradient(circle at 20% 50%, #1A6B2E 0%, transparent 50%), radial-gradient(circle at 80% 20%, #28a745 0%, transparent 50%); }
        .login-left-content { position: relative; z-index: 1; text-align: center; max-width: 420px; }
        .login-left .brand-icon { width: 90px; height: 90px; border-radius: 24px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 15px 40px rgba(26,107,46,0.3); }
        .login-left .brand-icon i { font-size: 42px; color: #fff; }
        .login-left h1 { font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 12px; }
        .login-left p { color: rgba(255,255,255,0.6); font-size: 1rem; line-height: 1.7; margin-bottom: 32px; }
        .login-left .stat-row { display: flex; gap: 32px; justify-content: center; }
        .login-left .stat-item { text-align: center; }
        .login-left .stat-value { font-size: 1.8rem; font-weight: 800; color: #fff; }
        .login-left .stat-label { font-size: 12px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

        .login-right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; background: #f8f9fa; }
        .login-card { width: 100%; max-width: 420px; }
        .login-card-header { text-align: center; margin-bottom: 32px; }
        .login-card-header .logo-wrap { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 20px; }
        .login-card-header .logo-wrap .logo-img { height: 40px; width: auto; border-radius: 8px; }
        .login-card-header .logo-wrap .logo-placeholder { width: 40px; height: 40px; border-radius: 10px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; }
        .login-card-header .logo-wrap .logo-placeholder i { color: #fff; font-size: 20px; }
        .login-card-header .logo-text { font-size: 1.3rem; font-weight: 800; color: var(--secondary); }
        .login-card-header h2 { font-size: 1.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 6px; }
        .login-card-header p { color: #888; font-size: 14px; }

        .form-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .input-group-custom { position: relative; margin-bottom: 20px; }
        .input-group-custom .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #adb5bd; font-size: 16px; z-index: 4; pointer-events: none; transition: color 0.2s; }
        .input-group-custom .form-control { border-radius: 12px; padding: 12px 14px 12px 44px; border: 1.5px solid #e9ecef; font-size: 14px; transition: all 0.2s; height: 48px; background: #fff; }
        .input-group-custom .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,107,46,0.1); }
        .input-group-custom .form-control:focus ~ .input-icon { color: var(--primary); }
        .input-group-custom .form-control.is-invalid { border-color: var(--primary); }

        .form-check-custom { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }
        .form-check-custom input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary); border-radius: 4px; cursor: pointer; }
        .form-check-custom label { font-size: 14px; color: #666; cursor: pointer; user-select: none; }

        .btn-signin { width: 100%; background: var(--gradient-primary); color: #fff; border: none; padding: 14px; border-radius: 12px; font-weight: 600; font-size: 15px; transition: all 0.3s ease; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-signin:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26,107,46,0.4); color: #fff; }
        .btn-signin:active { transform: translateY(0); }

        .forgot-link { text-align: center; margin-top: 18px; }
        .forgot-link a { color: #888; font-size: 14px; text-decoration: none; transition: color 0.2s; }
        .forgot-link a:hover { color: var(--primary); }

        .login-footer-links { text-align: center; margin-top: 28px; padding-top: 24px; border-top: 1px solid #eee; }
        .login-footer-links a { color: #888; font-size: 13px; text-decoration: none; transition: color 0.2s; margin: 0 8px; }
        .login-footer-links a:hover { color: var(--primary); }
        .login-footer-links .sep { color: #ddd; font-size: 13px; }

        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 12px; padding: 12px 16px; font-size: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

        .back-home { position: fixed; top: 24px; left: 24px; z-index: 100; display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.2s; background: rgba(0,0,0,0.2); padding: 8px 16px; border-radius: 50px; backdrop-filter: blur(10px); }
        .back-home:hover { color: #fff; }

        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { padding: 24px 16px; }
            .back-home { left: 16px; top: 16px; }
        }
    </style>
</head>
<body>
    <a href="{{ url('/') }}" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>

    <div class="login-container">
        <div class="login-left">
            <div class="login-left-content">
                <div class="brand-icon"><i class="fas fa-tint"></i></div>
                <h1>{{ $ngoName }}</h1>
                <p>Managing blood donations, saving lives. Every drop counts in our mission to ensure safe blood for everyone.</p>
                <div class="stat-row">
                    @php
                        $donorCount = \App\Models\Donor::count();
                        $lifeCount = \App\Models\BloodDonation::where('status', 'donated')->count() * 3;
                        $driveCount = \App\Models\Campaign::count();
                    @endphp
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($donorCount) }}+</div>
                        <div class="stat-label">Donors</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($lifeCount) }}+</div>
                        <div class="stat-label">Lives Saved</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($driveCount) }}+</div>
                        <div class="stat-label">Drives</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-right">
            <div class="login-card">
                <div class="login-card-header">
                    <div class="logo-wrap">
                        @if($ngoLogo && file_exists(storage_path('app/public/' . $ngoLogo)))
                            <img src="{{ asset('storage/' . $ngoLogo) }}" alt="Logo" class="logo-img">
                        @else
                            <div class="logo-placeholder"><i class="fas fa-tint"></i></div>
                        @endif
                        <span class="logo-text">{{ $ngoName }}</span>
                    </div>
                    <h2>Welcome Back</h2>
                    <p>Sign in to manage the blood donation system</p>
                </div>

                @if($errors->any())
                    <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-group-custom">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email address" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="input-group-custom">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <div class="form-check-custom">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn-signin"><i class="fas fa-sign-in-alt"></i> Sign In</button>
                </form>

                <div class="login-footer-links">
                    <a href="{{ route('portal.registration.create') }}"><i class="fas fa-user-plus me-1"></i> Register as Donor</a>
                    <span class="sep">|</span>
                    <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i> Visit Home</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
