<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer');

        if ($causerId = $request->get('causer_id')) {
            $query->where('causer_id', $causerId);
        }

        if ($event = $request->get('event')) {
            $query->where('event', $event);
        }

        if ($description = $request->get('description')) {
            $query->where('description', 'like', "%{$description}%");
        }

        if ($logName = $request->get('log_name')) {
            $query->where('log_name', $logName);
        }

        if ($fromDate = $request->get('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate = $request->get('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $logs = $query->latest()->paginate(50);

        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('admin.audit-logs.index', compact('logs', 'users'));
    }
}
