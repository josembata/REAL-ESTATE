<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LeaseController extends Controller
{

public function index()
{
    $leases = Lease::with([
        'property.ownerships.owner.user', 
        'tenant'
    ])
    ->where('user_id', auth()->id())
    ->get();

    return view('leases.index', compact('leases'));
}


    public function show(Lease $lease)
    {
        // ensure only tenant or owner can view
        $user = auth()->user();
        if ($lease->user_id !== $user->id && $lease->owner_id !== $user->id) {
            abort(403);
        }

        return view('leases.show', compact('lease'));
    }

    public function download(Lease $lease)
    {
        $user = auth()->user();
        if ($lease->user_id !== $user->id && $lease->owner_id !== $user->id) {
            abort(403);
        }

        if (!$lease->file_path || !Storage::disk('public')->exists($lease->file_path)) {
            abort(404, 'Lease file not found.');
        }

        return response()->file(storage_path('app/public/'.$lease->file_path));
    }
}
