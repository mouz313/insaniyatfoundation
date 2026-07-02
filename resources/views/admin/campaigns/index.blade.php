@extends('adminlte::page')

@section('title', 'Campaigns')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #dc3545, #e4606d); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(220,53,69,0.3);">
                <i class="fas fa-calendar-alt text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Campaigns</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $total }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            @can('campaign-create')
                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg text-center py-3">
                <div class="card-body py-2">
                    <h3 class="font-weight-bold text-primary mb-0">{{ $total }}</h3>
                    <small class="text-muted">Total Campaigns</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg text-center py-3">
                <div class="card-body py-2">
                    <h3 class="font-weight-bold text-success mb-0">{{ $upcoming }}</h3>
                    <small class="text-muted">Upcoming</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg text-center py-3">
                <div class="card-body py-2">
                    <h3 class="font-weight-bold text-secondary mb-0">{{ $past }}</h3>
                    <small class="text-muted">Past</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg text-center py-3">
                <div class="card-body py-2">
                    <h3 class="font-weight-bold text-danger mb-0">{{ $totalTargetUnits }}</h3>
                    <small class="text-muted">Target Units</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET" action="{{ route('admin.campaigns.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or venue..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
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
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Target Units</th>
                            <th>Created</th>
                            <th width="140" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $campaign->id }}</span></td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.campaigns.show', $campaign->id) }}" style="font-weight: 500;">{{ $campaign->name }}</a>
                                </td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $campaign->date->format('d M Y') }}</td>
                                <td class="align-middle">{{ $campaign->venue }}</td>
                                <td class="align-middle"><strong>{{ $campaign->target_units ?? 0 }}</strong></td>
                                <td class="align-middle" style="white-space: nowrap;">{{ $campaign->created_at->format('d M Y') }}</td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('campaign-show')
                                            <a href="{{ route('admin.campaigns.show', $campaign->id) }}" class="btn btn-outline-info" title="View" style="border-radius: 6px 0 0 6px;"><i class="fas fa-eye"></i></a>
                                        @endcan
                                        @can('campaign-edit')
                                            <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('campaign-delete')
                                            <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this campaign?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-calendar-alt text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2 mb-0">No campaigns found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $campaigns->firstItem() ?? 0 }} - {{ $campaigns->lastItem() ?? 0 }} of {{ $campaigns->total() }}</small>
            {{ $campaigns->appends(request()->query())->links() }}
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table td, .table th { vertical-align: middle; }
.btn-outline-warning, .btn-outline-danger, .btn-outline-info { border-width: 1.5px; }
.btn-group-sm .btn { padding: 3px 8px; }
</style>
@stop
