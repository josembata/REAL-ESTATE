<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
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
            'unit_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'unit_type' => 'required|in:single,double,suite,office',
            'furnishing' => 'required|in:unfurnished,partially_furnished,furnished',
            'size_sqft' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
        ]);

        // Default status after save → unavailable
        $validated['status'] = 'unavailable';

        Unit::create($validated);

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
    return view('units.show', compact('unit'));
}


    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
