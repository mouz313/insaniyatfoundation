@extends('adminlte::page')

@section('title', 'Add Blood Request')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1A6B2E, #145A26); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(26,107,46,0.3);">
            <i class="fas fa-hand-holding-heart text-white" style="font-size: 22px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">New Blood Request</h1>
            <small class="text-muted">Create a new patient blood request</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <form action="{{ route('admin.blood-requests.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Patient Name <span class="text-danger">*</span></label>
                                    <input type="text" name="patient_name" class="form-control" placeholder="Enter patient name" value="{{ old('patient_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hospital <span class="text-danger">*</span></label>
                                    <input type="text" name="hospital" class="form-control" placeholder="Enter hospital name" value="{{ old('hospital') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Blood Group <span class="text-danger">*</span></label>
                                    <select name="blood_group" class="form-control" id="bloodGroupSelect" required>
                                        <option value="">Select</option>
                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city_id" class="form-control">
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Units Required <span class="text-danger">*</span></label>
                                    <input type="number" name="units_required" class="form-control" min="1" value="{{ old('units_required', 1) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Person <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" class="form-control" placeholder="Enter contact name" value="{{ old('contact_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_phone" class="form-control" placeholder="03XX-XXXXXXX" value="{{ old('contact_phone') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Request</button>
                            <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Eligible Donors Section --}}
            <div class="card shadow-sm border-0 rounded-lg" id="donorsCard" style="display:none;">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #1A6B2E, #28a745); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h5 class="mb-0" style="font-weight: 600;">Eligible Donors</h5>
                    <span class="badge badge-success ml-auto" id="donorCount">0</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Blood</th>
                                    <th>Weight</th>
                                    <th>Last Donation</th>
                                    <th>Reliability</th>
                                </tr>
                            </thead>
                            <tbody id="donorsTableBody">
                                <tr id="noDonorsRow"><td colspan="7" class="text-center text-muted py-4">Select a blood group to see eligible donors</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(function() {
    $('#bloodGroupSelect').on('change', function() {
        var bg = $(this).val();
        var $card = $('#donorsCard');
        var $tbody = $('#donorsTableBody');
        var $count = $('#donorCount');

        if (!bg) {
            $card.hide();
            return;
        }

        $.get('{{ route("admin.donors.by-blood-group", "") }}/' + bg, function(data) {
            $card.show();

            if (data.length === 0) {
                $tbody.html('<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-info-circle mr-1"></i> No eligible donors found for ' + bg + '</td></tr>');
                $count.text('0');
                return;
            }

            var html = '';
            $.each(data, function(i, d) {
                var days = d.days_since_last ? d.days_since_last + ' days ago' : '<span class="badge badge-success">Never</span>';
                var reliabilityColor = d.reliability_score >= 70 ? 'success' : (d.reliability_score >= 40 ? 'warning' : 'danger');
                html += '<tr>' +
                    '<td class="font-weight-bold">' + (d.name || '') + '</td>' +
                    '<td>' + (d.phone || '') + '</td>' +
                    '<td>' + (d.city_name || 'N/A') + '</td>' +
                    '<td><span class="badge badge-danger">' + (d.blood_group || '') + '</span></td>' +
                    '<td>' + (d.weight ? d.weight + ' kg' : '-') + '</td>' +
                    '<td>' + days + '</td>' +
                    '<td><div class="progress" style="height:6px;max-width:60px;display:inline-block;vertical-align:middle;"><div class="progress-bar bg-' + reliabilityColor + '" style="width:' + d.reliability_score + '%"></div></div> <small class="font-weight-bold">' + d.reliability_score + '%</small></td>' +
                    '</tr>';
            });
            $tbody.html(html);
            $count.text(data.length);
        }).fail(function() {
            $card.show();
            $tbody.html('<tr><td colspan="7" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle mr-1"></i> Failed to load donors</td></tr>');
            $count.text('0');
        });
    });

    @if(old('blood_group'))
        $('#bloodGroupSelect').trigger('change');
    @endif
});
</script>
@stop
