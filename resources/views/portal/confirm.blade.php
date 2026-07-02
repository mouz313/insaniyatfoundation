@extends('adminlte::master')

@section('title', 'Registration Confirmed')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        body { background: linear-gradient(135deg, #28a745, #20c997); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .confirm-card { background: #fff; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); max-width: 500px; width: 100%; margin: 20px; padding: 40px; text-align: center; }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; background: #28a745; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .icon-circle i { font-size: 36px; color: #fff; }
    </style>
@stop

@section('body')
    <div class="confirm-card">
        <div class="icon-circle"><i class="fas fa-check"></i></div>
        <h2 class="font-weight-bold text-success mb-1">Registration Submitted!</h2>
        <p class="text-muted mb-4">Thank you for registering as a blood donor.</p>
        <div class="bg-light rounded-lg p-3 mb-4">
            <p class="mb-1"><strong>Name:</strong> {{ $donor->name }}</p>
            <p class="mb-1"><strong>Blood Group:</strong> {{ $donor->blood_group }}</p>
            <p class="mb-1"><strong>Registration No:</strong> <code>{{ $donor->registration_no ?? 'Pending' }}</code></p>
            <p class="mb-0"><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
        </div>
        <p class="text-muted" style="font-size:14px;">Our team will contact you for donation drives. You can also visit our center anytime.</p>
        <a href="{{ url('/') }}" class="btn btn-success px-4 rounded-pill"><i class="fas fa-home mr-1"></i>Go Home</a>
    </div>
@stop