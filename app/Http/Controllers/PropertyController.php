<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\AmenityCategory;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    
    //  Show all properties
     
    public function index()
    {
        $properties = Property::all();
        return view('properties.index', compact('properties'));
    }

    
    // Show create form
    
    public function create()
    {
        
        return view('properties.create');
    }

    
    //   Store new property
     
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:150',
        'description' => 'nullable|string',
        'type' => 'required|in:house,apartment,land,office',
        'city' => 'nullable|string|max:100',
        'region' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    

    if ($request->hasFile('cover_image')) {
        $filename = time() . '_' . $request->file('cover_image')->getClientOriginalName();
        $path = $request->file('cover_image')->move(public_path('property_images'), $filename);
        $validated['cover_image'] = 'property_images/' . $filename;
    }

    Property::create($validated);

    return redirect()->route('properties.index')->with('success', 'Property created successfully!');
}


    
    //   Show edit form
     
    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    
    //  Update property
     
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'type' => 'required|in:house,apartment,land,office',
            'status' => 'required|in:active,pending,archived',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imageName = time().'_'.$request->file('cover_image')->getClientOriginalName();
            $request->file('cover_image')->move(public_path('property_images'), $imageName);
            $validated['cover_image'] = 'property_images/' . $imageName;
        }

        $property->update($validated);

        return redirect()->route('properties.index')->with('success', 'Property updated successfully!');
    }

//show single proprty
    public function show(Property $property)
   {
    return view('properties.show', compact('property'));
   }


    
    //   Delete property
     
    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully!');
    }


  // Show form to assign amenities to a property
    public function assignAmenitiesForm(Property $property)
    {
        $categories = AmenityCategory::with('amenities')->get();

        return view('properties.assignamenities', compact('property', 'categories'));
    }

    // Save assigned amenities
    public function assignAmenities(Request $request, Property $property)
    {
        // Validate amenities 
        $validated = $request->validate([
            'amenities' => 'array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        // Sync amenities with property
        $property->amenities()->sync($validated['amenities'] ?? []);

        return redirect()->route('properties.index')
            ->with('success', 'Amenities updated successfully!');
    }


}
