@extends('adminlte::page')

@section('title', 'Edit Campaign')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Campaign</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #ffc107;">
        <div class="card-body">
            <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Campaign Name" placeholder="Enter campaign name" value="{{ old('name', $campaign->name) }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="date" label="Campaign Date" type="date" value="{{ old('date', $campaign->date->format('Y-m-d')) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="venue" label="Venue" placeholder="Enter venue/location" value="{{ old('venue', $campaign->venue) }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="target_units" label="Target Units" type="number" placeholder="e.g. 50" value="{{ old('target_units', $campaign->target_units) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <x-adminlte-textarea name="description" label="Description" rows="4">{{ old('description', $campaign->description) }}</x-adminlte-textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <x-adminlte-button type="submit" label="Update" theme="warning" icon="fas fa-save"/>
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </div>
            </form>
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
