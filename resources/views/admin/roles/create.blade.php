@extends('adminlte::page')

@section('title', 'Create Role')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width:48px;height:48px;background:linear-gradient(135deg,#6610f2,#6f42c1);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(102,16,242,0.3);">
            <i class="fas fa-plus text-white" style="font-size:20px;"></i>
        </div>
        <h1 class="mb-0" style="font-weight:600;">Create Role</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top:3px solid #6610f2;">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Role Name" placeholder="e.g. editor" value="{{ old('name') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Permissions</label>
                        @foreach($permissions as $group => $perms)
                            <div class="mb-3">
                                <strong class="text-muted text-uppercase" style="font-size:12px;letter-spacing:0.5px;">{{ $group }}</strong>
                                <div class="row mt-1">
                                    @foreach($perms as $perm)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm{{ $perm->id }}">
                                                <label class="form-check-label" for="perm{{ $perm->id }}">{{ $perm->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <x-adminlte-button type="submit" label="Save" theme="primary" icon="fas fa-save"/>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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
