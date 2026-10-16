<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function fetchMessages()
    {
        $messages = ChatMessage::with('sender')
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'message'   => $request->message,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => $message->load('sender'),
        ]);
    }
}