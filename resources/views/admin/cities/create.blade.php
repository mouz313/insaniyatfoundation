@extends('adminlte::page')

@section('title', 'Add City')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Add City</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #28a745;">
        <div class="card-body">
            <form action="{{ route('admin.cities.store') }}" method="POST">
                @csrf
                <x-adminlte-input name="name" label="City Name" placeholder="Enter city name" value="{{ old('name') }}"/>
                <x-adminlte-button type="submit" label="Save" theme="success" icon="fas fa-save"/>
                <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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
