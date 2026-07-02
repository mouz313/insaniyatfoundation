@extends('adminlte::page')

@section('title', 'Edit Follow-up')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #ffc107, #ffd54f); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255,193,7,0.3);">
            <i class="fas fa-bell text-white" style="font-size: 20px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Edit Follow-up #{{ $followUp->id }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #ffc107;">
                <div class="card-body">
                    <form action="{{ route('admin.follow-ups.update', $followUp->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Donor <span class="text-danger">*</span></label>
                            <select name="donor_id" class="form-control" required>
                                <option value="">Select Donor</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" {{ old('donor_id', $followUp->donor_id) == $donor->id ? 'selected' : '' }}>{{ $donor->name }} ({{ $donor->blood_group }} - {{ $donor->phone }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="re_engagement" {{ old('type', $followUp->type) == 're_engagement' ? 'selected' : '' }}>Re-engagement</option>
                                <option value="eligible_reminder" {{ old('type', $followUp->type) == 'eligible_reminder' ? 'selected' : '' }}>Eligible Reminder</option>
                                <option value="call_back" {{ old('type', $followUp->type) == 'call_back' ? 'selected' : '' }}>Call Back</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status', $followUp->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status', $followUp->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="skipped" {{ old('status', $followUp->status) == 'skipped' ? 'selected' : '' }}>Skipped</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Scheduled At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $followUp->scheduled_at->format('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Completed At</label>
                            <input type="datetime-local" name="completed_at" class="form-control" value="{{ old('completed_at', $followUp->completed_at?->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="form-group">
                            <label>Completed By</label>
                            <select name="completed_by" class="form-control">
                                <option value="">Select Staff</option>
                                @foreach($staff as $staffMember)
                                    <option value="{{ $staffMember->id }}" {{ old('completed_by', $followUp->completed_by) == $staffMember->id ? 'selected' : '' }}>{{ $staffMember->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="4" placeholder="Enter follow-up notes">{{ old('notes', $followUp->notes) }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Follow-up</button>
                            <a href="{{ route('admin.follow-ups.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
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
