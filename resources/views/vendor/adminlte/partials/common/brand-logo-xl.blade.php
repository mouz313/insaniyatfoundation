@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $ngoLogo = \App\Models\Setting::where('key', 'ngo_logo')->value('value');
    $ngoName = \App\Models\Setting::where('key', 'ngo_name')->value('value');
    $logoImg = $ngoLogo ? 'storage/' . $ngoLogo : config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png');
    $brandText = strip_tags($ngoName ?: config('adminlte.logo', 'BloodDonor'));
@endphp

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand logo-switch {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link logo-switch {{ config('adminlte.classes_brand') }}"
    @endif>

    {{-- Logo image centered --}}
    <div class="sidebar-logo-wrapper">
        <img src="{{ asset($logoImg) }}"
             alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
             class="sidebar-logo-img">
    </div>

    {{-- Brand text below --}}
    <span class="sidebar-brand-text">{{ $brandText }}</span>

</a>
