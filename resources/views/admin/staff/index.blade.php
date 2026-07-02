@extends('adminlte::page')

@section('title', 'Staff')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #6c757d, #adb5bd); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(108,117,125,0.3);">
                <i class="fas fa-users text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Staff</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $totalStaff }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            @can('staff-create')
                <a href="{{ route('admin.staff.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET" action="{{ route('admin.staff.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Created</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $user)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $user->id }}</span></td>
                                <td class="align-middle" style="font-weight: 500;">{{ $user->name }}</td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-info" style="border-radius: 20px; padding: 4px 12px;">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('staff-edit')
                                            <a href="{{ route('admin.staff.edit', $user->id) }}" class="btn btn-outline-warning" title="Edit" style="border-radius: 6px 0 0 6px;"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('staff-delete')
                                            <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: {{ auth()->user()->can('staff-edit') ? '0 6px 6px 0' : '6px' }};" onclick="return confirm('Delete this staff member?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-users text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2 mb-0">No staff members found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $staff->firstItem() ?? 0 }} - {{ $staff->lastItem() ?? 0 }} of {{ $staff->total() }}</small>
            {{ $staff->appends(request()->query())->links() }}
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
