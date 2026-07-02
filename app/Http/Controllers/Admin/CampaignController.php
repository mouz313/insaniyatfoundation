<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        $total = Campaign::count();
        $upcoming = Campaign::where('date', '>=', now())->count();
        $past = Campaign::where('date', '<', now())->count();
        $totalTargetUnits = Campaign::sum('target_units');

        $campaigns = $query->latest()->paginate(20);

        return view('admin.campaigns.index', compact('campaigns', 'total', 'upcoming', 'past', 'totalTargetUnits'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'target_units' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    public function show($id)
    {
        $campaign = Campaign::with(['donations.donor', 'moneyDonations.donor'])->findOrFail($id);
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'target_units' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
