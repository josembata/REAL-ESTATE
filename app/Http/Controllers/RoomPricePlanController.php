<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricePlanCategory;
use App\Models\RoomPricePlan;
use App\Models\Room;
use App\Models\Unit;

class RoomPricePlanController extends Controller
{

     public function index(Room $room)
    {
        $pricePlans = $room->pricePlans()->with('category')->get();
        return view('room_price_plans.index', compact('room', 'pricePlans'));
    }

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

     public function edit(Room $room, RoomPricePlan $pricePlan)
    {
        $categories = PricePlanCategory::all();
        return view('room_price_plans.edit', compact('room', 'pricePlan', 'categories'));
    }

    public function update(Request $request, Room $room, RoomPricePlan $pricePlan)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:price_plan_categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $pricePlan->update($validated);

        return redirect()->route('rooms.price-plans.index', $room->room_id)
            ->with('success', 'Room price plan updated successfully.');
    }

    public function destroy(Room $room, RoomPricePlan $pricePlan)
    {
        $pricePlan->delete();
        return redirect()->route('rooms.price-plans.index', $room->room_id)
            ->with('success', 'Room price plan deleted.');
    }
}

