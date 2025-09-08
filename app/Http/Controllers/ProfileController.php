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
          return view('profile.edit', [
        'user' => $request->user(),
    ]);
    }

    
    //  Update the user's profile information.
     
public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        if (!file_exists(public_path('avatars'))) {
            mkdir(public_path('avatars'), 0755, true);
        }

        $avatarName = time() . '_' . uniqid() . '.' . $request->file('avatar')->extension();
        $request->file('avatar')->move(public_path('avatars'), $avatarName);

        // Update avatar path only if a new one is uploaded
        $user->avatar = 'avatars/' . $avatarName;
    }

    // Update other profile fields
    $user->phone = $request->input('phone', $user->phone);
    $user->gender = $request->input('gender', $user->gender);
    $user->bio = $request->input('bio', $user->bio);

    // Reset email verification if email is changed
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    // Check if profile is complete
    $isProfileComplete = !empty($user->phone) 
        && !empty($user->gender) 
        && !empty($user->avatar) 
        && !empty($user->bio);

    if ($isProfileComplete) {
        $user->profile_complete = true;
        $user->save();

        return Redirect::route('dashboard')->with('status', 'Profile completed successfully!');
    }

    return Redirect::route('complete-profile')->with('status', 'Please complete your profile.');
}



public function showCompleteForm(Request $request)
{
    $user = $request->user();
    
    // If email not verified, redirect to verification notice
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    
    // If profile already complete, redirect to dashboard
    if ($user->profile_complete) {
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
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        if (!file_exists(public_path('avatars'))) {
            mkdir(public_path('avatars'), 0755, true);
        }
        
        $avatarName = time() . '_' . uniqid() . '.' . $request->file('avatar')->extension();
        $request->file('avatar')->move(public_path('avatars'), $avatarName);
        $user->avatar = 'avatars/' . $avatarName;
    }

    // Update profile fields
    $user->phone = $request->phone;
    $user->gender = $request->gender;
    $user->bio = $request->bio;
    $user->profile_complete = true;

    $user->save();

    // Redirect to dashboard
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