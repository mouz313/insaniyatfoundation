<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }
        .certificate-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .certificate {
            background: #fff;
            border: 5px solid #dc3545;
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .certificate:before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #dc3545;
            border-radius: 14px;
            pointer-events: none;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .header-text h2 {
            margin: 0;
            color: #dc3545;
            font-size: 22px;
            font-weight: 700;
        }
        .header-text p {
            margin: 2px 0 0;
            color: #6c757d;
            font-size: 12px;
        }
        .badge-icon {
            font-size: 70px;
            color: #dc3545;
            margin: 15px 0;
            line-height: 1;
        }
        h1 {
            color: #343a40;
            font-size: 28px;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 25px;
            border-bottom: 2px dashed #dee2e6;
            padding-bottom: 20px;
        }
        .donor-name {
            font-size: 32px;
            font-weight: 700;
            color: #dc3545;
            margin: 10px 0;
        }
        .details {
            margin: 20px 0;
            font-size: 14px;
            color: #495057;
            line-height: 2;
        }
        .details strong {
            color: #343a40;
        }
        .date-line {
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        .footer .sig {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .footer .sig strong {
            color: #343a40;
            display: block;
            margin-top: 5px;
        }
        .blood-drop {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #dc3545;
            border-radius: 50%;
            margin: 0 4px;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate">
            <div class="header">
                @if($logoPath && file_exists($logoPath))
                    <img src="{{ $logoPath }}" class="header-logo" alt="Logo">
                @else
                    <div style="width:60px;height:60px;background:#dc3545;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;font-weight:bold;">BD</div>
                @endif
                <div class="header-text">
                    <h2>{{ $ngoName }}</h2>
                    <p>{{ $ngoAddress }}</p>
                </div>
            </div>

            <div class="badge-icon">🩸</div>

            <h1>Certificate of Blood Donation</h1>
            <div class="subtitle">This is to certify that</div>

            <div class="donor-name">{{ $donor->name }}</div>

            <div class="details">
                <strong>Registration No:</strong> {{ $donor->registration_no ?? 'N/A' }}<br>
                <strong>Blood Group:</strong> {{ $donor->blood_group }}<br>
                <strong>Donated on:</strong> {{ \Carbon\Carbon::parse($lastDonation->donation_date)->format('F d, Y') }}<br>
                <strong>Units Donated:</strong> {{ $lastDonation->units }} unit(s)<br>
                <strong>Location:</strong> {{ $donor->city->name ?? 'N/A' }}
            </div>

            <p style="font-style:italic;color:#6c757d;font-size:13px;margin-top:15px;">
                "Your generous donation has saved lives and brought hope to those in need.<br>
                Thank you for being a hero."
            </p>

            <div class="date-line">
                Issued on: {{ now()->format('F d, Y') }}
            </div>

            <div class="footer">
                <div class="sig">
                    ___________________________<br>
                    <strong>Authorized Signatory</strong><br>
                    <span style="font-size:11px;">{{ $ngoName }}</span>
                </div>
                <div class="sig">
                    ___________________________<br>
                    <strong>Donor's Signature</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
