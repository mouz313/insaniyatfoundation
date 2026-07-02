@php
    $settings = \App\Models\Setting::all()->pluck('value', 'key');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings['ngo_name'] ?? config('app.name', 'Blood Donation'))</title>
    @if(isset($settings['favicon']) && $settings['favicon'])
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings['favicon']) }}">
    @endif
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('css')
</head>
<body style="background: #f8f9fa;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                @if(isset($settings['ngo_logo']) && $settings['ngo_logo'])
                    <img src="{{ asset('storage/' . $settings['ngo_logo']) }}" alt="Logo" style="height: 30px;">
                @else
                    <i class="fas fa-tint mr-2"></i> {{ $settings['ngo_name'] ?? config('app.name') }}
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#portalNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="portalNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item {{ request()->routeIs('portal.landing') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-home mr-1"></i> Home</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('portal.registration.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('portal.registration.create') }}"><i class="fas fa-user-plus mr-1"></i> Register</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('portal.verify') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('portal.verify') }}"><i class="fas fa-id-card mr-1"></i> Verify</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('portal.availability') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('portal.availability') }}"><i class="fas fa-search mr-1"></i> Availability</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="bg-light py-4 mt-4">
        <div class="container text-center">
            @if(isset($settings['footer_text']) && $settings['footer_text'])
                <p class="mb-1 text-muted">{{ $settings['footer_text'] }}</p>
            @endif
            <p class="mb-0 text-muted small">&copy; {{ date('Y') }} {{ $settings['ngo_name'] ?? config('app.name') }}. All rights reserved.</p>
            @if(isset($settings['footer_email']) || isset($settings['footer_phone']))
                <div class="mt-2 small">
                    @if(isset($settings['footer_email']) && $settings['footer_email'])
                        <a href="mailto:{{ $settings['footer_email'] }}" class="text-muted mr-3"><i class="fas fa-envelope"></i> {{ $settings['footer_email'] }}</a>
                    @endif
                    @if(isset($settings['footer_phone']) && $settings['footer_phone'])
                        <span class="text-muted"><i class="fas fa-phone"></i> {{ $settings['footer_phone'] }}</span>
                    @endif
                </div>
            @endif
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @stack('js')
</body>
</html>
