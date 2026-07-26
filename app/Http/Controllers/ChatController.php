<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Services\GeminiChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * How many recent messages to load for display and to send as conversation
     * context to Gemini.
     */
    private const HISTORY_LIMIT = 20;

    public function index(Request $request)
    {
        $messages = ChatMessage::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->limit(self::HISTORY_LIMIT)
            ->get()
            ->sortBy('id')
            ->values()
            ->map(fn (ChatMessage $message) => [
                'role' => $message->role,
                'message' => $message->message,
            ]);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, GeminiChatService $geminiChatService)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        $userMessage = ChatMessage::create([
            'user_id' => $user->id,
            'role' => 'user',
            'message' => $validated['message'],
        ]);

        $history = ChatMessage::query()
            ->where('user_id', $user->id)
            ->where('id', '<', $userMessage->id)
            ->latest('id')
            ->limit(self::HISTORY_LIMIT)
            ->get()
            ->sortBy('id')
            ->values()
            ->map(fn (ChatMessage $message) => [
                'role' => $message->role,
                'message' => $message->message,
            ])
            ->all();

        $reply = $geminiChatService->getReply($history, $validated['message']);

        $assistantMessage = ChatMessage::create([
            'user_id' => $user->id,
            'role' => 'assistant',
            'message' => $reply,
        ]);

        return response()->json([
            'message' => [
                'role' => $assistantMessage->role,
                'message' => $assistantMessage->message,
            ],
        ]);
    }
}
