<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = LandingSetting::getAll();
        return view('admin.landing-page.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) continue;
            LandingSetting::set($key, $value);
        }

        if ($request->hasFile('hero_bg_image')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('hero_bg_image'));
            $image->cover(1920, 1080);
            $path = 'landing/hero_bg.' . $request->file('hero_bg_image')->getClientOriginalExtension();
            $image->save(storage_path('app/public/' . $path));
            LandingSetting::set('hero_bg_image', $path);
        }

        if ($request->hasFile('cta_bg_image')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('cta_bg_image'));
            $image->cover(1920, 600);
            $path = 'landing/cta_bg.' . $request->file('cta_bg_image')->getClientOriginalExtension();
            $image->save(storage_path('app/public/' . $path));
            LandingSetting::set('cta_bg_image', $path);
        }

        if ($request->hasFile('about_image_1')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('about_image_1'));
            $image->cover(600, 500);
            $path = 'landing/about_1.' . $request->file('about_image_1')->getClientOriginalExtension();
            $image->save(storage_path('app/public/' . $path));
            LandingSetting::set('about_image_1', $path);
        }

        if ($request->hasFile('about_image_2')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('about_image_2'));
            $image->cover(400, 300);
            $path = 'landing/about_2.' . $request->file('about_image_2')->getClientOriginalExtension();
            $image->save(storage_path('app/public/' . $path));
            LandingSetting::set('about_image_2', $path);
        }

        return redirect()->route('admin.landing-page.index')
            ->with('success', 'Landing page settings updated successfully.');
    }
}
