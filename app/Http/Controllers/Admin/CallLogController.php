<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;

class CallLogController extends Controller
{
    public function index(Request $request)
    {
        $query = CallLog::with('bloodRequest', 'staff');

        if ($outcome = $request->get('outcome')) {
            $query->where('outcome', $outcome);
        }

        if ($bloodRequestId = $request->get('blood_request_id')) {
            $query->where('blood_request_id', $bloodRequestId);
        }

        if ($staffId = $request->get('staff_id')) {
            $query->where('staff_id', $staffId);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%");
            });
        }

        $callLogs = $query->latest()->paginate(20);
        $staff = User::orderBy('name')->get();

        $totalCallLogs = CallLog::count();
        $successCount = CallLog::where('outcome', 'success')->count();
        $pendingCount = CallLog::where('outcome', 'pending')->count();
        $failedCount = CallLog::where('outcome', 'failed')->count();

        return view('admin.call-logs.index', compact('callLogs', 'staff', 'totalCallLogs', 'successCount', 'pendingCount', 'failedCount'));
    }

    public function create()
    {
        $bloodRequests = BloodRequest::orderBy('created_at', 'desc')->get();
        $staff = User::orderBy('name')->get();
        $donors = Donor::orderBy('name')->get();
        return view('admin.call-logs.create', compact('bloodRequests', 'staff', 'donors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'donor_id' => 'nullable|exists:donors,id',
            'staff_id' => 'required|exists:users,id',
            'outcome' => 'required|in:success,failed,pending,donor_found,not_answered',
            'notes' => 'nullable|string',
        ]);

        CallLog::create($data);

        return redirect()->route('admin.call-logs.index')
            ->with('success', 'Call log created successfully.');
    }

    public function edit($id)
    {
        $callLog = CallLog::findOrFail($id);
        $bloodRequests = BloodRequest::orderBy('created_at', 'desc')->get();
        $staff = User::orderBy('name')->get();
        return view('admin.call-logs.edit', compact('callLog', 'bloodRequests', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $callLog = CallLog::findOrFail($id);

        $data = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'donor_id' => 'nullable|exists:donors,id',
            'staff_id' => 'required|exists:users,id',
            'outcome' => 'required|in:success,failed,pending,donor_found,not_answered',
            'notes' => 'nullable|string',
        ]);

        $callLog->update($data);

        return redirect()->route('admin.call-logs.index')
            ->with('success', 'Call log updated successfully.');
    }

    public function destroy($id)
    {
        $callLog = CallLog::findOrFail($id);
        $callLog->delete();

        return redirect()->route('admin.call-logs.index')
            ->with('success', 'Call log deleted successfully.');
    }
}
