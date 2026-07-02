@extends('adminlte::page')

@section('title', 'Follow-ups')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #6f42c1, #a16be7); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(111,66,193,0.3);">
                <i class="fas fa-bell text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Follow-ups</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $followUps->total() }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.follow-ups.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
            <div class="small-box bg-secondary rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-list fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $total }}</span><br><small class="text-white" style="opacity: 0.8;">Total</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
            <div class="small-box bg-warning rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-clock fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $pendingCount }}</span><br><small class="text-white" style="opacity: 0.8;">Pending</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
            <div class="small-box bg-success rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-check-circle fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $completedCount }}</span><br><small class="text-white" style="opacity: 0.8;">Completed</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="small-box bg-danger rounded-lg p-3 mb-0 d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-times-circle fa-2x text-white opacity-50"></i></div>
                <div><span class="text-white" style="font-size: 22px; font-weight: 700;">{{ $skippedCount }}</span><br><small class="text-white" style="opacity: 0.8;">Skipped</small></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET" action="{{ route('admin.follow-ups.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Donor name or notes..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Type</small>
                        <select name="type" class="form-control form-control-sm">
                            <option value="">All Types</option>
                            <option value="re_engagement" {{ request('type') == 're_engagement' ? 'selected' : '' }}>Re-engagement</option>
                            <option value="eligible_reminder" {{ request('type') == 'eligible_reminder' ? 'selected' : '' }}>Eligible Reminder</option>
                            <option value="call_back" {{ request('type') == 'call_back' ? 'selected' : '' }}>Call Back</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Status</small>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="skipped" {{ request('status') == 'skipped' ? 'selected' : '' }}>Skipped</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.follow-ups.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
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
                            <th>Donor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Scheduled</th>
                            <th>Completed</th>
                            <th>Notes</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($followUps as $followUp)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $followUp->id }}</span></td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.donors.show', $followUp->donor_id) }}" style="font-weight: 500;">{{ $followUp->donor->name ?? 'N/A' }}</a>
                                </td>
                                <td class="align-middle">
                                    @if($followUp->type == 're_engagement')
                                        <span class="badge badge-info" style="border-radius: 20px; padding: 4px 12px;">Re-engagement</span>
                                    @elseif($followUp->type == 'eligible_reminder')
                                        <span class="badge badge-success" style="border-radius: 20px; padding: 4px 12px;">Eligible Reminder</span>
                                    @else
                                        <span class="badge badge-warning" style="border-radius: 20px; padding: 4px 12px;">Call Back</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($followUp->status == 'completed')
                                        <span class="badge badge-success" style="border-radius: 20px; padding: 4px 12px;">Completed</span>
                                    @elseif($followUp->status == 'skipped')
                                        <span class="badge badge-danger" style="border-radius: 20px; padding: 4px 12px;">Skipped</span>
                                    @else
                                        <span class="badge badge-warning" style="border-radius: 20px; padding: 4px 12px;">Pending</span>
                                    @endif
                                </td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $followUp->scheduled_at->format('d-m-Y H:i') }}</td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $followUp->completed_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                <td class="align-middle">{{ Str::limit($followUp->notes, 40) }}</td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('admin.follow-ups.show', $followUp->id) }}" class="btn btn-outline-info btn-sm" title="View" style="border-radius: 6px 0 0 6px;"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.follow-ups.edit', $followUp->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.follow-ups.destroy', $followUp->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this follow-up?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-bell text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2 mb-0">No follow-ups found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $followUps->firstItem() ?? 0 }} - {{ $followUps->lastItem() ?? 0 }} of {{ $followUps->total() }}</small>
            {{ $followUps->appends(request()->query())->links() }}
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table td, .table th { vertical-align: middle; }
.btn-outline-info, .btn-outline-warning, .btn-outline-danger { border-width: 1.5px; }
.btn-group-sm .btn { padding: 3px 8px; }
.opacity-50 { opacity: 0.5; }
</style>
@stop
