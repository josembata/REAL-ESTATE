<?php

namespace App\Http\Controllers;
use App\Models\Amenity;
use App\Models\AmenityCategory;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::with('category')->get();
        $categories = AmenityCategory::all();
        return view('amenities.index', compact('amenities', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|unique:amenities,name',
            'category_id'=> 'required|exists:amenity_categories,id',
            'icon'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('icon')?->store('amenities', 'public');

        Amenity::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'icon' => $path,
        ]);

        return back()->with('success', 'Amenity added successfully!');
    }

    public function edit(Amenity $amenity)
    {
        $categories = AmenityCategory::all();
        return view('amenities.edit', compact('amenity', 'categories'));
    }

    public function update(Request $request, Amenity $amenity)
    {
        $request->validate([
            'name'       => 'required|unique:amenities,name,' . $amenity->id,
            'category_id'=> 'required|exists:amenity_categories,id',
            'icon'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $amenity->icon;
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('amenities', 'public');
        }

        $amenity->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'icon' => $path,
        ]);

        return redirect()->route('amenities.index')->with('success', 'Amenity updated!');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return back()->with('success', 'Amenity deleted!');
    }
}


