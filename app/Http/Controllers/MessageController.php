<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all messages for the authenticated user (sent or received)
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })
        ->latest()
        ->paginate(20);

        return view('messages.index', compact('messages'));
    }

    public function inbox()
    {
        $user = auth()->user();
        
        // Get unread messages
        $messages = Message::where('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.inbox', compact('messages'));
    }

    public function show(Message $message)
    {
        // Verify user has access to this message
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Mark as read if user is the receiver
        if ($message->receiver_id === auth()->id()) {
            $message->update(['read_at' => now()]);
        }

        return view('messages.show', compact('message'));
    }

    public function create()
    {
        if (Gate::denies('message_anyone')) {
            abort(403, 'You are not authorized to send messages.');
        }
        $users = User::where('id', '!=', auth()->id())->get();
        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('message_anyone')) {
            abort(403, 'You are not authorized to send messages.');
        }
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id|different:from:' . auth()->id(),
            'subject' => 'required|string|max:255',
            'body' => 'required|string|min:10',
        ], [
            'receiver_id.different' => 'You cannot send a message to yourself.',
        ]);

        $data['sender_id'] = auth()->id();
        
        Message::create($data);

        return redirect()->route('messages.index')->with('success', 'Message sent successfully!');
    }

    public function markAsRead(Message $message)
    {
        if ($message->receiver_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $message->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    public function destroy(Message $message)
    {
        // Users can delete their own sent or received messages
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (Gate::denies('message_anyone') && $message->sender_id !== auth()->id()) {
            abort(403, 'You can only delete your own messages.');
        }

        $message->delete();

        return redirect()->back()->with('success', 'Message deleted.');
    }
}
