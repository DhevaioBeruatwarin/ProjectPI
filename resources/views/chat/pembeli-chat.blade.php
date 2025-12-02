<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - Jogja Artsphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        /* Main Layout */
        .chat-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Chat Content Area */
        .chat-content {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        /* Sidebar */
        .chat-sidebar {
            width: 300px;
            background: white;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-header h6 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .conversation-item {
            display: flex;
            padding: 12px;
            margin-bottom: 6px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .conversation-item:hover,
        .conversation-item.active {
            background: #e9ecef;
            border-left-color: #667eea;
        }

        .conv-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .conv-content {
            flex: 1;
            min-width: 0;
        }

        .conv-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .conv-message {
            font-size: 12px;
            color: #999;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 4px 0 0 0;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-new-chat {
            width: 100%;
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-new-chat:hover {
            background: #5568d3;
        }

        /* Chat Area */
        .chat-messages-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fafafa;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .message-group {
            display: flex;
            margin-bottom: 4px;
        }

        .message-group.sent {
            justify-content: flex-end;
        }

        .message-group.received {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 60%;
            padding: 12px 16px;
            border-radius: 12px;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .message-group.sent .message-bubble {
            background: #667eea;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-group.received .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            display: block;
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
        }

        /* Quick Replies */
        .quick-replies {
            padding: 12px 16px;
            background: white;
            border-top: 1px solid #e5e7eb;
            overflow-x: auto;
        }

        .quick-replies-inner {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .quick-reply-btn {
            padding: 6px 12px;
            border: 1px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .quick-reply-btn:hover {
            background: #667eea;
            color: white;
        }

        /* Input Area */
        .input-area {
            padding: 12px 16px;
            background: white;
            border-top: 1px solid #e5e7eb;
        }

        .input-group-custom {
            display: flex;
            gap: 8px;
        }

        .message-input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: none;
            max-height: 100px;
        }

        .message-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-send {
            padding: 10px 16px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .btn-send:hover {
            background: #5568d3;
        }

        .btn-send:active {
            transform: scale(0.98);
        }

        /* Scrollbar */
        .conversations-list::-webkit-scrollbar,
        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .conversations-list::-webkit-scrollbar-track,
        .messages-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .conversations-list::-webkit-scrollbar-thumb,
        .messages-container::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .conversations-list::-webkit-scrollbar-thumb:hover,
        .messages-container::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 16px;
        }

        .modal-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .modal-body {
            padding: 16px;
        }

        .seniman-list-item {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .seniman-list-item:hover {
            background: #f8f9fa;
        }

        .seniman-list-item label {
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .seniman-list-item input {
            margin-right: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chat-sidebar {
                display: none;
            }

            .message-bubble {
                max-width: 80%;
            }
        }
    </style>
</head>
<body>
<!-- Navbar Site -->
<header class="header">
    <div class="container nav-container">
        <div class="header-left">
            <a href="{{ url('/') }}" class="logo-link">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
            </a>
            <span class="brand">JOGJA ARTSPHERE</span>
        </div>

        <form action="{{ route('dashboard.pembeli.search') }}" method="GET" class="search-form">
            <input type="search" name="query" placeholder="Cari karya seni..." value="{{ request('query') }}">
        </form>

        <div class="header-right">
            <a href="{{ route('keranjang.index') }}" class="icon-link" title="Keranjang">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 2L7.17 4H3a1 1 0 000 2h1l1.68 9.39A2 2 0 0011.56 17H18a2 2 0 001.97-1.61L21 8H7" stroke="currentColor" stroke-width="2"/>
                    <circle cx="9" cy="20" r="1" fill="currentColor"/>
                    <circle cx="18" cy="20" r="1" fill="currentColor"/>
                </svg>
            </a>

            @if(\Illuminate\Support\Facades\Auth::guard('pembeli')->check())
                @php
                    $pembeli = Auth::guard('pembeli')->user();
                    $fotoPath = $pembeli->foto ? asset('storage/foto_pembeli/' . $pembeli->foto) : asset('assets/defaultprofile.png');
                @endphp
                <a href="{{ route('pembeli.profil') }}" class="profile-link" title="Profil">
                    <img src="{{ $fotoPath }}" alt="Foto Profil" class="avatar">
                </a>
            @endif
        </div>
    </div>
</header>

<div class="chat-wrapper">
    <div class="chat-content">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <div class="sidebar-header">
                <h6>Konversasi</h6>
                <input type="text" class="search-input" id="conversationSearch" placeholder="Cari seniman...">
            </div>

            <div class="conversations-list">
                @if($conversations->count())
                    @foreach($conversations as $conv)
                        <a href="{{ route('pembeli.chat.index', ['conversation' => $conv->id]) }}" 
                           class="conversation-item {{ $activeConversation?->id === $conv->id ? 'active' : '' }}">
                            <img src="{{ $conv->seniman->foto ? asset('storage/' . $conv->seniman->foto) : asset('assets/defaultprofile.png') }}" 
                                 alt="{{ $conv->seniman->nama }}" 
                                 class="conv-avatar">
                            <div class="conv-content">
                                <p class="conv-name">{{ $conv->seniman->nama }}</p>
                                <p class="conv-message">
                                    @if($conv->messages->first())
                                        {{ $conv->messages->first()->message }}
                                    @else
                                        Belum ada pesan
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center p-3 text-muted">
                        <p>Belum ada konversasi</p>
                    </div>
                @endif
            </div>

            <div class="sidebar-footer">
                <button class="btn-new-chat" data-bs-toggle="modal" data-bs-target="#newChatModal">
                    <i class="fas fa-plus me-2"></i> Chat Baru
                </button>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-messages-area">
            @if($activeConversation)
                <div class="messages-container" id="messagesContainer">
                    @forelse($messages as $msg)
                        <div class="message-group {{ $msg->sender_type === 'pembeli' ? 'sent' : 'received' }}">
                            <div class="message-bubble">
                                <p class="mb-0">{{ $msg->message }}</p>
                                <small class="message-time">{{ $msg->created_at->format('H:i') }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <p>Mulai percakapan dengan mengirim pesan pertama</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Replies -->
                @if(isset($quickReplies) && $quickReplies->count())
                    <div class="quick-replies">
                        <div class="quick-replies-inner">
                            @foreach($quickReplies as $qr)
                                <button class="quick-reply-btn" data-message="{{ e($qr->message) }}" title="{{ e($qr->title) }}">
                                    {{ Str::limit($qr->title, 20) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Input -->
                <div class="input-area">
                    <form id="messageForm">
                        @csrf
                        <div class="input-group-custom">
                            <input type="text" id="messageInput" name="message" class="message-input" 
                                   placeholder="Ketik pesan..." autocomplete="off">
                            <button type="submit" class="btn-send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h5 class="mb-3">Pilih konversasi atau mulai chat baru</h5>
                    <button class="btn-new-chat" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="fas fa-plus me-2"></i> Chat Baru
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: New Chat -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mulai Chat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pembeli.chat.start') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; font-size: 14px;">Pilih Seniman</label>
                    <div style="max-height: 400px; overflow-y: auto;">
                        @forelse($counterparts as $seniman)
                            <div class="seniman-list-item">
                                <label>
                                    <input type="radio" name="counterpart_id" value="{{ $seniman->id_seniman }}">
                                    <img src="{{ $seniman->foto ? asset('storage/' . $seniman->foto) : asset('assets/defaultprofile.png') }}" 
                                         alt="{{ $seniman->nama }}" 
                                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px;">
                                    <span>{{ $seniman->nama }}</span>
                                </label>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada seniman tersedia</p>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Mulai Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Expose endpoints + CSRF to JS --}}
<script>
    window.chatEndpoints = {
        messages: {!! json_encode($endpoints['messages'] ?? null) !!},
        send: {!! json_encode($endpoints['send'] ?? null) !!}
    };
    window.csrfToken = {!! json_encode(csrf_token()) !!};
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');

    // Quick replies
    document.querySelectorAll('.quick-reply-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const msg = this.getAttribute('data-message');
            messageInput.value = msg;
            messageInput.focus();
        });
    });

    // Search conversations
    const searchInput = document.getElementById('conversationSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.conversation-item').forEach(item => {
                const name = item.querySelector('.conv-name')?.textContent.toLowerCase() || '';
                item.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }

    // Escape HTML
    function escapeHtml(unsafe) {
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/\"/g, "&quot;")
            .replace(/\'/g, "&#039;");
    }

    // Render messages
    function renderMessages(messages) {
        if (!messagesContainer) return;
        messagesContainer.innerHTML = '';
        if (messages.length === 0) {
            messagesContainer.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <p>Mulai percakapan dengan mengirim pesan pertama</p>
                </div>
            `;
            return;
        }
        messages.forEach(msg => {
            const group = document.createElement('div');
            group.className = 'message-group ' + (msg.is_self ? 'sent' : 'received');
            group.innerHTML = `
                <div class="message-bubble">
                    <p class="mb-0">${escapeHtml(msg.message)}</p>
                    <small class="message-time">${msg.sent_at || ''}</small>
                </div>
            `;
            messagesContainer.appendChild(group);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Polling for messages
    let lastMessages = [];
    async function fetchMessages() {
        const url = window.chatEndpoints.messages;
        if (!url) return;
        try {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            if (JSON.stringify(data.messages) !== JSON.stringify(lastMessages)) {
                lastMessages = data.messages;
                renderMessages(data.messages);
            }
        } catch (e) {
            console.error('Fetch failed', e);
        }
    }

    fetchMessages();
    const pollInterval = setInterval(fetchMessages, 3000);

    // Send message
    if (messageForm) {
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const url = window.chatEndpoints.send;
            if (!url) return;
            const text = messageInput.value.trim();
            if (!text) return;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ message: text })
                });

                if (res.ok) {
                    const json = await res.json();
                    lastMessages.push({
                        id: json.id,
                        message: json.message,
                        sent_at: json.sent_at,
                        is_self: true
                    });
                    renderMessages(lastMessages);
                    messageInput.value = '';
                    messageInput.focus();
                }
            } catch (err) {
                console.error('Send failed', err);
            }
        });
    }

    // Submit on Enter
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (messageForm) messageForm.requestSubmit();
            }
        });
    }

    window.addEventListener('beforeunload', () => clearInterval(pollInterval));
});
</script>
</body>
</html>
