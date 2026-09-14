<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate - {{ $donor->name }}</title>
    <style>
        @page { margin: 15mm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .cert-outer {
            border: 4px double #dc3545;
            padding: 30px;
            background: #ffffff;
            position: relative;
        }
        .cert-inner {
            border: 1px dashed #b02a37;
            padding: 35px 25px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .header-logo {
            width: 65px;
            height: 65px;
        }
        .header-text h2 {
            margin: 0;
            color: #dc3545;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header-text p {
            margin: 4px 0 0;
            color: #6c757d;
            font-size: 11px;
        }
        .cert-title {
            margin: 25px 0 5px;
            color: #212529;
            font-size: 26px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .cert-subtitle {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 20px;
            font-style: italic;
        }
        .donor-name {
            font-size: 30px;
            font-weight: 700;
            color: #dc3545;
            margin: 15px 0 10px;
            text-decoration: underline;
            text-underline-offset: 6px;
        }
        .tier-badge {
            display: inline-block;
            padding: 5px 16px;
            background-color: #f8f9fa;
            border: 1.5px solid #dc3545;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 15px;
        }
        .details-table {
            width: 80%;
            margin: 20px auto;
            text-align: left;
            font-size: 13px;
            line-height: 1.8;
            background: #fafafa;
            border: 1px solid #eeeeee;
        }
        .details-table td {
            padding: 8px 14px;
        }
        .quote {
            font-style: italic;
            color: #555555;
            font-size: 12px;
            margin: 20px auto 15px;
            line-height: 1.6;
            width: 85%;
        }
        .footer-table {
            margin-top: 35px;
            width: 100%;
        }
        .footer-table td {
            vertical-align: bottom;
            text-align: center;
        }
        .qr-box {
            text-align: center;
        }
        .qr-box img {
            width: 85px;
            height: 85px;
        }
        .qr-caption {
            font-size: 9px;
            color: #777777;
            margin-top: 4px;
        }
        .sig-line {
            border-top: 1px solid #888888;
            padding-top: 5px;
            display: inline-block;
            width: 160px;
            font-size: 11px;
            color: #444444;
        }
    </style>
</head>
<body>
    <div class="cert-outer">
        <div class="cert-inner">
            <!-- Header -->
            <table class="header-table">
                <tr>
                    <td style="width: 70px; text-align: left;">
                        @if($logoPath && file_exists($logoPath))
                            <img src="{{ $logoPath }}" class="header-logo" alt="Logo">
                        @else
                            <div style="width:60px;height:60px;background:#dc3545;border-radius:50%;line-height:60px;color:#fff;font-size:24px;font-weight:bold;text-align:center;">BD</div>
                        @endif
                    </td>
                    <td class="header-text" style="text-align: center;">
                        <h2>{{ $ngoName }}</h2>
                        <p>{{ $ngoAddress }}</p>
                    </td>
                    <td style="width: 70px; text-align: right;">
                        <span style="font-size: 32px; color: #dc3545;">🩸</span>
                    </td>
                </tr>
            </table>

            <div class="cert-title">Certificate of Blood Donation</div>
            <div class="cert-subtitle">This certificate is proudly awarded to</div>

            <div class="donor-name">{{ $donor->name }}</div>

            @if(isset($tier))
                <div>
                    <span class="tier-badge">
                        ★ {{ $tier['name'] }} ({{ $tier['ur'] }}) ★
                    </span>
                </div>
            @endif

            <table class="details-table">
                <tr>
                    <td style="width: 50%;"><strong>Registration No:</strong> {{ $donor->registration_no ?? 'N/A' }}</td>
                    <td style="width: 50%;"><strong>Blood Group:</strong> <span style="color:#dc3545;font-weight:bold;">{{ $donor->blood_group }}</span></td>
                </tr>
                <tr>
                    <td><strong>Donation Date:</strong> {{ \Carbon\Carbon::parse($lastDonation->donation_date)->format('d F Y') }}</td>
                    <td><strong>Units Donated:</strong> {{ $lastDonation->units }} Unit(s)</td>
                </tr>
                <tr>
                    <td><strong>City:</strong> {{ $donor->city->name ?? 'N/A' }}</td>
                    <td><strong>Total Lifetime Donations:</strong> {{ $donor->total_donations ?? 1 }}</td>
                </tr>
            </table>

            <div class="quote">
                "Whoever saves a life, it is as though he had saved the life of all mankind."<br>
                Thank you for your selfless contribution in saving precious lives.
            </div>

            <div style="font-size: 11px; color: #888888; margin-top: 10px;">
                Issued on: {{ now()->format('d F Y') }}
            </div>

            <!-- Footer signatures & QR -->
            <table class="footer-table">
                <tr>
                    <td style="width: 35%;">
                        <div class="sig-line">
                            <strong>Authorized Signatory</strong><br>
                            <span>{{ $ngoName }}</span>
                        </div>
                    </td>
                    <td style="width: 30%;">
                        @if(isset($qrCode))
                            <div class="qr-box">
                                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="Verification QR">
                                <div class="qr-caption">Scan to Verify Authenticity</div>
                            </div>
                        @endif
                    </td>
                    <td style="width: 35%;">
                        <div class="sig-line">
                            <strong>Donor's Signature</strong><br>
                            <span>{{ $donor->name }}</span>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>
