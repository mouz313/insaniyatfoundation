@extends('adminlte::page')

@section('title', 'Add Staff')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6c757d, #adb5bd); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(108,117,125,0.3);">
            <i class="fas fa-user-plus text-white" style="font-size: 20px;"></i>
        </div>
        <h1 class="mb-0" style="font-weight: 600;">Add Staff</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #6c757d !important;">
        <div class="card-body">
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Full Name" placeholder="Enter name" value="{{ old('name') }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="email" label="Email Address" type="email" placeholder="Enter email" value="{{ old('email') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="password" label="Password" type="password" placeholder="Enter password"/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="password_confirmation" label="Confirm Password" type="password" placeholder="Confirm password"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Roles</label>
                        <div class="row">
                            @foreach($roles ?? [] as $role)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role{{ $role->id }}">{{ $role->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <x-adminlte-button type="submit" label="Save" theme="success" icon="fas fa-save"/>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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
