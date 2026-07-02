@extends('adminlte::page')

@section('title', 'Add Area')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Add Area</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #17a2b8;">
        <div class="card-body">
            <form action="{{ route('admin.areas.store') }}" method="POST">
                @csrf
                <x-adminlte-select name="city_id" label="City">
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </x-adminlte-select>
                <x-adminlte-input name="name" label="Area Name" placeholder="Enter area name" value="{{ old('name') }}"/>
                <x-adminlte-button type="submit" label="Save" theme="success" icon="fas fa-save"/>
                <a href="{{ route('admin.areas.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
</style>
@stop
