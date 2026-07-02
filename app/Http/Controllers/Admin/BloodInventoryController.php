<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BloodInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodInventory::with('campaign', 'creator');

        if ($bloodGroup = $request->get('blood_group')) {
            $query->where('blood_group', $bloodGroup);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('batch_no', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(20);

        $stockByGroup = Cache::remember('blood-inventory.stock', 300, function () {
            return BloodInventory::stockByGroup();
        });
        $lowStock = BloodInventory::lowStockGroups(5, $stockByGroup);
        $totalAvailable = array_sum($stockByGroup);

        return view('admin.blood-inventory.index', compact('items', 'stockByGroup', 'lowStock', 'totalAvailable'));
    }

    public function create()
    {
        $campaigns = Campaign::orderBy('name')->get();
        return view('admin.blood-inventory.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units' => 'required|integer|min:1',
            'batch_no' => 'nullable|string|max:255',
            'received_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:received_date',
            'source' => 'nullable|string|max:255',
            'source_campaign_id' => 'nullable|exists:campaigns,id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,reserved,expired,discarded',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        BloodInventory::create($data);

        return redirect()->route('admin.blood-inventory.index')
            ->with('success', 'Blood inventory record added successfully.');
    }

    public function show(BloodInventory $bloodInventory)
    {
        $bloodInventory->load('campaign', 'creator');
        return view('admin.blood-inventory.show', compact('bloodInventory'));
    }

    public function edit(BloodInventory $bloodInventory)
    {
        $campaigns = Campaign::orderBy('name')->get();
        return view('admin.blood-inventory.edit', compact('bloodInventory', 'campaigns'));
    }

    public function update(Request $request, BloodInventory $bloodInventory)
    {
        $data = $request->validate([
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units' => 'required|integer|min:0',
            'batch_no' => 'nullable|string|max:255',
            'received_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:received_date',
            'source' => 'nullable|string|max:255',
            'source_campaign_id' => 'nullable|exists:campaigns,id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,reserved,expired,discarded',
            'notes' => 'nullable|string',
        ]);

        $bloodInventory->update($data);

        return redirect()->route('admin.blood-inventory.index')
            ->with('success', 'Blood inventory record updated.');
    }

    public function destroy(BloodInventory $bloodInventory)
    {
        $bloodInventory->delete();
        return redirect()->route('admin.blood-inventory.index')
            ->with('success', 'Blood inventory record deleted.');
    }
}
