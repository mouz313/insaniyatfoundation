@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width:56px;height:56px;background:linear-gradient(135deg,#17a2b8,#20c997);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(23,162,184,0.3);">
                <i class="fas fa-key text-white" style="font-size:24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight:600;">Permissions</h1>
                <small class="text-muted">{{ $permissions->flatten()->count() }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Add Permission</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            @if($permissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Group</th>
                                <th>Permission</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $group => $perms)
                                @foreach($perms as $perm)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge badge-secondary">{{ $group }}</span>
                                        </td>
                                        <td class="align-middle" style="font-weight:500;">{{ $perm->name }}</td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.permissions.edit', $perm) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('admin.permissions.destroy', $perm) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this permission?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-key text-muted" style="font-size:48px;opacity:0.3;"></i>
                    <p class="text-muted mt-2 mb-0">No permissions defined.</p>
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
