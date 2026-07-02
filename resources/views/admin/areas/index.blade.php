@extends('adminlte::page')

@section('title', 'Areas')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #17a2b8, #5fc8dd); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(23,162,184,0.3);">
            <i class="fas fa-map-marker-alt text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Areas</h1>
            <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $areas->total() }} total</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #17a2b8 !important;">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-plus text-info"></i> Add Area</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.areas.store') }}" method="POST">
                        @csrf
                        <x-adminlte-select name="city_id" label="City">
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </x-adminlte-select>
                        <x-adminlte-input name="name" label="Area Name" placeholder="Enter area name"/>
                        <x-adminlte-button type="submit" label="Add Area" theme="success" icon="fas fa-save"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt text-secondary"></i> All Areas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Area Name</th>
                                    <th>City</th>
                                    <th>Donors</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($areas as $area)
                                    <tr>
                                        <td class="align-middle"><span class="text-muted">#{{ $area->id }}</span></td>
                                        <td class="align-middle" style="font-weight: 500;">{{ $area->name }}</td>
                                        <td class="align-middle"><span class="badge badge-info" style="border-radius: 20px; padding: 4px 12px;">{{ $area->city->name ?? 'N/A' }}</span></td>
                                        <td class="align-middle"><span class="badge badge-secondary" style="border-radius: 20px; padding: 4px 12px;">{{ $area->donors_count }}</span></td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-warning" title="Edit" style="border-radius: 6px 0 0 6px;" onclick="editArea({{ $area->id }}, {{ $area->city_id }}, '{{ $area->name }}')"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('admin.areas.destroy', $area->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this area?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-map-marker-alt text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-2 mb-0">No areas found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <small class="text-muted">Showing {{ $areas->firstItem() ?? 0 }} - {{ $areas->lastItem() ?? 0 }} of {{ $areas->total() }}</small>
                    {{ $areas->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAreaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editAreaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Area</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <x-adminlte-select name="city_id" label="City" id="editAreaCity">
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </x-adminlte-select>
                        <x-adminlte-input name="name" label="Area Name" id="editAreaName"/>
                    </div>
                    <div class="modal-footer">
                        <x-adminlte-button type="submit" label="Update" theme="warning" icon="fas fa-save"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function editArea(id, cityId, name){
        $('#editAreaCity').val(cityId);
        $('#editAreaName').val(name);
        $('#editAreaForm').attr('action', '{{ url('admin/areas') }}/' + id);
        $('#editAreaModal').modal('show');
    }
</script>
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
