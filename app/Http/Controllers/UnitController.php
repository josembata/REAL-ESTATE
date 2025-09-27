<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use App\Models\UnitImage;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('property')->latest()->paginate(10);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $properties = Property::all();
        return view('units.create', compact('properties'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'property_id' => 'required|exists:properties,id',
        'unit_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'currency' => 'required|string|max:10',
        'unit_type' => 'required|string',
        'custom_unit_type' => 'nullable|string|max:255',
        'furnishing' => 'nullable|string',
        'size_sqft' => 'nullable|numeric',
        'furnished' => 'nullable|boolean',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle "Others" case
    if ($validated['unit_type'] === 'others' && !empty($validated['custom_unit_type'])) {
        $validated['unit_type'] = $validated['custom_unit_type'];
    }
    unset($validated['custom_unit_type']);

    //  Create the Unit
    $unit = Unit::create($validated);
    
    //  Save multiple images in unit_images table
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('unit_images', 'public');

            $unit->unitImages()->create([  //comes for relationship
                'image_path' => $path,
            ]);
        }
    }

    return redirect()->route('units.index')->with('success', 'Unit created successfully.');
}





    public function edit(Unit $unit)
    {
        $properties = Property::all();
        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'unit_type' => 'required|in:single,double,suite,office',
            'furnishing' => 'required|in:unfurnished,partially_furnished,furnished',
            'status' => 'required|in:available,booked,unavailable',
            'size_sqft' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

public function show(Unit $unit)
{
    
    $unit->load('unitImages'); 
    
    return view('units.show', compact('unit'));
}



    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
