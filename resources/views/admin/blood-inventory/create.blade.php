@extends('adminlte::page')

@section('title', 'Add Blood Stock')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);"><i class="fas fa-plus-circle text-white" style="font-size: 24px;"></i></div>
        <div><h1 class="mb-0" style="font-weight: 600;">Add Blood Stock</h1><small class="text-muted">Record new inventory entry</small></div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Stock Details" icon="fas fa-warehouse" style="border-top: 3px solid #28a745;">
                <form action="{{ route('admin.blood-inventory.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <x-adminlte-select name="blood_group" label="Blood Group" required>
                                <option value="">Select</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-input name="units" label="Units" type="number" min="1" value="{{ old('units', 1) }}" required/>
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-select name="status" label="Status">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="discarded" {{ old('status') == 'discarded' ? 'selected' : '' }}>Discarded</option>
                            </x-adminlte-select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input name="batch_no" label="Batch No" placeholder="e.g. BATCH-001" value="{{ old('batch_no') }}"/>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-input name="received_date" label="Received Date" type="date" value="{{ old('received_date', date('Y-m-d')) }}"/>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-input name="expiry_date" label="Expiry Date" type="date" value="{{ old('expiry_date') }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input name="source" label="Source" placeholder="e.g. Blood Camp, Hospital, Purchase" value="{{ old('source') }}"/>
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-select name="source_campaign_id" label="Campaign (if from event)">
                                <option value="">None</option>
                                @foreach($campaigns as $camp)
                                    <option value="{{ $camp->id }}" {{ old('source_campaign_id') == $camp->id ? 'selected' : '' }}>{{ $camp->name }}</option>
                                @endforeach
                            </x-adminlte-select>
                        </div>
                    </div>
                    <x-adminlte-input name="location" label="Storage Location" placeholder="e.g. Freezer A, Shelf 2" value="{{ old('location') }}"/>
                    <x-adminlte-textarea name="notes" label="Notes">{{ old('notes') }}</x-adminlte-textarea>
                    <div class="text-right">
                        <x-adminlte-button type="submit" label="Save Stock" theme="success" icon="fas fa-save"/>
                        <a href="{{ route('admin.blood-inventory.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </x-adminlte-card>
        </div>
    </div>
@stop
