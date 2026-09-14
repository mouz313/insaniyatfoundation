<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['hero_tagline'] ?? 'Blood Donor' }} | {{ $ngo['name'] }}</title>
    <meta name="description" content="{{ $settings['hero_description'] ?? 'Join us in saving lives through blood donation.' }}">

    @if($ngo['favicon'] && file_exists(storage_path('app/public/' . $ngo['favicon'])))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $ngo['favicon']) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩸</text></svg>">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900;1,14..32,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        /* ── Variables ─────────────────────────────────────────────── */
        :root {
            --green:        #1A6B2E;
            --green-mid:    #208a39;
            --green-light:  #28a745;
            --navy:         #0d1b2a;
            --navy-mid:     #152238;
            --navy-light:   #1e3a5f;
            --red:          #C0392B;
            --red-light:    #e74c3c;
            --accent:       #1565A8;
            --white:        #ffffff;
            --off-white:    #f8faf9;
            --text:         #1a1a2e;
            --text-muted:   #6b7280;
            --border:       #e5e9ed;

            --grad-hero:    linear-gradient(135deg, #0d1b2a 0%, #152238 50%, #1e3a5f 100%);
            --grad-green:   linear-gradient(135deg, #1A6B2E 0%, #28a745 100%);
            --grad-red:     linear-gradient(135deg, #C0392B 0%, #e74c3c 100%);
            --grad-accent:  linear-gradient(135deg, #1565A8 0%, #1a7acc 100%);

            --radius-sm:    10px;
            --radius:       16px;
            --radius-lg:    24px;
            --shadow-sm:    0 2px 12px rgba(0,0,0,.06);
            --shadow:       0 8px 30px rgba(0,0,0,.10);
            --shadow-lg:    0 20px 60px rgba(0,0,0,.16);
        }

        /* ── Reset & Base ──────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text);
            overflow-x: hidden;
            background: var(--white);
            -webkit-font-smoothing: antialiased;
        }
        img { max-width: 100%; display: block; }
        a { text-decoration: none; }

        /* ── Animations ────────────────────────────────────────────── */
        @keyframes fadeUp   { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes float    { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-16px); } }
        @keyframes drip     { 0%,100% { transform:translateY(0) scale(1); } 50% { transform:translateY(10px) scale(.95); } }
        @keyframes pulse    { 0%,100% { box-shadow:0 0 0 0 rgba(26,107,46,.4); } 70% { box-shadow:0 0 0 18px rgba(26,107,46,0); } }
        @keyframes spin-slow{ from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
        @keyframes shimmer  { 0% { background-position:-400px 0; } 100% { background-position:400px 0; } }

        .fade-up     { animation: fadeUp .8s ease both; }
        .float-anim  { animation: float 4s ease-in-out infinite; }
        .drip-anim   { animation: drip 2.5s ease-in-out infinite; }

        /* Scroll-reveal */
        .reveal { opacity:0; transform:translateY(36px); transition: opacity .7s ease, transform .7s ease; }
        .reveal.visible { opacity:1; transform:none; }
        .reveal-d1 { transition-delay:.1s; }
        .reveal-d2 { transition-delay:.2s; }
        .reveal-d3 { transition-delay:.3s; }
        .reveal-d4 { transition-delay:.4s; }

        /* ── Utility ───────────────────────────────────────────────── */
        .section-py   { padding: 96px 0; }
        .section-py-sm{ padding: 64px 0; }
        .grad-text {
            background: var(--grad-green);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .badge-pill {
            display:inline-flex; align-items:center; gap:6px;
            background: rgba(26,107,46,.1); color: var(--green);
            padding: 6px 18px; border-radius: 50px;
            font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
        }
        .section-heading {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            margin: 12px 0 16px;
        }
        .section-sub {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.8;
            max-width: 580px;
            margin: 0 auto;
        }
        .btn-primary-custom {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--grad-green);
            color: #fff; font-weight: 700; font-size: 15px;
            padding: 14px 34px; border-radius: 50px; border: none;
            transition: transform .25s, box-shadow .25s;
            cursor: pointer;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,107,46,.4); color:#fff; }
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: #fff; font-weight: 600; font-size: 15px;
            padding: 13px 30px; border-radius: 50px; border: 2px solid rgba(255,255,255,.45);
            transition: background .25s, border-color .25s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.12); border-color: #fff; color:#fff; }

        /* ── Navbar ────────────────────────────────────────────────── */
        #mainNav {
            position: fixed; inset: 0 0 auto;
            z-index: 1000;
            padding: 18px 0;
            transition: background .35s, padding .35s, box-shadow .35s;
        }
        #mainNav.scrolled {
            background: rgba(13,27,42,.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 10px 0;
            box-shadow: 0 4px 24px rgba(0,0,0,.3);
        }
        .nav-brand {
            display:flex; align-items:center; gap:10px;
            font-weight: 800; font-size: 1.25rem; color: #fff;
        }
        .nav-brand .logo-img { height:36px; border-radius:8px; }
        .nav-brand span { color: var(--green-light); }
        .navbar-nav .nav-link {
            color: rgba(255,255,255,.8) !important;
            font-weight: 500; font-size: 14px;
            padding: 6px 14px !important;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        .navbar-nav .nav-link:hover { color: #fff !important; background: rgba(255,255,255,.07); }
        .navbar-toggler { border: none; }
        .navbar-toggler-icon { filter: invert(1); }
        .btn-nav-donate {
            background: var(--grad-green); color: #fff;
            font-weight: 700; font-size: 14px;
            padding: 9px 24px; border-radius: 50px;
            transition: transform .2s, box-shadow .2s;
            text-decoration: none;
            display: inline-flex; align-items: center;
        }
        .btn-nav-donate:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(26,107,46,.4); color:#fff; }
        .btn-nav-dashboard {
            background: rgba(255,255,255,0.12); color: #fff;
            font-weight: 700; font-size: 14px;
            padding: 9px 20px; border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.25);
            transition: all .2s;
            text-decoration: none;
            display: inline-flex; align-items: center;
        }
        .btn-nav-dashboard:hover {
            background: #fff; color: #1a1a2e;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,0,0,.25);
        }

        /* ── Hero ──────────────────────────────────────────────────── */
        .hero-section {
            background: var(--grad-hero);
            min-height: 100vh;
            display: flex; align-items: center;
            position: relative; overflow: hidden;
            padding: 120px 0 80px;
        }
        .hero-particles {
            position: absolute; inset: 0; overflow: hidden; pointer-events: none;
        }
        .hero-particles span {
            position: absolute;
            border-radius: 50%;
            background: rgba(26,107,46,.15);
            animation: float 6s ease-in-out infinite;
        }
        .hero-particles span:nth-child(1) { width:300px;height:300px; top:-80px; right:-60px; animation-delay:0s; }
        .hero-particles span:nth-child(2) { width:200px;height:200px; bottom:10%; left:-40px; animation-delay:1.5s; }
        .hero-particles span:nth-child(3) { width:100px;height:100px; top:40%; right:20%; animation-delay:3s; opacity:.5; }

        .hero-title {
            font-size: clamp(2.4rem, 5vw, 4rem);
            font-weight: 900; color: #fff; line-height: 1.1;
            margin-bottom: 22px;
        }
        .hero-desc {
            font-size: 1.15rem; color: rgba(255,255,255,.7);
            line-height: 1.85; margin-bottom: 38px; max-width: 540px;
        }
        /* Floating blood-drop visual */
        .hero-visual {
            display:flex; align-items:center; justify-content:center;
            height: 100%;
        }
        .hero-drop-wrap {
            position: relative; width:340px; height:340px;
        }
        .hero-drop-ring {
            position: absolute; inset:0;
            border-radius: 50%;
            border: 2px solid rgba(26,107,46,.3);
            animation: spin-slow 18s linear infinite;
        }
        .hero-drop-ring::before {
            content:'';
            position:absolute; top:-6px; left:50%;
            transform:translateX(-50%);
            width:12px; height:12px;
            border-radius:50%;
            background: var(--green-light);
            box-shadow: 0 0 12px var(--green-light);
        }
        .hero-drop-inner {
            position: absolute;
            inset: 30px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(26,107,46,.25), rgba(26,107,46,.06));
            border: 1px solid rgba(26,107,46,.2);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
        }
        .hero-drop-icon {
            font-size: 80px;
            color: var(--green-light);
            filter: drop-shadow(0 0 20px rgba(40,167,69,.6));
        }
        .hero-drop-label {
            font-size: 13px; font-weight: 700; letter-spacing: 2px;
            color: rgba(255,255,255,.6); margin-top: 10px; text-transform: uppercase;
        }
        /* Hero stat mini-cards */
        .hero-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-top: 44px; }
        .hero-stat {
            background: rgba(255,255,255,.07);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius);
            padding: 18px 12px;
            text-align:center;
        }
        .hero-stat-val { font-size: 1.7rem; font-weight:800; color:#fff; line-height:1; }
        .hero-stat-lbl { font-size: 11px; color: rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.5px; margin-top:4px; }
        /* wave divider */
        .hero-wave { position:absolute; bottom:-2px; left:0; right:0; }

        /* ── Stats Bar ─────────────────────────────────────────────── */
        .stats-bar { background:#fff; padding: 50px 0; border-bottom: 1px solid var(--border); }
        .stat-item { text-align:center; padding:20px; }
        .stat-icon-wrap {
            width:60px; height:60px; border-radius:16px;
            display:flex; align-items:center; justify-content:center;
            margin: 0 auto 14px;
        }
        .stat-icon-wrap i { font-size:24px; }
        .stat-num {
            font-size: 2.4rem; font-weight:800; color:var(--text); line-height:1;
        }
        .stat-lbl { font-size:13px; color:var(--text-muted); font-weight:500; margin-top:4px; }

        /* ── About ─────────────────────────────────────────────────── */
        .about-section { background: var(--off-white); }
        .about-img-box {
            border-radius: var(--radius-lg);
            overflow:hidden;
            box-shadow: var(--shadow-lg);
            position:relative;
        }
        .about-img-box img { width:100%; height:440px; object-fit:cover; }
        .about-img-placeholder {
            width:100%; height:440px;
            background: var(--grad-green);
            display:flex; align-items:center; justify-content:center;
        }
        .about-overlay-card {
            position:absolute; bottom:24px; right:-20px;
            background:#fff; border-radius: var(--radius);
            padding: 18px 22px;
            box-shadow: var(--shadow);
            min-width: 170px;
        }
        .about-pill-card {
            background:#fff; border-radius:var(--radius);
            padding:22px; border-left: 4px solid var(--green);
            box-shadow: var(--shadow-sm);
        }
        .about-pill-num { font-size:2.2rem; font-weight:800; color:var(--green); }
        .about-pill-lbl { font-size:12px; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }

        /* ── Blood Groups ──────────────────────────────────────────── */
        .blood-section { background:#fff; }
        .blood-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; }
        @media(max-width:768px) { .blood-grid { grid-template-columns:repeat(2,1fr); } }
        .blood-card {
            background:#fff; border-radius:var(--radius);
            padding:28px 16px; text-align:center;
            border:1.5px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: transform .3s, box-shadow .3s, border-color .3s;
            position:relative; overflow:hidden;
        }
        .blood-card::before {
            content:''; position:absolute; inset:0;
            background: var(--grad-green);
            opacity:0; transition: opacity .3s;
        }
        .blood-card:hover { transform:translateY(-8px); box-shadow: var(--shadow); border-color: var(--green); }
        .blood-card:hover::before { opacity:.04; }
        .blood-card:hover .blood-circle { background:var(--grad-green); transform:scale(1.1); }
        .blood-card:hover .blood-circle i { color:#fff; }
        .blood-circle {
            width:64px; height:64px; border-radius:50%;
            background: rgba(26,107,46,.1);
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 14px;
            transition: background .3s, transform .3s;
        }
        .blood-circle i { font-size:24px; color:var(--green); transition:color .3s; }
        .blood-type { font-size:2rem; font-weight:800; color:var(--text); margin-bottom:6px; }
        .blood-pct {
            display:inline-block; background:rgba(26,107,46,.1);
            color:var(--green); padding:3px 14px;
            border-radius:50px; font-size:12px; font-weight:700;
        }
        .blood-donors { font-size:13px; color:var(--text-muted); margin-top:10px; }

        /* ── Campaigns ─────────────────────────────────────────────── */
        .campaigns-section { background:var(--off-white); }
        .campaign-card {
            background:#fff; border-radius:var(--radius-lg);
            overflow:hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: transform .3s, box-shadow .3s;
            height:100%;
        }
        .campaign-card:hover { transform:translateY(-8px); box-shadow: var(--shadow); }
        .campaign-header-img {
            height:190px;
            background: var(--grad-green);
            display:flex; flex-direction:column;
            align-items:center; justify-content:center;
            position:relative; overflow:hidden;
        }
        .campaign-header-img::before {
            content:''; position:absolute; inset:0;
            background: radial-gradient(circle at 70% 30%, rgba(255,255,255,.12), transparent 60%);
        }
        .campaign-date-day { font-size:3rem; font-weight:900; color:#fff; line-height:1; }
        .campaign-date-month { font-size:14px; color:rgba(255,255,255,.8); text-transform:uppercase; letter-spacing:1.5px; }
        .campaign-badge {
            position:absolute; top:12px; right:12px;
            background:rgba(255,255,255,.2); backdrop-filter:blur(6px);
            color:#fff; padding:5px 14px;
            border-radius:50px; font-size:11px; font-weight:700;
        }
        .campaign-body { padding:24px; }
        .campaign-title { font-weight:700; font-size:16px; color:var(--text); margin-bottom:10px; }
        .campaign-meta { font-size:13px; color:var(--text-muted); display:flex; align-items:center; gap:6px; margin-bottom:6px; }
        .campaign-progress { height:6px; background:var(--border); border-radius:10px; margin:14px 0 8px; overflow:hidden; }
        .campaign-bar { height:100%; background:var(--grad-green); border-radius:10px; transition:width 1.2s ease; }
        .campaign-units { font-size:12px; color:var(--text-muted); display:flex; justify-content:space-between; }

        /* ── How It Works ──────────────────────────────────────────── */
        .how-section { background:#fff; }
        .step-card {
            text-align:center; padding:40px 28px;
            border-radius:var(--radius-lg);
            background:#fff;
            border:1.5px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: transform .3s, box-shadow .3s, border-color .3s;
            height:100%; position:relative;
        }
        .step-card:hover { transform:translateY(-8px); box-shadow:var(--shadow); border-color:var(--green); }
        .step-icon-wrap {
            width:80px; height:80px; border-radius:50%;
            background: rgba(26,107,46,.1);
            border: 2px solid rgba(26,107,46,.15);
            display:flex; align-items:center; justify-content:center;
            margin: 0 auto 20px;
            position:relative;
            transition: background .3s;
        }
        .step-card:hover .step-icon-wrap { background: rgba(26,107,46,.18); }
        .step-icon-wrap i { font-size:28px; color:var(--green); }
        .step-num {
            position:absolute; top:-8px; right:-8px;
            width:28px; height:28px; border-radius:50%;
            background:var(--grad-green); color:#fff;
            font-size:13px; font-weight:800;
            display:flex; align-items:center; justify-content:center;
            box-shadow: 0 3px 10px rgba(26,107,46,.4);
        }
        .step-title { font-weight:700; font-size:17px; color:var(--text); margin-bottom:10px; }
        .step-desc { font-size:14px; color:var(--text-muted); line-height:1.7; }
        /* connector line between steps */
        .step-connector {
            position:absolute;
            top:50px; left:calc(50% + 60px); right:calc(-50% + 60px);
            height:2px;
            background: linear-gradient(90deg, var(--green), var(--green-light));
            opacity:.25;
        }
        @media(max-width:991px) { .step-connector { display:none; } }

        /* ── Testimonials ──────────────────────────────────────────── */
        .testimonials-section { background:var(--off-white); }
        .story-card {
            background:#fff;
            border-radius:var(--radius);
            padding:28px;
            border-left:4px solid var(--green);
            box-shadow:var(--shadow-sm);
            height:100%;
            transition:box-shadow .3s;
        }
        .story-card:hover { box-shadow: var(--shadow); }
        .story-quote { font-size:13px; color:var(--text-muted); line-height:1.7; font-style:italic; margin-bottom:18px; }
        .story-avatar {
            width:44px; height:44px; border-radius:50%;
            overflow:hidden; flex-shrink:0;
            background:var(--grad-green);
            display:flex; align-items:center; justify-content:center;
        }
        .story-avatar img { width:100%; height:100%; object-fit:cover; }
        .story-avatar i { font-size:18px; color:#fff; }
        .story-name { font-weight:700; font-size:14px; color:var(--text); }
        .story-meta { font-size:12px; color:var(--text-muted); margin-top:2px; }
        .carousel-btn {
            width:40px; height:40px; border-radius:50%;
            background:#fff; border:1.5px solid var(--border);
            display:flex; align-items:center; justify-content:center;
            color:var(--text-muted); cursor:pointer;
            transition: background .2s, color .2s, border-color .2s;
        }
        .carousel-btn:hover { background:var(--green); color:#fff; border-color:var(--green); }

        /* ── CTA ───────────────────────────────────────────────────── */
        .cta-section {
            background: var(--grad-hero);
            padding: 100px 0; position:relative; overflow:hidden;
        }
        .cta-section::before {
            content:''; position:absolute; inset:0;
            background: radial-gradient(circle at 20% 50%, rgba(26,107,46,.2), transparent 50%),
                        radial-gradient(circle at 80% 30%, rgba(21,101,168,.15), transparent 50%);
        }
        .cta-chip {
            display:inline-flex; align-items:center; gap:8px;
            background:rgba(255,255,255,.08); backdrop-filter:blur(10px);
            border:1px solid rgba(255,255,255,.12);
            color:#fff; padding:12px 22px; border-radius:12px;
            font-size:14px; font-weight:500;
        }
        .cta-chip i { font-size:16px; }

        /* ── Footer ────────────────────────────────────────────────── */
        .footer-section {
            background: var(--navy);
            padding: 70px 0 30px;
            border-top: 1px solid rgba(255,255,255,.05);
        }
        .footer-brand {
            display:flex; align-items:center; gap:10px;
            font-weight:800; font-size:1.3rem; color:#fff; margin-bottom:14px;
        }
        .footer-brand .logo-img { height:34px; border-radius:8px; }
        .footer-brand span { color:var(--green-light); }
        .footer-desc { color:rgba(255,255,255,.45); font-size:14px; line-height:1.8; }
        .footer-heading { font-weight:700; font-size:14px; color:#fff; margin-bottom:18px; letter-spacing:.5px; text-transform:uppercase; }
        .footer-link {
            display:block; color:rgba(255,255,255,.5); font-size:14px;
            padding:5px 0; transition:color .2s, padding-left .2s;
        }
        .footer-link:hover { color:#fff; padding-left:4px; }
        .footer-contact-row { display:flex; align-items:center; gap:10px; color:rgba(255,255,255,.5); font-size:14px; margin-bottom:12px; }
        .footer-contact-row i { color:var(--green-light); font-size:14px; flex-shrink:0; }
        .social-link {
            width:38px; height:38px; border-radius:50%;
            background:rgba(255,255,255,.07);
            display:inline-flex; align-items:center; justify-content:center;
            color:rgba(255,255,255,.6); font-size:15px;
            transition: background .2s, color .2s, transform .2s;
        }
        .social-link:hover { background:var(--grad-green); color:#fff; transform:translateY(-2px); }
        .footer-divider { border-color:rgba(255,255,255,.06); margin:32px 0 20px; }
        .footer-copy { color:rgba(255,255,255,.25); font-size:13px; }

        /* ── Responsive tweaks ─────────────────────────────────────── */
        @media (max-width:991px) {
            .hero-visual { margin-top:48px; }
            .hero-drop-wrap { width:260px; height:260px; }
            .about-overlay-card { position:relative; right:auto; bottom:auto; margin-top:16px; }
        }
        @media (max-width:767px) {
            .section-py { padding:64px 0; }
            .hero-stats { grid-template-columns:repeat(3,1fr); gap:10px; }
            .hero-stat-val { font-size:1.3rem; }
            .blood-grid { grid-template-columns:repeat(2,1fr); }
        }
        @media (max-width:480px) {
            .hero-title { font-size:2rem; }
            .hero-stats { grid-template-columns:repeat(3,1fr); }
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════════════════ --}}
<nav id="mainNav" class="navbar navbar-expand-lg">
    <div class="container">
        <a class="nav-brand" href="#">
            @if($ngo['logo'] && file_exists(storage_path('app/public/' . $ngo['logo'])))
                <img src="{{ asset('storage/' . $ngo['logo']) }}" alt="{{ $ngo['name'] }}" class="logo-img">
            @else
                <i class="fas fa-tint" style="color:var(--green-light);font-size:22px;"></i>
                <span>{{ $ngo['name'] }}</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#blood-groups">Blood Groups</a></li>
                <li class="nav-item"><a class="nav-link" href="#campaigns">Campaigns</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Stories</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('portal.verify') }}"><i class="fas fa-id-card me-1"></i>Verify</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('portal.availability') }}"><i class="fas fa-search me-1"></i>Availability</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-nav-donate">
                    <i class="fas fa-tint me-1"></i> Donate
                </a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn-nav-dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="hero-particles">
        <span></span><span></span><span></span>
    </div>
    <div class="container position-relative">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                @if(!empty($settings['hero_tagline']))
                    <div class="badge-pill mb-4 fade-up" style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.15);">
                        <i class="fas fa-heartbeat"></i>{{ $settings['hero_tagline'] }}
                    </div>
                @endif
                <h1 class="hero-title fade-up" style="animation-delay:.1s;">
                    {{ $settings['hero_sub_tagline'] ?? 'Every Drop Counts' }}
                </h1>
                <p class="hero-desc fade-up" style="animation-delay:.2s;">
                    {{ $settings['hero_description'] ?? 'Join our growing community of life-savers. Register as a donor today and help someone in need.' }}
                </p>
                <div class="d-flex flex-wrap gap-3 fade-up" style="animation-delay:.3s;">
                    <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-primary-custom">
                        <i class="fas fa-tint"></i>{{ $settings['hero_cta_text'] ?? 'Donate Blood' }}
                    </a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @endauth
                    <a href="#about" class="btn-ghost">
                        {{ $settings['hero_secondary_cta'] ?? 'Learn More' }}<i class="fas fa-arrow-down"></i>
                    </a>
                </div>

                {{-- Mini stats --}}
                <div class="hero-stats fade-up" style="animation-delay:.4s;">
                    @php
                        $hStats = [
                            ['value'=>$settings['hero_stat_1_value']??'5,000+','label'=>$settings['hero_stat_1_label']??'Lives Saved'],
                            ['value'=>$settings['hero_stat_2_value']??'2,000+','label'=>$settings['hero_stat_2_label']??'Donors'],
                            ['value'=>$settings['hero_stat_3_value']??'150+',  'label'=>$settings['hero_stat_3_label']??'Drives'],
                        ];
                    @endphp
                    @foreach($hStats as $hs)
                        <div class="hero-stat">
                            <div class="hero-stat-val">{{ $hs['value'] }}</div>
                            <div class="hero-stat-lbl">{{ $hs['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-visual">
                    <div class="hero-drop-wrap float-anim">
                        <div class="hero-drop-ring"></div>
                        <div class="hero-drop-inner">
                            <i class="fas fa-tint hero-drop-icon drip-anim"></i>
                            <div class="hero-drop-label">Donate Blood</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave --}}
    <div class="hero-wave">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
        </svg>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════════════════ --}}
<section class="stats-bar reveal">
    <div class="container">
        <div class="row g-2">
            @php
                $statItems = [
                    ['icon'=>'fas fa-users',       'target'=>$stats['donors'],     'label'=>'Registered Donors', 'color'=>'var(--green)',  'bg'=>'rgba(26,107,46,.1)'],
                    ['icon'=>'fas fa-heartbeat',   'target'=>$stats['lives_saved'],'label'=>'Lives Saved',       'color'=>'var(--red)',    'bg'=>'rgba(192,57,43,.1)'],
                    ['icon'=>'fas fa-calendar-alt','target'=>$stats['drives'],     'label'=>'Drives Held',       'color'=>'var(--accent)', 'bg'=>'rgba(21,101,168,.1)'],
                    ['icon'=>'fas fa-city',        'target'=>$stats['cities'],     'label'=>'Cities Covered',    'color'=>'#7c3aed',       'bg'=>'rgba(124,58,237,.1)'],
                ];
            @endphp
            @foreach($statItems as $s)
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-icon-wrap" style="background:{{ $s['bg'] }};">
                            <i class="{{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
                        </div>
                        <div class="stat-num" data-target="{{ $s['target'] }}">0</div>
                        <div class="stat-lbl">{{ $s['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     ABOUT
══════════════════════════════════════════════════════ --}}
<section id="about" class="section-py about-section reveal">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal-d1">
                <div class="badge-pill mb-3"><i class="fas fa-leaf"></i>{{ $settings['about_eyebrow'] ?? 'Who We Are' }}</div>
                <h2 class="section-heading">{{ $settings['about_heading'] ?? 'Making Blood Donation Accessible for Everyone' }}</h2>
                <p style="color:var(--text-muted);font-size:1.05rem;line-height:1.85;margin-bottom:28px;">
                    {{ $settings['about_body'] ?? 'We are a dedicated non-profit organization committed to building a sustainable blood donation network in Pakistan. Our mission is to connect generous donors with patients in need — saving lives every single day.' }}
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="about-pill-card">
                            <div class="about-pill-num">{{ $settings['about_founded_year'] ?? '2018' }}</div>
                            <div class="about-pill-lbl">Founded</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-pill-card" style="border-left-color:var(--accent);">
                            <div style="font-size:14px;font-weight:600;color:var(--text);line-height:1.5;">{{ $settings['about_mission'] ?? 'Saving lives through community-driven blood donation.' }}</div>
                        </div>
                    </div>
                </div>
                <a href="#campaigns" class="btn-primary-custom">
                    <i class="fas fa-arrow-right"></i>{{ $settings['about_cta_text'] ?? 'Our Campaigns' }}
                </a>
            </div>
            <div class="col-lg-6 reveal-d2">
                <div class="about-img-box">
                    @if(!empty($settings['about_image_1']) && file_exists(storage_path('app/public/' . $settings['about_image_1'])))
                        <img src="{{ asset('storage/' . $settings['about_image_1']) }}" alt="About {{ $ngo['name'] }}">
                    @else
                        <div class="about-img-placeholder">
                            <i class="fas fa-hands-helping" style="font-size:80px;color:rgba(255,255,255,.35);"></i>
                        </div>
                    @endif
                    {{-- Floating overlay card --}}
                    <div class="about-overlay-card text-center" style="position:absolute;bottom:24px;right:-16px;">
                        <div style="font-size:2rem;font-weight:800;color:var(--green);">{{ $stats['donors'] ?? '0' }}+</div>
                        <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Active Donors</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     BLOOD GROUPS
══════════════════════════════════════════════════════ --}}
<section id="blood-groups" class="section-py blood-section reveal">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge-pill mb-3"><i class="fas fa-tint"></i>Blood Types</div>
            <h2 class="section-heading">{{ $settings['blood_section_heading'] ?? 'All Blood Groups We Serve' }}</h2>
            <p class="section-sub">Every blood type matters. We have active donors across all blood groups ready to help.</p>
        </div>
        @php
            $allGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
            $groupColors = ['A+'=>'#e74c3c','A-'=>'#c0392b','B+'=>'#28a745','B-'=>'#1a6b2e','AB+'=>'#17a2b8','AB-'=>'#0d6efd','O+'=>'#fd7e14','O-'=>'#6f42c1'];
            $donorsByGroup = $bloodGroupDonors ?? [];
            $totalD = max(array_sum($donorsByGroup), 1);
        @endphp
        <div class="blood-grid">
            @foreach($allGroups as $bg)
                @php
                    $count = $donorsByGroup[$bg] ?? 0;
                    $pct   = round(($count / $totalD) * 100);
                    $color = $groupColors[$bg] ?? 'var(--green)';
                    $avail = ($bloodStock[$bg] ?? 0) > 0;
                @endphp
                <div class="blood-card reveal reveal-d{{ ($loop->index % 4) + 1 }}">
                    <div class="blood-circle">
                        <i class="fas fa-tint" style="color:{{ $color }};"></i>
                    </div>
                    <div class="blood-type" style="color:{{ $color }};">{{ $bg }}</div>
                    <div class="blood-pct" style="background:{{ $color }}18;color:{{ $color }};">{{ $pct }}%</div>
                    <div class="blood-donors">{{ number_format($count) }} donors</div>
                    <div style="margin-top:10px;">
                        @if($avail)
                            <span style="font-size:11px;font-weight:700;color:#28a745;"><i class="fas fa-check-circle me-1"></i>Available</span>
                        @else
                            <span style="font-size:11px;font-weight:700;color:#dc3545;"><i class="fas fa-times-circle me-1"></i>Needed</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('portal.availability') }}" class="btn-primary-custom">
                <i class="fas fa-search"></i>Check Blood Availability
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     CAMPAIGNS
══════════════════════════════════════════════════════ --}}
<section id="campaigns" class="section-py campaigns-section reveal">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge-pill mb-3"><i class="fas fa-calendar-alt"></i>Campaigns</div>
            <h2 class="section-heading">{{ $settings['campaigns_section_heading'] ?? 'Upcoming Blood Drives' }}</h2>
            <p class="section-sub">Join our drives and make a direct impact in your community.</p>
        </div>
        @if(isset($campaigns) && $campaigns->count() > 0)
            <div class="row g-4">
                @foreach($campaigns->take(6) as $campaign)
                    <div class="col-lg-4 col-md-6">
                        <div class="campaign-card">
                            <div class="campaign-header-img">
                                <div class="campaign-badge">
                                    {{ \Carbon\Carbon::parse($campaign->date)->diffForHumans() }}
                                </div>
                                <div class="campaign-date-day">{{ \Carbon\Carbon::parse($campaign->date)->format('d') }}</div>
                                <div class="campaign-date-month">{{ \Carbon\Carbon::parse($campaign->date)->format('M Y') }}</div>
                            </div>
                            <div class="campaign-body">
                                <h5 class="campaign-title">{{ $campaign->name }}</h5>
                                <div class="campaign-meta">
                                    <i class="fas fa-map-marker-alt" style="color:var(--green);"></i>
                                    {{ $campaign->venue ?? 'TBA' }}
                                </div>
                                @if($campaign->city_name ?? null)
                                    <div class="campaign-meta">
                                        <i class="fas fa-city" style="color:var(--accent);"></i>
                                        {{ $campaign->city_name }}
                                    </div>
                                @endif
                                @if($campaign->target_units)
                                    @php
                                        $filled = min($campaign->collected_units ?? 0, $campaign->target_units);
                                        $pctBar = round(($filled / max($campaign->target_units,1)) * 100);
                                    @endphp
                                    <div class="campaign-progress">
                                        <div class="campaign-bar" style="width:{{ $pctBar }}%;"></div>
                                    </div>
                                    <div class="campaign-units">
                                        <span>{{ $filled }} / {{ $campaign->target_units }} units</span>
                                        <span>{{ $pctBar }}%</span>
                                    </div>
                                @endif
                                <a href="{{ route('admin.campaigns.show', $campaign->id) }}" class="btn-primary-custom mt-3 w-100 justify-content-center" style="font-size:14px;padding:10px 20px;">
                                    <i class="fas fa-arrow-right"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5" style="color:var(--text-muted);">
                <i class="fas fa-calendar-times" style="font-size:60px;opacity:.3;"></i>
                <p class="mt-3">No upcoming campaigns at the moment. Check back soon!</p>
            </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════════════════ --}}
<section id="how-it-works" class="section-py how-section reveal">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge-pill mb-3"><i class="fas fa-route"></i>Process</div>
            <h2 class="section-heading">{{ $settings['how_section_heading'] ?? 'How It Works' }}</h2>
            <p class="section-sub">Donating blood is simple, safe, and takes less than an hour. Here's how:</p>
        </div>
        @php
            $steps = [
                ['icon'=>$settings['how_step_1_icon']??'fas fa-user-plus',      'title'=>$settings['how_step_1_title']??'Register',      'desc'=>$settings['how_step_1_desc']??'Sign up with your basic information and blood type. The process takes 2 minutes.'],
                ['icon'=>$settings['how_step_2_icon']??'fas fa-bell',           'title'=>$settings['how_step_2_title']??'Get Notified',   'desc'=>$settings['how_step_2_desc']??'Receive alerts when blood of your type is urgently needed near your location.'],
                ['icon'=>$settings['how_step_3_icon']??'fas fa-tint',           'title'=>$settings['how_step_3_title']??'Donate',         'desc'=>$settings['how_step_3_desc']??'Visit a nearby drive or centre. The actual donation takes just 8-10 minutes.'],
                ['icon'=>$settings['how_step_4_icon']??'fas fa-heartbeat',      'title'=>$settings['how_step_4_title']??'Save a Life',    'desc'=>$settings['how_step_4_desc']??'Your blood is processed, tested, and delivered to a patient in critical need.'],
            ];
        @endphp
        <div class="row g-4 position-relative">
            @foreach($steps as $i => $step)
                <div class="col-lg-3 col-md-6">
                    <div class="step-card reveal reveal-d{{ $i+1 }}">
                        @if(!$loop->last)
                            <div class="step-connector"></div>
                        @endif
                        <div class="step-icon-wrap">
                            <i class="{{ $step['icon'] }}"></i>
                            <div class="step-num">{{ $i+1 }}</div>
                        </div>
                        <h5 class="step-title">{{ $step['title'] }}</h5>
                        <p class="step-desc">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-primary-custom" style="padding:16px 44px;font-size:16px;">
                <i class="fas fa-tint"></i>Register as Donor
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     TESTIMONIALS / STORIES
══════════════════════════════════════════════════════ --}}
<section id="testimonials" class="section-py testimonials-section reveal">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge-pill mb-3"><i class="fas fa-quote-left"></i>Stories</div>
            <h2 class="section-heading">{{ $settings['testimonials_section_heading'] ?? 'Hear From Our Heroes' }}</h2>
            <p class="section-sub">Real stories from real donors who have made a difference.</p>
        </div>

        @if($stories->count() > 0)
            <div id="storiesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    @foreach($stories->chunk(3) as $ci => $chunk)
                        <div class="carousel-item {{ $ci === 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($chunk as $story)
                                    <div class="col-md-4">
                                        <div class="story-card">
                                            <i class="fas fa-quote-left mb-3" style="font-size:20px;color:rgba(26,107,46,.2);"></i>
                                            <p class="story-quote">"{{ Str::limit($story->quote, 120) }}"</p>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="story-avatar">
                                                    @if($story->photo)
                                                        <img src="{{ asset('storage/' . $story->photo) }}" alt="{{ $story->name }}">
                                                    @else
                                                        <i class="fas fa-user"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="story-name">{{ $story->name }}</div>
                                                    <div class="story-meta">
                                                        @if($story->blood_group)
                                                            <span style="background:var(--green);color:#fff;padding:2px 8px;border-radius:50px;font-size:10px;font-weight:700;">{{ $story->blood_group }}</span>
                                                        @endif
                                                        @if($story->city)
                                                            <span><i class="fas fa-map-marker-alt me-1" style="color:var(--green);"></i>{{ $story->city }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($stories->count() > 3)
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button class="carousel-btn" data-bs-target="#storiesCarousel" data-bs-slide="prev">
                            <i class="fas fa-chevron-left" style="font-size:13px;"></i>
                        </button>
                        <button class="carousel-btn" data-bs-target="#storiesCarousel" data-bs-slide="next">
                            <i class="fas fa-chevron-right" style="font-size:13px;"></i>
                        </button>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-heart" style="font-size:64px;color:var(--border);"></i>
                <p style="color:var(--text-muted);margin-top:18px;font-size:1.05rem;">No stories yet. Be the first hero to share yours!</p>
                <a href="{{ route('portal.registration.create') }}" class="btn-primary-custom mt-3">
                    <i class="fas fa-user-plus"></i>Become a Donor
                </a>
            </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     CTA
══════════════════════════════════════════════════════ --}}
<section id="contact" class="cta-section reveal">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 style="font-size:clamp(2rem,4vw,2.8rem);font-weight:900;color:#fff;margin-bottom:16px;">
                    {{ $settings['cta_heading'] ?? 'Ready to Make a Difference?' }}
                </h2>
                <p style="font-size:1.15rem;color:rgba(255,255,255,.7);margin-bottom:38px;line-height:1.7;">
                    {{ $settings['cta_subheading'] ?? 'Join thousands of donors saving lives every day.' }}
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
                    <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-primary-custom" style="padding:16px 40px;font-size:16px;">
                        <i class="fas fa-tint"></i>{{ $settings['cta_donate_btn_text'] ?? 'Donate Blood Now' }}
                    </a>
                    <a href="{{ route('portal.registration.create') }}" class="btn-ghost" style="padding:16px 40px;font-size:16px;">
                        <i class="fas fa-user-plus"></i>{{ $settings['cta_register_btn_text'] ?? 'Register as Donor' }}
                    </a>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @if(!empty($settings['cta_phone']))
                        <div class="cta-chip"><i class="fas fa-phone-alt" style="color:var(--green-light);"></i>{{ $settings['cta_phone'] }}</div>
                    @endif
                    @if(!empty($settings['cta_whatsapp']))
                        <div class="cta-chip"><i class="fab fa-whatsapp" style="color:#25d366;"></i>{{ $settings['cta_whatsapp'] }}</div>
                    @endif
                    @if(!empty($settings['cta_email']))
                        <div class="cta-chip"><i class="fas fa-envelope" style="color:#60a5fa;"></i>{{ $settings['cta_email'] }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════ --}}
<footer class="footer-section">
    <div class="container">
        <div class="row g-4">
            {{-- Brand --}}
            <div class="col-lg-4">
                <div class="footer-brand">
                    @if($ngo['logo'] && file_exists(storage_path('app/public/' . $ngo['logo'])))
                        <img src="{{ asset('storage/' . $ngo['logo']) }}" alt="{{ $ngo['name'] }}" class="logo-img">
                    @else
                        <i class="fas fa-tint" style="color:var(--green-light);font-size:20px;"></i>
                        <span>{{ $ngo['name'] }}</span>
                    @endif
                </div>
                <p class="footer-desc mb-4">{{ $ngo['footer_text'] ?? 'Dedicated to saving lives through a sustainable blood donation network.' }}</p>
                @if(!empty($ngo['address']))
                    <div class="footer-contact-row mb-3">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $ngo['address'] }}</span>
                    </div>
                @endif
                <div class="d-flex gap-2 mt-3">
                    @if(!empty($settings['cta_whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['cta_whatsapp']) }}" target="_blank" class="social-link" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    @endif
                    @if(!empty($settings['cta_email']))
                        <a href="mailto:{{ $settings['cta_email'] }}" class="social-link" title="Email"><i class="fas fa-envelope"></i></a>
                    @endif
                    @if(!empty($settings['cta_phone']))
                        <a href="tel:{{ $settings['cta_phone'] }}" class="social-link" title="Phone"><i class="fas fa-phone-alt"></i></a>
                    @endif
                    <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-6">
                <h6 class="footer-heading">Quick Links</h6>
                <a href="#about" class="footer-link">About Us</a>
                <a href="#blood-groups" class="footer-link">Blood Groups</a>
                <a href="#campaigns" class="footer-link">Campaigns</a>
                <a href="#how-it-works" class="footer-link">How It Works</a>
                <a href="#testimonials" class="footer-link">Stories</a>
                <a href="{{ route('portal.verify') }}" class="footer-link">Verify Donor</a>
                <a href="{{ route('portal.availability') }}" class="footer-link">Blood Availability</a>
            </div>

            {{-- Contact --}}
            <div class="col-lg-3 col-6">
                <h6 class="footer-heading">Contact Info</h6>
                @if(!empty($ngo['footer_phone']) || !empty($settings['cta_phone']))
                    <div class="footer-contact-row">
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ $ngo['footer_phone'] ?: ($settings['cta_phone'] ?? '') }}</span>
                    </div>
                @endif
                @if(!empty($ngo['footer_email']) || !empty($settings['cta_email']))
                    <div class="footer-contact-row">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $ngo['footer_email'] ?: ($settings['cta_email'] ?? '') }}</span>
                    </div>
                @endif
                @if(!empty($settings['cta_whatsapp']))
                    <div class="footer-contact-row">
                        <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                        <span>{{ $settings['cta_whatsapp'] }}</span>
                    </div>
                @endif
            </div>

            {{-- CTA block --}}
            <div class="col-lg-3">
                <h6 class="footer-heading">Get Involved</h6>
                <p class="footer-desc mb-4">Every drop counts. Register today and become a hero in your community.</p>
                <a href="{{ $settings['hero_cta_url'] ?? route('portal.registration.create') }}" class="btn-primary-custom" style="font-size:14px;padding:11px 26px;">
                    <i class="fas fa-tint"></i>Donate Now
                </a>
            </div>
        </div>

        <hr class="footer-divider">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <p class="footer-copy mb-0">&copy; {{ date('Y') }} {{ $ngo['name'] }}. All rights reserved.</p>
            <p class="footer-copy mb-0">Made with <i class="fas fa-heart" style="color:var(--green-light);"></i> for humanity</p>
        </div>
    </div>
</footer>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function () {

    /* ── Navbar scroll ── */
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive:true });

    /* ── Smooth scroll ── */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const tgt = document.querySelector(a.getAttribute('href'));
            if (tgt) { e.preventDefault(); tgt.scrollIntoView({ behavior:'smooth', block:'start' }); }
        });
    });

    /* ── Counter animation ── */
    let counted = false;
    function runCounters() {
        if (counted) return;
        counted = true;
        document.querySelectorAll('.stat-num[data-target]').forEach(el => {
            const end = parseInt(el.dataset.target) || 0;
            const dur = 2000;
            const step = Math.ceil(end / (dur / 16));
            let cur = 0;
            const t = setInterval(() => {
                cur = Math.min(cur + step, end);
                el.textContent = cur.toLocaleString();
                if (cur >= end) clearInterval(t);
            }, 16);
        });
    }

    /* ── Intersection Observer (reveal + counters) ── */
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                if (entry.target.classList.contains('stats-bar')) runCounters();
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(el => io.observe(el));

    /* ── Campaign progress bars animate on scroll ── */
    const barObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const bar = e.target.querySelector('.campaign-bar');
                if (bar) { const w = bar.dataset.width; setTimeout(() => bar.style.width = w, 100); }
            }
        });
    }, { threshold: .3 });
    document.querySelectorAll('.campaign-card').forEach(c => barObserver.observe(c));

});
</script>
</body>
</html>
