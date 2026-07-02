@extends('adminlte::page')

@section('title', 'Cities')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);">
            <i class="fas fa-map-marked-alt text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Cities</h1>
            <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $cities->total() }} total</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #28a745 !important;">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-plus text-success"></i> Add City</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cities.store') }}" method="POST">
                        @csrf
                        <x-adminlte-input name="name" label="City Name" placeholder="Enter city name"/>
                        <x-adminlte-button type="submit" label="Add City" theme="success" icon="fas fa-save"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-city text-secondary"></i> All Cities</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Areas</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    <tr>
                                        <td class="align-middle"><span class="text-muted">#{{ $city->id }}</span></td>
                                        <td class="align-middle" style="font-weight: 500;">{{ $city->name }}</td>
                                        <td class="align-middle"><span class="badge badge-secondary" style="border-radius: 20px; padding: 4px 12px;">{{ $city->areas_count }}</span></td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-warning" title="Edit" style="border-radius: 6px 0 0 6px;" onclick="editCity({{ $city->id }}, '{{ $city->name }}')"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this city?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="fas fa-city text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-2 mb-0">No cities found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <small class="text-muted">Showing {{ $cities->firstItem() ?? 0 }} - {{ $cities->lastItem() ?? 0 }} of {{ $cities->total() }}</small>
                    {{ $cities->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCityForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit City</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <x-adminlte-input name="name" label="City Name" id="editCityName"/>
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
    function editCity(id, name){
        $('#editCityName').val(name);
        $('#editCityForm').attr('action', '{{ url('admin/cities') }}/' + id);
        $('#editCityModal').modal('show');
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
