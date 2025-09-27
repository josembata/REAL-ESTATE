<?php
namespace App\Http\Controllers;

use App\Models\AmenityCategory;
use App\Models\Property;

use Illuminate\Http\Request;
class AmenityCategoryController extends Controller
{
    public function index()
    {
        $categories = AmenityCategory::all();
         $property = Property::first();
        return view('amenity_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:amenity_categories,name',
        ]);

        AmenityCategory::create($request->only('name'));

        return back()->with('success', 'Category added successfully!');
    }

    public function edit(AmenityCategory $category)
    {
        return view('amenity_categories.edit', compact('category'));
    }

    public function update(Request $request, AmenityCategory $category)
    {
        $request->validate([
            'name' => 'required|unique:amenity_categories,name,' . $category->id,
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('amenity-categories.index')->with('success', 'Category updated!');
    }

    public function destroy(AmenityCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted!');
    }
}


