<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Spatie\Permission\Models\Permission;
use App\Models\Permission;
use App\Models\PermissionCategory;
class PermissionController extends Controller
{
 
public function index()
{
    $categories = PermissionCategory::with('permissions')->get();
    $permissions = Permission::with('category')->get();
    return view('permissions.index', compact('categories', 'permissions'));
}

  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:permissions,name',
        'category_id' => 'nullable|exists:permission_categories,id',
    ]);

    Permission::create([
        'name' => $request->name,
        'permission_category_id' => $request->category_id,
        'guard_name' => 'web',
    ]);

    return back()->with('success', 'Permission added successfully!');
}

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }


    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id); 

        // delete related records in  role_permission pivot)
        $permission->roles()->detach();

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }


// Store a new permission category
    
public function storeCategory(Request $request)
{
    $request->validate(['name' => 'required|string|max:255|unique:permission_categories,name']);
    PermissionCategory::create(['name' => $request->name]);
    return back()->with('success', 'Category created successfully!');
}
}
