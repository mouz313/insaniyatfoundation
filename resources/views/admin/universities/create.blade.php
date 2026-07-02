@extends('adminlte::page')

@section('title', 'Add University')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Add University</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #6610f2;">
        <div class="card-body">
            <form action="{{ route('admin.universities.store') }}" method="POST">
                @csrf
                <x-adminlte-input name="name" label="University Name" placeholder="Enter university name" value="{{ old('name') }}"/>
                <x-adminlte-button type="submit" label="Save" theme="primary" icon="fas fa-save"/>
                <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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
