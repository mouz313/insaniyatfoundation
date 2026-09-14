<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    <div class="sidebar">

        {{-- Sidebar Menu --}}
        <nav class="sidebar-nav-container mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

    {{-- User Panel at the End / Bottom of Sidebar --}}
    @auth
    <div class="user-panel user-panel-bottom">
        <div class="user-panel-avatar">
            <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle" alt="{{ Auth::user()->name }}">
        </div>
        <div class="user-panel-info">
            <span class="user-panel-name">{{ Auth::user()->name }}</span>
            <span class="user-panel-role">{{ Auth::user()->adminlte_desc() }}</span>
        </div>
        <a href="{{ route('admin.profile') }}" class="user-panel-link" title="{{ __('app.my_profile') }}">
            <i class="fas fa-cog"></i>
        </a>
    </div>
    @endauth

</aside>

<style>
/* ─── Sidebar Base ─── */
.main-sidebar {
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%) !important;
    border-right: none !important;
    box-shadow: 2px 0 20px rgba(0,0,0,0.1);
    display: flex !important;
    flex-direction: column !important;
    height: 100vh !important;
    overflow: hidden !important;
    position: fixed !important;
    top: 0;
    left: 0;
    bottom: 0;
    z-index: 1038 !important;
    transition: width 0.3s ease-in-out, transform 0.3s ease-in-out !important;
}

@media (min-width: 992px) {
    body:not(.sidebar-collapse) .main-sidebar {
        width: 250px !important;
        max-width: 250px !important;
    }
    body:not(.sidebar-collapse) .brand-link {
        width: 250px !important;
        max-width: 250px !important;
    }
    body:not(.sidebar-collapse) .sidebar {
        width: 250px !important;
        max-width: 250px !important;
    }
    body:not(.sidebar-collapse) .user-panel {
        width: 250px !important;
        max-width: 250px !important;
    }
}

.brand-link {
    flex-shrink: 0;
    padding: 16px 16px !important;
    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    display: flex !important;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    text-decoration: none !important;
    min-height: 72px;
    margin-bottom: 12px;
    box-sizing: border-box !important;
    transition: padding 0.3s ease-in-out, width 0.3s ease-in-out !important;
}
.brand-link:hover {
    background: rgba(255,255,255,0.03);
}

/* ─── Sidebar scrollable area ─── */
.sidebar {
    flex: 1 1 auto;
    min-height: 0;
    padding-bottom: 20px;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    box-sizing: border-box !important;
}

.sidebar-logo-wrapper {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background: rgba(26,107,46,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0;
    margin-right: 12px;
    transition: transform 0.2s;
    flex-shrink: 0;
}
.brand-link:hover .sidebar-logo-wrapper {
    transform: scale(1.05);
}
.sidebar-logo-img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}
.sidebar-brand-text {
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.3px;
    text-align: left;
    line-height: 1.3;
    flex: 1;
    min-width: 0;
    max-width: 100%;
    white-space: normal;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ─── Sidebar Menu Spacing ─── */
.sidebar-nav-container {
    margin-top: 14px !important;
    padding-top: 4px !important;
}

/* ─── Disable all horizontal scrolling in sidebar ─── */
.main-sidebar,
.main-sidebar *,
.sidebar,
.sidebar *,
.sidebar-nav-container,
.nav-sidebar,
.os-host,
.os-viewport,
.os-content {
    overflow-x: hidden !important;
    box-sizing: border-box !important;
}

/* Hide OverlayScrollbars horizontal bar completely */
.os-scrollbar-horizontal,
.os-scrollbar-horizontal * {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    width: 0 !important;
    pointer-events: none !important;
}

@if(app()->getLocale() === 'ur')
.sidebar-logo-wrapper {
    margin-right: 0;
    margin-left: 12px;
}
.sidebar-brand-text {
    text-align: right;
    font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', Arial, serif;
    font-size: 13px;
    line-height: 1.6;
}
.user-panel-info {
    text-align: right;
}
.nav-sidebar .nav-item > .nav-link {
    text-align: right;
    font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', Arial, serif;
}
.nav-sidebar .nav-item > .nav-link .nav-icon {
    margin-right: 0;
    margin-left: 8px;
}
.nav-sidebar .nav-item > .nav-link.active::after {
    left: auto;
    right: 0;
    border-radius: 3px 0 0 3px;
}
.nav-sidebar .nav-treeview {
    padding-left: 0;
    padding-right: 10px;
}
.nav-sidebar .nav-treeview .nav-item > .nav-link {
    padding: 8px 24px 8px 14px;
}
@endif

/* ─── User Panel (Pinned at Bottom) ─── */
.user-panel {
    flex-shrink: 0;
    box-sizing: border-box !important;
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-top: 1px solid rgba(255,255,255,0.08);
    border-bottom: none !important;
    background: rgba(13, 27, 42, 0.75);
    backdrop-filter: blur(10px);
    margin: 0;
    z-index: 10;
    transition: padding 0.3s ease-in-out, width 0.3s ease-in-out !important;
}
.user-panel-avatar {
    flex-shrink: 0;
}
.user-panel-avatar img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.15);
}
.user-panel-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}
.user-panel-name {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-panel-role {
    font-size: 11px;
    color: rgba(255,255,255,0.4);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-panel-link {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.4);
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
}
.user-panel-link:hover {
    background: rgba(255,255,255,0.08);
    color: #fff;
}

/* ─── Nav Header ─── */
.nav-header {
    font-size: 10px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    color: rgba(255,255,255,0.3) !important;
    padding: 18px 20px 6px !important;
    font-weight: 600 !important;
}

/* ─── Nav Items ─── */
.nav-sidebar .nav-item > .nav-link {
    border-radius: 10px;
    margin: 2px 10px;
    padding: 10px 14px;
    color: rgba(255,255,255,0.6);
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
}
.nav-sidebar .nav-item > .nav-link:hover {
    color: #fff;
    background: rgba(255,255,255,0.06);
}
.nav-sidebar .nav-item > .nav-link .nav-icon {
    font-size: 16px;
    margin-right: 8px;
    color: rgba(255,255,255,0.4);
    transition: color 0.2s;
}
.nav-sidebar .nav-item > .nav-link:hover .nav-icon {
    color: #28a745;
}

/* ─── Active Item ─── */
.nav-sidebar .nav-item > .nav-link.active {
    background: linear-gradient(135deg, #1A6B2E 0%, #145A26 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 15px rgba(26,107,46,0.3);
}
.nav-sidebar .nav-item > .nav-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    bottom: 8px;
    width: 3px;
    background: #28a745;
    border-radius: 0 3px 3px 0;
}
.nav-sidebar .nav-item > .nav-link.active .nav-icon {
    color: #fff !important;
}

/* ─── Sub-menu ─── */
.nav-sidebar .nav-treeview {
    padding-left: 10px;
}
.nav-sidebar .nav-treeview .nav-item > .nav-link {
    padding: 8px 14px 8px 24px;
    font-size: 13px;
    margin: 1px 10px;
}
.nav-sidebar .nav-treeview .nav-item > .nav-link.active {
    background: rgba(26,107,46,0.25) !important;
    box-shadow: none !important;
    position: relative;
}
.nav-sidebar .nav-treeview .nav-item > .nav-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    top: 6px;
    bottom: 6px;
    width: 3px;
    background: #28a745;
    border-radius: 0 3px 3px 0;
}

/* ─── Collapsed Mode (Mini Sidebar) ─── */
@media (min-width: 992px) {
    body.sidebar-collapse .main-sidebar:not(:hover) {
        width: 4.6rem !important;
        max-width: 4.6rem !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .brand-link {
        width: 4.6rem !important;
        max-width: 4.6rem !important;
        padding: 12px 0 !important;
        min-height: 56px !important;
        justify-content: center !important;
        align-items: center !important;
        margin-bottom: 6px !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .sidebar-logo-wrapper {
        width: 38px !important;
        height: 38px !important;
        margin: 0 auto !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .sidebar-logo-img {
        width: 26px !important;
        height: 26px !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .sidebar-brand-text {
        display: none !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .sidebar {
        width: 4.6rem !important;
        max-width: 4.6rem !important;
        padding: 0 !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .sidebar-nav-container {
        width: 100% !important;
        margin-top: 8px !important;
        padding: 0 !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar {
        width: 100% !important;
        padding: 0 !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-item {
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-item > .nav-link {
        width: 44px !important;
        height: 44px !important;
        margin: 4px auto !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 10px !important;
        text-align: center !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-item > .nav-link .nav-icon {
        margin: 0 !important;
        font-size: 18px !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-item > .nav-link p {
        display: none !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-item > .nav-link::after {
        display: none !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-header {
        display: none !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .nav-treeview {
        display: none !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .user-panel {
        width: 4.6rem !important;
        max-width: 4.6rem !important;
        padding: 10px 0 !important;
        justify-content: center !important;
        align-items: center !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .user-panel-avatar {
        margin: 0 auto !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .user-panel-avatar img {
        width: 34px !important;
        height: 34px !important;
    }
    body.sidebar-collapse .main-sidebar:not(:hover) .user-panel-info,
    body.sidebar-collapse .main-sidebar:not(:hover) .user-panel-link {
        display: none !important;
    }

    /* ─── Hover Expansion in Collapsed Mode ─── */
    body.sidebar-collapse .main-sidebar:hover {
        width: 250px !important;
        max-width: 250px !important;
        box-shadow: 4px 0 25px rgba(0,0,0,0.3) !important;
        z-index: 1040 !important;
        overflow-y: auto !important;
    }
    body.sidebar-collapse .main-sidebar:hover .brand-link {
        width: 250px !important;
        max-width: 250px !important;
        padding: 16px 16px !important;
        justify-content: flex-start !important;
        min-height: 72px !important;
    }
    body.sidebar-collapse .main-sidebar:hover .sidebar-logo-wrapper {
        width: 45px !important;
        height: 45px !important;
        margin-right: 12px !important;
        margin-left: 0 !important;
    }
    body.sidebar-collapse .main-sidebar:hover .sidebar-brand-text {
        display: -webkit-box !important;
    }
    body.sidebar-collapse .main-sidebar:hover .sidebar {
        width: 250px !important;
        max-width: 250px !important;
        padding-bottom: 20px !important;
    }
    body.sidebar-collapse .main-sidebar:hover .sidebar-nav-container {
        width: 100% !important;
        margin-top: 14px !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-sidebar .nav-item {
        width: 100% !important;
        display: block !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-sidebar .nav-item > .nav-link {
        width: auto !important;
        height: auto !important;
        margin: 2px 10px !important;
        padding: 10px 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        text-align: left !important;
        border-radius: 10px !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-sidebar .nav-item > .nav-link .nav-icon {
        margin-right: 8px !important;
        margin-left: 0 !important;
        font-size: 16px !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-sidebar .nav-item > .nav-link p {
        display: inline-block !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-header {
        display: block !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-treeview {
        display: block !important;
    }
    body.sidebar-collapse .main-sidebar:hover .user-panel {
        width: 250px !important;
        max-width: 250px !important;
        padding: 12px 16px !important;
        justify-content: flex-start !important;
    }
    body.sidebar-collapse .main-sidebar:hover .user-panel-info,
    body.sidebar-collapse .main-sidebar:hover .user-panel-link {
        display: flex !important;
    }
    body.sidebar-collapse .main-sidebar:hover .user-panel-avatar img {
        width: 36px !important;
        height: 36px !important;
    }

    @if(app()->getLocale() === 'ur')
    body.sidebar-collapse .main-sidebar:hover .sidebar-logo-wrapper {
        margin-right: 0 !important;
        margin-left: 12px !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-sidebar .nav-item > .nav-link {
        text-align: right !important;
    }
    body.sidebar-collapse .main-sidebar:hover .nav-sidebar .nav-item > .nav-link .nav-icon {
        margin-right: 0 !important;
        margin-left: 8px !important;
    }
    @endif
}
</style>
