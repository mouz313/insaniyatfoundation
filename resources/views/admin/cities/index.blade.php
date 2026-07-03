@extends('adminlte::page')

@section('title', 'Cities')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #5dd475); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(40,167,69,0.3);">
            <i class="fas fa-map-marked-alt text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Cities</h1>
            <small class="text-muted"><i class="fas fa-fw fa-database"></i> <span id="totalCities">{{ $cities->total() }}</span> total</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        @if($topCities->isNotEmpty())
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #ffc107 !important;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-trophy text-warning"></i> Top 3 Cities with Highest Donors</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($topCities as $i => $city)
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center p-3 rounded @if($i == 0) bg-warning text-white @else bg-light @endif" style="height: 100%;">
                                        <div class="mr-3 text-center" style="min-width: 40px;">
                                            <span style="font-size: 24px; font-weight: 800;">
                                                @if($i == 0) <i class="fas fa-crown"></i>
                                                @elseif($i == 1) <i class="fas fa-medal"></i>
                                                @else <i class="fas fa-award"></i> @endif
                                            </span>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 16px;">{{ $city->name }}</div>
                                            <small><i class="fas fa-users"></i> {{ $city->donors_count }} donors</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0"><i class="fas fa-city text-secondary"></i> All Cities</h5>
                        <div style="min-width: 200px;">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="searchCity" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
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
                            <tbody id="citiesTableBody">
                                @include('admin.cities._table')
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <small class="text-muted">Showing <span id="firstItem">{{ $cities->firstItem() ?? 0 }}</span> - <span id="lastItem">{{ $cities->lastItem() ?? 0 }}</span> of <span id="totalCitiesFooter">{{ $cities->total() }}</span></small>
                    <div id="paginationLinks">
                        {{ $cities->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
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
    let searchTimeout;

    function editCity(id, name){
        $('#editCityName').val(name);
        $('#editCityForm').attr('action', '{{ url('admin/cities') }}/' + id);
        $('#editCityModal').modal('show');
    }

    $('#searchCity').on('keyup', function() {
        clearTimeout(searchTimeout);
        const search = $(this).val();
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route('admin.cities.index') }}',
                type: 'GET',
                data: { search: search },
                dataType: 'json',
                success: function(res) {
                    $('#citiesTableBody').html(res.html);
                    $('#paginationLinks').html(res.pagination);
                    $('#firstItem').text(res.firstItem);
                    $('#lastItem').text(res.lastItem);
                    $('#totalCities, #totalCitiesFooter').text(res.total);
                }
            });
        }, 400);
    });
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
