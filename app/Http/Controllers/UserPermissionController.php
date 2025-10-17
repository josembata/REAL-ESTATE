<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array',
        ]);

        $user = User::findOrFail($request->user_id);

        // Replace existing direct permissions
        $user->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permissions assigned to user successfully.');
    }
}
