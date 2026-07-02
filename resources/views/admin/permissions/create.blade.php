@extends('adminlte::page')

@section('title', 'Create Permission')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width:48px;height:48px;background:linear-gradient(135deg,#17a2b8,#20c997);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(23,162,184,0.3);">
            <i class="fas fa-plus text-white" style="font-size:20px;"></i>
        </div>
        <h1 class="mb-0" style="font-weight:600;">Create Permission</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top:3px solid #17a2b8;">
        <div class="card-body">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Permission Name" placeholder="e.g. donor-export" value="{{ old('name') }}"/>
                        <small class="text-muted">Use format: <code>resource-action</code> (e.g. <code>donor-export</code>)</small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <x-adminlte-button type="submit" label="Save" theme="info" icon="fas fa-save"/>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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
</style>
@stop
