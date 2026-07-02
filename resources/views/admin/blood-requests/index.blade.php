@extends('adminlte::page')

@section('title', 'Blood Requests')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(231,76,60,0.3);">
                <i class="fas fa-hand-holding-heart text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Blood Requests</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $bloodRequests->total() }} total</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.blood-requests.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET" action="{{ route('admin.blood-requests.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Patient, hospital, contact..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Blood Group</small>
                        <select name="blood_group" class="form-control form-control-sm">
                            <option value="">All</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">City</small>
                        <select name="city_id" class="form-control form-control-sm">
                            <option value="">All</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Status</small>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
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
                            <th>Patient</th>
                            <th>Hospital</th>
                            <th>Blood</th>
                            <th>City</th>
                            <th>Units</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th width="130" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bloodRequests as $request)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $request->id }}</span></td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.blood-requests.show', $request->id) }}" style="font-weight: 500;">{{ $request->patient_name }}</a>
                                </td>
                                <td class="align-middle">{{ $request->hospital }}</td>
                                <td class="align-middle"><span class="badge badge-danger" style="border-radius: 20px; padding: 4px 12px;"><i class="fas fa-tint"></i> {{ $request->blood_group }}</span></td>
                                <td class="align-middle">{{ $request->city->name ?? 'N/A' }}</td>
                                <td class="align-middle"><strong>{{ $request->units_required }}</strong></td>
                                <td class="align-middle">
                                    @if($request->status == 'resolved')
                                        <span class="badge badge-success" style="border-radius: 20px;">Resolved</span>
                                    @elseif($request->status == 'pending')
                                        <span class="badge badge-warning" style="border-radius: 20px;">Pending</span>
                                    @elseif($request->status == 'closed')
                                        <span class="badge badge-danger" style="border-radius: 20px;">Closed</span>
                                    @else
                                        <span class="badge badge-secondary" style="border-radius: 20px;">{{ ucfirst($request->status) }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $request->contact_phone }}</td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.blood-requests.show', $request->id) }}" class="btn btn-outline-info" title="View" style="border-radius: 6px 0 0 6px;"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.blood-requests.edit', $request->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.blood-requests.destroy', $request->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this request?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-hand-holding-heart text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="text-muted mt-2 mb-0">No blood requests found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $bloodRequests->firstItem() ?? 0 }} - {{ $bloodRequests->lastItem() ?? 0 }} of {{ $bloodRequests->total() }}</small>
            {{ $bloodRequests->appends(request()->query())->links() }}
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
</style>
@stop
