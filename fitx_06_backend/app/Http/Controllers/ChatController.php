<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // send
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return response()->json(['success' => true, 'message' => 'Đã gửi tin nhắn', 'data' => $message]);
    }

    // get mess
    public function getMessages($receiver_id)
    {
        $userId = Auth::id();

        $messages = ChatMessage::where(function ($q) use ($userId, $receiver_id) {
                $q->where('sender_id', $userId)->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($q) use ($userId, $receiver_id) {
                $q->where('sender_id', $receiver_id)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $messages]);
    }

    // read
    public function markAsRead($sender_id)
    {
        ChatMessage::where('sender_id', $sender_id)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'Tin nhắn đã được đánh dấu là đã đọc']);
    }
}
