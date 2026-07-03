<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::withCount('areas', 'donors');

        if ($request->ajax() && $request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $cities = $query->latest()->paginate(20);

            return response()->json([
                'html' => view('admin.cities._table', compact('cities'))->render(),
                'pagination' => (string) $cities->appends(['search' => $request->search])->links('pagination::bootstrap-4'),
                'firstItem' => $cities->firstItem() ?? 0,
                'lastItem' => $cities->lastItem() ?? 0,
                'total' => $cities->total(),
            ]);
        }

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $cities = $query->latest()->paginate(20);
        $topCities = City::whereHas('donors')->withCount('donors')->orderByDesc('donors_count')->take(3)->get();

        return view('admin.cities.index', compact('cities', 'topCities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
        ]);

        $city = City::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $city->id, 'name' => $city->name]);
        }

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $id,
        ]);

        $city->update($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }
}
