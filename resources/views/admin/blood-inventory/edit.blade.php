@extends('adminlte::page')

@section('title', 'Edit Inventory Record')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #ffc107, #e0a800); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255,193,7,0.3);"><i class="fas fa-edit text-white" style="font-size: 24px;"></i></div>
        <div><h1 class="mb-0" style="font-weight: 600;">Edit Inventory #{{ $bloodInventory->id }}</h1><small class="text-muted">{{ $bloodInventory->blood_group }} &bull; {{ $bloodInventory->units }} units</small></div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Edit Stock" icon="fas fa-edit" style="border-top: 3px solid #ffc107;">
                <form action="{{ route('admin.blood-inventory.update', $bloodInventory->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <x-adminlte-select name="blood_group" label="Blood Group" required>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $bloodInventory->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-input name="units" label="Units" type="number" min="0" value="{{ old('units', $bloodInventory->units) }}" required/>
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-select name="status" label="Status">
                                <option value="available" {{ old('status', $bloodInventory->status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="reserved" {{ old('status', $bloodInventory->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="expired" {{ old('status', $bloodInventory->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="discarded" {{ old('status', $bloodInventory->status) == 'discarded' ? 'selected' : '' }}>Discarded</option>
                            </x-adminlte-select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input name="batch_no" label="Batch No" value="{{ old('batch_no', $bloodInventory->batch_no) }}"/>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-input name="received_date" label="Received Date" type="date" value="{{ old('received_date', $bloodInventory->received_date ? $bloodInventory->received_date->format('Y-m-d') : '') }}"/>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-input name="expiry_date" label="Expiry Date" type="date" value="{{ old('expiry_date', $bloodInventory->expiry_date ? $bloodInventory->expiry_date->format('Y-m-d') : '') }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input name="source" label="Source" value="{{ old('source', $bloodInventory->source) }}"/>
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-select name="source_campaign_id" label="Campaign">
                                <option value="">None</option>
                                @foreach($campaigns as $camp)
                                    <option value="{{ $camp->id }}" {{ old('source_campaign_id', $bloodInventory->source_campaign_id) == $camp->id ? 'selected' : '' }}>{{ $camp->name }}</option>
                                @endforeach
                            </x-adminlte-select>
                        </div>
                    </div>
                    <x-adminlte-input name="location" label="Storage Location" value="{{ old('location', $bloodInventory->location) }}"/>
                    <x-adminlte-textarea name="notes">{{ old('notes', $bloodInventory->notes) }}</x-adminlte-textarea>
                    <div class="text-right">
                        <x-adminlte-button type="submit" label="Update" theme="warning" icon="fas fa-save"/>
                        <a href="{{ route('admin.blood-inventory.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </x-adminlte-card>
        </div>
    </div>
@stop
