@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width:56px;height:56px;background:linear-gradient(135deg,#6610f2,#6f42c1);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(102,16,242,0.3);">
                <i class="fas fa-shield-alt text-white" style="font-size:24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight:600;">Roles</h1>
                <small class="text-muted">{{ $roles->count() }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Role</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Users</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td class="align-middle" style="font-weight:500;">
                                        {{ $role->name }}
                                        @if($role->name === 'super_admin')
                                            <span class="badge badge-danger ml-1">Protected</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @foreach($role->permissions->take(8) as $perm)
                                            <span class="badge badge-info" style="border-radius:20px;padding:3px 10px;margin:1px;">{{ $perm->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 8)
                                            <span class="badge badge-secondary">+{{ $role->permissions->count() - 8 }} more</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $role->users_count ?? $role->users()->count() }}</td>
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm">
                                            @if($role->name !== 'super_admin')
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this role?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shield-alt text-muted" style="font-size:48px;opacity:0.3;"></i>
                    <p class="text-muted mt-2 mb-0">No roles defined.</p>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
</style>
@stop
