<nav class="navbar navbar-landing navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            @if($ngo['logo'] && file_exists(storage_path('app/public/' . $ngo['logo'])))
                <img src="{{ asset('storage/' . $ngo['logo']) }}" alt="Logo" class="logo-img">
            @else
                <i class="fas fa-tint"></i> <span>{{ $ngo['name'] }}</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#blood-groups">Blood Groups</a></li>
                <li class="nav-item"><a class="nav-link" href="#campaigns">Campaigns</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Stories</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('portal.verify') }}"><i class="fas fa-id-card me-1"></i> Verify</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('portal.availability') }}"><i class="fas fa-search me-1"></i> Availability</a></li>
            </ul>
            <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn btn-nav-donate"><i class="fas fa-tint me-1"></i> Donate</a>
        </div>
    </div>
</nav>
