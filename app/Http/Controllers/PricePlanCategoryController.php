<?php
namespace App\Http\Controllers;

use App\Models\PricePlanCategory;
use Illuminate\Http\Request;

class PricePlanCategoryController extends Controller
{
    public function index()
    {
        $categories = PricePlanCategory::all();
        return view('price_plan_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('price_plan_categories.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:price_plan_categories,name',
    ]);

    $category = PricePlanCategory::create($request->only('name'));

    if ($request->expectsJson()) {
        return response()->json(['id' => $category->id, 'name' => $category->name]);
    }

    return redirect()->route('price_plan_categories.index')
        ->with('success', 'Category created successfully.');
}


    public function edit(PricePlanCategory $category)
    {
        return view('price_plan_categories.edit', compact('category'));
    }

    public function update(Request $request, PricePlanCategory $category)
    {
        $request->validate([
            'name' => 'required|string|unique:price_plan_categories,name,' . $category->id
        ]);

        $category->update($request->only('name'));

        return redirect()->route('price_plan_categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(PricePlanCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
