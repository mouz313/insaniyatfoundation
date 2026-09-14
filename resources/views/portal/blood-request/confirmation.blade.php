@extends('portal.layout')

@section('title', 'Blood Request Received')

@section('content')
<div class="container py-5" style="max-width: 620px;">
    <div class="card shadow-sm border-0 rounded-lg text-center p-4 p-md-5" style="border-radius:20px;">
        <div style="width:84px;height:84px;border-radius:50%;background:rgba(40,167,69,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="fas fa-check-circle text-success" style="font-size:48px;"></i>
        </div>

        <h2 class="font-weight-bold mb-2" style="font-size:26px;color:#1a202c;">Request Received!</h2>
        <p class="text-muted">Your emergency blood request has been successfully registered in the {{ $ngoName }} system.</p>

        <div class="p-3 my-3 text-left" style="background:#f8f9fa;border-radius:14px;border:1px solid #edf2f7;font-size:14px;">
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Tracking Code</span>
                <span class="font-weight-bold font-monospace text-danger">REQ-{{ str_pad($bloodRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Patient Name</span>
                <span class="font-weight-bold">{{ $bloodRequest->patient_name }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Blood Group</span>
                <span class="badge badge-danger" style="font-size:14px;">{{ $bloodRequest->blood_group }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Required Units</span>
                <span class="font-weight-bold">{{ $bloodRequest->units_required }} Unit(s)</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Hospital</span>
                <span class="font-weight-bold">{{ $bloodRequest->hospital }}</span>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Current Status</span>
                <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Under Coordination</span>
            </div>
        </div>

        <p class="text-muted small mt-2">
            Our donor coordinator team has received your request and will contact matching donors in {{ $bloodRequest->city->name ?? 'your city' }}.
        </p>

        <div class="mt-3">
            <a href="tel:{{ $ngoPhone }}" class="btn btn-outline-danger btn-block py-2 mb-2 font-weight-bold" style="border-radius:10px;">
                <i class="fas fa-phone-alt mr-2"></i> Call Helpline for Immediate Verification
            </a>
            <a href="{{ route('portal.landing') }}" class="btn btn-light btn-block py-2" style="border-radius:10px;">
                Back to Home
            </a>
        </div>
    </div>
</div>
@stop
