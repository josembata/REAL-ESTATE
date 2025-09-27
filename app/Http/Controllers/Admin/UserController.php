<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Exclude Admin users using Spatie roles
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

   public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'role' => 'required|string|in:Agent,Landlord,Staff,Tenant',
    ]);

    // Assign role using Spatie
    $user->syncRoles([$validated['role']]);

    // Handle extra tables
    if ($validated['role'] === 'Agent') {
        $user->agent()->firstOrCreate([
            'user_id' => $user->id,
        ]);
        $user->landlord()->delete();
        $user->staff()->delete();
    }

    if ($validated['role'] === 'Landlord') {
        $user->landlord()->firstOrCreate([
            'user_id' => $user->id,
        ]);
        $user->agent()->delete();
        $user->staff()->delete();
    }

    if ($validated['role'] === 'Staff') {
        $user->staff()->firstOrCreate([
            'user_id' => $user->id,
        ]);
        $user->agent()->delete();
        $user->landlord()->delete();
    }

    // If Tenant → just roles, no extra table
    if ($validated['role'] === 'Tenant') {
        $user->agent()->delete();
        $user->landlord()->delete();
        $user->staff()->delete();
    }

    return redirect()->route('admin.users.index')
        ->with('success', "User role updated and migrated successfully.");
}

} 

