<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            $tenant = Tenant::where('user_id', $user->id)->first();

            if (! $tenant || ! $tenant->profile_complete) {
                if (! $request->routeIs('profile.complete') && ! $request->routeIs('profile.complete.submit')) {
                    return redirect()->route('profile.complete')
                        ->with('status', 'Please complete your profile.');
                }
            }
        }

        return $next($request);
    }
}