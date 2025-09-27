<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class MessageController extends Controller
{
 public function store(Request $request, Inquiry $inquiry)
{
    $request->validate([
        'body' => 'required|string',
         'attachments.*' => 'file|max:35102400' //  100MB per file
    ]);

    //  create the message
    $message = $inquiry->messages()->create([
        'sender_id' => auth()->id(),
        'body' => $request->body,
    ]);

    // Handle attachments
    if ($request->hasFile('attachments')) {
        $paths = [];
        foreach ($request->file('attachments') as $file) {
            $paths[] = $file->store('attachments', 'public'); 
        }

        // Save paths into JSON column
        $message->attachments = $paths;
        $message->save();
    }

    return back()->with('success', 'Message sent successfully.');
}

}

