@extends('adminlte::page')

@section('title', 'Inventory Record #' . $bloodInventory->id)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);"><i class="fas fa-warehouse text-white" style="font-size: 24px;"></i></div>
            <div><h1 class="mb-0" style="font-weight: 600;">Inventory #{{ $bloodInventory->id }}</h1><small class="text-muted">{{ $bloodInventory->blood_group }} &bull; {{ $bloodInventory->units }} units</small></div>
        </div>
        <div>
            <a href="{{ route('admin.blood-inventory.edit', $bloodInventory->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('admin.blood-inventory.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Stock Details" icon="fas fa-info-circle" class="shadow-sm">
                <table class="table table-sm">
                    <tr><th style="width:130px;">Blood Group</th><td><span class="badge badge-danger" style="border-radius: 20px; padding: 4px 14px;"><i class="fas fa-tint"></i> {{ $bloodInventory->blood_group }}</span></td></tr>
                    <tr><th>Units</th><td><strong>{{ $bloodInventory->units }}</strong></td></tr>
                    <tr><th>Batch No</th><td><code>{{ $bloodInventory->batch_no ?? 'N/A' }}</code></td></tr>
                    <tr><th>Status</th><td>@switch($bloodInventory->status) @case('available') <span class="badge badge-success">Available</span> @break @case('reserved') <span class="badge badge-warning">Reserved</span> @break @case('expired') <span class="badge badge-danger">Expired</span> @break @case('discarded') <span class="badge badge-secondary">Discarded</span> @break @endswitch</td></tr>
                    <tr><th>Location</th><td>{{ $bloodInventory->location ?? 'N/A' }}</td></tr>
                    <tr><th>Received</th><td>{{ $bloodInventory->received_date ? $bloodInventory->received_date->format('d M Y') : 'N/A' }}</td></tr>
                    <tr><th>Expiry</th><td>{{ $bloodInventory->expiry_date ? $bloodInventory->expiry_date->format('d M Y') : 'N/A' }}</td></tr>
                    <tr><th>Source</th><td>{{ $bloodInventory->source ?? ($bloodInventory->campaign->name ?? 'N/A') }}</td></tr>
                    <tr><th>Notes</th><td>{{ $bloodInventory->notes ?? 'N/A' }}</td></tr>
                    <tr><th>Created By</th><td>{{ $bloodInventory->creator->name ?? 'System' }}</td></tr>
                    <tr><th>Created</th><td>{{ $bloodInventory->created_at->format('d M Y h:i A') }}</td></tr>
                </table>
            </x-adminlte-card>
        </div>
    </div>
@stop
