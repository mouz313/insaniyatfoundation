<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        $query = FollowUp::with('donor', 'completer');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('donor', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $followUps = $query->latest()->paginate(20);

        $stats = FollowUp::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_count,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as skipped_count
        ', ['pending', 'completed', 'skipped'])->first();
        $total = $stats->total;
        $pendingCount = $stats->pending_count;
        $completedCount = $stats->completed_count;
        $skippedCount = $stats->skipped_count;

        return view('admin.follow-ups.index', compact('followUps', 'total', 'pendingCount', 'completedCount', 'skippedCount'));
    }

    public function create()
    {
        $donors = Donor::orderBy('name')->get();
        return view('admin.follow-ups.create', compact('donors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'type' => 'required|in:re_engagement,eligible_reminder,call_back',
            'notes' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:pending,completed,skipped',
            'completed_at' => 'nullable|date',
            'completed_by' => 'nullable|exists:users,id',
        ]);

        FollowUp::create($data);

        return redirect()->route('admin.follow-ups.index')
            ->with('success', 'Follow-up created successfully.');
    }

    public function show($id)
    {
        $followUp = FollowUp::with('donor', 'completer')->findOrFail($id);
        return view('admin.follow-ups.show', compact('followUp'));
    }

    public function edit($id)
    {
        $followUp = FollowUp::findOrFail($id);
        $donors = Donor::orderBy('name')->get();
        $staff = User::orderBy('name')->get();
        return view('admin.follow-ups.edit', compact('followUp', 'donors', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $followUp = FollowUp::findOrFail($id);

        $data = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'type' => 'required|in:re_engagement,eligible_reminder,call_back',
            'notes' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:pending,completed,skipped',
            'completed_at' => 'nullable|date',
            'completed_by' => 'nullable|exists:users,id',
        ]);

        $followUp->update($data);

        return redirect()->route('admin.follow-ups.index')
            ->with('success', 'Follow-up updated successfully.');
    }

    public function destroy($id)
    {
        $followUp = FollowUp::findOrFail($id);
        $followUp->delete();

        return redirect()->route('admin.follow-ups.index')
            ->with('success', 'Follow-up deleted successfully.');
    }
}
