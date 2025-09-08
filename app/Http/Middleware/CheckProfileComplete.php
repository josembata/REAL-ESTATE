<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip middleware for verification and profile completion routes
            if ($request->is('email/verify*') || $request->is('complete-profile*')) {
                return $next($request);
            }
            
            // Check if email is verified first
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            
            // Then check if profile is complete
            if (!$user->profile_complete) {
                return redirect()->route('complete-profile')
                    ->with('message', 'Please complete your profile before accessing this page.');
            }
        }

        return $next($request);
    }
}