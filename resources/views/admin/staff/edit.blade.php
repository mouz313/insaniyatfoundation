@extends('adminlte::page')

@section('title', 'Edit Staff')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #ffc107, #ffcd39); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255,193,7,0.3);">
            <i class="fas fa-user-edit text-white" style="font-size: 20px;"></i>
        </div>
        <h1 class="mb-0" style="font-weight: 600;">Edit Staff</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #ffc107 !important;">
        <div class="card-body">
            <form action="{{ route('admin.staff.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Full Name" placeholder="Enter name" value="{{ old('name', $user->name) }}"/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="email" label="Email Address" type="email" placeholder="Enter email" value="{{ old('email', $user->email) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="password" label="New Password" type="password" placeholder="Leave blank to keep current"/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="password_confirmation" label="Confirm New Password" type="password" placeholder="Confirm new password"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Roles</label>
                        @if($isSuperAdmin)
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="fas fa-shield-alt mr-1"></i> Super admin role is protected and cannot be modified.
                            </div>
                            @foreach($user->roles as $role)
                                <span class="badge badge-info mt-2" style="border-radius: 20px; padding: 4px 12px;">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <div class="row">
                                @foreach($roles ?? [] as $role)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role{{ $role->id }}" {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role{{ $role->id }}">{{ $role->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <x-adminlte-button type="submit" label="Update" theme="warning" icon="fas fa-save"/>
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
