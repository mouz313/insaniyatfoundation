@extends('portal.layout')

@section('title', 'Emergency Blood Request')

@section('content')
<div class="container py-5" style="max-width: 760px;">
    <div class="text-center mb-4">
        <div style="width:70px;height:70px;border-radius:18px;background:linear-gradient(135deg,#dc3545,#e74c3c);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 8px 25px rgba(220,53,69,0.3);">
            <i class="fas fa-hand-holding-medical text-white" style="font-size:32px;"></i>
        </div>
        <h1 class="font-weight-bold" style="font-size:2.2rem;color:#1a202c;">Emergency Blood Request</h1>
        <p class="text-muted">Fill out the details below. Our team will verify and immediately dispatch requests to matching donors.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-lg shadow-sm mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-lg overflow-hidden" style="border-radius:18px;">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <span class="font-weight-bold text-danger"><i class="fas fa-heartbeat mr-1"></i> Patient & Hospital Info</span>
                <span class="badge badge-danger px-3 py-1" style="border-radius:20px;">Emergency</span>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('portal.blood-request.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small">Patient Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" class="form-control" required placeholder="e.g. Muhammad Ali" value="{{ old('patient_name') }}" style="border-radius:10px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small">Blood Group Needed <span class="text-danger">*</span></label>
                        <select name="blood_group" class="form-control" required style="border-radius:10px;">
                            <option value="">Select Blood Group</option>
                            @foreach($bloodGroups as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small">Units (Bags) Required <span class="text-danger">*</span></label>
                        <input type="number" name="units_required" class="form-control" required min="1" max="20" value="{{ old('units_required', 1) }}" style="border-radius:10px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small">City <span class="text-danger">*</span></label>
                        <select name="city_id" class="form-control" required style="border-radius:10px;">
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="font-weight-bold text-muted small">Hospital / Medical Center Name & Ward <span class="text-danger">*</span></label>
                        <input type="text" name="hospital" class="form-control" required placeholder="e.g. Services Hospital Lahore, Emergency Ward Room 4" value="{{ old('hospital') }}" style="border-radius:10px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small">Attendant / Contact Person Name <span class="text-danger">*</span></label>
                        <input type="text" name="contact_name" class="form-control" required placeholder="e.g. Tariq Mehmood" value="{{ old('contact_name') }}" style="border-radius:10px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small">Contact Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_phone" class="form-control" required placeholder="e.g. 03001234567" value="{{ old('contact_phone') }}" style="border-radius:10px;">
                    </div>
                </div>

                <div class="p-3 mb-4 rounded" style="background:#fff3cd;border:1px solid #ffeeba;color:#856404;font-size:13px;">
                    <i class="fas fa-info-circle mr-1"></i> <strong>Note:</strong> All blood donations coordinated through {{ $ngoName }} are 100% voluntary and completely free of charge. Never pay anyone for donor coordination.
                </div>

                <button type="submit" class="btn btn-danger btn-block py-3 font-weight-bold" style="border-radius:12px;font-size:16px;box-shadow:0 4px 15px rgba(220,53,69,0.3);">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Emergency Blood Request
                </button>
            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted small">
            Need urgent assistance? Call our 24/7 Helpline: 
            <a href="tel:{{ $ngoPhone }}" class="font-weight-bold text-danger">{{ $ngoPhone }}</a>
        </p>
    </div>
</div>
@stop
