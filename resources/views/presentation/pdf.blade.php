<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Enterprise Blood Bank &amp; Donor Management Portal - Executive Pitch Deck</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 7mm 9mm 7mm 9mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            background: #ffffff;
            font-size: 9.5px;
            line-height: 1.35;
        }

        /* ── Slide Frame ── */
        .slide {
            width: 100%;
            height: 191mm;
            max-height: 191mm;
            overflow: hidden;
            page-break-after: always;
            position: relative;
        }
        .slide:last-child {
            page-break-after: avoid;
        }

        /* ── Cover Slide (Full Bleed Dark Theme) ── */
        .cover-slide {
            width: 100%;
            height: 100%;
            background: #0b132b;
            border-radius: 8px;
            padding: 24px 32px;
            color: #ffffff;
        }
        .cover-badge {
            display: inline-block;
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #34d399;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 14px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }
        .cover-title {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.15;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .cover-subtitle {
            font-size: 13px;
            color: #cbd5e1;
            line-height: 1.5;
            max-width: 820px;
            margin-bottom: 24px;
        }
        .cover-stat-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 14px 12px;
            text-align: center;
        }
        .cover-stat-val {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .cover-stat-lbl {
            font-size: 8.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
        }
        .cover-highlights-bar {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            padding: 10px 16px;
            margin-top: 22px;
            font-size: 9.5px;
            color: #cbd5e1;
            line-height: 1.5;
        }

        /* ── Standard Slide Layout ── */
        .slide-content {
            width: 100%;
            height: 100%;
            display: table;
        }
        .slide-header {
            border-bottom: 2px solid #059669;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .slide-header table {
            width: 100%;
            border-collapse: collapse;
        }
        .slide-eyebrow {
            font-size: 9px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .slide-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
        }
        .slide-badge {
            background: #0f172a;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 12px;
            display: inline-block;
        }

        .slide-body {
            height: 154mm;
            max-height: 154mm;
            overflow: hidden;
        }

        .slide-footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            margin-top: 8px;
            font-size: 8px;
            color: #94a3b8;
        }
        .slide-footer table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ── Table Grids ── */
        table.grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
        }
        table.grid > tbody > tr > td {
            vertical-align: top;
        }

        /* ── Cards ── */
        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }
        .card-emerald { border-left: 4px solid #059669; }
        .card-blue    { border-left: 4px solid #2563eb; }
        .card-red     { border-left: 4px solid #dc2626; }
        .card-amber   { border-left: 4px solid #d97706; }
        .card-purple  { border-left: 4px solid #7c3aed; }
        .card-dark    { border-left: 4px solid #0f172a; }

        .card-title {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .card-desc {
            font-size: 9px;
            color: #475569;
            line-height: 1.42;
        }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            font-size: 7.5px;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 10px;
            text-transform: uppercase;
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }
        .badge-amber  { background: #fef3c7; color: #b45309; }
        .badge-dark   { background: #1e293b; color: #f8fafc; }

        /* ── Metric Card ── */
        .metric-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 9px 10px;
            text-align: center;
        }
        .metric-val {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.1;
        }
        .metric-lbl {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* ── Tables ── */
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }
        .styled-table th {
            background: #0f172a;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
        }
        .styled-table td {
            padding: 5.5px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .styled-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        /* ── Process Steps ── */
        .step-box {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 9px 10px;
            text-align: center;
        }
        .step-num {
            width: 22px;
            height: 22px;
            line-height: 22px;
            background: #059669;
            color: #ffffff;
            font-weight: bold;
            font-size: 9.5px;
            border-radius: 50%;
            margin: 0 auto 5px;
        }
    </style>
</head>
<body>

    {{-- ══════════════════════════════════════════════════
         SLIDE 1: COVER SLIDE (Full Height Bleed)
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="cover-slide">
            <div class="cover-badge">Enterprise Edition 2026 &bull; Technical Overview &amp; Platform Architecture</div>
            <div class="cover-title">
                SMART BLOOD BANK &amp;<br>
                DONOR MANAGEMENT PORTAL
            </div>
            <div class="cover-subtitle">
                A mission-critical healthcare platform for blood banks, thalassemia welfare societies, and humanitarian trusts. Built to automate donor matching, eliminate operational bottlenecks, and save lives in real-time.
            </div>

            <table style="width: 100%; border-collapse: separate; border-spacing: 12px 0;">
                <tr>
                    <td style="width: 25%;">
                        <div class="cover-stat-box">
                            <div class="cover-stat-val" style="color: #34d399;">12 Modules</div>
                            <div class="cover-stat-lbl">Full Lifecycle Suite</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="cover-stat-box">
                            <div class="cover-stat-val" style="color: #38bdf8;">Live QR Code</div>
                            <div class="cover-stat-lbl">Public Instant Verification</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="cover-stat-box">
                            <div class="cover-stat-val" style="color: #f87171;">1-Click WhatsApp</div>
                            <div class="cover-stat-lbl">Zero Gateway Tolls</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="cover-stat-box">
                            <div class="cover-stat-val" style="color: #fbbf24;">English + Urdu</div>
                            <div class="cover-stat-lbl">Native Nastaliq RTL</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="cover-highlights-bar">
                <strong style="color: #34d399;">Core Value Proposition:</strong>
                Sub-3-second emergency matching &bull; 90-day medical cooldown countdown &bull; 24/7 public emergency blood request portal &bull; Bulk CSV donor migration &bull; Zero third-party recurring gateway costs.
            </div>

            <table style="width: 100%; margin-top: 18px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <tr>
                    <td style="font-size: 8.5px; color: #94a3b8;">
                        <strong>Target Audience:</strong> NGO Board of Trustees, Healthcare Directors, Medical Camp Coordinators
                    </td>
                    <td style="font-size: 8.5px; color: #34d399; text-align: right; font-weight: bold;">
                        ✔ Confirmed Production-Ready &bull; Laravel 13 Architecture
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 2: THE OPERATIONAL CRISIS
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Strategic Problem Statement</div>
                        <div class="slide-title">The Critical Challenges in Traditional Blood Banking</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 02 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="grid" style="margin-bottom: 12px;">
                <tr>
                    <td style="width: 33.33%;">
                        <div class="card card-red" style="height: 150px;">
                            <div class="card-title" style="color: #b91c1c;">1. Fatal Emergency Delays</div>
                            <div class="card-desc">
                                In urgent surgeries and thalassemia transfusions, coordinators must search physical paper registers or unindexed spreadsheets. Manual search and calling takes <strong>45 to 60 minutes</strong>, creating life-threatening risks during trauma.
                            </div>
                        </div>
                    </td>
                    <td style="width: 33.33%;">
                        <div class="card card-amber" style="height: 150px;">
                            <div class="card-title" style="color: #b45309;">2. Unverified Donor Claims</div>
                            <div class="card-desc">
                                Hospitals have no mechanism to verify if a donor's blood card is authentic or whether they are currently in a medical cooldown period. Unscrupulous blood agents exploit paper slips, jeopardizing patient health and blood safety.
                            </div>
                        </div>
                    </td>
                    <td style="width: 33.33%;">
                        <div class="card card-purple" style="height: 150px;">
                            <div class="card-title" style="color: #6d28d9;">3. Astronomical Gateway Bills</div>
                            <div class="card-desc">
                                Traditional SMS gateways cost Rs. 25,000–45,000 monthly, with low recipient open rates. Paper ledgers tear, lose records, and cannot produce validated audit reports for foreign grant applications.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 7px; padding: 12px 16px;">
                <div style="font-size: 11.5px; font-weight: bold; color: #15803d; margin-bottom: 6px;">
                    ✔ How Our Platform Completely Solves This Crisis:
                </div>
                <table style="width: 100%; font-size: 9px; line-height: 1.55; color: #334155;">
                    <tr>
                        <td style="width: 50%; padding-right: 12px;">
                            &bull; <strong>Sub-3-Second Matching:</strong> Multi-city matching filters donors by compatible group, city, and cooldown.<br>
                            &bull; <strong>1-Click Free WhatsApp:</strong> Pre-filled emergency dispatch via <code>wa.me</code> protocol with zero SMS costs.
                        </td>
                        <td style="width: 50%;">
                            &bull; <strong>Instant QR Verification:</strong> Doctors scan digital donor cards on smartphones to verify real-time eligibility.<br>
                            &bull; <strong>Automated 90-Day Engine:</strong> Automatic pulse indicator prevents premature donations per WHO standards.
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 02</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 3: BEFORE VS AFTER TRANSFORMATION
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Transformation Matrix</div>
                        <div class="slide-title">Traditional Paper System vs Smart Digital Platform</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 03 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="styled-table" style="margin-bottom: 12px;">
                <thead>
                    <tr>
                        <th style="width: 22%;">Operational Function</th>
                        <th style="width: 33%;">Traditional Manual Method</th>
                        <th style="width: 33%;">Smart Digital Platform</th>
                        <th style="width: 12%;">Impact / Gain</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Emergency Matching</strong></td>
                        <td>Leafing through physical notebooks (45–60 mins)</td>
                        <td>Smart algorithm matches eligible donors (&lt; 3 seconds)</td>
                        <td><span class="badge badge-green">95% Faster</span></td>
                    </tr>
                    <tr>
                        <td><strong>Donor Messaging</strong></td>
                        <td>Typing manual SMS or paying third-party gateways</td>
                        <td>1-Click WhatsApp Direct Emergency Dispatch (Pre-filled)</td>
                        <td><span class="badge badge-green">100% Free</span></td>
                    </tr>
                    <tr>
                        <td><strong>Donor Verification</strong></td>
                        <td>Unverified paper slips or photocopies</td>
                        <td>Live Dynamic QR Code scannable with any smartphone</td>
                        <td><span class="badge badge-green">Zero Fraud</span></td>
                    </tr>
                    <tr>
                        <td><strong>Public Access</strong></td>
                        <td>Family must visit center or dial busy landlines</td>
                        <td>24/7 Public Request Portal with unique tracking code</td>
                        <td><span class="badge badge-green">24/7 Online</span></td>
                    </tr>
                    <tr>
                        <td><strong>Eligibility Protocol</strong></td>
                        <td>Manual date calculation errors lead to premature calls</td>
                        <td>Automated 90-Day Medical Cooldown countdown pulse</td>
                        <td><span class="badge badge-green">100% Safe</span></td>
                    </tr>
                    <tr>
                        <td><strong>Donor Retention</strong></td>
                        <td>No recognition; donors donate once and disappear</td>
                        <td>Bronze-to-Platinum Gamification &amp; PDF Certificates</td>
                        <td><span class="badge badge-green">3x Repeats</span></td>
                    </tr>
                    <tr>
                        <td><strong>Executive Reporting</strong></td>
                        <td>3–4 days of manual ledger tallying</td>
                        <td>Instant exports in Excel (.xlsx), PDF, and Word (.docx)</td>
                        <td><span class="badge badge-green">Instant</span></td>
                    </tr>
                </tbody>
            </table>

            <table style="width: 100%; border-collapse: separate; border-spacing: 10px 0;">
                <tr>
                    <td style="width: 25%;">
                        <div class="metric-card">
                            <div class="metric-val" style="color: #059669;">&lt; 3 Sec</div>
                            <div class="metric-lbl">Matching Response</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="metric-card">
                            <div class="metric-val" style="color: #2563eb;">Rs. 0</div>
                            <div class="metric-lbl">Messaging Tolls</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="metric-card">
                            <div class="metric-val" style="color: #d97706;">100%</div>
                            <div class="metric-lbl">Paperless Audit</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="metric-card">
                            <div class="metric-val" style="color: #7c3aed;">300%</div>
                            <div class="metric-lbl">Repeat Donor Boost</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 03</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 4: DONOR INTELLIGENCE & BULK MIGRATION
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Module Showcase 01 &amp; 02</div>
                        <div class="slide-title">Donor Intelligence, 90-Day Cooldown &amp; Bulk CSV Migration</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 04 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-emerald">
                            <div class="card-title">Automated 90-Day Medical Cooldown Engine</div>
                            <div class="card-desc">
                                In accordance with WHO and Red Crescent safety standards, the system calculates exact remaining cooldown days from the last donation. Displays a green <strong>Eligible Now</strong> pulse or amber countdown (e.g. <code>24d remaining</code>).
                            </div>
                        </div>

                        <div class="card card-blue">
                            <div class="card-title">Real-Time Duplicate Prevention Engine</div>
                            <div class="card-desc">
                                Cross-references CNIC and phone numbers dynamically during online public sign-ups and admin manual entry. Eliminates duplicate profiles and ensures consolidated medical histories.
                            </div>
                        </div>

                        <div class="card card-dark">
                            <div class="card-title">Automated Standardized Registration Code</div>
                            <div class="card-desc">
                                Generates official unique hospital codes based on City, Initials, and Phone (e.g., <code>LHR-MA-12345</code>), ideal for indexing on blood bags, lab reports, and physical PVC cards.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-purple">
                            <div class="card-title">Excel / CSV Bulk Migration Tool</div>
                            <div class="card-desc">
                                Upload thousands of historical donor records from CSV spreadsheets in seconds. Features sample CSV template download, automatic duplicate detection, and automated city-to-ID mapping.
                            </div>
                        </div>

                        <div class="card card-amber">
                            <div class="card-title">Filtered CSV Data Export</div>
                            <div class="card-desc">
                                Instantly export filtered donor rosters by blood group, city, or status directly into Excel-ready CSV format for mobile camp planning, auditing, and offline emergency calls.
                            </div>
                        </div>

                        <div class="card card-red">
                            <div class="card-title">Student &amp; University Ambassador Network</div>
                            <div class="card-desc">
                                Flags student donors and tracks university campus affiliations, empowering the NGO to organize targeted campus blood camps during critical blood shortages.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 04</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 5: EMERGENCY MATCHING & WHATSAPP
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Module Showcase 03 &amp; 04</div>
                        <div class="slide-title">Emergency Matching &amp; Free 1-Click WhatsApp Dispatch</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 05 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table style="width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-bottom: 12px;">
                <tr>
                    <td style="width: 25%;">
                        <div class="step-box">
                            <div class="step-num">1</div>
                            <div style="font-weight: bold; font-size: 10px; color: #0f172a;">Patient Request</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;">Blood group, hospital, units &amp; urgency level logged.</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="step-box">
                            <div class="step-num">2</div>
                            <div style="font-weight: bold; font-size: 10px; color: #0f172a;">Smart Matching</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;">System instantly filters eligible, un-cooldown donors.</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="step-box" style="border-color: #059669; background: #f0fdf4;">
                            <div class="step-num">3</div>
                            <div style="font-weight: bold; font-size: 10px; color: #059669;">1-Click WhatsApp</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;">Pre-formatted emergency dispatch text sent instantly.</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="step-box">
                            <div class="step-num">4</div>
                            <div style="font-weight: bold; font-size: 10px; color: #0f172a;">Call Logging</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;">Coordinator logs outcome (Agreed / Donated).</div>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-emerald">
                            <div class="card-title">100% Free WhatsApp Direct Messaging</div>
                            <div class="card-desc">
                                Uses the official <code>wa.me</code> protocol to open chats directly with verified donors. No third-party API subscription, no monthly character fees, and no risk of gateway downtime.
                            </div>
                        </div>
                        <div class="card card-blue">
                            <div class="card-title">Bilingual Emergency Message Templates</div>
                            <div class="card-desc">
                                Pre-populates patient name, hospital, units needed, urgency, and direct helpline in Urdu or English, saving precious minutes during critical surgical emergencies.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-red">
                            <div class="card-title">Multi-Parameter Proximity Matching</div>
                            <div class="card-desc">
                                Ranks donors based on blood compatibility, geographic district/city, and historical responsiveness, ensuring the closest available hero is notified first.
                            </div>
                        </div>
                        <div class="card card-purple">
                            <div class="card-title">Coordinator Performance CRM</div>
                            <div class="card-desc">
                                Records outbound call duration, agent name, and donor response, enabling management to monitor response times and staff fulfillment rates.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 05</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 6: DIGITAL CARDS & LIVE QR VERIFICATION
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Module Showcase 05 &amp; 06</div>
                        <div class="slide-title">Digital Donor Cards &amp; Live Public QR Verification Portal</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 06 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-emerald">
                            <div class="card-title">Official Dual-Sided Printable PVC Cards</div>
                            <div class="card-desc">
                                Renders official donor ID cards complete with NGO logo, donor photograph, blood group, registration number, emergency contacts, and personalized dynamic QR code.
                            </div>
                        </div>

                        <div class="card card-blue">
                            <div class="card-title">Batch Card Print Queue</div>
                            <div class="card-desc">
                                Automated printing queue flags new donors who haven't received physical cards yet. Coordinators select multiple donors and print full sheets for thermal PVC card printers in seconds.
                            </div>
                        </div>

                        <div class="card card-dark">
                            <div class="card-title">Permanent Fraud Protection</div>
                            <div class="card-desc">
                                Card design contains cryptographic verification tokens, making it impossible for unauthorized persons to forge donor cards or counterfeit safe blood claims.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-purple">
                            <div class="card-title">Live Public Verification Portal (/portal/verify-donor)</div>
                            <div class="card-desc">
                                Scanning the card's QR code with any smartphone camera opens a dedicated public verification page without requiring any app installation or staff login.
                            </div>
                        </div>

                        <div class="card card-amber">
                            <div class="card-title">Real-Time Medical Status on Scan</div>
                            <div class="card-desc">
                                Doctors and hospitals see live data:
                                <ul style="padding-left: 14px; margin-top: 5px; line-height: 1.55;">
                                    <li>&bull; <strong>Eligibility Pulse:</strong> Verified Eligible Now or Days remaining.</li>
                                    <li>&bull; <strong>Honor Tier Badge:</strong> Platinum Hero / Gold Lifesaver.</li>
                                    <li>&bull; <strong>Donation Milestones:</strong> Verified count of lifetime donations.</li>
                                    <li>&bull; <strong>Reliability Score:</strong> Track record of honored emergency calls.</li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 06</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 7: PUBLIC REQUEST PORTAL & INVENTORY
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Module Showcase 07 &amp; 08</div>
                        <div class="slide-title">Public Emergency Portal &amp; Blood Inventory Control</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 07 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-red">
                            <div class="card-title">24/7 Public Request Portal (/portal/request-blood)</div>
                            <div class="card-desc">
                                Enables patient families and hospital coordinators to submit urgent blood needs online 24/7 without creating an account. Captures patient name, required units, hospital, doctor, and contact info.
                            </div>
                        </div>

                        <div class="card card-blue">
                            <div class="card-title">Instant Request Tracking Code (REQ-00042)</div>
                            <div class="card-desc">
                                Upon submission, patients receive an official tracking reference and a direct coordinator helpline button, keeping anxious families informed and reassured in real time.
                            </div>
                        </div>

                        <div class="card card-emerald">
                            <div class="card-title">Anti-Spam &amp; Rate-Limiting Protection</div>
                            <div class="card-desc">
                                Throttles submissions per IP address and sanitizes all inputs to protect the NGO from automated web spam while keeping emergency channels open.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-amber">
                            <div class="card-title">Live 8-Blood Group Inventory Control</div>
                            <div class="card-desc">
                                Real-time tracking of available, collected, and reserved blood bags across all major groups (A+, A-, B+, B-, AB+, AB-, O+, O-).
                            </div>
                        </div>

                        <div class="card card-purple">
                            <div class="card-title">Critical Shortage &amp; Expiry Alerts</div>
                            <div class="card-desc">
                                Color-coded threshold alerts warn coordinators when rare blood groups (e.g. O-, AB-) drop below safety stock, triggering early camp announcements before stocks hit zero.
                            </div>
                        </div>

                        <div class="card card-dark">
                            <div class="card-title">Public Transparency Page (/portal/availability)</div>
                            <div class="card-desc">
                                Public inventory transparency page allows doctors and patients to check blood group availability in real time before traveling to the center.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 07</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 8: GAMIFICATION, CERTIFICATES & CAMPAIGNS
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Module Showcase 09 &amp; 10</div>
                        <div class="slide-title">Gamification Badges, Official PDF Certificates &amp; Campaigns</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 08 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-emerald">
                            <div class="card-title">5-Tier Honor Badge Gamification System</div>
                            <div class="card-desc">
                                Rewards repeated life-saving milestones, dramatically increasing donor retention:
                                <table style="width: 100%; font-size: 8.5px; margin-top: 5px; line-height: 1.6;">
                                    <tr>
                                        <td><span class="badge badge-dark">Platinum Hero</span> (10+ Donations)</td>
                                        <td><span class="badge badge-amber">Gold Lifesaver</span> (5–9 Donations)</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-blue">Silver Champion</span> (3–4 Donations)</td>
                                        <td><span class="badge badge-green">Bronze Saver</span> (1–2 Donations)</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="card card-blue">
                            <div class="card-title">Official Printable PDF Certificates</div>
                            <div class="card-desc">
                                Generates official branded appreciation certificates featuring donor's tier, lifetime donations, NGO watermark, authorized signatures, and embedded verification QR code.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-amber">
                            <div class="card-title">Financial Donations &amp; Automated Receipts</div>
                            <div class="card-desc">
                                Tracks cash, online, bank transfer, and cheque monetary donations for NGO medical operations. Generates printable official receipts with tax registration numbers and donor records.
                            </div>
                        </div>

                        <div class="card card-purple">
                            <div class="card-title">Campaign &amp; Mobile Drive Attendance Tracker</div>
                            <div class="card-desc">
                                Coordinates university, college, and corporate blood camps. Generates printable attendance registers and logs volunteer staff hours and donor turnouts.
                            </div>
                        </div>

                        <div class="card card-red">
                            <div class="card-title">Public Story &amp; Hero Showcase</div>
                            <div class="card-desc">
                                Admin tool to publish real-life patient survival stories and donor testimonials onto the public landing page to build community trust and drive new registrations.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 08</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 9: BILINGUAL URDU RTL, DARK MODE & SECURITY
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Module Showcase 11 &amp; 12</div>
                        <div class="slide-title">Bilingual Urdu RTL / English, Dark Mode &amp; Security</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 09 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-emerald">
                            <div class="card-title">1-Click Bilingual Urdu &amp; English Switcher</div>
                            <div class="card-desc">
                                Animated iOS-style pill switcher in the top navigation toggles the entire dashboard between English and native Urdu seamlessly, empowering non-English speaking field staff.
                            </div>
                        </div>

                        <div class="card card-blue">
                            <div class="card-title">Authentic Noto Nastaliq Typography &amp; True RTL</div>
                            <div class="card-desc">
                                Complete Right-to-Left (RTL) layout with beautiful Google Noto Nastaliq Urdu typography. Mirrors form inputs, data tables, and navigation icons cleanly.
                            </div>
                        </div>

                        <div class="card card-dark">
                            <div class="card-title">Day / Night Dark Mode Switch</div>
                            <div class="card-desc">
                                Persistent sun/moon dark theme switch with zero-flash startup. Protects coordinator eyesight during late-night emergency hospital shifts.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-purple">
                            <div class="card-title">Triple-Format Report Exporter (Excel, PDF, Word)</div>
                            <div class="card-desc">
                                Export comprehensive reports for donors, donations, financials, and requests in Microsoft Excel (.xlsx), Adobe PDF (.pdf), and Microsoft Word (.docx).
                            </div>
                        </div>

                        <div class="card card-red">
                            <div class="card-title">Role-Based Access Control (RBAC)</div>
                            <div class="card-desc">
                                Powered by Spatie. Granular permissions separate Super Admins, Branch Managers, Calling Coordinators, and Lab Technicians, securing sensitive donor data.
                            </div>
                        </div>

                        <div class="card card-amber">
                            <div class="card-title">Immutable Audit Trail &amp; Forensic Logging</div>
                            <div class="card-desc">
                                Every sensitive database edit, card reprint, and deletion is recorded permanently with timestamp, user ID, and IP address for complete transparency.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 09</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         SLIDE 10: IMPLEMENTATION ROADMAP & COMPLIANCE
         (Completely replacing commercial/pricing section)
         ══════════════════════════════════════════════════ --}}
    <div class="slide">
        <div class="slide-header">
            <table>
                <tr>
                    <td>
                        <div class="slide-eyebrow">Deployment &amp; Governance</div>
                        <div class="slide-title">Implementation Roadmap, Compliance &amp; Go-Live Plan</div>
                    </td>
                    <td style="text-align: right; width: 100px;">
                        <span class="slide-badge">Slide 10 / 10</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-body">
            {{-- 14-Day Roadmap Steps --}}
            <table style="width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 12px;">
                <tr>
                    <td style="width: 25%;">
                        <div class="step-box">
                            <div class="step-num">1</div>
                            <div style="font-weight: bold; font-size: 10px; color: #0f172a;">Days 1 – 3</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;"><strong>Server &amp; Branding</strong><br>Dedicated cloud setup, SSL security &amp; NGO identity.</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="step-box">
                            <div class="step-num">2</div>
                            <div style="font-weight: bold; font-size: 10px; color: #0f172a;">Days 4 – 7</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;"><strong>Data Migration</strong><br>Bulk CSV import of historical donor lists &amp; sanitization.</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="step-box">
                            <div class="step-num">3</div>
                            <div style="font-weight: bold; font-size: 10px; color: #0f172a;">Days 8 – 11</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;"><strong>Staff Training</strong><br>Hands-on staff onboarding for call coordinators &amp; doctors.</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="step-box" style="border-color: #059669; background: #f0fdf4;">
                            <div class="step-num">4</div>
                            <div style="font-weight: bold; font-size: 10px; color: #059669;">Days 12 – 14</div>
                            <div style="font-size: 8px; color: #64748b; margin-top: 3px;"><strong>Official Go-Live</strong><br>Live public launch, hospital QR verification &amp; hotline active.</div>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- 2 Columns: Compliance & Support --}}
            <table class="grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="card card-emerald">
                            <div class="card-title">Healthcare Data Governance &amp; Compliance</div>
                            <div class="card-desc">
                                &bull; <strong>Encrypted Communications:</strong> 256-bit TLS/SSL encryption for all data transmissions.<br>
                                &bull; <strong>Automated Database Backups:</strong> Daily automated database snapshots with off-site storage.<br>
                                &bull; <strong>Medical Privacy:</strong> Masked donor contacts &amp; CNIC protection behind RBAC permissions.<br>
                                &bull; <strong>Forensic Audit Trail:</strong> Immutable activity logging protects against internal manipulation.
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="card card-blue">
                            <div class="card-title">Technical Support &amp; Operational Guarantee</div>
                            <div class="card-desc">
                                &bull; <strong>24/7 System Health Monitoring:</strong> Proactive server uptime and database health tracking.<br>
                                &bull; <strong>Dedicated Emergency Support SLA:</strong> Rapid resolution for any matching or dispatch issues.<br>
                                &bull; <strong>Zero Additional Gateway Fees:</strong> Fully autonomous operation without monthly message bills.<br>
                                &bull; <strong>Continuous Upgrades:</strong> Regular feature patches and healthcare regulatory alignments.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="slide-footer">
            <table>
                <tr>
                    <td>Enterprise Blood Bank &amp; Donor Management Portal &bull; Feature Pitch Deck</td>
                    <td style="text-align: right;">Page 10 / 10 &bull; Ready for Deployment</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
