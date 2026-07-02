<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    <div class="sidebar">

        {{-- User Panel --}}
        @auth
        <div class="user-panel">
            <div class="user-panel-avatar">
                <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle" alt="{{ Auth::user()->name }}">
            </div>
            <div class="user-panel-info">
                <span class="user-panel-name">{{ Auth::user()->name }}</span>
                <span class="user-panel-role">{{ Auth::user()->adminlte_desc() }}</span>
            </div>
            <a href="{{ route('admin.profile') }}" class="user-panel-link" title="Profile">
                <i class="fas fa-cog"></i>
            </a>
        </div>
        @endauth

        {{-- Sidebar Menu --}}
        <nav class="mt-2">
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

</aside>

<style>
/* ─── Sidebar Base ─── */
.main-sidebar {
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%) !important;
    border-right: none !important;
    box-shadow: 2px 0 20px rgba(0,0,0,0.1);
}

/* ─── Sidebar scrollable area ─── */
.sidebar {
    padding-bottom: 20px;
    overflow-y: auto !important;
    overflow-x: hidden !important;
}

.sidebar::-webkit-scrollbar {
    width: 4px;
}
.sidebar::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.15);
    border-radius: 4px;
}
.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.25);
}

/* ─── Brand Link ─── */
.brand-link {
    padding: 15px 15px !important;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    display: flex !important;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    text-decoration: none !important;
    min-height: 70px;
}
.brand-link:hover {
    background: rgba(255,255,255,0.03);
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
    max-width: 100%;
    white-space: normal;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ─── User Panel ─── */
.user-panel {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    margin: 0 0 6px;
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

/* ─── Collapsed Mode ─── */
.sidebar-collapse .main-sidebar .brand-link {
    padding: 10px 8px !important;
    min-height: 56px;
    justify-content: center !important;
}
.sidebar-collapse .main-sidebar .sidebar-logo-wrapper {
    width: 36px;
    height: 36px;
    margin-bottom: 0;
    margin-right: 0;
}
.sidebar-collapse .main-sidebar .sidebar-logo-img {
    width: 24px;
    height: 24px;
}
.sidebar-collapse .main-sidebar .sidebar-brand-text {
    display: none;
}
.sidebar-collapse .user-panel .user-panel-info,
.sidebar-collapse .user-panel .user-panel-link {
    display: none;
}
.sidebar-collapse .user-panel {
    padding: 10px;
    justify-content: center;
}
.sidebar-collapse .user-panel-avatar img {
    width: 30px;
    height: 30px;
}
.sidebar-collapse .main-sidebar:hover .user-panel .user-panel-info,
.sidebar-collapse .main-sidebar:hover .user-panel .user-panel-link {
    display: flex;
}
.sidebar-collapse .main-sidebar:hover .user-panel {
    padding: 14px 16px;
    justify-content: flex-start;
}
.sidebar-collapse .main-sidebar:hover .user-panel-avatar img {
    width: 36px;
    height: 36px;
}

/* ─── Mini sidebar hover expand ─── */
.sidebar-mini.sidebar-collapse .main-sidebar {
    width: 60px !important;
    overflow: hidden;
}
.sidebar-mini.sidebar-collapse .main-sidebar:hover {
    width: 250px !important;
    overflow-y: auto;
}
.sidebar-mini.sidebar-collapse .main-sidebar .nav-link p {
    display: none;
}
.sidebar-mini.sidebar-collapse .main-sidebar .nav-treeview {
    display: none !important;
}
.sidebar-mini.sidebar-collapse .main-sidebar:hover .nav-treeview {
    display: block !important;
}
.sidebar-mini.sidebar-collapse .main-sidebar:hover .nav-link p,
.sidebar-mini.sidebar-collapse .main-sidebar:hover .sidebar-brand-text {
    display: block !important;
}
</style>
