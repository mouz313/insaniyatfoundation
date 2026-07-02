<section class="stats-section" style="background:#fff;padding:50px 0;border-bottom:1px solid #eee;">
    <div class="container">
        <div class="row text-center g-4">
            @php
                $statItems = [
                    ['icon' => 'fas fa-users', 'target' => $stats['donors'], 'label' => 'Registered Donors', 'color' => '#1A6B2E'],
                    ['icon' => 'fas fa-heartbeat', 'target' => $stats['lives_saved'], 'label' => 'Lives Saved', 'color' => '#28a745'],
                    ['icon' => 'fas fa-calendar-alt', 'target' => $stats['drives'], 'label' => 'Drives Held', 'color' => '#17a2b8'],
                    ['icon' => 'fas fa-city', 'target' => $stats['cities'], 'label' => 'Cities Covered', 'color' => '#6610f2'],
                ];
            @endphp
            @foreach($statItems as $s)
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon-box" style="background:{{ $s['color'] }}15;">
                            <i class="{{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
                        </div>
                        <div class="stat-number" data-target="{{ $s['target'] }}">0</div>
                        <div class="stat-label">{{ $s['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
