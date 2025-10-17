<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use App\Models\Landlord;
use App\Models\Staff;
use App\Models\PermissionCategory;
use App\Models\Permission;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public function index()
    {
        // if (! Auth::user() || ! Auth::user()->hasRole('Admin')) {
        //     abort(403);
        // }
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
            $users = \App\Models\User::all();
        return view('roles.index', compact('roles', 'permissions','users'));
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
        'name' => 'required|string|max:150',
       
    ]);
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        return back()->with('success', 'Role created successfully.');
    }

   public function edit($id)
{
    $role = Role::findOrFail($id);
    return view('roles.edit', compact('role'));
}

public function update(Request $request, $id)
{
    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);
    return redirect()->route('roles.index')->with('success', 'Role updated successfully');
}

public function destroy($id)
{
    $role = Role::findOrFail($id);
    $role->delete();
    return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
}



 // Asign role

  public function assignForm()
    {
        // allow only admins
        if (! Auth::user() || ! Auth::user()->hasRole('Admin')) {
            abort(403);
        }

        $roles = Role::all();
        $users = User::orderBy('name')->get();

        return view('admin.roles.assign', compact('roles', 'users'));
    }

    public function assignRole(Request $request)
    {
        if (! Auth::user() || ! Auth::user()->hasRole('Admin')) {
            abort(403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
            //  fields for role-specific tables:
            'license_number' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'company_name' => 'nullable|string',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'department' => 'nullable|string',
            'position' => 'nullable|string',
            'employee_number' => 'nullable|string',
        ]);

        $user = User::findOrFail($data['user_id']);

        // assign role via Spatie
        $user->syncRoles([$data['role']]);

        // migrate to role-specific tables
        if ($data['role'] === 'Agent') {
            Agent::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_number' => $data['license_number'] ?? null,
                    'experience_years' => $data['experience_years'] ?? 0,
                ]
            );
            // cleanup other role tables if needed
            $user->landlord()->delete();
            $user->staff()->delete();
        } elseif ($data['role'] === 'Landlord') {
            Landlord::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $data['company_name'] ?? null,
                    'address' => $data['address'] ?? null,
                    'tax_id' => $data['tax_id'] ?? null,
                    'bank_account' => $data['bank_account'] ?? null,
                ]
            );
            $user->agent()->delete();
            $user->staff()->delete();
        } elseif ($data['role'] === 'Staff') {
            Staff::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department' => $data['department'] ?? null,
                    'position' => $data['position'] ?? null,
                    'employee_number' => $data['employee_number'] ?? null,
                ]
            );
            $user->agent()->delete();
            $user->landlord()->delete();
        } else { // Tenant
            // remove all special records for other roles
            $user->agent()->delete();
            $user->landlord()->delete();
            $user->staff()->delete();
        }

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }


 // Show form for assigning permissions to roles & users
    public function showAssignPermissionsForm()
    {
       $roles = Role::all();
    $permissions = Permission::with('category')->get();
    $categories = PermissionCategory::with('permissions')->get();
    $users = User::orderBy('name')->get();
    $uncategorizedPermissions = Permission::whereNull('permission_category_id')->get();

    return view('admin.roles.assign_permissions', compact('roles', 'permissions', 'categories', 'users', 'uncategorizedPermissions'));
    }

  // Assign permissions to either a role or a user
    public function assignPermissions(Request $request)
    {
        $request->validate([
            'permissions' => 'array',
            'role_id' => 'nullable|exists:roles,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $permissions = $request->permissions ?? [];

        if ($request->filled('role_id')) {
            //  Assign to role
            $role = Role::findOrFail($request->role_id);
            $role->syncPermissions($permissions);

            return redirect()
                ->route('roles.assign.permissions.form')
                ->with('success', "Permissions updated for role: {$role->name}");
        }

        if ($request->filled('user_id')) {
            //  Assign to user
            $user = User::findOrFail($request->user_id);
            $user->syncPermissions($permissions);

            return redirect()
                ->route('roles.assign.permissions.form')
                ->with('success', "Permissions updated for user: {$user->name}");
        }

        return back()->with('error', 'Please select a role or user.');
    }

    //  Toggle permission dynamically  updates)
    public function togglePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'nullable|exists:roles,id',
            'user_id' => 'nullable|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $permission = Permission::findOrFail($request->permission_id);

        // For Role toggle
        if ($request->filled('role_id')) {
            $role = Role::findOrFail($request->role_id);
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                return response()->json(['status' => 'removed', 'type' => 'role']);
            } else {
                $role->givePermissionTo($permission);
                return response()->json(['status' => 'added', 'type' => 'role']);
            }
        }

        // For User toggle
        if ($request->filled('user_id')) {
            $user = User::findOrFail($request->user_id);
            if ($user->hasPermissionTo($permission)) {
                $user->revokePermissionTo($permission);
                return response()->json(['status' => 'removed', 'type' => 'user']);
            } else {
                $user->givePermissionTo($permission);
                return response()->json(['status' => 'added', 'type' => 'user']);
            }
        }

        return response()->json(['error' => 'No target found'], 422);
    }

}
