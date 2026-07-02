@extends('adminlte::page')

@section('title', 'Donor Cards')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Donor Cards</h1>
        <div>
            <button id="printSelected" class="btn btn-info"><i class="fas fa-print"></i> Print Selected</button>
            <button id="markPrinted" class="btn btn-success"><i class="fas fa-check"></i> Mark Printed</button>
        </div>
    </div>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-3">
            <select id="filterBloodGroup" class="form-control">
                <option value="">All Blood Groups</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterCity" class="form-control">
                <option value="">All Cities</option>
                @foreach($cities ?? [] as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterStatus" class="form-control">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-body p-0">
            <table id="donorCardsTable" style="width:100%" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donors as $donor)
                        <tr>
                            <td><input type="checkbox" class="donor-checkbox" value="{{ $donor->id }}"></td>
                            <td>{{ $donor->id }}</td>
                            <td>{{ $donor->name }}</td>
                            <td><span class="badge badge-danger">{{ $donor->blood_group }}</span></td>
                            <td>{{ $donor->phone }}</td>
                            <td>{{ $donor->city->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $donor->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($donor->status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form id="printForm" action="{{ route('admin.donor-cards.print') }}" method="POST" target="_blank" style="display:none;">
        @csrf
        <input type="hidden" name="donor_ids" id="printDonorIds">
    </form>

    <form id="markForm" action="{{ route('admin.donor-cards.mark-printed') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="donor_ids" id="markDonorIds">
    </form>
@stop

@section('js')
<script>
    $(function() {
        var table = $('#donorCardsTable').DataTable({
            pageLength: 20,
            lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
            order: [[1, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0] }
            ]
        });

        $('#selectAll').on('change', function(){
            $('.donor-checkbox').prop('checked', this.checked);
        });

        $('#printSelected').on('click', function(){
            var ids = [];
            $('.donor-checkbox:checked').each(function(){ ids.push($(this).val()); });
            if(ids.length === 0){ alert('Select at least one donor.'); return; }
            $('#printDonorIds').val(JSON.stringify(ids));
            $('#printForm').submit();
        });

        $('#markPrinted').on('click', function(){
            var ids = [];
            $('.donor-checkbox:checked').each(function(){ ids.push($(this).val()); });
            if(ids.length === 0){ alert('Select at least one donor.'); return; }
            if(!confirm('Mark selected donors as printed?')) return;
            $('#markDonorIds').val(JSON.stringify(ids));
            $('#markForm').submit();
        });

        $('#filterBloodGroup, #filterCity, #filterStatus').on('change', function(){
            table.columns(3).search($('#filterBloodGroup').val());
            table.columns(5).search($('#filterCity').val());
            table.columns(6).search($('#filterStatus').val());
            table.draw();
        });
    });
</script>
@stop
