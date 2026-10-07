<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    // Public: visitor sends a message
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $data['user_id'] = User::firstOrFail()->id;

        ContactMessage::create($data);

        return response()->json(['message' => 'Message sent. Thank you!'], 201);
    }

    // Admin
    public function index()
    {
        return response()->json(ContactMessage::latest()->get());
    }

    public function markRead(ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => true]);
        return response()->json($contactMessage);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
