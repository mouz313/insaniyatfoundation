@extends('adminlte::page')

@section('title', 'Donors')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(102,126,234,0.3);">
                <i class="fas fa-users text-white" style="font-size: 24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight: 600;">Donors</h1>
                <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $donors->count() }} total</small>
            </div>
        </div>
        <div class="d-flex align-items-center mt-2 mt-md-0">
            <div id="liveClock" class="mr-4 text-center" style="min-width: 140px;">
                <div style="font-size: 22px; font-weight: 700; color: #333; line-height: 1.2; font-family: 'Courier New', monospace;" id="clockTime"></div>
                <div style="font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px;" id="clockDate"></div>
            </div>
            <a href="{{ route('admin.donor-cards.index') }}" class="btn btn-info btn-sm mr-1"><i class="fas fa-id-card"></i> Cards</a>
            <a href="{{ route('admin.donors.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Donor</a>
        </div>
    </div>
@stop

@section('content')
    @php
        $activeCount = $donors->where('status', 'active')->count();
        $eligibleNow = $donors->where('is_eligible', true)->where('status', 'active')->count();
        $pendingCount = $donors->where('is_eligible', false)->count();
        $neverDonated = $donors->whereNull('last_donation_date')->count();
    @endphp

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-0 font-weight-bold">{{ $donors->count() }}</h3>
                            <small class="text-white-50">Total Donors</small>
                        </div>
                        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users text-white" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-0 font-weight-bold">{{ $activeCount }}</h3>
                            <small class="text-white-50">Active Donors</small>
                        </div>
                        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-check text-white" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-0 font-weight-bold">{{ $eligibleNow }}</h3>
                            <small class="text-white-50">Eligible Now</small>
                        </div>
                        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle text-white" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4" style="background: linear-gradient(135deg, #e67e22, #f39c12);">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-0 font-weight-bold">{{ $pendingCount }}</h3>
                            <small class="text-white-50">On Cooldown</small>
                        </div>
                        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-hourglass-half text-white" style="font-size: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Search</small>
                    <input type="text" id="searchName" class="form-control form-control-sm" placeholder="Name, phone or CNIC...">
                </div>
                <div class="col-md-2">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Blood Group</small>
                    <select id="filterBloodGroup" class="form-control form-control-sm">
                        <option value="">All</option>
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
                <div class="col-md-2">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Status</small>
                    <select id="filterStatus" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">City</small>
                    <select id="filterCity" class="form-control form-control-sm">
                        <option value="">All</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->name }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <small class="text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">Eligibility</small>
                    <select id="filterEligible" class="form-control form-control-sm">
                        <option value="">All Donors</option>
                        <option value="eligible">Eligible Now</option>
                        <option value="cooldown">On Cooldown</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="donorsTable" class="table table-hover mb-0" style="width:100%;">
                    <thead class="thead-light">
                        <tr>
                            <th>Reg No</th>
                            <th>Donor</th>
                            <th>Phone</th>
                            <th>Blood</th>
                            <th>City</th>
                            <th>Education</th>
                            <th>Status</th>
                            <th>Eligibility</th>
                            <th width="130" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donors as $donor)
                            <tr>
                                <td class="align-middle"><code class="text-dark" style="font-size: 11px;">{{ $donor->registration_no ?? 'N/A' }}</code></td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 38px; height: 38px; border-radius: 10px; overflow: hidden; flex-shrink: 0; margin-right: 10px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                                            @if($donor->photo)
                                                <img src="{{ asset('storage/' . $donor->photo) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-user text-white" style="font-size: 16px;"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.donors.show', $donor->id) }}" style="font-weight: 500; color: #333;">{{ $donor->name }}</a>
                                            @if($donor->is_student)
                                                <span class="badge badge-info" style="border-radius: 20px; font-size: 9px; vertical-align: top;"><i class="fas fa-graduation-cap"></i></span>
                                            @endif
                                            @if(!$donor->last_donation_date)
                                                <div><small class="text-success"><i class="fas fa-circle" style="font-size: 6px; vertical-align: middle;"></i> New</small></div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">{{ $donor->phone }}</td>
                                <td class="align-middle"><span class="badge badge-danger" style="border-radius: 20px; padding: 4px 12px;"><i class="fas fa-tint"></i> {{ $donor->blood_group }}</span></td>
                                <td class="align-middle">{{ $donor->city->name ?? 'N/A' }}</td>
                                <td class="align-middle"><small>{{ $donor->education ?? 'N/A' }}</small></td>
                                <td class="align-middle">
                                    @if($donor->status == 'active')
                                        <span class="badge badge-success" style="border-radius: 20px;">Active</span>
                                    @elseif($donor->status == 'ineligible')
                                        <span class="badge badge-danger" style="border-radius: 20px;">Ineligible</span>
                                    @else
                                        <span class="badge badge-secondary" style="border-radius: 20px;">Inactive</span>
                                    @endif
                                </td>
                                <td class="align-middle" style="white-space: nowrap;">
                                    @if($donor->is_eligible)
                                        <span class="badge badge-success" style="border-radius: 20px; padding: 5px 14px;">
                                            <i class="fas fa-check-circle"></i> Eligible
                                        </span>
                                    @else
                                        <span class="badge badge-warning" style="border-radius: 20px; padding: 5px 14px;">
                                            <i class="fas fa-hourglass-half"></i>
                                            @if($donor->days_until_eligible > 0)
                                                {{ $donor->days_until_eligible }}d
                                            @else
                                                Deferred
                                            @endif
                                        </span>
                                        @if($donor->next_eligible_date)
                                            <small class="text-muted d-block" style="font-size: 10px; line-height: 1.1;">{{ $donor->next_eligible_date->format('d M') }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.donors.show', $donor->id) }}" class="btn btn-outline-info" title="View" style="border-radius: 6px 0 0 6px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.donors.edit', $donor->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-delete" data-id="{{ $donor->id }}" data-name="{{ $donor->name }}" title="Delete" style="border-radius: 0 6px 6px 0;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $donor->id }}" action="{{ route('admin.donors.destroy', $donor->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.text-white-50 { color: rgba(255,255,255,0.7) !important; }
.table td, .table th { vertical-align: middle; }
.btn-outline-info, .btn-outline-warning, .btn-outline-danger { border-width: 1.5px; }
.btn-group-sm .btn { padding: 3px 8px; }
</style>
@stop

@section('js')
<script>
$(function() {
    function updateClock() {
        var now = new Date();
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        var h = String(now.getHours()).padStart(2, '0');
        var m = String(now.getMinutes()).padStart(2, '0');
        var s = String(now.getSeconds()).padStart(2, '0');
        $('#clockTime').text(h + ':' + m + ':' + s);
        $('#clockDate').text(days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear());
    }
    updateClock();
    setInterval(updateClock, 1000);

    var table = $('#donorsTable').DataTable({
        pageLength: 20,
        lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [1, 8] },
            { searchable: false, targets: [1, 4] }
        ]
    });

    $('#searchName').on('keyup change', function() {
        table.search(this.value).draw();
    });
    $('#filterBloodGroup').on('change', function() {
        table.column(3).search(this.value).draw();
    });
    $('#filterStatus').on('change', function() {
        table.column(5).search(this.value).draw();
    });
    $('#filterCity').on('change', function() {
        table.column(4).search(this.value).draw();
    });
    $('#filterEligible').on('change', function() {
        var val = this.value;
        if (val === 'eligible') {
            table.column(7).search('Eligible').draw();
        } else if (val === 'cooldown') {
            table.column(7).search('d').draw();
        } else {
            table.column(7).search('').draw();
        }
    });

    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        Swal.fire({
            title: 'Delete Donor?',
            text: 'Are you sure you want to delete ' + name + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form-' + id).submit();
            }
        });
    });
});
</script>

@if(session('success'))
<script>$(function() { toastr.success('{{ session('success') }}', 'Success'); });</script>
@endif
@if(session('error'))
<script>$(function() { toastr.error('{{ session('error') }}', 'Error'); });</script>
@endif
@if($errors->any())
<script>$(function() { toastr.error('{{ $errors->first() }}', 'Validation Error'); });</script>
@endif
@stop
