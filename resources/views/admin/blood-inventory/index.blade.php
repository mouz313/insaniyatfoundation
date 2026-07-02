@extends('adminlte::page')

@section('title', 'Blood Inventory')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);">
                <i class="fas fa-warehouse text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Blood Inventory</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $items->total() }} records &bull; {{ $totalAvailable }} total units available</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.blood-inventory.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Stock</a>
        </div>
    </div>
@stop

@section('content')
    @php $allGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-']; @endphp

    <div class="row mb-4">
        @foreach($allGroups as $bg)
            @php
                $units = $stockByGroup[$bg] ?? 0;
                $isLow = isset($lowStock[$bg]);
                $color = $units > 10 ? 'success' : ($units > 5 ? 'warning' : 'danger');
            @endphp
            <div class="col-3 col-md-1-5 mb-2" style="min-width: 110px;">
                <div class="card border-0 shadow-sm rounded-lg text-center h-100" style="border-left: 4px solid {{ $isLow ? '#dc3545' : ($units > 10 ? '#28a745' : '#ffc107') }};">
                    <div class="card-body py-2 px-2">
                        <span class="badge badge-{{ $color }}" style="font-size: 12px;">{{ $bg }}</span>
                        <h4 class="mb-0 font-weight-bold mt-1">{{ $units }}</h4>
                        <small class="text-muted" style="font-size: 9px;">units</small>
                        @if($isLow)<div><small class="text-danger" style="font-size: 8px;"><i class="fas fa-exclamation-triangle"></i> Low</small></div>@endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <form method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Batch, location..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Blood Group</small>
                        <select name="blood_group" class="form-control form-control-sm">
                            <option value="">All</option>
                            @foreach($allGroups as $bg)
                                <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Status</small>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="discarded" {{ request('status') == 'discarded' ? 'selected' : '' }}>Discarded</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">&nbsp;</small>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            <a href="{{ route('admin.blood-inventory.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Clear</a>
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
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Batch</th>
                            <th>Received</th>
                            <th>Expiry</th>
                            <th>Source</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td class="align-middle"><span class="text-muted">#{{ $item->id }}</span></td>
                                <td class="align-middle"><span class="badge badge-danger" style="border-radius: 20px; padding: 4px 12px;"><i class="fas fa-tint"></i> {{ $item->blood_group }}</span></td>
                                <td class="align-middle"><strong>{{ $item->units }}</strong></td>
                                <td class="align-middle"><code>{{ $item->batch_no ?? 'N/A' }}</code></td>
                                <td class="align-middle">{{ $item->received_date ? $item->received_date->format('d M Y') : 'N/A' }}</td>
                                <td class="align-middle">
                                    @if($item->expiry_date)
                                        <span class="{{ $item->expiry_date->isPast() ? 'text-danger' : 'text-muted' }}">{{ $item->expiry_date->format('d M Y') }}</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="align-middle">{{ $item->source ?? ($item->campaign->name ?? 'N/A') }}</td>
                                <td class="align-middle">{{ $item->location ?? 'N/A' }}</td>
                                <td class="align-middle">
                                    @switch($item->status)
                                        @case('available') <span class="badge badge-success" style="border-radius: 20px;">Available</span> @break
                                        @case('reserved') <span class="badge badge-warning" style="border-radius: 20px;">Reserved</span> @break
                                        @case('expired') <span class="badge badge-danger" style="border-radius: 20px;">Expired</span> @break
                                        @case('discarded') <span class="badge badge-secondary" style="border-radius: 20px;">Discarded</span> @break
                                    @endswitch
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.blood-inventory.show', $item->id) }}" class="btn btn-outline-info" title="View" style="border-radius: 6px 0 0 6px;"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.blood-inventory.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.blood-inventory.destroy', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this record?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-5"><i class="fas fa-warehouse text-muted" style="font-size: 48px; opacity: 0.3;"></i><p class="text-muted mt-2 mb-0">No inventory records found.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }}</small>
            {{ $items->appends(request()->query())->links() }}
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
.col-1-5, .col-md-1-5 { flex: 0 0 12.5%; max-width: 12.5%; }
@media (max-width: 768px) { .col-1-5 { flex: 0 0 25%; max-width: 25%; } }
</style>
@stop
