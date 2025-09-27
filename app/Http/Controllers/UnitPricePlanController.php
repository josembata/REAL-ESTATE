<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitPricePlan;
use App\Models\PricePlanCategory;
use Illuminate\Http\Request;

class UnitPricePlanController extends Controller
{
    // List all price plans for a unit
    public function index(Unit $unit)
    {
        $plans = $unit->pricePlans;
        return view('price_plans.index', compact('unit', 'plans'));
    }

    // Show form to create a price plan
   public function create(Unit $unit)
{
    // Fetch all categories to pass to the view
    $categories = \App\Models\PricePlanCategory::all();

    return view('price_plans.create', compact('unit', 'categories'));
}


    // Store a new price plan
    public function store(Request $request, Unit $unit)
{
    //  Validate input
    $validated = $request->validate([
        'category_id' => 'required|exists:price_plan_categories,id',
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'currency' => 'required|string|size:3',
    ]);

    // Create price plan
    $plan = $unit->pricePlans()->create([
        'category_id' => $validated['category_id'],  // link to category
        'name' => $validated['name'],
        'price' => $validated['price'],
        'currency' => $validated['currency'],
    ]);

    return redirect()->route('price-plans.index', $unit->id)
        ->with('success', 'Price plan created successfully.');
}


    // Show form to edit a price plan
    public function edit(Unit $unit, UnitPricePlan $pricePlan)
    {
        return view('price_plans.edit', compact('unit', 'pricePlan'));
    }

    // Update a price plan
    public function update(Request $request, Unit $unit, UnitPricePlan $pricePlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $pricePlan->update($validated);

        return redirect()->route('price-plans.index', $unit)
            ->with('success', 'Price plan updated successfully.');
    }

    // Delete a price plan
    public function destroy(Unit $unit, UnitPricePlan $pricePlan)
    {
        $pricePlan->delete();
        return redirect()->route('price-plans.index', $unit)
            ->with('success', 'Price plan deleted successfully.');
    }
}
