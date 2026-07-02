@extends('portal.layout')

@section('title', 'Donor Verification')

@push('css')
<style>
@media print {
    nav, footer, .no-print { display: none !important; }
    main { padding: 0 !important; }
    main > .container { max-width: 100% !important; padding: 0 !important; }
    .card { border: 2px solid #28a745 !important; box-shadow: none !important; }
}
</style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="text-center mb-5 no-print">
            <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#28a745,#34ce57);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 10px 30px rgba(40,167,69,0.3);">
                <i class="fas fa-id-card text-white" style="font-size:36px;"></i>
            </div>
            <h1 style="font-weight:800;color:#1a1a2e;font-size:2.2rem;">Verify a Donor</h1>
            <p class="text-muted" style="font-size:1.05rem;">Search by name, phone, or CNIC to verify a donor's registration</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-lg shadow-sm no-print" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded-lg mb-4 no-print" style="border-top:4px solid #28a745;">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('portal.verify') }}">
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <label class="font-weight-bold text-muted small">Search by Name, Phone, or CNIC</label>
                            <input type="text" name="query" class="form-control rounded-pill" placeholder="e.g. John, 03XX, XXXXX-XXXXXXX-X" value="{{ request('query') }}" style="border-color:#dee2e6;">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn-block rounded-pill py-2" style="font-weight:600;">
                                <i class="fas fa-search mr-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($donor))
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden" style="border-left:4px solid #28a745;">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div style="width:160px;height:160px;border-radius:50%;background:linear-gradient(135deg,#e9ecef,#dee2e6);margin:0 auto;display:flex;align-items:center;justify-content:center;overflow:hidden;border:4px solid #28a745;">
                                @if($donor->photo)
                                    <img src="{{ asset('storage/' . $donor->photo) }}" alt="Photo" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <span style="font-size:48px;font-weight:700;color:#6c757d;">{{ strtoupper(substr($donor->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <span class="badge rounded-pill px-4 py-2" style="background:#28a745;color:#fff;font-size:15px;font-weight:600;">
                                    <i class="fas fa-check-circle mr-1"></i> Verified Donor
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="badge rounded-pill px-3 py-1" style="background:#dc3545;color:#fff;font-size:14px;">
                                    <i class="fas fa-tint mr-1"></i> {{ $donor->blood_group }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3" style="font-weight:700;">{{ $donor->name }}</h4>
                            <div class="row" style="font-size:14px;">
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block"><i class="fas fa-fw fa-id-card mr-1"></i> Registration No</small>
                                    <strong>{{ $donor->registration_no ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block"><i class="fas fa-fw fa-phone mr-1"></i> Phone</small>
                                    <strong>{{ $donor->phone }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block"><i class="fas fa-fw fa-calendar mr-1"></i> Last Donation</small>
                                    <strong>{{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y') : 'Never' }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block"><i class="fas fa-fw fa-map-marker-alt mr-1"></i> City</small>
                                    <strong>{{ $donor->city->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block"><i class="fas fa-fw fa-heart mr-1"></i> Total Donations</small>
                                    <strong>{{ $donor->total_donations }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block"><i class="fas fa-fw fa-check-circle mr-1"></i> Status</small>
                                    <span class="badge badge-success rounded-pill px-3 py-1">{{ ucfirst($donor->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4 no-print">
                        <button onclick="window.print()" class="btn btn-success rounded-pill px-4 py-2" style="font-weight:600;">
                            <i class="fas fa-print mr-1"></i> Print / Download
                        </button>
                        <a href="{{ route('portal.verify', 0) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 ml-2" style="font-weight:600;">
                            <i class="fas fa-search mr-1"></i> New Search
                        </a>
                    </div>
                </div>
            </div>
        @elseif(request()->has('query') && !session('error'))
            <div class="card shadow-sm border-0 rounded-lg no-print">
                <div class="card-body text-center py-5">
                    <div style="width:80px;height:80px;border-radius:50%;background:#f8f9fa;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-search text-muted" style="font-size:32px;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#495057;">No Donor Found</h5>
                    <p class="text-muted mb-0">Try a different name, phone, or CNIC.</p>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0 rounded-lg no-print" style="border:2px dashed #dee2e6;">
                <div class="card-body text-center py-5">
                    <div style="width:80px;height:80px;border-radius:50%;background:#f8f9fa;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-id-card text-muted" style="font-size:32px;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#495057;">Search for a Donor</h5>
                    <p class="text-muted mb-0">Enter a name, phone number, or CNIC above and click Search.</p>
                </div>
            </div>
        @endif
    </div>
@endsection