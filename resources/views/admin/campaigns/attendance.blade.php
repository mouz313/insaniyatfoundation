<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Attendance Sheet - {{ $campaign->name }}</title>
    <style>
        @page { margin: 15px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #dc3545; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #dc3545; }
        .header p { margin: 2px 0; color: #666; font-size: 12px; }
        .header-logo { width: 50px; height: 50px; object-fit: contain; margin-bottom: 5px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #dee2e6; padding: 6px 4px; text-align: left; font-size: 10px; }
        th { background: #dc3545; color: #fff; font-weight: 600; text-align: center; }
        td { text-align: center; }
        td.left { text-align: left; }
        .sig-line { height: 30px; }
        .footer { margin-top: 20px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #dee2e6; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        @if($logoPath && file_exists($logoPath))
            <img src="{{ $logoPath }}" class="header-logo" alt="Logo">
        @endif
        <h2>{{ $ngoName }}</h2>
        <p>{{ $ngoAddress }}</p>
        <h3 style="margin-top:5px;">Donor Attendance & Consent Sheet</h3>
        <p><strong>{{ $campaign->name }}</strong> &bull; {{ $campaign->date->format('d M Y') }} &bull; {{ $campaign->venue }}</p>
    </div>

    <div class="info-row">
        <span><strong>Target Units:</strong> {{ $campaign->target_units ?? 'N/A' }}</span>
        <span><strong>Donors Present:</strong> ________</span>
        <span><strong>Total Collected:</strong> ________ units</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th width="130">Donor Name</th>
                <th width="90">CNIC</th>
                <th width="40">Age</th>
                <th width="50">Blood Group</th>
                <th width="60">Phone</th>
                <th width="45">Weight</th>
                <th width="55">BP</th>
                <th width="50">Hemoglobin</th>
                <th width="40">Donated</th>
                <th width="60">Signature</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donors as $i => $donor)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="left">{{ $donor->name }}</td>
                    <td>{{ $donor->cnic ?? '—' }}</td>
                    <td>{{ $donor->age ?? '—' }}</td>
                    <td><strong>{{ $donor->blood_group }}</strong></td>
                    <td>{{ $donor->phone }}</td>
                    <td>{{ $donor->weight ? $donor->weight . 'kg' : '____' }}</td>
                    <td>____/____</td>
                    <td>{{ $donor->hemoglobin ? $donor->hemoglobin : '____' }}</td>
                    <td>□ Yes<br>□ No</td>
                    <td class="sig-line"></td>
                </tr>
            @empty
                <tr><td colspan="11" style="text-align:center;padding:20px;color:#999;">No donors registered for this campaign yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:15px;">
        <p><strong>Coordinator Signature:</strong> _________________________ &nbsp;&nbsp; <strong>Date:</strong> ______________</p>
    </div>

    <div class="footer">
        {{ $ngoName }} &bull; {{ $ngoAddress }} &bull; Generated on {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
