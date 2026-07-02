<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with('city')->withCount('donors')->latest()->paginate(20);
        $cities = City::orderBy('name')->get();
        return view('admin.areas.index', compact('areas', 'cities'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('admin.areas.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        $area = Area::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $area->id, 'name' => $area->name, 'city_id' => $area->city_id]);
        }

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully.');
    }

    public function getByCity($cityId)
    {
        $areas = Area::where('city_id', $cityId)->orderBy('name')->get(['id', 'name']);
        return response()->json($areas);
    }

    public function edit($id)
    {
        $area = Area::findOrFail($id);
        $cities = City::orderBy('name')->get();
        return view('admin.areas.edit', compact('area', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $data = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        $area->update($data);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully.');
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area deleted successfully.');
    }
}
