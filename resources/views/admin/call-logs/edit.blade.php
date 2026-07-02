@extends('adminlte::page')

@section('title', 'Edit Call Log')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #ffc107, #ffd54f); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255,193,7,0.3);">
            <i class="fas fa-phone text-white" style="font-size: 20px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Edit Call Log #{{ $callLog->id }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #ffc107;">
                <div class="card-body">
                    <form action="{{ route('admin.call-logs.update', $callLog->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Blood Request <span class="text-danger">*</span></label>
                            <select name="blood_request_id" class="form-control" required>
                                <option value="">Select Blood Request</option>
                                @foreach($bloodRequests as $request)
                                    <option value="{{ $request->id }}" {{ old('blood_request_id', $callLog->blood_request_id) == $request->id ? 'selected' : '' }}>
                                        #{{ $request->id }} - {{ $request->patient_name }} ({{ $request->blood_group }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Staff Member <span class="text-danger">*</span></label>
                            <select name="staff_id" class="form-control" required>
                                <option value="">Select Staff</option>
                                @foreach($staff as $staffMember)
                                    <option value="{{ $staffMember->id }}" {{ old('staff_id', $callLog->staff_id) == $staffMember->id ? 'selected' : '' }}>{{ $staffMember->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Outcome <span class="text-danger">*</span></label>
                            <select name="outcome" class="form-control" required>
                                <option value="">Select Outcome</option>
                                <option value="success" {{ old('outcome', $callLog->outcome) == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="pending" {{ old('outcome', $callLog->outcome) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ old('outcome', $callLog->outcome) == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="donor_found" {{ old('outcome', $callLog->outcome) == 'donor_found' ? 'selected' : '' }}>Donor Found</option>
                                <option value="not_answered" {{ old('outcome', $callLog->outcome) == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="4" placeholder="Enter call notes">{{ old('notes', $callLog->notes) }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Call Log</button>
                            <a href="{{ route('admin.call-logs.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
</style>
@stop
