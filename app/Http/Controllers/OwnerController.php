<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Landlord::with('user')->latest()->paginate(10);
        return view('owners.index', compact('owners'));
    }

    public function create()
    {
        return view('owners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address'      => 'nullable|string|max:255',
            'tax_id'       => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
        ]);

        Landlord::create([
            'user_id'      => auth()->id(), // link to logged in user
            'company_name' => $request->company_name,
            'address'      => $request->address,
            'tax_id'       => $request->tax_id,
            'bank_account' => $request->bank_account,
        ]);

        return redirect()->route('owners.index')->with('success', 'Owner created successfully.');
    }

    public function edit(Landlord $owner)
    {
        return view('owners.edit', compact('owner'));
    }

    public function update(Request $request, Landlord $owner)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address'      => 'nullable|string|max:255',
            'tax_id'       => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
        ]);

        $owner->update([
            'company_name' => $request->company_name,
            'address'      => $request->address,
            'tax_id'       => $request->tax_id,
            'bank_account' => $request->bank_account,
        ]);

        return redirect()->route('owners.index')->with('success', 'Owner updated successfully.');
    }

    public function destroy(Landlord $owner)
    {
        $owner->delete();
        return redirect()->route('owners.index')->with('success', 'Owner deleted successfully.');
    }
}
