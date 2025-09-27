<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricePlanCategory;
use App\Models\Room;
use App\Models\Unit;

class RoomPricePlanController extends Controller
{
    public function create(Room $room)
    {
        $categories = PricePlanCategory::all();
        return view('room_price_plans.create', compact('room', 'categories'));
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:price_plan_categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        $room->pricePlans()->create($validated);

        return redirect()->route('units.rooms.show', [$room->unit_id, $room->room_id])
                         ->with('success', 'Price plan added for this room.');
    }
}

