@extends('adminlte::page')

@section('title', 'Money Donation #' . $moneyDonation->id)

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);">
            <i class="fas fa-money-bill-wave text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Money Donation #{{ $moneyDonation->id }}</h1>
            <small class="text-muted"><i class="fas fa-fw fa-calendar"></i> {{ $moneyDonation->donation_date->format('d M Y') }}</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-3 pb-0">
                    <span style="font-weight: 600; font-size: 16px;">Donation Details</span>
                    <a href="{{ route('admin.money-donations.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 140px;">ID</td>
                            <td><strong>#{{ $moneyDonation->id }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Donor</td>
                            <td>
                                @if($moneyDonation->donor)
                                    <a href="{{ route('admin.donors.show', $moneyDonation->donor_id) }}" style="font-weight: 500;">{{ $moneyDonation->donor->name }}</a>
                                @else
                                    <span class="badge badge-secondary">{{ $moneyDonation->anonymous_name ?? 'Anonymous' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Amount</td>
                            <td><strong style="font-size: 18px; color: #28a745;">PKR {{ number_format($moneyDonation->amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Donation Date</td>
                            <td>{{ $moneyDonation->donation_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payment Method</td>
                            <td>
                                @php $methodColors = ['cash' => 'success', 'bank' => 'info', 'JazzCash' => 'warning', 'Easypaisa' => 'primary']; @endphp
                                <span class="badge badge-{{ $methodColors[$moneyDonation->payment_method] ?? 'secondary' }}" style="border-radius: 20px; padding: 4px 12px;">
                                    @if($moneyDonation->payment_method == 'cash')
                                        <i class="fas fa-money-bill-wave"></i>
                                    @elseif($moneyDonation->payment_method == 'bank')
                                        <i class="fas fa-university"></i>
                                    @elseif($moneyDonation->payment_method == 'JazzCash')
                                        <i class="fas fa-mobile-alt"></i>
                                    @else
                                        <i class="fas fa-mobile-alt"></i>
                                    @endif
                                    {{ ucfirst($moneyDonation->payment_method) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Campaign</td>
                            <td>{{ $moneyDonation->campaign->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Receipt #</td>
                            <td><code>{{ $moneyDonation->receipt_number ?? 'N/A' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created</td>
                            <td>{{ $moneyDonation->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-lg" style="border-left: 3px solid #28a745;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-money-bill-wave text-success" style="font-size: 48px; opacity: 0.4;"></i>
                    <p class="text-muted mt-3 mb-0">Receipt Number</p>
                    <h3 class="mb-0" style="font-weight: 700;">
                        <code>{{ $moneyDonation->receipt_number ?? 'N/A' }}</code>
                    </h3>
                    @if($moneyDonation->receipt_number)
                        <a href="{{ route('admin.money-donations.receipt', $moneyDonation->id) }}" class="btn btn-success btn-sm mt-3" target="_blank">
                            <i class="fas fa-download"></i> Download Receipt PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table td, .table th { vertical-align: middle; }
</style>
@stop
