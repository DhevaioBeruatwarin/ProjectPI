<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Pembeli;
use App\Models\Seniman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function pembeliIndex(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $conversations = Conversation::with([
            'seniman:id_seniman,nama,foto',
            'messages' => function ($query) {
                $query->latest()->limit(1);
            }
        ])
            ->where('pembeli_id', $pembeli->id_pembeli)
            ->orderByDesc('updated_at')
            ->get();

        $activeConversation = $this->resolveConversation(
            $request->get('conversation'),
            'pembeli',
            $pembeli->id_pembeli,
            $conversations->first()
        );

        $messages = $activeConversation ? $this->loadMessages($activeConversation) : collect();

        if ($activeConversation) {
            $this->markMessagesAsRead($activeConversation, 'pembeli');
        }

        $senimanList = Seniman::orderBy('nama')->get(['id_seniman', 'nama']);

        return view('chat.index', [
            'userType' => 'pembeli',
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
            'counterparts' => $senimanList,
            'counterpartLabel' => 'Seniman',
            'routeConfig' => [
                'index' => 'pembeli.chat.index',
                'start' => 'pembeli.chat.start',
            ],
            'endpoints' => [
                'messages' => $activeConversation ? route('pembeli.chat.messages', $activeConversation) : null,
                'send' => $activeConversation ? route('pembeli.chat.send', $activeConversation) : null,
            ],
        ]);
    }

    public function pembeliStart(Request $request): RedirectResponse
    {
        $pembeli = Auth::guard('pembeli')->user();

        $validated = $request->validate([
            'counterpart_id' => 'required|exists:seniman,id_seniman',
        ]);

        $conversation = Conversation::firstOrCreate([
            'pembeli_id' => $pembeli->id_pembeli,
            'seniman_id' => $validated['counterpart_id'],
        ]);

        return redirect()->route('pembeli.chat.index', ['conversation' => $conversation->id]);
    }

    public function pembeliMessages(Conversation $conversation): JsonResponse
    {
        $pembeli = Auth::guard('pembeli')->user();
        $this->ensureConversationOwner($conversation, 'pembeli', $pembeli->id_pembeli);
        $this->markMessagesAsRead($conversation, 'pembeli');

        return $this->messagesResponse($conversation, 'pembeli');
    }

    public function pembeliSend(Request $request, Conversation $conversation)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $this->ensureConversationOwner($conversation, 'pembeli', $pembeli->id_pembeli);

        return $this->handleSend(
            $request,
            $conversation,
            'pembeli',
            $pembeli->id_pembeli,
            'pembeli.chat.index'
        );
    }

    public function senimanIndex(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        $conversations = Conversation::with([
            'pembeli:id_pembeli,nama,foto',
            'messages' => function ($query) {
                $query->latest()->limit(1);
            }
        ])
            ->where('seniman_id', $seniman->id_seniman)
            ->orderByDesc('updated_at')
            ->get();

        $activeConversation = $this->resolveConversation(
            $request->get('conversation'),
            'seniman',
            $seniman->id_seniman,
            $conversations->first()
        );

        $messages = $activeConversation ? $this->loadMessages($activeConversation) : collect();

        if ($activeConversation) {
            $this->markMessagesAsRead($activeConversation, 'seniman');
        }

        $pembeliList = Pembeli::orderBy('nama')->get(['id_pembeli', 'nama']);

        return view('chat.index', [
            'userType' => 'seniman',
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
            'counterparts' => $pembeliList,
            'counterpartLabel' => 'Pembeli',
            'routeConfig' => [
                'index' => 'seniman.chat.index',
                'start' => 'seniman.chat.start',
            ],
            'endpoints' => [
                'messages' => $activeConversation ? route('seniman.chat.messages', $activeConversation) : null,
                'send' => $activeConversation ? route('seniman.chat.send', $activeConversation) : null,
            ],
        ]);
    }

    public function senimanStart(Request $request): RedirectResponse
    {
        $seniman = Auth::guard('seniman')->user();

        $validated = $request->validate([
            'counterpart_id' => 'required|exists:pembeli,id_pembeli',
        ]);

        $conversation = Conversation::firstOrCreate([
            'pembeli_id' => $validated['counterpart_id'],
            'seniman_id' => $seniman->id_seniman,
        ]);

        return redirect()->route('seniman.chat.index', ['conversation' => $conversation->id]);
    }

    public function senimanMessages(Conversation $conversation): JsonResponse
    {
        $seniman = Auth::guard('seniman')->user();
        $this->ensureConversationOwner($conversation, 'seniman', $seniman->id_seniman);
        $this->markMessagesAsRead($conversation, 'seniman');

        return $this->messagesResponse($conversation, 'seniman');
    }

    public function senimanSend(Request $request, Conversation $conversation)
    {
        $seniman = Auth::guard('seniman')->user();
        $this->ensureConversationOwner($conversation, 'seniman', $seniman->id_seniman);

        return $this->handleSend(
            $request,
            $conversation,
            'seniman',
            $seniman->id_seniman,
            'seniman.chat.index'
        );
    }

    private function resolveConversation(?string $conversationId, string $userType, int $userId, $fallback = null): ?Conversation
    {
        if ($conversationId) {
            return Conversation::with($userType === 'pembeli' ? 'seniman' : 'pembeli')
                ->where($userType === 'pembeli' ? 'pembeli_id' : 'seniman_id', $userId)
                ->where('id', $conversationId)
                ->first();
        }

        if (!$fallback) {
            return null;
        }

        return Conversation::with($userType === 'pembeli' ? 'seniman' : 'pembeli')
            ->find($fallback->id);
    }

    private function loadMessages(Conversation $conversation): Collection
    {
        return $conversation->messages()
            ->orderBy('created_at')
            ->get();
    }

    private function markMessagesAsRead(Conversation $conversation, string $viewerType): void
    {
        $conversation->messages()
            ->where('sender_type', $viewerType === 'pembeli' ? 'seniman' : 'pembeli')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    private function ensureConversationOwner(Conversation $conversation, string $userType, int $userId): void
    {
        $matches = $userType === 'pembeli'
            ? $conversation->pembeli_id === $userId
            : $conversation->seniman_id === $userId;

        abort_if(!$matches, 403, 'Akses chat tidak valid.');
    }

    private function messagesResponse(Conversation $conversation, string $viewerType): JsonResponse
    {
        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) use ($viewerType) {
                return [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'sent_at' => optional($message->created_at)->format('d M H:i'),
                    'is_self' => $message->sender_type === $viewerType,
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    private function handleSend(
        Request $request,
        Conversation $conversation,
        string $senderType,
        int $senderId,
        string $redirectRoute
    ) {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $message->id,
                'message' => $message->message,
                'sender_type' => $message->sender_type,
                'sent_at' => optional($message->created_at)->format('d M H:i'),
            ], 201);
        }

        return redirect()->route($redirectRoute, ['conversation' => $conversation->id]);
    }
}

