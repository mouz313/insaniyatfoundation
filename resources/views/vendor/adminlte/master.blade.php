<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Instant Dark Mode Initializer --}}
    <script>
        (function() {
            var theme = localStorage.getItem('admin_theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
                var obs = new MutationObserver(function() {
                    if (document.body) {
                        document.body.classList.add('dark-mode');
                        obs.disconnect();
                    }
                });
                obs.observe(document.documentElement, { childList: true });
            }
        })();
    </script>

    {{-- IFrame Preloader Removal Workaround --}}
    <style type="text/css">
        body.iframe-mode .preloader {
            display: none !important;
        }
    </style>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_css_path', 'css/app.css')) }}">
            @break

            @case('vite')
                @vite([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')])
            @break

            @case('vite_js_only')
                @vite(config('adminlte.laravel_js_path', 'resources/js/app.js'))
            @break

            @default
                <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom-admin.css') }}">

                @if(config('adminlte.google_fonts.allowed', true))
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
                @endif
        @endswitch
    @endif

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- RTL / Urdu Language Support --}}
    @if(app()->getLocale() === 'ur')
        {{-- Noto Nastaliq Urdu font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap">
        <style>
            /* Urdu font for text content — chrome stays LTR */
            .content-wrapper,
            .content-wrapper h1, .content-wrapper h2, .content-wrapper h3,
            .content-wrapper h4, .content-wrapper h5, .content-wrapper h6,
            .content-wrapper p, .content-wrapper span:not([class*="fa"]),
            .content-wrapper a:not([class*="fa"]),
            .card-title, .card-text, .modal-body, .modal-title,
            .form-label, label, td, th, .breadcrumb-item,
            .form-control, .btn, .alert, .badge,
            .db-card-label, .db-card-stat, .db-mini-label {
                font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', Arial, serif;
            }

            /* STRICTLY protect FontAwesome icons everywhere from font override */
            i, i::before, i::after,
            .fa, .fas, .far, .fal, .fad, .fab, [class*="fa-"], [class^="fa-"],
            [class*="fa-"]::before, [class^="fa-"]::before,
            .content-wrapper i, .content-wrapper .fa, .content-wrapper .fas, .content-wrapper .far,
            .content-wrapper [class*="fa-"], .btn [class*="fa-"],
            .db-card-icon i, .db-mini-icon i, i.fas, i.far, i.fab {
                font-family: "Font Awesome 5 Free" !important;
                font-style: normal !important;
                font-weight: 900 !important;
            }
            .far, .content-wrapper .far, i.far, .far::before {
                font-weight: 400 !important;
            }
            .fab, .content-wrapper .fab, i.fab, .fa-brands, .fab::before, .fa-brands::before {
                font-family: "Font Awesome 5 Brands" !important;
                font-weight: 400 !important;
            }

            /* RTL text direction for content area */
            .content-wrapper {
                direction: rtl;
                text-align: right;
            }

            /* RTL layout adjustments for stat cards and elements */
            .content-wrapper .db-card-icon {
                left: 18px !important;
                right: auto !important;
            }
            .content-wrapper .db-inv-icon {
                left: 6px !important;
                right: auto !important;
            }
            .content-wrapper .db-mini-card {
                border-right-width: 4px !important;
                border-right-style: solid !important;
                border-left: none !important;
            }
            .content-wrapper .db-avatar {
                margin-left: 10px !important;
                margin-right: 0 !important;
            }
            .content-wrapper .db-blood-count {
                margin-right: auto !important;
                margin-left: 0 !important;
            }
            .content-wrapper .db-blood-pct {
                text-align: left !important;
            }
            .content-wrapper .float-right {
                float: left !important;
            }
            .content-wrapper .float-left {
                float: right !important;
            }
            .content-wrapper .mr-1 { margin-left: 0.25rem !important; margin-right: 0 !important; }
            .content-wrapper .mr-2 { margin-left: 0.5rem !important; margin-right: 0 !important; }
            .content-wrapper .mr-3 { margin-left: 1rem !important; margin-right: 0 !important; }
            .content-wrapper .ml-1 { margin-right: 0.25rem !important; margin-left: 0 !important; }
            .content-wrapper .ml-2 { margin-right: 0.5rem !important; margin-left: 0 !important; }
            .content-wrapper .ml-3 { margin-right: 1rem !important; margin-left: 0 !important; }

            /* Form labels & inputs in RTL */
            .content-wrapper .form-group label {
                text-align: right;
                display: block;
            }

            /* Tables: keep column order LTR (numbers, IDs, dates read better LTR)
               but text alignment RTL */
            .content-wrapper table {
                direction: rtl;
            }
            .content-wrapper table th,
            .content-wrapper table td {
                text-align: right;
            }
        </style>
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')
    @stack('css')

    <style>
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }
        @media (min-width: 992px) {
            body:not(.sidebar-collapse) .content-wrapper {
                margin-left: 250px !important;
            }
            body.sidebar-collapse .content-wrapper {
                margin-left: 4.6rem !important;
            }
        }
        @media (max-width: 991.98px) {
            .content-wrapper {
                margin-left: 0 !important;
            }
        }
        .content-wrapper {
            transition: margin-left 0.3s ease-in-out !important;
        }
        .os-scrollbar-horizontal {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            pointer-events: none !important;
        }
        .main-sidebar .sidebar-label {
            padding: 16px 16px 4px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            pointer-events: none;
            user-select: none;
        }
        .nav-item-dashboard .nav-link {
            border: 1px solid rgba(40,167,69,0.1);
        }
        .nav-item-dashboard .nav-link.active {
            border-color: transparent;
        }

        /* ══════════════════════════════════════════════════
           DARK MODE STYLES & OVERRIDES
           ══════════════════════════════════════════════════ */
        body.dark-mode,
        .dark-mode .wrapper,
        .dark-mode .content-wrapper {
            background-color: #17191c !important;
            color: #e2e8f0 !important;
        }

        .dark-mode .main-header.navbar {
            background-color: #1f2328 !important;
            border-bottom: 1px solid #2d333b !important;
            color: #f1f5f9 !important;
        }
        .dark-mode .main-header.navbar .nav-link {
            color: #94a3b8 !important;
        }
        .dark-mode .main-header.navbar .nav-link:hover {
            color: #ffffff !important;
        }

        .dark-mode .card,
        .dark-mode .db-mini-card,
        .dark-mode .db-card-flat,
        .dark-mode .modal-content {
            background-color: #21262d !important;
            border-color: #30363d !important;
            color: #e2e8f0 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
        }

        .dark-mode .db-card-flat-header,
        .dark-mode .card-header {
            background-color: #262c36 !important;
            border-bottom-color: #30363d !important;
            color: #f8fafc !important;
        }
        .dark-mode .card-header.bg-white {
            background-color: #262c36 !important;
        }

        .dark-mode .table {
            color: #e2e8f0 !important;
        }
        .dark-mode .table td,
        .dark-mode .table th {
            border-color: #30363d !important;
        }
        .dark-mode .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }
        .dark-mode .thead-light th {
            background-color: #262c36 !important;
            color: #cbd5e1 !important;
            border-color: #30363d !important;
        }

        .dark-mode .text-dark,
        .dark-mode .db-mini-value,
        .dark-mode .db-blood-count,
        .dark-mode .db-inventory-total {
            color: #f8fafc !important;
        }
        .dark-mode .text-muted,
        .dark-mode .db-mini-label,
        .dark-mode .db-blood-pct {
            color: #8b949e !important;
        }

        .dark-mode .form-control {
            background-color: #1b1f24 !important;
            border-color: #373e47 !important;
            color: #ffffff !important;
        }
        .dark-mode .form-control:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }
        .dark-mode .custom-file-label {
            background-color: #1b1f24 !important;
            border-color: #373e47 !important;
            color: #8b949e !important;
        }
        .dark-mode .custom-file-label::after {
            background-color: #30363d !important;
            color: #e2e8f0 !important;
        }

        .dark-mode .bg-light,
        .dark-mode .modal-footer.bg-light {
            background-color: #161b22 !important;
            border-color: #30363d !important;
        }

        .dark-mode #liveClock #clockTime {
            color: #f8fafc !important;
        }
        .dark-mode #liveClock #clockDate {
            color: #8b949e !important;
        }
    </style>

    {{-- Favicon - Dynamic from settings, falls back to static favicons --}}
    @php
        $favicon = \App\Models\Setting::where('key', 'favicon')->value('value');
    @endphp
    @if($favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}" />
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}" />
    @elseif(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <script src="{{ mix(config('adminlte.laravel_js_path', 'js/app.js')) }}"></script>
            @break

            @case('vite')
            @case('vite_js_only')
            @break

            @default
                <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
                <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
        @endswitch
    @endif

    {{-- Extra Configured Plugins Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    {{-- Dark Mode Toggle Handler & Synchronization --}}
    <script>
        (function() {
            function syncTheme() {
                var isDark = localStorage.getItem('admin_theme') === 'dark';
                if (isDark) {
                    $('body').addClass('dark-mode');
                    $('html').addClass('dark-mode');
                } else {
                    $('body').removeClass('dark-mode');
                    $('html').removeClass('dark-mode');
                }
            }

            $(document).ready(function() {
                syncTheme();

                $(document).on('click', '#themeToggleBtn', function(e) {
                    e.preventDefault();
                    var currentlyDark = $('body').hasClass('dark-mode');
                    if (currentlyDark) {
                        $('body').removeClass('dark-mode');
                        $('html').removeClass('dark-mode');
                        localStorage.setItem('admin_theme', 'light');
                    } else {
                        $('body').addClass('dark-mode');
                        $('html').addClass('dark-mode');
                        localStorage.setItem('admin_theme', 'dark');
                    }
                });
            });
        })();
    </script>
</body>

</html>
