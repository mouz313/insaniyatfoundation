@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- Locale Toggle --}}
        @php
            $currentLocale = app()->getLocale();
            $otherLocale = $currentLocale === 'ur' ? 'en' : 'ur';
        @endphp
        <li class="nav-item d-flex align-items-center ml-2">
            <a href="{{ route('locale.switch', ['locale' => $otherLocale]) }}"
               class="locale-toggle"
               title="{{ $otherLocale === 'ur' ? 'اردو میں دیکھیں' : 'Switch to English' }}">
                <div class="toggle-switch {{ $currentLocale }}">
                    <span class="toggle-bg"></span>
                    <span class="toggle-option en-text">EN</span>
                    <span class="toggle-option ur-text">UR</span>
                </div>
            </a>
        </li>

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>

@push('css')
<style>
.locale-toggle {
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}
.toggle-switch {
    display: flex;
    align-items: center;
    background: #e9ecef;
    border-radius: 20px;
    position: relative;
    width: 70px;
    height: 30px;
    padding: 2px;
    cursor: pointer;
}
.toggle-switch.ur {
    background: #1A6B2E;
}
.toggle-bg {
    position: absolute;
    top: 2px;
    bottom: 2px;
    width: 33px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
}
.toggle-switch.en .toggle-bg {
    transform: translateX(0);
}
.toggle-switch.ur .toggle-bg {
    transform: translateX(33px);
}
.toggle-option {
    flex: 1;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    z-index: 2;
    transition: color 0.3s ease;
    line-height: 26px;
    user-select: none;
}
.toggle-switch.en .en-text { color: #1A6B2E; }
.toggle-switch.en .ur-text { color: #6c757d; }
.toggle-switch.ur .en-text { color: rgba(255,255,255,0.6); }
.toggle-switch.ur .ur-text { color: #1A6B2E; }
.toggle-switch:hover {
    opacity: 0.9;
}
</style>
@endpush
