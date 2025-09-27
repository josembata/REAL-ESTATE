<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use App\Models\Unit;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // List rooms for a unit
    public function index(Unit $unit)
    {
         $rooms = $unit->rooms()->get(); 
        return view('rooms.index', compact('unit', 'rooms'));
    }

    // Show form to create a room
    public function create(Unit $unit)
    {
        return view('rooms.create', compact('unit'));
    }

    // Store new room
   public function store(Request $request, Unit $unit)
{
    $validated = $request->validate([
        'room_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'availability_status' => 'required|string|in:available,reserved,occupied',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Create room
    $room = Room::create([
        'unit_id' => $unit->id,
        'room_name' => $validated['room_name'],
        'description' => $validated['description'] ?? null,
        'price' => $validated['price'],
        'availability_status' => $validated['availability_status'],
    ]);

    // Save images if uploaded
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('rooms', 'public');

            \App\Models\RoomImage::create([
                'room_id' => $room->room_id,
                'image_path' => $path,
            ]);
        }
    }

    return redirect()->route('units.rooms.show', [$unit->id, $room->room_id])
        ->with('success', 'Room created successfully with images.');
}


    // Show form to edit a room
   public function edit(Unit $unit, Room $room)
{
    return view('rooms.edit', compact('unit', 'room'));
}


public function update(Request $request, Unit $unit, Room $room)
{
    // Validate request
    $validated = $request->validate([
        'room_name' => 'required|string|max:100',
        'room_type' => 'required|in:bedroom,bathroom,office,shop,warehouse,other',
        'size_sqft' => 'nullable|numeric|min:0',
        'price' => 'nullable|numeric|min:0',
        'availability_status' => 'required|in:available,occupied,reserved',
    ]);

    //  make sure room belongs to this unit
    if ($room->unit_id !== $unit->id) {
        abort(404, 'Room does not belong to this unit.');
    }

    // Update room
    $room->update($validated);

    // Redirect back with success message
    return redirect()
        ->route('units.rooms.index', $unit)
        ->with('success', 'Room updated successfully.');
}

public function show(Unit $unit, Room $room)
{
    // make sure the room belongs to this unit
    if ($room->unit_id !== $unit->id) {
        abort(404, 'This room does not belong to the selected unit.');
    }

    return view('rooms.show', compact('unit', 'room'));
}




    // Delete room
    public function destroy(Unit $unit, Room $room)
    {
        $room->delete();

        return redirect()->route('units.rooms.index', $unit->id)
            ->with('success', 'Room deleted successfully.');
    }
}
