@extends('adminlte::page')

@section('title', 'Edit Blood Request')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(231,76,60,0.3);">
            <i class="fas fa-hand-holding-heart text-white" style="font-size: 22px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Edit Blood Request #{{ $bloodRequest->id }}</h1>
            <small class="text-muted">Update patient blood request details</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible rounded-lg">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-circle mr-1"></i> Please fix the following errors:
                            <ul class="mt-1 mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.blood-requests.update', $bloodRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Patient Name <span class="text-danger">*</span></label>
                                    <input type="text" name="patient_name" class="form-control" placeholder="Enter patient name" value="{{ old('patient_name', $bloodRequest->patient_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hospital <span class="text-danger">*</span></label>
                                    <input type="text" name="hospital" class="form-control" placeholder="Enter hospital name" value="{{ old('hospital', $bloodRequest->hospital) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Blood Group <span class="text-danger">*</span></label>
                                    <select name="blood_group" class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group', $bloodRequest->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
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
                                            <option value="{{ $city->id }}" {{ old('city_id', $bloodRequest->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Units Required <span class="text-danger">*</span></label>
                                    <input type="number" name="units_required" class="form-control" min="1" value="{{ old('units_required', $bloodRequest->units_required) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Person <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" class="form-control" placeholder="Enter contact name" value="{{ old('contact_name', $bloodRequest->contact_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_phone" class="form-control" placeholder="03XX-XXXXXXX" value="{{ old('contact_phone', $bloodRequest->contact_phone) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="pending" {{ old('status', $bloodRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="resolved" {{ old('status', $bloodRequest->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ old('status', $bloodRequest->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="text-right">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Request</button>
                            <a href="{{ route('admin.blood-requests.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
