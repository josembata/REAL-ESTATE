<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\AmenityCategory;
use App\Models\Agent;
use App\Models\Ownership;
use App\Models\User;
use App\Models\Owner;
use App\Models\landlord;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    // Show create form

public function create()
{
    $agents = User::whereHas('roles', function ($q) {
        $q->where('name', 'Agent');
    })->get();

    return view('properties.create', compact('agents'));
}

    //  Show all properties
     
 
public function index()
{
    $user = auth()->user();

    if ($user->hasRole('Landlord')) {
        // Find the landlord record linked to this logged-in user
        $landlord = \App\Models\Landlord::where('user_id', $user->id)->first();

        if (!$landlord) {
            return back()->with('error', 'No landlord record found.');
        }

        // Get only verified ownership properties for this landlord
        $properties = \App\Models\Property::whereHas('ownerships', function ($q) use ($landlord) {
            $q->where('owner_id', $landlord->id)
              ->where('status', 'verified');
        })->paginate(10);

    } elseif ($user->hasRole('Agent')) {
        // Show properties assigned to this agent, with verified ownership
        $properties = \App\Models\Property::where('agent_id', $user->id)
            ->whereHas('ownerships', function ($q) {
                $q->where('status', 'verified');
            })
            ->paginate(10);

    } else {
        // Admin or others see all verified properties
        $properties = \App\Models\Property::whereHas('ownerships', function ($q) {
            $q->where('status', 'verified');
        })->paginate(10);
    }

    return view('properties.index', compact('properties'));
}




    
   

    
    //   Store new property
     
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:150',
        'title_deed_number' => 'nullable|string|max:100',
        'agent_user_id' => 'nullable|exists:users,id',
        'description' => 'nullable|string',
        'type' => 'required|in:house,apartment,land,office',
        'city' => 'nullable|string|max:100',
        'region' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle image upload
    if ($request->hasFile('cover_image')) {
        $filename = time() . '_' . $request->file('cover_image')->getClientOriginalName();
        $path = $request->file('cover_image')->move(public_path('property_images'), $filename);
        $validated['cover_image'] = 'property_images/' . $filename;
    }

    // Rename agent_user_id → agent_id to match DB column
    if ($request->filled('agent_user_id')) {
        $validated['agent_id'] = $request->agent_user_id;
        unset($validated['agent_user_id']); // optional cleanup
    }

    // Create the property
    Property::create($validated);

    return redirect()->route('properties.index')->with('success', 'Property created successfully!');
}

    
    //   Show edit form
     
    
public function edit(Property $property)
{
    $agents = User::whereHas('roles', function ($q) {
        $q->where('name', 'Agent');
    })->get();

    return view('properties.edit', compact('property', 'agents'));
}

    
    //  Update property
     
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'title_deed_number' => 'nullable|string|max:100',
             'agent_user_id' => 'nullable|exists:users,id',
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
