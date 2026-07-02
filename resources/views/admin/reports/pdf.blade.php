<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 15px; }
        .header .ngo-name { font-size: 16px; font-weight: bold; color: #dc3545; }
        .header .ngo-address { font-size: 9px; color: #888; }
        .header .report-title { font-size: 14px; margin-top: 6px; color: #333; }
        .header .generated { font-size: 8px; color: #aaa; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9px; }
        th { background: #dc3545; color: #fff; padding: 6px; text-align: left; font-size: 9px; }
        td { padding: 5px 6px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #fafafa; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 8px; color: #aaa; padding: 8px; border-top: 1px solid #eee; }
        .footer strong { color: #777; }
        @page { margin: 12mm 10mm 18mm; }
    </style>
</head>
<body>
    <div class="header">
        <div class="ngo-name">{{ $ngo['name'] }}</div>
        @if($ngo['address'])<div class="ngo-address">{{ $ngo['address'] }}</div>@endif
        <div class="report-title">{{ $title }}</div>
        <div class="generated">Generated: {{ now()->format('d M Y h:i A') }}</div>
    </div>

    @if($rows->count())
        <table>
            <thead>
                <tr>
                    @foreach(array_keys((array)$rows->first()->toArray()) as $col)
                        <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach((array)$row->toArray() as $val)
                            <td>{{ is_string($val) || is_numeric($val) ? $val : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align:center;color:#999;margin-top:40px;">No data found for the selected criteria.</p>
    @endif

    <div class="footer">
        <strong>{{ $ngo['name'] }}</strong> &bull; {{ $ngo['address'] ?? '' }}<br>
        Generated {{ now()->format('d M Y') }} &bull; Page {PAGE_NUM} of {PAGE_COUNT}
    </div>
</body>
</html>
