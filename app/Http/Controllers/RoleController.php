<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use App\Models\Landlord;
use App\Models\Staff;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public function index()
    {
        if (! Auth::user() || ! Auth::user()->hasRole('Admin')) {
            abort(403);
        }
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
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
            // optional extra fields for role-specific tables:
            'license_number' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'company_name' => 'nullable|string',
            'address' => 'nullable|string',
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


}
