<?php

namespace App\Http\Controllers;

use App\Models\Ownership;
use App\Models\OwnershipDocument;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OwnershipVerifiedNotification;
use App\Notifications\OwnershipRejectedNotification;
use Illuminate\Support\Facades\Notification;


class OwnershipController extends Controller
{
    // List all ownerships
   public function index()
{
    if (auth()->user()->hasRole('Landlord')) {
        // Show only ownerships belonging to the logged-in landlord
        $ownerships = Ownership::with(['owner.user', 'property', 'documents'])
            ->whereHas('owner', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);
    } else {
        // Admin can see all ownerships
        $ownerships = Ownership::with(['owner.user', 'property', 'documents'])
            ->latest()
            ->paginate(10);
    }

    return view('ownerships.index', compact('ownerships'));
}


    // Show create form (Landlord side)
    public function create()
    {
        $landlords = Landlord::with('user')->get();
        $properties = Property::all();
        return view('ownerships.create', compact('landlords', 'properties'));
    }

    // Store new ownership request
    public function store(Request $request)
    {
        $request->validate([
            'owner_id'        => 'required|exists:landlords,id', 
            'property_id'     => 'required|exists:properties,id',
            'purchase_date'   => 'nullable|date',
            'ownership_type'  => 'required|string',
            'share_percentage'=> 'nullable|numeric|min:0|max:100',
            'documents.*'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // When landlord requests ownership → status stays pending
        $ownership = Ownership::create([
            'owner_id'        => $request->owner_id,
            'property_id'     => $request->property_id,
            'ownership_type'  => $request->ownership_type,
            'share_percentage'=> $request->share_percentage,
            'purchase_date'   => $request->purchase_date,
            'status'          => 'pending', // Always pending until admin verifies
        ]);

        // Handle documents upload
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('ownership_docs', 'public');
                OwnershipDocument::create([
                    'ownership_id'   => $ownership->id,
                    'document_name'  => $file->getClientOriginalName(),
                    'document_type'  => $file->getClientOriginalExtension(),
                    'file_path'      => $path,
                ]);
            }
        }
// dd(Auth::user()->hasRole('Landlord'));

        // Different message if landlord vs admin
        if (Auth::user()->hasRole('Landlord')) {
            return redirect()->route('ownerships.index')->with('success', 'Your ownership request has been submitted and is pending admin verification.');
        }

        return redirect()->route('ownerships.index')->with('success', 'Ownership created successfully.');
    }

    // Show edit form
    public function edit(Ownership $ownership)
    {
        $landlords = Landlord::with('user')->get();
        $properties = Property::all();
        $ownership->load('documents');

        return view('ownerships.edit', compact('ownership', 'landlords', 'properties'));
    }

    // Update ownership (Admin verifies/rejects)
    public function update(Request $request, Ownership $ownership)
    {
        $request->validate([
            'owner_id'        => 'required|exists:landlords,id',
            'property_id'     => 'required|exists:properties,id',
            'purchase_date'   => 'nullable|date',
            'status'          => 'required|in:pending,verified,rejected',
            'remarks'         => 'nullable|string',
            'documents.*'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $ownership->update([
            'owner_id'      => $request->owner_id,
            'property_id'   => $request->property_id,
            'purchase_date' => $request->purchase_date,
            'status'        => $request->status,
            'remarks'       => $request->remarks,
        ]);

        // Upload new documents if any
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('ownership_docs', 'public');
                OwnershipDocument::create([
                    'ownership_id'  => $ownership->id,
                    'document_name' => $file->getClientOriginalName(),
                    'document_type' => $file->getClientOriginalExtension(),
                    'file_path'     => $path,
                ]);
            }
        }

        // If admin verified, mark as officially linked
        if ($request->status === 'verified') {
            // Optional: you can trigger notifications or logs here
        }

        return redirect()->route('ownerships.index')->with('success', 'Ownership updated successfully.');
    }

    // Delete ownership
    public function destroy(Ownership $ownership)
    {
        $ownership->delete();
        return redirect()->route('ownerships.index')->with('success', 'Ownership deleted successfully.');
    }


    // Document verification (Admin)



public function verifyDocument(Request $request, OwnershipDocument $document)
{
    $request->validate([
        'status' => 'required|in:verified,rejected',
        'remarks' => 'nullable|string',
    ]);

    $document->update([
        'verified_by' => auth()->id(),
        'verification_date' => now(),
        'status' => $request->status,
        'remarks' => $request->remarks,
    ]);

    $ownership = $document->ownership;
  $landlordUser = $ownership->owner->user;

if ($landlordUser) {
    if ($request->status === 'verified') {
        $landlordUser->notify(new OwnershipVerifiedNotification($ownership->property->name));
    } else {
        $landlordUser->notify(new OwnershipRejectedNotification($ownership->property->name));
    }
}


    // Update ownership status based on all docs
    $allDocs = $ownership->documents;
    if ($allDocs->where('status', 'verified')->count() === $allDocs->count()) {
        $ownership->update(['status' => 'verified']);
    } elseif ($allDocs->where('status', 'rejected')->count() > 0) {
        $ownership->update(['status' => 'rejected']);
    } else {
        $ownership->update(['status' => 'pending']);
    }

    return back()->with('success', 'Document ' . $request->status . ' and notification sent.');
}




    // List pending documents for admin
    public function documentsPending()
    {
        $documents = OwnershipDocument::with(['ownership', 'ownership.owner', 'ownership.property'])
            ->whereNull('verified_by')
            ->latest()
            ->paginate(10);

        return view('admin.documents.pending', compact('documents'));
    }
}
