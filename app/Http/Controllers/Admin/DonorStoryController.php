<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonorStory;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DonorStoryController extends Controller
{
    public function index()
    {
        $stories = DonorStory::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.donor-stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.donor-stories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quote' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'blood_group' => 'nullable|string|max:5',
            'city' => 'nullable|string|max:255',
            'donations_count' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $dir = storage_path('app/public/stories');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($request->file('photo'));
            $image->cover(200, 200);
            $path = 'stories/' . uniqid() . '.webp';
            $image->save(storage_path('app/public/' . $path), quality: 80);
            $data['photo'] = $path;
        }

        $data['sort_order'] = $data['sort_order'] ?? DonorStory::max('sort_order') + 1;

        DonorStory::create($data);

        return redirect()->route('admin.donor-stories.index')
            ->with('success', 'Story created successfully.');
    }

    public function edit(DonorStory $donorStory)
    {
        return view('admin.donor-stories.edit', compact('donorStory'));
    }

    public function update(Request $request, DonorStory $donorStory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quote' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'blood_group' => 'nullable|string|max:5',
            'city' => 'nullable|string|max:255',
            'donations_count' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $dir = storage_path('app/public/stories');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($request->file('photo'));
            $image->cover(200, 200);
            $path = 'stories/' . uniqid() . '.webp';
            $image->save(storage_path('app/public/' . $path), quality: 80);
            $data['photo'] = $path;
        }

        $donorStory->update($data);

        return redirect()->route('admin.donor-stories.index')
            ->with('success', 'Story updated successfully.');
    }

    public function destroy(DonorStory $donorStory)
    {
        $donorStory->delete();
        return redirect()->route('admin.donor-stories.index')
            ->with('success', 'Story deleted successfully.');
    }
}
