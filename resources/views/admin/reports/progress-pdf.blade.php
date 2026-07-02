<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Progress Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 18px; }
        .header .ngo-name { font-size: 16px; font-weight: bold; color: #dc3545; }
        .header .ngo-address { font-size: 9px; color: #888; }
        .header .report-title { font-size: 14px; margin-top: 6px; color: #333; }
        .header .generated { font-size: 8px; color: #aaa; margin-top: 2px; }
        .section { margin-bottom: 16px; }
        .section-title { background: #28a745; color: #fff; padding: 6px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; margin-bottom: 8px; }
        .stat-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; }
        .stat-card { flex: 1; min-width: 100px; border: 1px solid #e0e0e0; border-radius: 6px; padding: 8px 10px; text-align: center; }
        .stat-card .num { font-size: 20px; font-weight: bold; color: #28a745; }
        .stat-card .lbl { font-size: 8px; color: #888; text-transform: uppercase; letter-spacing: 0.3px; }
        .stat-card.red .num { color: #dc3545; }
        .stat-card.blue .num { color: #17a2b8; }
        .stat-card.orange .num { color: #fd7e14; }
        table.breakdown { width: 100%; border-collapse: collapse; font-size: 10px; }
        table.breakdown th { background: #f5f5f5; color: #555; padding: 5px 8px; text-align: left; font-size: 9px; }
        table.breakdown td { padding: 4px 8px; border-bottom: 1px solid #eee; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 8px; color: #aaa; padding: 8px; border-top: 1px solid #eee; }
        .footer strong { color: #777; }
        @page { margin: 12mm 10mm 20mm; }
    </style>
</head>
<body>
    <div class="header">
        <div class="ngo-name">{{ $ngo['name'] }}</div>
        @if($ngo['address'])<div class="ngo-address">{{ $ngo['address'] }}</div>@endif
        <div class="report-title">Progress Report</div>
        <div class="generated">Generated: {{ now()->format('d M Y h:i A') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Donor Statistics</div>
        <div class="stat-grid">
            <div class="stat-card"><div class="num">{{ $data['totalDonors'] }}</div><div class="lbl">Total Donors</div></div>
            <div class="stat-card"><div class="num">{{ $data['activeDonors'] }}</div><div class="lbl">Active</div></div>
            <div class="stat-card red"><div class="num">{{ $data['inactiveDonors'] }}</div><div class="lbl">Inactive</div></div>
            <div class="stat-card orange"><div class="num">{{ $data['ineligibleDonors'] }}</div><div class="lbl">Ineligible</div></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Blood Donations</div>
        <div class="stat-grid">
            <div class="stat-card blue"><div class="num">{{ $data['totalDonations'] }}</div><div class="lbl">Total Donations</div></div>
            <div class="stat-card"><div class="num">{{ $data['donatedUnits'] }}</div><div class="lbl">Units Donated</div></div>
        </div>
        @if($data['donationsByGroup']->count())
            <table class="breakdown">
                <tr><th>Blood Group</th><th>Units</th></tr>
                @foreach($data['donationsByGroup'] as $bg => $units)
                    <tr><td>{{ $bg }}</td><td><strong>{{ $units }}</strong></td></tr>
                @endforeach
            </table>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Money Collected</div>
        <div class="stat-grid">
            <div class="stat-card"><div class="num">PKR {{ number_format($data['totalMoney'], 0) }}</div><div class="lbl">Total</div></div>
            <div class="stat-card blue"><div class="num">PKR {{ number_format($data['moneyThisMonth'], 0) }}</div><div class="lbl">This Month</div></div>
        </div>
        @if($data['moneyByMethod']->count())
            <table class="breakdown">
                <tr><th>Method</th><th>Total</th></tr>
                @foreach($data['moneyByMethod'] as $method => $total)
                    <tr><td>{{ ucfirst($method) }}</td><td><strong>PKR {{ number_format($total, 2) }}</strong></td></tr>
                @endforeach
            </table>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Campaigns</div>
        <div class="stat-grid">
            <div class="stat-card blue"><div class="num">{{ $data['totalCampaigns'] }}</div><div class="lbl">Total Campaigns</div></div>
            <div class="stat-card orange"><div class="num">{{ $data['upcomingCampaigns'] }}</div><div class="lbl">Upcoming</div></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Blood Requests</div>
        <div class="stat-grid">
            <div class="stat-card orange"><div class="num">{{ $data['pendingRequests'] }}</div><div class="lbl">Pending</div></div>
            <div class="stat-card"><div class="num">{{ $data['resolvedRequests'] }}</div><div class="lbl">Resolved</div></div>
            <div class="stat-card red"><div class="num">{{ $data['closedRequests'] }}</div><div class="lbl">Closed</div></div>
        </div>
    </div>

    <div class="footer">
        <strong>{{ $ngo['name'] }}</strong> &bull; {{ $ngo['address'] ?? '' }}<br>
        Generated {{ now()->format('d M Y') }} &bull; Page {PAGE_NUM} of {PAGE_COUNT}
    </div>
</body>
</html>
