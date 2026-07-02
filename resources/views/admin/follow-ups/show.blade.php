@extends('adminlte::page')

@section('title', 'Follow-up Details')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #17a2b8, #5dd0e6); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(23,162,184,0.3);">
            <i class="fas fa-bell text-white" style="font-size: 20px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Follow-up #{{ $followUp->id }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Donor</th>
                            <td>
                                <a href="{{ route('admin.donors.show', $followUp->donor_id) }}">
                                    {{ $followUp->donor->name ?? 'N/A' }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>
                                @if($followUp->type == 're_engagement')
                                    <span class="badge badge-info">Re-engagement</span>
                                @elseif($followUp->type == 'eligible_reminder')
                                    <span class="badge badge-success">Eligible Reminder</span>
                                @else
                                    <span class="badge badge-warning">Call Back</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($followUp->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($followUp->status == 'skipped')
                                    <span class="badge badge-danger">Skipped</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Scheduled At</th>
                            <td>{{ $followUp->scheduled_at->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Completed At</th>
                            <td>{{ $followUp->completed_at?->format('d M Y h:i A') ?? 'Not completed' }}</td>
                        </tr>
                        <tr>
                            <th>Completed By</th>
                            <td>{{ $followUp->completer->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>{{ $followUp->notes ?? 'No notes' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $followUp->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $followUp->updated_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('admin.follow-ups.edit', $followUp->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('admin.follow-ups.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table th { font-weight: 600; }
</style>
@stop
