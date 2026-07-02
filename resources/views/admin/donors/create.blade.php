@extends('adminlte::page')

@section('title', 'Add Donor')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #dc3545, #e4606d); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(220,53,69,0.3);">
            <i class="fas fa-user-plus text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Donor Registration</h1>
            <small class="text-muted">Register a new blood donor</small>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('admin.donors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Duplicate warning --}}
        <div id="duplicateWarning" class="alert alert-warning d-none shadow-sm rounded-lg">
            <i class="fas fa-exclamation-triangle"></i> <span id="duplicateMsg"></span>
        </div>

        {{-- Personal Information --}}
        <div class="card shadow-sm border-0 rounded-lg mb-4" style="border-top: 3px solid #dc3545 !important;">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-user text-danger"></i> Personal Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Full Name" placeholder="Enter full name" value="{{ old('name') }}" required/>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="father_name" label="Father's Name" placeholder="Enter father's name" value="{{ old('father_name') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <x-adminlte-input name="cnic" label="CNIC" placeholder="XXXXX-XXXXXXX-X" value="{{ old('cnic') }}" id="cnic"/>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-input name="phone" label="Phone Number" placeholder="03XX-XXXXXXX" value="{{ old('phone') }}" required id="phone"/>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-input name="dob" label="Date of Birth" type="date" value="{{ old('dob') }}"/>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-select name="gender" label="Gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </x-adminlte-select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <x-adminlte-select name="blood_group" label="Blood Group" required>
                            <option value="">Select Blood Group</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-4">
                        <x-adminlte-select name="status" label="Status">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="ineligible" {{ old('status') == 'ineligible' ? 'selected' : '' }}>Ineligible</option>
                        </x-adminlte-select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact & Location --}}
        <div class="card shadow-sm border-0 rounded-lg mb-4" style="border-top: 3px solid #17a2b8 !important;">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-map-marker-alt text-info"></i> Contact & Location</h5></div>
            <div class="card-body">
                <x-adminlte-textarea name="address" label="Address" placeholder="Enter full address">{{ old('address') }}</x-adminlte-textarea>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city_id">City <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="city_id" id="city_id" class="form-control" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#addCityModal"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="area_id">Area</label>
                            <div class="input-group">
                                <select name="area_id" id="area_id" class="form-control">
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#addAreaModal"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Health & Medical --}}
        <div class="card shadow-sm border-0 rounded-lg mb-4" style="border-top: 3px solid #28a745 !important;">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-heartbeat text-success"></i> Health & Medical</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-adminlte-input name="weight" label="Weight (kg)" type="number" step="0.1" min="30" max="300" placeholder="e.g. 65.5" value="{{ old('weight') }}"/>
                    </div>
                    <div class="col-md-4">
                        <x-adminlte-input name="hemoglobin" label="Hemoglobin (g/dL)" type="number" step="0.1" min="5" max="20" placeholder="e.g. 13.5" value="{{ old('hemoglobin') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <x-adminlte-input name="last_donation_date" id="lastDonationDate" label="Last Donation Date" type="date" value="{{ old('last_donation_date') }}"/>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-input name="months_ago" id="monthsAgo" label="Or Months Ago" type="number" min="0" max="120" value="0" placeholder="0"/>
                        <small class="text-muted" style="margin-top:-8px;display:block;">Set months back from today</small>
                    </div>
                    <div class="col-md-4">
                        <x-adminlte-input name="total_donations" label="Total Donations (Life Time)" type="number" min="0" value="{{ old('total_donations', 0) }}"/>
                        <small class="text-muted" style="margin-top:-8px;display:block;">How many times they donated</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Health Flags</label>
                    <div class="row">
                        @php
                            $flags = [
                                'recent_illness' => ['Recent Illness/Surgery', 'Recent illness or surgery in past 6 months', 'warning'],
                                'pregnant' => ['Pregnant / Breastfeeding', 'Currently pregnant or breastfeeding', 'danger'],
                                'recent_tattoo' => ['Recent Tattoo/Piercing', 'Tattoo or piercing in past 6 months', 'info'],
                                'medication' => ['On Medication', 'Taking restricted medications', 'secondary'],
                                'chronic_disease' => ['Chronic Disease', 'Has a chronic disease (diabetes, BP, etc.)', 'danger'],
                                'low_risk' => ['High-Risk Behavior', 'High-risk behavior flagged', 'warning'],
                            ];
                        @endphp
                        @foreach($flags as $key => $info)
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="health_flags[]" value="{{ $key }}"
                                        id="flag_{{ $key }}" {{ is_array(old('health_flags')) && in_array($key, old('health_flags')) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="flag_{{ $key }}">
                                        {{ $info[0] }}
                                        <i class="fas fa-info-circle text-muted" data-toggle="tooltip" title="{{ $info[1] }}"></i>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Education & Referral --}}
        <div class="card shadow-sm border-0 rounded-lg mb-4" style="border-top: 3px solid #ffc107 !important;">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-graduation-cap text-warning"></i> Education & Referral</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-adminlte-select name="education" label="Education">
                            <option value="">Select Education</option>
                            @foreach(['Matric', 'Intermediate', 'Bachelor\'s', 'Master\'s', 'PhD', 'Other'] as $edu)
                                <option value="{{ $edu }}" {{ old('education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="d-block">&nbsp;</label>
                            <div class="custom-control custom-switch custom-switch-off-secondary custom-switch-on-success" style="padding-top: 6px;">
                                <input type="checkbox" class="custom-control-input" name="is_student" value="1" {{ old('is_student') ? 'checked' : '' }} id="is_student">
                                <label class="custom-control-label" for="is_student">Is Student</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="university_field_wrapper" style="{{ old('is_student') ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label for="university_id">University</label>
                            <div class="input-group">
                                <select name="university_id" id="university_id" class="form-control">
                                    <option value="">Select University</option>
                                    @foreach($universities as $uni)
                                        <option value="{{ $uni->id }}" {{ old('university_id') == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                                    @endforeach
                                    <option value="new" {{ old('university_id') == 'new' ? 'selected' : '' }}>&#10133; Other (add new)</option>
                                </select>
                                <div class="input-group-append">
                                    <a href="{{ route('admin.universities.create') }}" class="btn btn-outline-primary" target="_blank"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <input type="text" name="university_name" id="university_name_input" class="form-control mt-2" placeholder="Enter university name" value="{{ old('university_name') }}" style="{{ old('university_id') == 'new' ? '' : 'display:none;' }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label class="d-block">Referred By <small class="text-muted">(search by CNIC or Phone)</small></label>
                        <div class="input-group">
                            <input type="text" id="refSearch" class="form-control" placeholder="Search by CNIC or Phone (min 3 chars)" value="{{ old('ref_search') }}">
                            <div class="input-group-append">
                                <button type="button" id="refSearchBtn" class="btn btn-success"><i class="fas fa-search"></i> Search</button>
                            </div>
                        </div>
                        <div id="refResults" class="mt-2" style="display:none;"></div>
                        <div id="refSelected" class="mt-2 p-3 bg-light rounded border" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1A6B2E,#28a745);display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;"><i class="fas fa-user"></i></div>
                                    <div>
                                        <strong id="refSelectedName" style="font-size:14px;"></strong>
                                        <div id="refSelectedDetail" style="font-size:12px;color:#888;"></div>
                                    </div>
                                </div>
                                <button type="button" id="refChangeBtn" class="btn btn-sm btn-outline-warning"><i class="fas fa-sync-alt"></i> Change</button>
                            </div>
                        </div>
                        <input type="hidden" name="referred_by" id="referredByInput" value="{{ old('referred_by') }}">
                        <input type="hidden" name="no_referral" id="noReferralInput" value="{{ old('no_referral') ? '1' : '0' }}">
                    </div>
                    <div class="col-md-4" style="padding-top:38px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="no_referral_check" id="noReferral" value="1" {{ old('no_referral') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="noReferral"><i class="fas fa-ban text-muted"></i> No Referred Donor</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Photo --}}
        <div class="card shadow-sm border-0 rounded-lg mb-4" style="border-top: 3px solid #6f42c1 !important;">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-camera text-purple"></i> Photo</h5></div>
            <div class="card-body">
                <div class="form-group">
                    <div class="mb-2">
                        <img id="photoPreview" src="#" alt="Preview" class="img-thumbnail d-none" width="120" height="120" style="object-fit:cover;">
                    </div>
                    <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*">
                    <small class="text-muted">Photo will be cropped to 300x300px automatically.</small>
                </div>
            </div>
        </div>

        <div class="text-right mb-4">
            <x-adminlte-button type="submit" label="Save Donor" theme="success" icon="fas fa-save"/>
            <a href="{{ route('admin.donors.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>

    {{-- Add City Modal --}}
    <div class="modal fade" id="addCityModal" tabindex="-1">...</div>
    {{-- Add Area Modal --}}
    <div class="modal fade" id="addAreaModal" tabindex="-1">...</div>
@stop

@section('js')
<script>
$(function() {
    $('[data-toggle="tooltip"]').tooltip();

    $('#photo').on('change', function(e) {
        var reader = new FileReader();
        reader.onload = function(e) { $('#photoPreview').attr('src', e.target.result).removeClass('d-none'); };
        if (this.files && this.files[0]) reader.readAsDataURL(this.files[0]);
    });

    function loadAreas(cityId, selectedAreaId) {
        if (!cityId) { $('#area_id').html('<option value="">Select Area</option>'); return; }
        $.get('{{ url("admin/areas/by-city") }}/' + cityId, function(data) {
            var html = '<option value="">Select Area</option>';
            $.each(data, function(i, area) { html += '<option value="' + area.id + '"' + (selectedAreaId == area.id ? ' selected' : '') + '>' + area.name + '</option>'; });
            $('#area_id').html(html);
        });
    }
    $('#city_id').on('change', function() { loadAreas($(this).val(), null); });
    @if(old('city_id')) loadAreas({{ old('city_id') }}, {{ old('area_id') ?? 'null' }}); @endif

    $('#is_student').on('change', function() {
        if ($(this).is(':checked')) $('#university_field_wrapper').slideDown(200);
        else { $('#university_field_wrapper').slideUp(200); $('#university_field_wrapper input').val(''); $('#university_id').val(''); }
    });

    $('#university_id').on('change', function() {
        var $input = $('#university_name_input');
        if ($(this).val() === 'new') { $input.show().prop('disabled', false); }
        else { $input.hide().val('').prop('disabled', true); }
    });

    {{-- Duplicate detection --}}
    var _duplicateFound = false;

    function checkDuplicate(callback) {
        var cnic = $('#cnic').val().trim();
        var phone = $('#phone').val().trim();
        if (!cnic && !phone) { _duplicateFound = false; if(callback) callback(); return; }
        $.getJSON('{{ route("admin.donors.check-duplicate") }}', { cnic: cnic, phone: phone }, function(res) {
            if (res.duplicate) {
                var d = res.donor;
                _duplicateFound = true;
                $('#duplicateWarning').removeClass('d-none');
                $('#duplicateMsg').html('Potential duplicate found: <strong>' + d.name + '</strong> (CNIC: ' + d.cnic + ', Phone: ' + d.phone + '). ' +
                    '<a href="{{ url("admin/donors") }}/' + d.id + '" target="_blank" class="alert-link">View existing donor</a>');
            } else {
                _duplicateFound = false;
                $('#duplicateWarning').addClass('d-none');
            }
            if (callback) callback();
        });
    }
    $('#cnic, #phone').on('blur', function() { checkDuplicate(); });

    $('form').on('submit', function(e) {
        if (_duplicateFound) {
            e.preventDefault();
            toastr.error('Please review the duplicate warning before submitting.', 'Duplicate Detected');
            return false;
        }
    });

    function monthsBetween(d1, d2) {
        return Math.max(0, (d2.getFullYear() - d1.getFullYear()) * 12 + (d2.getMonth() - d1.getMonth()));
    }

    $('#monthsAgo').on('input', function() {
        var val = parseInt($(this).val()) || 0;
        var d = new Date();
        d.setMonth(d.getMonth() - val);
        $('#lastDonationDate').val(d.toISOString().split('T')[0]);
    });

    $('#lastDonationDate').on('change', function() {
        var val = $(this).val();
        if (val) {
            var d = new Date(val + 'T00:00:00');
            var today = new Date();
            today.setHours(0,0,0,0);
            if (d <= today) $('#monthsAgo').val(monthsBetween(d, today));
        }
    });

    {{-- Referrer search --}}
    var referredById = {{ old('referred_by') ?: 'null' }};
    if (referredById) {
        var dn = $('#referredByInput').data('name');
    }

    $('#refSearchBtn').on('click', function() { searchReferrer(); });
    $('#refSearch').on('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchReferrer(); } });

    function searchReferrer() {
        var q = $('#refSearch').val().trim();
        if (q.length < 3) return;
        $.get('{{ route("admin.donors.search-referrer") }}', { q: q }, function(data) {
            var $res = $('#refResults');
            $res.empty().show();
            if (data.length === 0) {
                $res.html('<div class="text-muted small py-2"><i class="fas fa-search"></i> No donor found with this CNIC or Phone.</div>');
                return;
            }
            $.each(data, function(i, d) {
                $res.append(
                    '<div class="ref-result-item p-2 border rounded mb-1" style="cursor:pointer;" data-id="' + d.id + '" data-name="' + d.name + '" data-cnic="' + d.cnic + '" data-phone="' + d.phone + '" data-blood="' + (d.blood_group || '') + '">' +
                    '<strong style="font-size:14px;">' + d.name + '</strong>' +
                    '<div style="font-size:12px;color:#888;">CNIC: ' + d.cnic + ' \u00B7 Phone: ' + d.phone + (d.blood_group ? ' \u00B7 ' + d.blood_group : '') + '</div>' +
                    '</div>'
                );
            });
            $('.ref-result-item').on('click', function() {
                selectReferrer($(this).data('id'), $(this).data('name'), $(this).data('cnic'), $(this).data('phone'), $(this).data('blood'));
            });
        });
    }

    function selectReferrer(id, name, cnic, phone, blood) {
        referredById = id;
        $('#referredByInput').val(id);
        $('#refSearch').val('');
        $('#refResults').hide().empty();
        $('#refSelected').show();
        $('#refSelectedName').text(name);
        $('#refSelectedDetail').text('CNIC: ' + cnic + ' \u00B7 Phone: ' + phone + (blood ? ' \u00B7 ' + blood : ''));
        $('#noReferral').prop('checked', false);
        $('#noReferralInput').val('0');
    }

    $('#refChangeBtn').on('click', function() {
        referredById = null;
        $('#referredByInput').val('');
        $('#refSelected').hide();
        $('#refSearch').focus();
    });

    $('#noReferral').on('change', function() {
        if ($(this).is(':checked')) {
            referredById = null;
            $('#referredByInput').val('');
            $('#refSelected').hide();
            $('#refResults').hide().empty();
            $('#refSearch').val('').prop('disabled', true);
            $('#refSearchBtn').prop('disabled', true);
            $('#noReferralInput').val('1');
        } else {
            $('#refSearch').prop('disabled', false);
            $('#refSearchBtn').prop('disabled', false);
            $('#noReferralInput').val('0');
        }
    });

    {{-- Add City --}}
    $('#addCityForm').on('submit', function(e) { e.preventDefault(); /* TODO */ });
    $('#addAreaForm').on('submit', function(e) { e.preventDefault(); /* TODO */ });
    $('#addCityModal, #addAreaModal').on('hidden.bs.modal', function() { $(this).find('input[type="text"], select').val(''); });
});
</script>
@stop