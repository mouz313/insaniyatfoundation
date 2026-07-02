@extends('adminlte::page')

@section('title', 'Add Money Donation')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #e83e8c, #f06a9e); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(232,62,140,0.3);">
            <i class="fas fa-money-bill-wave text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Add Money Donation</h1>
            <small class="text-muted"><i class="fas fa-fw fa-plus-circle"></i> Record a new donation</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Donation Form" icon="fas fa-money-bill-wave" style="border-top: 3px solid #e83e8c;">
                <form action="{{ route('admin.money-donations.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-select name="donor_id" label="Donor">
                                <option value="">No Donor (Anonymous)</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" {{ old('donor_id') == $donor->id ? 'selected' : '' }}>{{ $donor->name }} ({{ $donor->phone }})</option>
                                @endforeach
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-input name="anonymous_name" label="Anonymous Name" placeholder="Name if no donor selected" value="{{ old('anonymous_name') }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-adminlte-input name="amount" label="Amount" type="number" step="0.01" placeholder="0.00" value="{{ old('amount') }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text text-success font-weight-bold">PKR</div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-input name="donation_date" label="Donation Date" type="date" value="{{ old('donation_date', date('Y-m-d')) }}"/>
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-select name="payment_method" label="Payment Method">
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="JazzCash" {{ old('payment_method') == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                                <option value="Easypaisa" {{ old('payment_method') == 'Easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                            </x-adminlte-select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-select name="campaign_id" label="Campaign">
                                <option value="">No Campaign</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                                @endforeach
                            </x-adminlte-select>
                        </div>
                    </div>
                    <div class="text-right">
                        <x-adminlte-button type="submit" label="Save" theme="success" icon="fas fa-save"/>
                        <a href="{{ route('admin.money-donations.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </x-adminlte-card>
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
