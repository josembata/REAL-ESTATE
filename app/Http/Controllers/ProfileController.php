<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    
    //  Display the user's profile form.
     
public function edit(Request $request)
{
    $user = $request->user();

    // Get tenant record linked to this user
    $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();

    return view('profile.edit', [
        'user' => $user,
        'tenant' => $tenant, // pass tenant data to the view
    ]);
}


    
    //  Update the user profile information.
     
public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // Validate all tenant-related inputs
    $validated = $request->validate([
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female,other',
        'bio' => 'nullable|string|max:500',
        'bank_name' => 'nullable|string|max:100',
        'account_number' => 'nullable|string|max:50',
        'account_holder' => 'nullable|string|max:100',

        'id_type' => 'nullable|string|max:100',
        'id_number' => 'nullable|string|max:100|unique:tenants,id_number,' . ($user->tenant->id ?? 'NULL'),
        'id_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'home_address' => 'nullable|string|max:255',
        'professional' => 'nullable|string|max:255',
        'work_address' => 'nullable|string|max:255',
        'emergency_person_name' => 'nullable|string|max:100',
        'emergency_person_contact' => 'nullable|numeric|max:20',

        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find or create tenant linked to user
    $tenant = $user->tenant ?: new \App\Models\Tenant(['user_id' => $user->id]);

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        if (!file_exists(public_path('avatars'))) {
            mkdir(public_path('avatars'), 0755, true);
        }

        $avatarName = time() . '_' . uniqid() . '.' . $request->file('avatar')->extension();
        $request->file('avatar')->move(public_path('avatars'), $avatarName);
        $tenant->avatar = 'avatars/' . $avatarName;
    }

    // Handle ID picture upload
    if ($request->hasFile('id_picture')) {
        $path = $request->file('id_picture')->store('tenant_ids', 'public');
        $tenant->id_picture = $path;
    }

    // Update tenant fields safely
    $tenant->fill([
        'phone' => $validated['phone'] ?? $tenant->phone,
        'gender' => $validated['gender'] ?? $tenant->gender,
        'bio' => $validated['bio'] ?? $tenant->bio,
        'bank_name' => $validated['bank_name'] ?? $tenant->bank_name,
        'account_number' => $validated['account_number'] ?? $tenant->account_number,
        'account_holder' => $validated['account_holder'] ?? $tenant->account_holder,
        'id_type' => $validated['id_type'] ?? $tenant->id_type,
        'id_number' => $validated['id_number'] ?? $tenant->id_number,
        'home_address' => $validated['home_address'] ?? $tenant->home_address,
        'professional' => $validated['professional'] ?? $tenant->professional,
        'work_address' => $validated['work_address'] ?? $tenant->work_address,
        'emergency_person_name' => $validated['emergency_person_name'] ?? $tenant->emergency_person_name,
        'emergency_person_contact' => $validated['emergency_person_contact'] ?? $tenant->emergency_person_contact,
    ]);

    // Check if profile is complete
    $tenant->profile_complete = !empty($tenant->phone)
        && !empty($tenant->gender)
        && !empty($tenant->avatar)
        && !empty($tenant->bio);

    $tenant->save();

    return Redirect::route('dashboard')->with('status', 'Profile updated successfully!');
}





public function showCompleteForm(Request $request)
{
    $user = $request->user();

    if (! $user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    $tenant = \App\Models\Tenant::where('user_id', $user->id)->first();

    if ($tenant && $tenant->profile_complete) {
        return redirect()->route('dashboard');
    }

    return view('profile.complete', [
        'status' => session('status'),
    ]);
}


public function completeProfile(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // Ensure user is verified
    if (! $user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    // Handle avatar upload
    $avatarPath = null;
    if ($request->hasFile('avatar')) {
        if (!file_exists(public_path('avatars'))) {
            mkdir(public_path('avatars'), 0755, true);
        }

        $avatarName = time() . '_' . uniqid() . '.' . $request->file('avatar')->extension();
        $request->file('avatar')->move(public_path('avatars'), $avatarName);
        $avatarPath = 'avatars/' . $avatarName;
    }

    // Create or update tenant profile
    $tenant = \App\Models\Tenant::updateOrCreate(
        ['user_id' => $user->id],
        [
            'phone'   => $request->phone,
            'gender'  => $request->gender,
            'bio'     => $request->bio,
            'bank_name' => $request->bank_name, 
            'account_number' => $request->account_number, 
            'account_holder' => $request->account_holder,
            'avatar'  => $avatarPath,
            'profile_complete' => true,
        ]
    );

    return redirect()->route('dashboard')->with('status', 'Profile completed successfully!');
}



    
    //  Delete the user's account.
     
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}