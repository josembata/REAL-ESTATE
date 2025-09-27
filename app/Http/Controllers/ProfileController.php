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

    // find or create tenant linked to user
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

    // Update tenant profile fields
    $tenant->phone = $request->input('phone', $tenant->phone);
    $tenant->gender = $request->input('gender', $tenant->gender);
    $tenant->bio = $request->input('bio', $tenant->bio);

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