@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $ngoLogo = \App\Models\Setting::where('key', 'ngo_logo')->value('value');
    $logoImg = $ngoLogo ? 'storage/' . $ngoLogo : config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png');
@endphp

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link {{ config('adminlte.classes_brand') }}"
    @endif>

    <img src="{{ asset($logoImg) }}"
         alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
         class="sidebar-logo-img"
         style="width:30px;height:30px;object-fit:contain;display:block;margin:0 auto;">

</a>
