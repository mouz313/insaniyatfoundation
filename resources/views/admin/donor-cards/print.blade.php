<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Cards</title>
    <style>
        @page { size: A4; margin: 8mm; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .card-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .donor-card { border: 2px solid #333; border-radius: 10px; padding: 10px; page-break-inside: avoid; min-height: 180px; }
        .card-header { display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #ddd; padding-bottom: 6px; margin-bottom: 6px; }
        .card-photo { width: 65px; height: 65px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 26px; color: #6c757d; border: 2px solid #dee2e6; overflow: hidden; flex-shrink: 0; }
        .card-photo img { width: 100%; height: 100%; object-fit: cover; }
        .card-title { font-size: 15px; font-weight: bold; }
        .card-subtitle { font-size: 11px; color: #666; }
        .card-body { font-size: 12px; }
        .card-body table { width: 100%; }
        .card-body td { padding: 1px 0; }
        .blood-badge { display: inline-block; background: #dc3545; color: #fff; padding: 3px 10px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd; }
        .logo-area { display: flex; align-items: center; gap: 6px; }
        .logo-area img { max-width: 40px; max-height: 40px; }
        .logo-area .ngo-name { font-size: 10px; font-weight: bold; color: #333; line-height: 1.2; }
        .qr-area img { width: 55px; height: 55px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:10px;">
        <button onclick="window.print()">Print</button>
        <button onclick="window.close()">Close</button>
    </div>
    <div class="card-grid">
        @foreach($donors as $donor)
            <div class="donor-card">
                <div class="card-header">
                    <div class="card-photo">
                        @if($donor->photo)
                            <img src="{{ asset('storage/' . $donor->photo) }}" alt="photo">
                        @else
                            <span style="font-size: 26px; color: #6c757d;">&#x1F464;</span>
                        @endif
                    </div>
                    <div>
                        <div class="card-title">{{ $donor->name }}</div>
                        <div class="card-subtitle">Reg #: {{ $donor->registration_no ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="card-body">
                    <table>
                        <tr><td><strong>Blood Group</strong></td><td><span class="blood-badge">{{ $donor->blood_group }}</span></td></tr>
                        <tr><td><strong>Phone</strong></td><td>{{ $donor->phone }}</td></tr>
                        <tr><td><strong>City</strong></td><td>{{ $donor->city->name ?? 'N/A' }}</td></tr>
                        <tr><td><strong>Last Donation</strong></td><td>{{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y') : 'Never' }}</td></tr>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="logo-area">
                        @if($ngoLogo)
                            <img src="{{ asset('storage/' . $ngoLogo) }}" alt="Logo">
                        @else
                            <div class="ngo-name">{{ $ngoName }}</div>
                        @endif
                    </div>
                    <div class="qr-area">
                        <img src="data:image/svg+xml;base64,{{ $qrCodes[$donor->id] }}" alt="QR">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
