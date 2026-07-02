@extends('adminlte::page')

@section('title', 'Call Logs')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #17a2b8, #5dd0e6); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(23,162,184,0.3);">
                <i class="fas fa-phone text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Call Logs</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $callLogs->total() }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            @can('call-log-create')
                <a href="{{ route('admin.call-logs.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
            <div class="small-box bg-info rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-phone-alt fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $totalCallLogs }}</span><br><small class="text-white" style="opacity: 0.8;">Total Calls</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
            <div class="small-box bg-success rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-check-circle fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $successCount }}</span><br><small class="text-white" style="opacity: 0.8;">Success</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
            <div class="small-box bg-warning rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-clock fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $pendingCount }}</span><br><small class="text-white" style="opacity: 0.8;">Pending</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="small-box bg-danger rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-times-circle fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $failedCount }}</span><br><small class="text-white" style="opacity: 0.8;">Failed</small></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET" action="{{ route('admin.call-logs.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search notes..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Outcome</small>
                        <select name="outcome" class="form-control form-control-sm">
                            <option value="">All Outcomes</option>
                            <option value="success" {{ request('outcome') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="pending" {{ request('outcome') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('outcome') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="donor_found" {{ request('outcome') == 'donor_found' ? 'selected' : '' }}>Donor Found</option>
                            <option value="not_answered" {{ request('outcome') == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Staff</small>
                        <select name="staff_id" class="form-control form-control-sm">
                            <option value="">All Staff</option>
                            @foreach($staff as $staffMember)
                                <option value="{{ $staffMember->id }}" {{ request('staff_id') == $staffMember->id ? 'selected' : '' }}>{{ $staffMember->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.call-logs.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="width:100%;">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Blood Request</th>
                            <th>Staff</th>
                            <th>Outcome</th>
                            <th>Notes</th>
                            <th>Date</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($callLogs as $log)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $log->id }}</span></td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.blood-requests.show', $log->blood_request_id) }}" style="font-weight: 500;">#{{ $log->blood_request_id }}</a>
                                </td>
                                <td class="align-middle">{{ $log->staff->name ?? 'N/A' }}</td>
                                <td class="align-middle">
                                    @if($log->outcome == 'success')
                                        <span class="badge badge-success" style="border-radius: 20px; padding: 4px 12px;">Success</span>
                                    @elseif($log->outcome == 'failed')
                                        <span class="badge badge-danger" style="border-radius: 20px; padding: 4px 12px;">Failed</span>
                                    @elseif($log->outcome == 'pending')
                                        <span class="badge badge-warning" style="border-radius: 20px; padding: 4px 12px;">Pending</span>
                                    @elseif($log->outcome == 'donor_found')
                                        <span class="badge badge-info" style="border-radius: 20px; padding: 4px 12px;">Donor Found</span>
                                    @else
                                        <span class="badge badge-secondary" style="border-radius: 20px; padding: 4px 12px;">Not Answered</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ Str::limit($log->notes, 50) }}</td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $log->created_at->format('d-m-Y') }}</td>
                                <td class="align-middle text-center">
                                    @can('call-log-edit')
                                        <a href="{{ route('admin.call-logs.edit', $log->id) }}" class="btn btn-outline-warning btn-sm" title="Edit" style="border-radius: 6px 0 0 6px;"><i class="fas fa-edit"></i></a>
                                    @endcan
                                    @can('call-log-delete')
                                        <form action="{{ route('admin.call-logs.destroy', $log->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this call log?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-phone text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2 mb-0">No call logs found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $callLogs->firstItem() ?? 0 }} - {{ $callLogs->lastItem() ?? 0 }} of {{ $callLogs->total() }}</small>
            {{ $callLogs->appends(request()->query())->links() }}
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
.opacity-50 { opacity: 0.5; }
</style>
@stop
