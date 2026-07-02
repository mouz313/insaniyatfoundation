@extends('adminlte::page')

@section('title', 'Money Donations')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);">
                <i class="fas fa-money-bill-wave text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Money Donations</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $moneyDonations->total() }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            @can('money-donation-create')
                <a href="{{ route('admin.money-donations.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-3 col-6 mb-2 mb-md-0">
            <div class="card border-0 shadow-sm rounded-lg h-100" style="border-left: 4px solid #28a745;">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Total Donations</small>
                    <h3 class="mb-0 font-weight-bold" style="color: #28a745;">PKR {{ number_format($totalAmount, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2 mb-md-0">
            <div class="card border-0 shadow-sm rounded-lg h-100" style="border-left: 4px solid #17a2b8;">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">This Month</small>
                    <h3 class="mb-0 font-weight-bold" style="color: #17a2b8;">PKR {{ number_format($thisMonth, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2 mb-md-0">
            <div class="card border-0 shadow-sm rounded-lg h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">This Year</small>
                    <h3 class="mb-0 font-weight-bold" style="color: #e6a800;">PKR {{ number_format($thisYear, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2 mb-md-0">
            <div class="card border-0 shadow-sm rounded-lg h-100" style="border-left: 4px solid #6f42c1;">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Avg Amount</small>
                    <h3 class="mb-0 font-weight-bold" style="color: #6f42c1;">PKR {{ number_format($avgAmount ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET" action="{{ route('admin.money-donations.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Donor, anonymous name, receipt..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Payment Method</small>
                        <select name="payment_method" class="form-control form-control-sm">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="JazzCash" {{ request('payment_method') == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                            <option value="Easypaisa" {{ request('payment_method') == 'Easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Campaign</small>
                        <select name="campaign_id" class="form-control form-control-sm">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.money-donations.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="width:100%;">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Donor</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Campaign</th>
                            <th>Receipt #</th>
                            <th width="100" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($moneyDonations as $donation)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $donation->id }}</span></td>
                                <td class="align-middle">
                                    @if($donation->donor)
                                        <a href="{{ route('admin.donors.show', $donation->donor_id) }}" style="font-weight: 500;">{{ $donation->donor->name }}</a>
                                    @elseif($donation->anonymous_name)
                                        {{ $donation->anonymous_name }}
                                    @else
                                        <span class="badge badge-secondary">Anonymous</span>
                                    @endif
                                </td>
                                <td class="align-middle"><strong style="color: #28a745;">PKR {{ number_format($donation->amount, 2) }}</strong></td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $donation->donation_date->format('d M Y') }}</td>
                                <td class="align-middle">
                                    @php
                                        $methodColors = ['cash' => 'success', 'bank' => 'info', 'JazzCash' => 'warning', 'Easypaisa' => 'primary'];
                                        $methodIcons = ['cash' => 'fa-money-bill-wave', 'bank' => 'fa-university', 'JazzCash' => 'fa-mobile-alt', 'Easypaisa' => 'fa-mobile-alt'];
                                    @endphp
                                    <span class="badge badge-{{ $methodColors[$donation->payment_method] ?? 'secondary' }}" style="border-radius: 20px; padding: 4px 12px;">
                                        <i class="fas {{ $methodIcons[$donation->payment_method] ?? 'fa-credit-card' }}"></i>
                                        {{ ucfirst($donation->payment_method) }}
                                    </span>
                                </td>
                                <td class="align-middle">{{ $donation->campaign->name ?? 'N/A' }}</td>
                                <td class="align-middle"><code>{{ $donation->receipt_number ?? 'N/A' }}</code></td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('money-donation-show')
                                            <a href="{{ route('admin.money-donations.show', $donation->id) }}" class="btn btn-outline-info" title="View" style="border-radius: 6px 0 0 6px;"><i class="fas fa-eye"></i></a>
                                        @endcan
                                        @if($donation->receipt_number)
                                            <a href="{{ route('admin.money-donations.receipt', $donation->id) }}" class="btn btn-outline-success" title="Download Receipt PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                        @endif
                                        @can('money-donation-edit')
                                            <a href="{{ route('admin.money-donations.edit', $donation->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('money-donation-delete')
                                            <form action="{{ route('admin.money-donations.destroy', $donation->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this donation?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-money-bill-wave text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2 mb-0">No money donations found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $moneyDonations->firstItem() ?? 0 }} - {{ $moneyDonations->lastItem() ?? 0 }} of {{ $moneyDonations->total() }}</small>
            {{ $moneyDonations->appends(request()->query())->links() }}
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table td, .table th { vertical-align: middle; }
.btn-outline-warning, .btn-outline-danger { border-width: 1.5px; }
.btn-group-sm .btn { padding: 3px 8px; }
</style>
@stop
