<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Pembeli;
use App\Models\Seniman;
use App\Models\PembeliConversation;
use App\Models\PembeliChatMessage;
use App\Models\Transaksi;
use App\Models\KaryaSeni;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\QuickReply;

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

        // Handle auto-select conversation by seniman_id
        $selectedConversation = null;
        if ($request->has('seniman_id')) {
            $selectedConversation = Conversation::with(['seniman'])
                ->where('pembeli_id', $pembeli->id_pembeli)
                ->where('seniman_id', $request->get('seniman_id'))
                ->first();
        }

        $activeConversation = $selectedConversation ?? $this->resolveConversation(
            $request->get('conversation'),
            'pembeli',
            $pembeli->id_pembeli,
            $conversations->first()
        );

        $messages = $activeConversation ? $this->loadMessages($activeConversation) : collect();

        if ($activeConversation) {
            $this->markMessagesAsRead($activeConversation, 'pembeli');
        }

        // Tampilkan semua seniman untuk chat (tidak perlu checkout dulu)
        $senimanList = Seniman::orderBy('nama')->get(['id_seniman', 'nama']);

        // Ambil quick replies default/aktif untuk pembeli (user_id null = global)
        $quickReplies = QuickReply::where('user_type', 'pembeli')
            ->where(function ($q) {
                $q->whereNull('user_id');
            })
            ->where('is_active', true)
            ->orderBy('category')
            ->get();

        // Foto path untuk navbar (mengikuti pola pada view lain)
        $fotoPath = null;
        if (Auth::guard('pembeli')->check()) {
            $pembeliUser = Auth::guard('pembeli')->user();
            $fotoPath = $pembeliUser->foto ? asset('storage/foto_pembeli/' . $pembeliUser->foto) : asset('assets/defaultprofile.png');
        }

        return view('chat.pembeli-chat', [
            'userType' => 'pembeli',
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
            'counterparts' => $senimanList,
            'quickReplies' => $quickReplies,
            'endpoints' => [
                'messages' => $activeConversation ? route('pembeli.chat.messages', $activeConversation) : null,
                'send' => $activeConversation ? route('pembeli.chat.send', $activeConversation) : null,
            ],
            'fotoPath' => $fotoPath,
        ]);
    }

    public function pembeliStart(Request $request): RedirectResponse
    {
        $pembeli = Auth::guard('pembeli')->user();

        $validated = $request->validate([
            'counterpart_id' => 'required|exists:seniman,id_seniman',
        ]);

        // Bebaskan chat tanpa perlu checkout
        $conversation = Conversation::firstOrCreate([
            'pembeli_id' => $pembeli->id_pembeli,
            'seniman_id' => $validated['counterpart_id'],
        ]);

        return redirect()->route('pembeli.chat.index', ['conversation' => $conversation->id]);
    }

    public function pembeliStartFromKarya(Request $request, $kode_seni): RedirectResponse
    {
        $pembeli = Auth::guard('pembeli')->user();

        $karya = KaryaSeni::with('seniman')->where('kode_seni', $kode_seni)->firstOrFail();

        // Bebaskan chat tanpa perlu checkout - pembeli bisa menanyakan hal mengenai karya seni
        $conversation = Conversation::firstOrCreate([
            'pembeli_id' => $pembeli->id_pembeli,
            'seniman_id' => $karya->id_seniman,
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

    // ========== PEMBELI-TO-PEMBELI CHAT ==========

    public function pembeliToPembeliIndex(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $conversations = PembeliConversation::with([
            'pembeli1:id_pembeli,nama,foto',
            'pembeli2:id_pembeli,nama,foto',
            'messages' => function ($query) {
                $query->latest()->limit(1);
            }
        ])
            ->where(function ($query) use ($pembeli) {
                $query->where('pembeli1_id', $pembeli->id_pembeli)
                    ->orWhere('pembeli2_id', $pembeli->id_pembeli);
            })
            ->orderByDesc('updated_at')
            ->get();

        $activeConversation = $this->resolvePembeliConversation(
            $request->get('conversation'),
            $pembeli->id_pembeli,
            $conversations->first()
        );

        $messages = $activeConversation ? $this->loadPembeliMessages($activeConversation) : collect();

        if ($activeConversation) {
            $this->markPembeliMessagesAsRead($activeConversation, $pembeli->id_pembeli);
        }

        // Get list of all other pembeli (excluding current user)
        $pembeliList = Pembeli::where('id_pembeli', '!=', $pembeli->id_pembeli)
            ->orderBy('nama')
            ->get(['id_pembeli', 'nama', 'foto']);

        return view('chat.pembeli-to-pembeli', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
            'counterparts' => $pembeliList,
            'currentPembeli' => $pembeli,
        ]);
    }

    public function pembeliToPembeliStart(Request $request): RedirectResponse
    {
        $pembeli = Auth::guard('pembeli')->user();

        $validated = $request->validate([
            'counterpart_id' => 'required|exists:pembeli,id_pembeli|different:' . $pembeli->id_pembeli,
        ], [
            'counterpart_id.different' => 'Anda tidak dapat chat dengan diri sendiri.',
        ]);

        $counterpartId = $validated['counterpart_id'];

        // Ensure consistent ordering (smaller ID first) to avoid duplicates
        $pembeli1Id = min($pembeli->id_pembeli, $counterpartId);
        $pembeli2Id = max($pembeli->id_pembeli, $counterpartId);

        $conversation = PembeliConversation::firstOrCreate([
            'pembeli1_id' => $pembeli1Id,
            'pembeli2_id' => $pembeli2Id,
        ]);

        return redirect()->route('pembeli.chat.pembeli.index', ['conversation' => $conversation->id]);
    }

    public function pembeliToPembeliMessages(PembeliConversation $conversation): JsonResponse
    {
        $pembeli = Auth::guard('pembeli')->user();
        $this->ensurePembeliConversationAccess($conversation, $pembeli->id_pembeli);
        $this->markPembeliMessagesAsRead($conversation, $pembeli->id_pembeli);

        return $this->pembeliMessagesResponse($conversation, $pembeli->id_pembeli);
    }

    public function pembeliToPembeliSend(Request $request, PembeliConversation $conversation)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $this->ensurePembeliConversationAccess($conversation, $pembeli->id_pembeli);

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $pembeli->id_pembeli,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'sent_at' => optional($message->created_at)->format('d M H:i'),
            ], 201);
        }

        return redirect()->route('pembeli.chat.pembeli.index', ['conversation' => $conversation->id]);
    }

    // ========== PRIVATE HELPERS FOR PEMBELI-TO-PEMBELI ==========

    private function resolvePembeliConversation(?string $conversationId, int $pembeliId, $fallback = null): ?PembeliConversation
    {
        if ($conversationId) {
            $conversation = PembeliConversation::with(['pembeli1', 'pembeli2'])
                ->where(function ($query) use ($pembeliId) {
                    $query->where('pembeli1_id', $pembeliId)
                        ->orWhere('pembeli2_id', $pembeliId);
                })
                ->where('id', $conversationId)
                ->first();

            return $conversation && $conversation->hasPembeli($pembeliId) ? $conversation : null;
        }

        return $fallback && $fallback->hasPembeli($pembeliId) ? $fallback : null;
    }

    private function loadPembeliMessages(PembeliConversation $conversation): Collection
    {
        return $conversation->messages()
            ->with('sender:id_pembeli,nama,foto')
            ->orderBy('created_at')
            ->get();
    }

    private function markPembeliMessagesAsRead(PembeliConversation $conversation, int $pembeliId): void
    {
        $conversation->messages()
            ->where('sender_id', '!=', $pembeliId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    private function ensurePembeliConversationAccess(PembeliConversation $conversation, int $pembeliId): void
    {
        abort_if(!$conversation->hasPembeli($pembeliId), 403, 'Akses chat tidak valid.');
    }

    private function pembeliMessagesResponse(PembeliConversation $conversation, int $currentPembeliId): JsonResponse
    {
        $messages = $conversation->messages()
            ->with('sender:id_pembeli,nama,foto')
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) use ($currentPembeliId) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->nama ?? 'Unknown',
                    'message' => $message->message,
                    'sent_at' => optional($message->created_at)->format('d M H:i'),
                    'is_self' => $message->sender_id === $currentPembeliId,
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}

