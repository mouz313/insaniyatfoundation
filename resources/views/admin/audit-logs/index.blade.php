@extends('adminlte::page')

@section('title', 'Audit Trail')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-history text-info mr-2"></i>Audit Trail</h1>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-white">
            <form method="GET" class="form-inline flex-wrap" style="gap:8px;">
                <div class="form-group">
                    <select name="causer_id" class="form-control form-control-sm" style="min-width:150px;">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="description" class="form-control form-control-sm" placeholder="Action..." value="{{ request('description') }}" style="min-width:120px;">
                </div>
                <div class="form-group">
                    <input type="text" name="log_name" class="form-control form-control-sm" placeholder="Model..." value="{{ request('log_name') }}" style="min-width:120px;">
                </div>
                <div class="form-group">
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From">
                </div>
                <div class="form-group">
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To">
                </div>
                <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-filter mr-1"></i>Filter</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times mr-1"></i>Clear</a>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Date/Time</th><th>User</th><th>Action</th><th>Model/Subject</th><th>Details</th></tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td><small>{{ $log->created_at->format('d M Y H:i:s') }}</small></td>
                                <td>{{ $log->causer->name ?? 'System' }}</td>
                                <td><span class="badge badge-info">{{ $log->description }}</span></td>
                                <td><code>{{ class_basename($log->subject_type ?? 'N/A') }} #{{ $log->subject_id ?? '' }}</code></td>
                                <td>
                                    @if($log->properties)
                                        <small><code>{{ json_encode($log->properties->toArray()) }}</code></small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No audit logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-3">{{ $logs->links() }}</div>
@stop