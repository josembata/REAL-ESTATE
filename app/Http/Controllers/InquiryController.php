<?php

namespace App\Http\Controllers;
use App\Models\Property;
use App\Models\Message;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    // Show inquiry form (tenant side)
    public function create($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        return view('inquiries.create', compact('property'));
    }

    // Store inquiry
    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'subject' => 'required|string|max:255',
        ]);

        $inquiry = Inquiry::create([
            'property_id' => $data['property_id'],
            'unit_id' => $data['unit_id'] ?? null,
            'tenant_id' => auth()->id(),
            'subject' => $data['subject'],
            'status' => 'open',
        ]);

        Message::create([
            'inquiry_id' => $inquiry->id,
            'sender_id' => auth()->id(),
            'body' => $request->message,
        ]);

        return redirect()->route('inquiries.show', $inquiry->id);
    }

    // List for agents
    public function index()
    {
        $inquiries = Inquiry::with(['tenant','property'])->latest()->get();
        return view('inquiries.index', compact('inquiries'));
    }

    // Show messages thread
    public function show(Inquiry $inquiry)
    {
        $inquiry->load('messages.sender');
        return view('inquiries.show', compact('inquiry'));
    }

    // Mark closed
    public function close(Inquiry $inquiry)
    {
        $inquiry->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return back()->with('success','Inquiry closed.');
    }
}

