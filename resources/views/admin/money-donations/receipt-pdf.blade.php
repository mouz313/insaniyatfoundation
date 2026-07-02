<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $donation->receipt_number }}</title>
    <style>
        @page { margin: 8mm; }
        body {
            font-family: 'DejaVu Sans', 'DejaVu Sans Mono', sans-serif;
            font-size: 9px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .receipt-wrapper {
            border: 1.5px solid #28a745;
            border-radius: 8px;
            padding: 14px 14px 10px;
        }
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            border-bottom: 1.5px dashed #ddd;
            margin-bottom: 10px;
        }
        .logo-area img {
            max-width: 42px;
            max-height: 42px;
        }
        .logo-placeholder {
            width: 42px; height: 42px;
            background: #dc3545;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
        }
        .title-area {
            text-align: right;
        }
        .title-area .ngo-name {
            font-size: 13px;
            font-weight: bold;
            color: #dc3545;
        }
        .title-area .subtitle {
            font-size: 8px;
            color: #999;
            margin-top: 1px;
        }
        .receipt-badge {
            text-align: center;
            margin-bottom: 10px;
        }
        .receipt-badge .badge {
            display: inline-block;
            background: #28a745;
            color: #fff;
            padding: 3px 18px;
            border-radius: 16px;
            font-size: 8px;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        table.info {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        table.info td {
            padding: 3px 4px;
            vertical-align: top;
        }
        table.info .lbl {
            font-weight: bold;
            color: #555;
            width: 90px;
            font-size: 8px;
        }
        table.info .val {
            color: #222;
            font-size: 9px;
        }
        .divider-row td {
            border-top: 1px dotted #ddd;
            padding-top: 3px !important;
        }
        .amount-box {
            text-align: center;
            background: #f5fcf5;
            border: 1.5px solid #28a745;
            border-radius: 6px;
            padding: 8px;
            margin: 8px 0;
        }
        .amount-box .lbl {
            font-size: 7px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amount-box .amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
        .amount-box .words {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }
        .signature-row {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #eee;
        }
        .signature-row table {
            width: 100%;
        }
        .signature-row td {
            width: 50%;
            vertical-align: top;
        }
        .sig-right {
            text-align: right;
        }
        .sig-line {
            border-top: 1px solid #333;
            margin-top: 22px;
            padding-top: 3px;
        }
        .sig-line .label {
            font-size: 7px;
            color: #555;
        }
        .sig-line .name {
            font-size: 10px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 7px;
            color: #aaa;
            line-height: 1.3;
        }
        .footer strong {
            color: #777;
        }
    </style>
</head>
<body>
    <div class="receipt-wrapper">
        <div class="top-row">
            <div class="logo-area">
                @if($ngoLogo)
                    <img src="{{ public_path('storage/' . $ngoLogo) }}" alt="Logo">
                @else
                    <div class="logo-placeholder">BD</div>
                @endif
            </div>
            <div class="title-area">
                <div class="ngo-name">{{ $ngoName }}</div>
                <div class="subtitle">Official Donation Receipt</div>
            </div>
        </div>

        <div class="receipt-badge">
            <span class="badge">RECEIPT &bull; {{ $donation->receipt_number }}</span>
        </div>

        <table class="info">
            <tr><td class="lbl">Receipt No</td><td class="val"><strong>{{ $donation->receipt_number }}</strong></td></tr>
            <tr><td class="lbl">Date</td><td class="val">{{ $donation->donation_date->format('d F Y') }}</td></tr>
            <tr class="divider-row"><td colspan="2"></td></tr>
            <tr><td class="lbl">Donor Name</td><td class="val">{{ $donation->donor->name ?? $donation->anonymous_name ?? 'Anonymous' }}</td></tr>
            @if($donation->donor)
            <tr><td class="lbl">Phone</td><td class="val">{{ $donation->donor->phone }}</td></tr>
            @endif
            <tr class="divider-row"><td colspan="2"></td></tr>
            <tr><td class="lbl">Payment</td><td class="val">{{ ucfirst($donation->payment_method) }}</td></tr>
            @if($donation->campaign)
            <tr><td class="lbl">Campaign</td><td class="val">{{ $donation->campaign->name }}</td></tr>
            @endif
        </table>

        <div class="amount-box">
            <div class="lbl">Amount Donated</div>
            <div class="amount">PKR {{ number_format($donation->amount, 2) }}</div>
        </div>

        <div class="signature-row">
            <table>
                <tr>
                    <td>
                        <div class="sig-line">
                            <div class="name">Admin</div>
                            <div class="label">Authorized Signature</div>
                        </div>
                    </td>
                    <td class="sig-right">
                        <div class="sig-line">
                            <div class="label">Donor Signature</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <strong>{{ $ngoName }}</strong>
        @if($ngoAddress) &bull; {{ $ngoAddress }} @endif<br>
        Receipt #{{ $donation->receipt_number }} &bull; Generated {{ now()->format('d M Y') }}
    </div>
</body>
</html>
