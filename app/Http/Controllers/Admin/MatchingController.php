<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\CallLog;
use App\Models\Donor;
use App\Services\MatchingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MatchingController extends Controller
{
    public function index(BloodRequest $bloodRequest, MatchingService $matchingService)
    {
        $donors = $matchingService->findMatches($bloodRequest);

        return view('admin.blood-requests.matching', compact('bloodRequest', 'donors'));
    }

    public function logCall(Request $request, BloodRequest $bloodRequest, Donor $donor)
    {
        $request->validate([
            'outcome' => 'required|in:donor_found,no_answer,refused,call_back',
            'notes' => 'nullable|string|max:500',
        ]);

        CallLog::create([
            'blood_request_id' => $bloodRequest->id,
            'donor_id' => $donor->id,
            'staff_id' => auth()->id(),
            'outcome' => $request->outcome,
            'notes' => $request->notes,
        ]);

        if ($request->outcome === 'donor_found') {
            $bloodRequest->update(['status' => 'resolved']);
        }

        activity()->causedBy(auth()->user())
            ->performedOn($donor)
            ->withProperties([
                'blood_request_id' => $bloodRequest->id,
                'outcome' => $request->outcome,
            ])
            ->log('call_logged');

        return redirect()->back()->with('success', 'Call logged successfully.');
    }
}
