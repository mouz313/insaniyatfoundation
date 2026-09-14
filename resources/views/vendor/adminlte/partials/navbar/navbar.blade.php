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

        {{-- Locale Toggle Button --}}
        @php
            $currentLocale = app()->getLocale();
            $otherLocale = $currentLocale === 'ur' ? 'en' : 'ur';
        @endphp
        <li class="nav-item locale-nav-item">
            <a href="{{ route('locale.switch', ['locale' => $otherLocale]) }}"
               class="lang-toggle-btn {{ $currentLocale }}"
               role="button"
               title="{{ $otherLocale === 'ur' ? 'اردو میں دیکھیں' : 'Switch to English' }}">
                <span class="lang-slider"></span>
                <span class="lang-opt lang-en">EN</span>
                <span class="lang-opt lang-ur">UR</span>
            </a>
        </li>

        {{-- Theme (Dark / Light) Toggle Button --}}
        <li class="nav-item theme-nav-item">
            <button type="button" id="themeToggleBtn" class="theme-toggle-btn" title="Toggle Day / Night Mode" aria-label="Toggle Dark Mode">
                <i class="fas fa-moon theme-icon-moon"></i>
                <i class="fas fa-sun theme-icon-sun"></i>
            </button>
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

<style>
/* ─── Top Navbar Base ─── */
.main-header.navbar {
    z-index: 1030 !important;
    background-color: #ffffff !important;
    border-bottom: 1px solid #dee2e6 !important;
    min-height: 56px;
    display: flex !important;
    align-items: center;
    transition: margin-left 0.3s ease-in-out !important;
}

@media (min-width: 992px) {
    body:not(.sidebar-collapse) .main-header.navbar {
        margin-left: 250px !important;
    }
    body.sidebar-collapse .main-header.navbar {
        margin-left: 4.6rem !important;
    }
}
@media (max-width: 991.98px) {
    .main-header.navbar {
        margin-left: 0 !important;
    }
}

/* ─── Language Switch Toggle Button ─── */
.locale-nav-item {
    display: inline-flex;
    align-items: center;
    margin: 0 10px;
}
.lang-toggle-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    width: 72px;
    height: 30px;
    background: #e2e8f0;
    border-radius: 20px;
    padding: 3px;
    text-decoration: none !important;
    cursor: pointer;
    border: 1px solid #cbd5e1;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);
    transition: all 0.25s ease;
    direction: ltr !important;
}
.lang-toggle-btn:hover {
    border-color: #1A6B2E;
    box-shadow: 0 2px 8px rgba(26,107,46,0.25), inset 0 1px 2px rgba(0,0,0,0.1);
}
.lang-slider {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 32px;
    height: 22px;
    background: linear-gradient(135deg, #1A6B2E 0%, #28a745 100%);
    border-radius: 14px;
    box-shadow: 0 2px 6px rgba(26,107,46,0.45);
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
}
.lang-toggle-btn.en .lang-slider {
    transform: translateX(0);
}
.lang-toggle-btn.ur .lang-slider {
    transform: translateX(33px);
}
.lang-opt {
    flex: 1;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    line-height: 22px;
    z-index: 2;
    transition: color 0.25s ease;
    user-select: none;
}
.lang-toggle-btn.en .lang-en {
    color: #ffffff !important;
    font-weight: 800;
}
.lang-toggle-btn.en .lang-ur {
    color: #64748b !important;
}
.lang-toggle-btn.ur .lang-en {
    color: #64748b !important;
}
.lang-toggle-btn.ur .lang-ur {
    color: #ffffff !important;
    font-weight: 800;
}

/* ─── Day / Night Theme Toggle Button ─── */
.theme-nav-item {
    display: inline-flex;
    align-items: center;
    margin: 0 6px;
}
.theme-toggle-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    color: #475569;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
    padding: 0;
}
.theme-toggle-btn:hover {
    background: #e2e8f0;
    color: #1A6B2E;
    transform: scale(1.05);
}
.theme-toggle-btn .theme-icon-sun {
    display: none;
    color: #f59e0b;
    font-size: 15px;
}
.theme-toggle-btn .theme-icon-moon {
    display: inline-block;
    color: #64748b;
    font-size: 14px;
}
.dark-mode .theme-toggle-btn {
    background: #2a2f36 !important;
    border-color: #475569 !important;
    color: #f59e0b !important;
}
.dark-mode .theme-toggle-btn:hover {
    background: #373e47 !important;
    color: #fbbf24 !important;
}
.dark-mode .theme-toggle-btn .theme-icon-moon {
    display: none;
}
.dark-mode .theme-toggle-btn .theme-icon-sun {
    display: inline-block;
}
</style>
