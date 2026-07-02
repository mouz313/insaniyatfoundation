@extends('adminlte::page')

@section('title', 'Edit Blood Donation')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width:48px;height:48px;background:linear-gradient(135deg,#ffc107,#ffcd39);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(255,193,7,0.3);">
            <i class="fas fa-edit text-white" style="font-size:20px;"></i>
        </div>
        <h1 class="mb-0" style="font-weight:600;">Edit Blood Donation #{{ $bloodDonation->id }}</h1>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg" style="border-top:3px solid #ffc107;">
                <div class="card-body">
                    <form action="{{ route('admin.blood-donations.update', $bloodDonation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <x-adminlte-select name="donor_id" id="donorSelect" label="Donor" required>
                            <option value="">Select Donor</option>
                            @foreach($donors as $donor)
                                <option value="{{ $donor->id }}"
                                    data-blood-group="{{ $donor->blood_group }}"
                                    data-last-donation="{{ $donor->last_donation_date?->format('Y-m-d') }}"
                                    data-next-eligible="{{ $donor->next_eligible_date?->format('Y-m-d') }}"
                                    data-days-until-eligible="{{ $donor->days_until_eligible }}"
                                    data-reliability="{{ $donor->reliability_score }}"
                                    data-donations-count="{{ $donor->donated_count }}"
                                    {{ old('donor_id', $bloodDonation->donor_id) == $donor->id ? 'selected' : '' }}>
                                    {{ $donor->name }} ({{ $donor->blood_group }})
                                </option>
                            @endforeach
                        </x-adminlte-select>

                        <div id="donorInfoPanel" class="mb-3" style="display:none;">
                            <div class="d-flex flex-wrap align-items-center p-3" style="background:#f8f9fa;border-radius:10px;gap:16px;">
                                <div>
                                    <small class="text-muted d-block">Last Donation</small>
                                    <strong id="donorLastDonation" style="font-size:15px;">—</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Next Eligible</small>
                                    <strong id="donorNextEligible" style="font-size:15px;">—</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Status</small>
                                    <strong id="donorEligibilityBadge" style="font-size:15px;">—</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Donations</small>
                                    <strong id="donorDonationsCount" style="font-size:15px;">—</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Reliability</small>
                                    <strong id="donorReliability" style="font-size:15px;">—</strong>
                                </div>
                            </div>
                        </div>

                        <x-adminlte-select name="blood_request_id" label="Patient / Recipient (from Blood Requests)" id="patientSelect">
                            <option value="">None (manual entry)</option>
                            @if($bloodDonation->bloodRequest)
                                <option value="{{ $bloodDonation->bloodRequest->id }}" selected data-name="{{ $bloodDonation->bloodRequest->patient_name }}">{{ $bloodDonation->bloodRequest->patient_name }} ({{ $bloodDonation->bloodRequest->hospital }})</option>
                            @endif
                        </x-adminlte-select>
                        <x-adminlte-input name="patient_name" id="patientNameInput" label="Or type patient name" placeholder="Manual patient name" value="{{ old('patient_name', $bloodDonation->patient_name) }}"/>

                        <div class="row">
                            <div class="col-md-5">
                                <x-adminlte-input name="donation_date" id="donationDate" label="Donation Date" type="date" value="{{ old('donation_date', $bloodDonation->donation_date?->format('Y-m-d')) }}" required/>
                            </div>
                            <div class="col-md-3">
                                <x-adminlte-input name="months_ago" id="monthsAgo" label="Or Months Ago" type="number" min="0" max="120" value="0" placeholder="0"/>
                                <small class="text-muted" style="margin-top:-8px;display:block;">Set months back from today</small>
                            </div>
                            <div class="col-md-4">
                                <x-adminlte-select name="blood_group" id="bloodGroupSelect" label="Blood Group" required>
                                    <option value="">Select Blood Group</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group', $bloodDonation->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </x-adminlte-select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <x-adminlte-input name="units" label="Units" type="number" min="1" value="{{ old('units', $bloodDonation->units) }}" required/>
                            </div>
                            <div class="col-md-4">
                                <x-adminlte-select name="campaign_id" label="Campaign (Optional)">
                                    <option value="">None</option>
                                    @foreach($campaigns ?? [] as $campaign)
                                        <option value="{{ $campaign->id }}" {{ old('campaign_id', $bloodDonation->campaign_id) == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                                    @endforeach
                                </x-adminlte-select>
                            </div>
                            <div class="col-md-4">
                                <x-adminlte-select name="status" label="Status">
                                    <option value="donated" {{ old('status', $bloodDonation->status) == 'donated' ? 'selected' : '' }}>Donated</option>
                                    <option value="pending" {{ old('status', $bloodDonation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="deferred" {{ old('status', $bloodDonation->status) == 'deferred' ? 'selected' : '' }}>Deferred</option>
                                </x-adminlte-select>
                            </div>
                        </div>

                        <x-adminlte-input name="location" label="Location" placeholder="Donation location" value="{{ old('location', $bloodDonation->location) }}"/>

                        <div class="text-right">
                            <x-adminlte-button type="submit" label="Update" theme="warning" icon="fas fa-save"/>
                            <a href="{{ route('admin.blood-donations.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(function() {
    var oldPatientId = '{{ old('blood_request_id', $bloodDonation->blood_request_id) }}';

    function monthsBetween(d1, d2) {
        var months = (d2.getFullYear() - d1.getFullYear()) * 12 + (d2.getMonth() - d1.getMonth());
        return Math.max(0, Math.round(months));
    }

    function recalcMonthsAgo() {
        var val = $('#donationDate').val();
        if (val) {
            var d = new Date(val + 'T00:00:00');
            var today = new Date();
            today.setHours(0,0,0,0);
            if (d <= today) {
                $('#monthsAgo').val(monthsBetween(d, today));
            }
        }
    }

    $('#monthsAgo').on('input', function() {
        var val = parseInt($(this).val()) || 0;
        var d = new Date();
        d.setMonth(d.getMonth() - val);
        $('#donationDate').val(d.toISOString().split('T')[0]);
    });

    $('#donationDate').on('change', recalcMonthsAgo);

    $('#donorSelect').on('change', function() {
        var opt = $(this).find(':selected');
        var bg = opt.data('blood-group');
        $('#bloodGroupSelect').val(bg);

        var lastDonation = opt.data('last-donation');
        var nextEligible = opt.data('next-eligible');
        var daysUntil = opt.data('days-until-eligible');
        var reliability = opt.data('reliability');
        var donationsCount = opt.data('donations-count');

        if (opt.val()) {
            $('#donorInfoPanel').show();
            $('#donorLastDonation').text(lastDonation || 'Never donated');
            $('#donorNextEligible').text(nextEligible || 'Eligible now');
            $('#donorDonationsCount').text(donationsCount || 0);

            if (reliability !== undefined) {
                $('#donorReliability').html('<span class="badge badge-' + (reliability >= 70 ? 'success' : reliability >= 40 ? 'warning' : 'secondary') + '" style="border-radius:20px;">' + reliability + '%</span>');
            }

            if (!lastDonation) {
                $('#donorEligibilityBadge').html('<span class="badge badge-success" style="border-radius:20px;">Eligible</span>');
            } else if (daysUntil !== undefined && daysUntil <= 0) {
                $('#donorEligibilityBadge').html('<span class="badge badge-success" style="border-radius:20px;">Eligible</span>');
            } else if (daysUntil !== undefined) {
                $('#donorEligibilityBadge').html('<span class="badge badge-warning" style="border-radius:20px;">' + daysUntil + ' days until eligible</span>');
            } else {
                $('#donorEligibilityBadge').html('<span class="badge badge-secondary" style="border-radius:20px;">Check eligibility</span>');
            }
        } else {
            $('#donorInfoPanel').hide();
        }

        var $select = $('#patientSelect');
        var currentVal = $select.val();
        $select.html('<option value="">Loading...</option>').prop('disabled', true);

        if (!bg) {
            $select.html('<option value="">None (manual entry)</option>');
            return;
        }

        $.get('{{ url("admin/patients/by-blood-group") }}/' + bg, function(data) {
            var html = '<option value="">None (manual entry)</option>';
            $.each(data, function(i, p) {
                var city = p.city_name || '';
                var label = p.patient_name + ' (' + p.hospital + ')';
                if (city) label += ' - ' + city;
                html += '<option value="' + p.id + '" data-name="' + p.patient_name + '">' + label + '</option>';
            });
            $select.html(html).prop('disabled', false);
            if (oldPatientId) $select.val(oldPatientId);
            else if (currentVal) $select.val(currentVal);
        }).fail(function() {
            $select.html('<option value="">None (manual entry)</option>').prop('disabled', false);
        });
    });

    $('#patientSelect').on('change', function() {
        var name = $(this).find(':selected').data('name');
        if (name) $('#patientNameInput').val(name);
    });

    if ($('#donorSelect').val()) {
        $('#donorSelect').trigger('change');
    }

    recalcMonthsAgo();
});
</script>
@stop
