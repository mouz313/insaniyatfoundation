@extends('portal.layout')

@section('title', 'Registration Confirmed')

@push('css')
<style>
    .confirm-wrapper { min-height: calc(100vh - 140px); display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
    .confirm-card { background: #fff; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.12); max-width: 480px; width: 100%; padding: 48px 36px; text-align: center; position: relative; overflow: hidden; }
    .confirm-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient-primary); }
    .icon-circle { width: 88px; height: 88px; border-radius: 50%; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 10px 30px rgba(26,107,46,0.3); animation: successPop 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards; }
    .icon-circle i { font-size: 40px; color: #fff; }
    .detail-box { background: #f8f9fa; border-radius: 16px; padding: 20px; margin: 24px 0; text-align: left; }
    .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 14px; }
    .detail-row:not(:last-child) { border-bottom: 1px solid #eee; }
    .detail-label { color: #888; }
    .detail-value { font-weight: 600; color: var(--secondary); }
    .badge-success { background: var(--gradient-primary); color: #fff; border-radius: 50px; padding: 2px 12px; font-size: 12px; font-weight: 600; }

    @keyframes successPop {
        0% { transform: scale(0) rotate(-30deg); opacity: 0; }
        60% { transform: scale(1.15) rotate(3deg); opacity: 1; }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .confirm-card > * { animation: fadeSlideUp 0.5s ease forwards; opacity: 0; }
    .confirm-card > *:nth-child(1) { animation-delay: 0.1s; }
    .confirm-card > *:nth-child(2) { animation-delay: 0.2s; }
    .confirm-card > *:nth-child(3) { animation-delay: 0.35s; }
    .confirm-card > *:nth-child(4) { animation-delay: 0.5s; }
    .confirm-card > *:nth-child(5) { animation-delay: 0.65s; }
    .confirm-card > *:nth-child(6) { animation-delay: 0.8s; }
</style>
@endpush

@section('content')
    <div class="confirm-wrapper">
        <div class="confirm-card">
            <div class="icon-circle"><i class="fas fa-check"></i></div>
            <h2 style="font-weight:800;color:var(--secondary);margin-bottom:4px;font-size:1.8rem;">Registration Submitted!</h2>
            <p style="color:#888;font-size:15px;">Thank you for registering as a blood donor.</p>
            <div class="detail-box">
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-user me-2" style="color:var(--primary);width:16px;"></i>Name</span>
                    <span class="detail-value">{{ $donor->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-tint me-2" style="color:var(--primary);width:16px;"></i>Blood Group</span>
                    <span class="detail-value">{{ $donor->blood_group }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-id-card me-2" style="color:var(--primary);width:16px;"></i>Registration No</span>
                    <span class="detail-value"><code style="background:#e9ecef;padding:2px 8px;border-radius:6px;font-size:13px;">{{ $donor->registration_no ?? 'Pending' }}</code></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-check-circle me-2" style="color:var(--primary);width:16px;"></i>Status</span>
                    <span class="badge-success">Active</span>
                </div>
            </div>
            <p style="color:#999;font-size:14px;margin-bottom:24px;">Our team will contact you for donation drives. You can also visit our center anytime.</p>
            <a href="{{ url('/') }}" class="btn-custom-primary"><i class="fas fa-home me-2"></i>Go Home</a>
        </div>
    </div>
@endsection
