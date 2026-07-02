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
            /* ── Direction ── */
            html, body { direction: rtl !important; }

            /* ── Urdu font for all visible text ── */
            body, span, p, a, h1, h2, h3, h4, h5, h6, label, td, th,
            input, textarea, select, button, .nav-link p, .nav-link,
            .dropdown-item, .card-title, .card-text,
            .user-panel-name, .user-panel-role, .brand-text {
                font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', Arial, serif !important;
                line-height: 1.8 !important;
            }

            /* ── Sidebar: move to right ── */
            .main-sidebar {
                right: 0 !important;
                left: auto !important;
            }
            /* ── Content wrapper: offset from right ── */
            .content-wrapper, .main-footer {
                margin-right: 250px !important;
                margin-left: 0 !important;
            }
            body.sidebar-collapse .content-wrapper,
            body.sidebar-collapse .main-footer {
                margin-right: 4.6rem !important;
                margin-left: 0 !important;
            }

            /* ── Navbar ── */
            .main-header.navbar {
                margin-right: 250px !important;
                margin-left: 0 !important;
            }
            body.sidebar-collapse .main-header.navbar {
                margin-right: 4.6rem !important;
            }
            /* flip left / right nav groups */
            .navbar-nav.ml-auto {
                margin-right: auto !important;
                margin-left: 0 !important;
            }

            /* ── Sidebar brand: fix OUR horizontal layout margins ── */
            .sidebar-logo-wrapper {
                margin-right: 0 !important;
                margin-left: 12px !important;
            }
            .sidebar-brand-text { text-align: right !important; }

            /* ── Nav links: flip icon margin ── */
            .nav-sidebar .nav-link .nav-icon {
                margin-right: 0 !important;
                margin-left: .5rem !important;
            }
            /* ── Active indicator: move to right edge ── */
            .nav-sidebar .nav-item > .nav-link.active::after {
                left: auto !important;
                right: 0 !important;
                border-radius: 3px 0 0 3px !important;
            }
            /* ── Submenu indent ── */
            .nav-sidebar .nav-treeview {
                padding-right: 1rem !important;
                padding-left: 0 !important;
            }

            /* ── Dropdown ── */
            .dropdown-menu { text-align: right !important; }

            /* ── Tables ── */
            .table th, .table td { text-align: right !important; }

            /* ── Forms ── */
            .form-control { text-align: right !important; }
        </style>
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    <style>
        .main-sidebar .nav-sidebar .nav-item > .nav-link {
            border-radius: 8px;
            margin: 2px 8px;
            transition: all 0.2s ease;
        }
        .main-sidebar .nav-sidebar .nav-item > .nav-link:hover {
            background: rgba(255,255,255,0.06);
            transform: translateX(3px);
        }
        .main-sidebar .nav-sidebar .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #dc3545, #e4606d) !important;
            box-shadow: 0 4px 15px rgba(220,53,69,0.3);
        }
        .main-sidebar .nav-sidebar .nav-item > .nav-link .nav-icon {
            transition: transform 0.2s ease;
        }
        .main-sidebar .nav-sidebar .nav-item > .nav-link:hover .nav-icon {
            transform: scale(1.15);
        }
        .main-sidebar .nav-sidebar .nav-treeview .nav-item .nav-link {
            border-radius: 6px;
            margin: 1px 8px 1px 20px;
            padding: 6px 12px;
            font-size: 13px;
            transition: all 0.15s ease;
        }
        .main-sidebar .nav-sidebar .nav-treeview .nav-item .nav-link:hover {
            background: rgba(255,255,255,0.05);
            padding-left: 16px;
        }
        .main-sidebar .nav-sidebar .nav-treeview .nav-item .nav-link.active {
            background: rgba(220,53,69,0.15) !important;
            border-left: 3px solid #dc3545;
            padding-left: 13px;
        }
        .main-sidebar .brand-link {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .main-sidebar .brand-text {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .nav-item-dashboard .nav-link {
            background: linear-gradient(135deg, rgba(220,53,69,0.12), rgba(220,53,69,0.05));
            border: 1px solid rgba(220,53,69,0.1);
        }
        .nav-item-dashboard .nav-link.active {
            border-color: transparent;
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

</body>

</html>
